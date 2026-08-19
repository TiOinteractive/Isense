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

    /**
     * Całe drzewo cennika (kategorie → usługi → modele) dla bloku na stronie.
     *
     * Jedno zapytanie zamiast pętli po poziomach. Filtry `publish` usług i modeli muszą siedzieć
     * w warunku JOIN, nie w WHERE — inaczej LEFT JOIN zachowuje się jak INNER i wypadają
     * kategorie bez usług oraz usługi bez modeli, a te chcemy pokazać z komunikatem.
     *
     * @param array $id_categories puste = wszystkie opublikowane kategorie
     */
    public function getTreeForFront(int $id_lang, array $id_categories = []): array
    {
        $id_lang = (int) $id_lang;
        $query   = $this->db->table('pricing_category c')
            ->join('pricing_category_lang cl', 'cl.id_category=c.id AND cl.id_lang=' . $id_lang, 'left')
            ->join('pricing_service s', 's.id_category=c.id AND s.publish=1', 'left')
            ->join('pricing_service_lang sl', 'sl.id_service=s.id AND sl.id_lang=' . $id_lang, 'left')
            ->join('pricing_model m', 'm.id_service=s.id AND m.publish=1', 'left')
            ->join('pricing_model_lang ml', 'ml.id_model=m.id AND ml.id_lang=' . $id_lang, 'left')
            ->select('c.id as id_category,cl.name as category_name,cl.slug as category_slug,'
                . 's.id as id_service,sl.name as service_name,'
                . 'm.id as id_model,ml.name as model_name,m.price')
            ->where('c.publish', 1);

        if (! empty($id_categories)) {
            $query->whereIn('c.id', $id_categories);
        }

        $rows = $query->orderBy('c.order', 'ASC')->orderBy('c.id', 'ASC')
            ->orderBy('s.order', 'ASC')->orderBy('s.id', 'ASC')
            ->orderBy('m.order', 'ASC')->orderBy('m.id', 'ASC')
            ->get()->getResultArray();

        $tree = [];
        foreach ($rows as $row) {
            $id_category = (int) $row['id_category'];
            // Kategoria bez tłumaczenia nie ma czym podpisać zakładki — pomijamy ją w całości.
            if (trim((string) $row['category_name']) === '') {
                continue;
            }
            if (! isset($tree[$id_category])) {
                $tree[$id_category] = [
                    'id'       => $id_category,
                    'name'     => $row['category_name'],
                    'slug'     => $row['category_slug'],
                    'services' => [],
                ];
            }

            $id_service = (int) $row['id_service'];
            if (empty($id_service) || trim((string) $row['service_name']) === '') {
                continue;
            }
            if (! isset($tree[$id_category]['services'][$id_service])) {
                $tree[$id_category]['services'][$id_service] = [
                    'id'     => $id_service,
                    'name'   => $row['service_name'],
                    'models' => [],
                ];
            }

            $id_model = (int) $row['id_model'];
            if (empty($id_model) || trim((string) $row['model_name']) === '') {
                continue;
            }
            $tree[$id_category]['services'][$id_service]['models'][$id_model] = [
                'id'    => $id_model,
                'name'  => $row['model_name'],
                'price' => $row['price'],
            ];
        }

        // Klucze były potrzebne tylko do składania drzewa — widok iteruje po listach.
        foreach ($tree as $k => $category) {
            foreach ($category['services'] as $s => $service) {
                $tree[$k]['services'][$s]['models'] = array_values($service['models']);
            }
            $tree[$k]['services'] = array_values($tree[$k]['services']);
        }

        return array_values($tree);
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
