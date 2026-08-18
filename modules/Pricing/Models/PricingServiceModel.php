<?php

namespace Modules\Pricing\Models;

use CodeIgniter\Model;

/**
 * Usługa w obrębie kategorii cennika (np. „Wymiana wyświetlacza" w kategorii iPhone).
 * Kolejność (`order`) liczona jest osobno w każdej kategorii.
 */
class PricingServiceModel extends Model
{
    use OrderableTrait;

    protected $table         = 'pricing_service';
    protected $allowedFields = ['id_category', 'order', 'publish', 'edited_at', 'created_at'];

    public $id = null;

    public function getById($id, $id_lang): array
    {
        $service = $this->where('id', $id)->first();
        if (empty($service)) {
            return [];
        }
        $service['lang']        = $this->getLang($id);
        $service['name']        = $service['lang'][$id_lang]['name'] ?? '';
        $service['description'] = $service['lang'][$id_lang]['description'] ?? '';

        return $service;
    }

    private function getLang($id_service): array
    {
        $langs = [];
        $rows  = $this->db->table('pricing_service_lang')->where('id_service', $id_service)->orderBy('id_lang')->get()->getResultArray();
        foreach ($rows as $row) {
            $langs[$row['id_lang']] = $row;
        }

        return $langs;
    }

    /** Usługa + nazwa jej kategorii — do okruszków i nagłówków. */
    public function getWithCategory($id, $id_lang): array
    {
        $row = $this->db->table('pricing_service ps')
            ->join('pricing_service_lang psl', 'ps.id=psl.id_service AND psl.id_lang=' . (int) $id_lang, 'left', false)
            ->join('pricing_category_lang pcl', 'pcl.id_category=ps.id_category AND pcl.id_lang=' . (int) $id_lang, 'left', false)
            ->select('ps.id,ps.id_category,ps.publish,psl.name,pcl.name as category_name')
            ->where('ps.id', $id)
            ->get()->getRowArray();

        return ! empty($row) ? $row : [];
    }

    public function getListForAdmin(int $id_category, array $get, int $id_lang): array
    {
        $query = $this->select('pricing_service.id,pricing_service.publish,pricing_service.order,psl.name')
            ->join('pricing_service_lang psl', 'pricing_service.id=psl.id_service')
            ->where('psl.id_lang', $id_lang)
            ->where('pricing_service.id_category', $id_category);

        if (! empty($get['name'])) {
            $query->like('psl.name', $get['name']);
        }
        if (isset($get['publish']) && in_array($get['publish'], ['0', '1'], true)) {
            $query->where('pricing_service.publish', (int) $get['publish']);
        }

        $tmp = explode(',', $get['order'] ?? 'order,asc');
        $dir = ($tmp[1] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        switch ($tmp[0]) {
            case 'name':
                $query->orderBy('psl.name', $dir);
                break;
            case 'publish':
                $query->orderBy('pricing_service.publish', $dir);
                break;
            default:
                $query->orderBy('pricing_service.order', $dir);
        }

        $list = $query->paginate(! empty($get['on_page']) ? (int) $get['on_page'] : 50);

        foreach ($list as $k => $row) {
            $list[$k]['models'] = $this->db->table('pricing_model')->where('id_service', $row['id'])->countAllResults();
        }

        return $list;
    }

    /** Usługi danej kategorii do selecta (np. przy kopiowaniu modeli). */
    public function getForSelect(int $id_category, int $id_lang, int $exclude_id = 0): array
    {
        $query = $this->db->table('pricing_service ps')
            ->join('pricing_service_lang psl', 'ps.id=psl.id_service')
            ->select('ps.id,psl.name')
            ->where('psl.id_lang', $id_lang)
            ->where('ps.id_category', $id_category);
        if ($exclude_id) {
            $query->where('ps.id !=', $exclude_id);
        }

        return $query->orderBy('ps.order', 'ASC')->get()->getResultArray();
    }

    public function saveService($id, $id_category, array $post): bool
    {
        if (empty($post) || empty($id_category)) {
            return false;
        }
        $data = [
            'id_category' => (int) $id_category,
            'publish'     => ! empty($post['publish']) ? 1 : 0,
            'edited_at'   => date('Y-m-d H:i:s'),
        ];

        $this->db->transStart();
        if ($id) {
            $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $data['order']      = $this->nextOrder(['id_category' => (int) $id_category]);
            $data['created_at'] = date('Y-m-d H:i:s');
            unset($data['edited_at']);
            $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveLang($this->id, $post['lang'] ?? []);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    private function saveLang($id_service, array $lang_data): void
    {
        foreach ($lang_data as $id_lang => $lang) {
            $data = [
                'id_service'  => $id_service,
                'id_lang'     => $id_lang,
                'name'        => trim((string) ($lang['name'] ?? '')),
                'description' => trim((string) ($lang['description'] ?? '')),
            ];
            $existing = $this->db->table('pricing_service_lang')->select('id')->where('id_service', $id_service)->where('id_lang', $id_lang)->get()->getRowArray();
            if (! empty($existing)) {
                $this->db->table('pricing_service_lang')->set($data)->where('id', $existing['id'])->update();
            } else {
                $this->db->table('pricing_service_lang')->insert($data);
            }
        }
    }

    /**
     * Usuwa usługę wraz z modelami.
     * $reset_order=false przy kasowaniu całej kategorii — porządkowanie pozycji nie ma wtedy sensu.
     */
    public function deleteService($id, bool $reset_order = true): bool
    {
        if (empty($id)) {
            return false;
        }
        $service = $this->select('id,id_category')->where('id', $id)->first();
        if (empty($service)) {
            return false;
        }

        $this->db->transStart();

        $modelModel = new PricingModelModel();
        $models     = $this->db->table('pricing_model')->select('id')->where('id_service', $id)->get()->getResultArray();
        foreach ($models as $model) {
            $modelModel->deleteModel($model['id'], false);
        }

        $this->db->table('pricing_service_lang')->where('id_service', $id)->delete();
        $this->where('id', $id)->delete();
        if ($reset_order) {
            $this->resetOrder(['id_category' => $service['id_category']]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
