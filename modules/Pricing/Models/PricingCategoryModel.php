<?php

namespace Modules\Pricing\Models;

use CodeIgniter\Model;

/**
 * Kategoria cennika — najwyższy poziom (iPhone, iPad, MacBook, ...).
 *
 * Nazwa i slug są per język (pricing_category_lang). Slug nie tworzy adresu strony —
 * służy do powiązania kategorii z podstroną przy pobieraniu cennika na froncie.
 */
class PricingCategoryModel extends Model
{
    use OrderableTrait;

    protected $table         = 'pricing_category';
    protected $allowedFields = ['order', 'publish', 'edited_at', 'created_at'];

    public $id = null;

    /** Kategoria z danymi wszystkich języków (formularz) + nazwą w języku panelu. */
    public function getById($id, $id_lang): array
    {
        $category = $this->where('id', $id)->first();
        if (empty($category)) {
            return [];
        }
        $category['lang'] = $this->getLang($id);
        $category['name'] = $category['lang'][$id_lang]['name'] ?? '';

        return $category;
    }

    private function getLang($id_category): array
    {
        $langs = [];
        $rows  = $this->db->table('pricing_category_lang')->where('id_category', $id_category)->orderBy('id_lang')->get()->getResultArray();
        foreach ($rows as $row) {
            $langs[$row['id_lang']] = $row;
        }

        return $langs;
    }

    /** Lista do panelu (filtry: name, publish; sortowanie: order/name/publish). */
    public function getListForAdmin(array $get, int $id_lang): array
    {
        $query = $this->select('pricing_category.id,pricing_category.publish,pricing_category.order,pcl.name')
            ->join('pricing_category_lang pcl', 'pricing_category.id=pcl.id_category')
            ->where('pcl.id_lang', $id_lang);

        if (! empty($get['name'])) {
            $query->like('pcl.name', $get['name']);
        }
        if (isset($get['publish']) && in_array($get['publish'], ['0', '1'], true)) {
            $query->where('pricing_category.publish', (int) $get['publish']);
        }

        $tmp = explode(',', $get['order'] ?? 'order,asc');
        $dir = ($tmp[1] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        switch ($tmp[0]) {
            case 'name':
                $query->orderBy('pcl.name', $dir);
                break;
            case 'publish':
                $query->orderBy('pricing_category.publish', $dir);
                break;
            default:
                $query->orderBy('pricing_category.order', $dir);
        }

        $list = $query->paginate(! empty($get['on_page']) ? (int) $get['on_page'] : 50);

        // Licznik usług — pokazujemy w liście, żeby było widać, gdzie cennik jest jeszcze pusty.
        foreach ($list as $k => $row) {
            $list[$k]['services'] = $this->db->table('pricing_service')->where('id_category', $row['id'])->countAllResults();
        }

        return $list;
    }

    /** Kategorie do selecta (np. przy przenoszeniu usługi). */
    public function getForSelect(int $id_lang, bool $publish_only = false): array
    {
        $query = $this->db->table('pricing_category pc')
            ->join('pricing_category_lang pcl', 'pc.id=pcl.id_category')
            ->select('pc.id,pcl.name')
            ->where('pcl.id_lang', $id_lang);
        if ($publish_only) {
            $query->where('pc.publish', 1);
        }

        return $query->orderBy('pc.order', 'ASC')->get()->getResultArray();
    }

    public function saveCategory($id, array $post): bool
    {
        if (empty($post)) {
            return false;
        }
        $data = ['publish' => ! empty($post['publish']) ? 1 : 0, 'edited_at' => date('Y-m-d H:i:s')];

        $this->db->transStart();
        if ($id) {
            $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $data['order']      = $this->nextOrder();
            $data['created_at'] = date('Y-m-d H:i:s');
            unset($data['edited_at']);
            $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveLang($this->id, $post['lang'] ?? []);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    private function saveLang($id_category, array $lang_data): void
    {
        helper('text');
        foreach ($lang_data as $id_lang => $lang) {
            $name = trim((string) ($lang['name'] ?? ''));
            $slug = trim((string) ($lang['slug'] ?? ''));
            if ($slug === '') {
                $slug = mb_url_title(str_replace(['/', ','], '-', $name), '-', true);
            } else {
                $slug = mb_url_title(str_replace(['/', ','], '-', $slug), '-', true);
            }
            $data = [
                'id_category' => $id_category,
                'id_lang'     => $id_lang,
                'name'        => $name,
                'slug'        => $slug,
            ];
            $existing = $this->db->table('pricing_category_lang')->select('id')->where('id_category', $id_category)->where('id_lang', $id_lang)->get()->getRowArray();
            if (! empty($existing)) {
                $this->db->table('pricing_category_lang')->set($data)->where('id', $existing['id'])->update();
            } else {
                $this->db->table('pricing_category_lang')->insert($data);
            }
        }
    }

    /** Usuwa kategorię wraz z usługami i modelami (kaskada w PHP — brak FK w bazie). */
    public function deleteCategory($id): bool
    {
        if (empty($id)) {
            return false;
        }
        $this->db->transStart();

        $serviceModel = new PricingServiceModel();
        $services     = $this->db->table('pricing_service')->select('id')->where('id_category', $id)->get()->getResultArray();
        foreach ($services as $service) {
            $serviceModel->deleteService($service['id'], false);
        }

        $this->db->table('pricing_category_lang')->where('id_category', $id)->delete();
        $this->where('id', $id)->delete();
        $this->resetOrder();

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
