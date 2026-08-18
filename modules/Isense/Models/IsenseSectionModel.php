<?php

namespace Modules\Isense\Models;

use CodeIgniter\Model;

/**
 * Przechowywanie danych sekcji strony głównej iSense.
 *
 * Jeden wiersz `isense_section` = jeden blok treści strony (powiązany przez id_page_cont).
 * Pola sekcji trzymane są jako JSON w `isense_section_lang.data` (per język),
 * dzięki czemu jeden moduł obsługuje różne typy sekcji (hero, cta, itd.) bez osobnych tabel.
 */
class IsenseSectionModel extends Model
{
    protected $table = 'isense_section';
    protected $allowedFields = ['id_page_cont', 'edited_at', 'created_at'];

    /** Zwraca zdekodowane pola sekcji dla jednego języka (front). */
    public function getFields($id_page_cont, $id_lang): array
    {
        $row = $this->where('id_page_cont', $id_page_cont)->first();
        if (empty($row)) {
            return [];
        }
        $lang = $this->db->table('isense_section_lang')
            ->select('data')
            ->where('id_isense_section', $row['id'])
            ->where('id_lang', $id_lang)
            ->get()->getRowArray();
        if (empty($lang) || empty($lang['data'])) {
            return [];
        }
        $decoded = json_decode($lang['data'], true);
        return is_array($decoded) ? $decoded : [];
    }

    /** Zwraca pola sekcji dla wszystkich języków (formularz admina): [id_lang => fields]. */
    public function getFieldsAllLang($id_page_cont): array
    {
        $row = $this->where('id_page_cont', $id_page_cont)->first();
        if (empty($row)) {
            return [];
        }
        $rows = $this->db->table('isense_section_lang')
            ->where('id_isense_section', $row['id'])
            ->get()->getResultArray();
        $out = [];
        foreach ($rows as $r) {
            $decoded = json_decode($r['data'] ?? '', true);
            $out[$r['id_lang']] = is_array($decoded) ? $decoded : [];
        }
        return $out;
    }

    /** Zapis pól sekcji per język (dane wejściowe: [id_lang => [field => value]]). */
    public function saveFields($id_page_cont, array $lang_data): void
    {
        $row = $this->where('id_page_cont', $id_page_cont)->first();
        if (! empty($row)) {
            $id = $row['id'];
            $this->set(['edited_at' => date('Y-m-d H:i:s')])->where('id', $id)->update();
        } else {
            $this->insert(['id_page_cont' => $id_page_cont, 'created_at' => date('Y-m-d H:i:s')]);
            $id = $this->getInsertID();
        }

        foreach ($lang_data as $id_lang => $fields) {
            $payload = json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($payload === false) {
                // Błąd kodowania (np. nieprawidłowe UTF-8) — nie nadpisuj poprawnych danych "zerem".
                continue;
            }
            $existing = $this->db->table('isense_section_lang')
                ->select('id')
                ->where('id_isense_section', $id)
                ->where('id_lang', $id_lang)
                ->get()->getRowArray();
            if (! empty($existing)) {
                $this->db->table('isense_section_lang')
                    ->set(['data' => $payload])
                    ->where('id', $existing['id'])->update();
            } else {
                $this->db->table('isense_section_lang')->insert([
                    'id_isense_section' => $id,
                    'id_lang'           => $id_lang,
                    'data'              => $payload,
                ]);
            }
        }
    }

    /** Sprzątanie przy usuwaniu bloku. */
    public function deleteByContentId($id_page_cont): void
    {
        $row = $this->where('id_page_cont', $id_page_cont)->first();
        if (! empty($row)) {
            $this->db->table('isense_section_lang')->where('id_isense_section', $row['id'])->delete();
            $this->where('id', $row['id'])->delete();
        }
    }
}
