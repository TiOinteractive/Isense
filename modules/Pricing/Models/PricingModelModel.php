<?php

namespace Modules\Pricing\Models;

use CodeIgniter\Model;

/**
 * Model urządzenia wyceniony w ramach usługi (np. „iPhone 15 Pro" przy wymianie wyświetlacza).
 *
 * Cena, czas realizacji i gwarancja to pola tekstowe — w cenniku występują wpisy typu
 * „bezpłatnie", „od 1200 zł", „2–3 dni robocze", więc każde ograniczenie do liczby by przeszkadzało.
 * Trzymamy je w tabeli językowej, bo w wersji obcojęzycznej muszą brzmieć inaczej.
 */
class PricingModelModel extends Model
{
    use OrderableTrait;

    protected $table         = 'pricing_model';
    protected $allowedFields = ['id_service', 'order', 'publish', 'edited_at', 'created_at'];

    public $id = null;

    public function getById($id, $id_lang): array
    {
        $model = $this->where('id', $id)->first();
        if (empty($model)) {
            return [];
        }
        $model['lang']     = $this->getLang($id);
        $model['name']     = $model['lang'][$id_lang]['name'] ?? '';
        $model['price']    = $model['lang'][$id_lang]['price'] ?? '';
        $model['time']     = $model['lang'][$id_lang]['time'] ?? '';
        $model['warranty'] = $model['lang'][$id_lang]['warranty'] ?? '';

        return $model;
    }

    private function getLang($id_model): array
    {
        $langs = [];
        $rows  = $this->db->table('pricing_model_lang')->where('id_model', $id_model)->orderBy('id_lang')->get()->getResultArray();
        foreach ($rows as $row) {
            $langs[$row['id_lang']] = $row;
        }

        return $langs;
    }

    public function getListForAdmin(int $id_service, array $get, int $id_lang): array
    {
        $query = $this->select('pricing_model.id,pricing_model.publish,pricing_model.order,pml.name,pml.price,pml.time,pml.warranty')
            ->join('pricing_model_lang pml', 'pricing_model.id=pml.id_model')
            ->where('pml.id_lang', $id_lang)
            ->where('pricing_model.id_service', $id_service);

        if (! empty($get['name'])) {
            $query->like('pml.name', $get['name']);
        }
        if (isset($get['publish']) && in_array($get['publish'], ['0', '1'], true)) {
            $query->where('pricing_model.publish', (int) $get['publish']);
        }

        $tmp = explode(',', $get['order'] ?? 'order,asc');
        $dir = ($tmp[1] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
        switch ($tmp[0]) {
            case 'name':
                $query->orderBy('pml.name', $dir);
                break;
            case 'publish':
                $query->orderBy('pricing_model.publish', $dir);
                break;
            default:
                $query->orderBy('pricing_model.order', $dir);
        }

        return $query->paginate(! empty($get['on_page']) ? (int) $get['on_page'] : 50);
    }

    public function saveModel($id, $id_service, array $post): bool
    {
        if (empty($post) || empty($id_service)) {
            return false;
        }
        $data = [
            'id_service' => (int) $id_service,
            'publish'    => ! empty($post['publish']) ? 1 : 0,
            'edited_at'  => date('Y-m-d H:i:s'),
        ];

        $this->db->transStart();
        if ($id) {
            $this->set($data)->where('id', $id)->update();
            $this->id = $id;
        } else {
            $data['order']      = $this->nextOrder(['id_service' => (int) $id_service]);
            $data['created_at'] = date('Y-m-d H:i:s');
            unset($data['edited_at']);
            $this->insert($data);
            $this->id = $this->getInsertID();
        }
        $this->saveLang($this->id, $post['lang'] ?? []);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    private function saveLang($id_model, array $lang_data): void
    {
        foreach ($lang_data as $id_lang => $lang) {
            $data = [
                'id_model' => $id_model,
                'id_lang'  => $id_lang,
                'name'     => trim((string) ($lang['name'] ?? '')),
                'price'    => trim((string) ($lang['price'] ?? '')),
                'time'     => trim((string) ($lang['time'] ?? '')),
                'warranty' => trim((string) ($lang['warranty'] ?? '')),
            ];
            $existing = $this->db->table('pricing_model_lang')->select('id')->where('id_model', $id_model)->where('id_lang', $id_lang)->get()->getRowArray();
            if (! empty($existing)) {
                $this->db->table('pricing_model_lang')->set($data)->where('id', $existing['id'])->update();
            } else {
                $this->db->table('pricing_model_lang')->insert($data);
            }
        }
    }

    /**
     * Szybkie dodawanie wielu modeli — jedna linia na model:
     * „Nazwa | cena | czas | gwarancja" (pola po nazwie są opcjonalne).
     * Ta sama treść trafia do wszystkich języków; tłumaczenie zostaje do poprawienia w edycji modelu.
     * Zwraca liczbę dodanych modeli.
     */
    public function importModels($id_service, string $text, array $languages): int
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $added = 0;

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $cols = array_map('trim', explode('|', $line));
            if ($cols[0] === '') {
                continue;
            }
            $lang_data = [];
            foreach ($languages as $language) {
                $lang_data[$language['id']] = [
                    'name'     => $cols[0],
                    'price'    => $cols[1] ?? '',
                    'time'     => $cols[2] ?? '',
                    'warranty' => $cols[3] ?? '',
                ];
            }
            if ($this->saveModel(0, $id_service, ['publish' => 1, 'lang' => $lang_data])) {
                ++$added;
            }
        }

        return $added;
    }

    public function deleteModel($id, bool $reset_order = true): bool
    {
        if (empty($id)) {
            return false;
        }
        $model = $this->select('id,id_service')->where('id', $id)->first();
        if (empty($model)) {
            return false;
        }

        $this->db->transStart();
        $this->db->table('pricing_model_lang')->where('id_model', $id)->delete();
        $this->where('id', $id)->delete();
        if ($reset_order) {
            $this->resetOrder(['id_service' => $model['id_service']]);
        }
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
