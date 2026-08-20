<?php

namespace Modules\Form\Models;
use CodeIgniter\Model;

/**
 * Model konfiguracji formularzy.
 *
 * Schemat: form -> form_lang
 *               -> form_field -> form_field_lang
 *                             -> form_field_option -> form_field_option_lang
 *
 * Pola moga byc warunkowe: form_field.parent_field wskazuje wczesniejsze pole
 * typu `select`, a form_field.parent_values to CSV identyfikatorow jego opcji,
 * przy ktorych pole ma byc widoczne. Zagniezdzenie wynika z lancucha rodzicow.
 */
class FormModel extends Model{

    /** Typy pol dozwolone w konfiguracji. */
    const FIELD_TYPES = array('text', 'textarea', 'number', 'checkbox', 'select', 'file');

    /** Typy, dla ktorych ma sens select `validation` (NIP, PESEL, e-mail...). */
    const VALIDATABLE_TYPES = array('text', 'textarea', 'number');

    protected $table = 'form';

    protected $allowedFields = [
        'id_page_cont',
        'template',
        'addressee',
        'addressee_cc',
        'addressee_bcc',
        'edited_at',
        'created_at',
        'captcha'
    ];


    public function getFormByContentId($id_content)
    {
        $form = $this->where('id_page_cont', $id_content)->first();
        if(!empty($form)) {
            $form['lang'] = $this->getFromLang($form['id']);
            $form['fields'] = $this->getFormFields($form['id']);
        }
        return $form;
    }

    private function getFromLang($id)
    {
        $langs = array();
        $data = $this->db->table('form_lang')->where('id_form', $id)->orderBy('id_lang')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    /**
     * Pola formularza wraz z tlumaczeniami i opcjami selectow.
     * Opcje pobierane sa dwoma zapytaniami zbiorczymi (bez N+1).
     */
    private function getFormFields($id_form)
    {
        $fields = $this->db->table('form_field')->where('id_form', $id_form)->orderBy('order', 'ASC')->get()->getResultArray();
        if(empty($fields)) {
            return array();
        }

        $ids = array_column($fields, 'id');
        $options = $this->db->table('form_field_option')->whereIn('id_field', $ids)->orderBy('order', 'ASC')->get()->getResultArray();

        $option_langs = array();
        if(!empty($options)) {
            $option_ids = array_column($options, 'id');
            $rows = $this->db->table('form_field_option_lang')->whereIn('id_option', $option_ids)->orderBy('id_lang', 'ASC')->get()->getResultArray();
            foreach($rows as $row) {
                $option_langs[$row['id_option']][$row['id_lang']] = $row;
            }
        }

        $options_by_field = array();
        foreach($options as $option) {
            $option['lang'] = !empty($option_langs[$option['id']]) ? $option_langs[$option['id']] : array();
            $options_by_field[$option['id_field']][] = $option;
        }

        foreach($fields as $k=>$f) {
            $fields[$k]['lang'] = $this->getFormFieldLang($f['id']);
            $fields[$k]['options'] = !empty($options_by_field[$f['id']]) ? $options_by_field[$f['id']] : array();
        }
        return $fields;
    }

    private function getFormFieldLang($id_field)
    {
        $langs = array();
        $data = $this->db->table('form_field_lang')->where('id_field', $id_field)->orderBy('id_lang', 'ASC')->get()->getResultArray();
        if(!empty($data)) {
            foreach($data as $d) {
                $langs[$d['id_lang']] = $d;
            }
        }
        return $langs;
    }

    public function saveForm($id_content, $post)
    {
        $data = array(
            'id_page_cont' => $id_content,
            'template' => $post['template'],
            'addressee' => !empty($post['addressee']) ? $post['addressee'] : '',
            'addressee_cc' => !empty($post['addressee_cc']) ? $post['addressee_cc'] : '',
            'addressee_bcc' => !empty($post['addressee_bcc']) ? $post['addressee_bcc'] : '',
            'captcha' => !empty($post['captcha']) ? 1 : 0,
        );
        $form = $this->where('id_page_cont', $id_content)->first();
        if(!empty($form)) {
            $this->set($data)->where('id_page_cont', $id_content)->update();
            $id = $form['id'];
			HistoryStat($id,$id_content,'form','Form',lang('Admin.page.EditSuccess'));
        } else {
            $this->insert($data);
            $id = $this->getInsertID();
			HistoryStat($id,$id_content,'form','Form',lang('Admin.page.AddSuccess'));
        }
        $this->saveFormLang($id, $post['lang']);
        $this->saveFormFields(
            $id,
            !empty($post['field']) ? $post['field'] : array(),
            !empty($post['fields_present'])
        );
    }

    private function saveFormLang($id_form, $lang_data)
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_form' => $id_form,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
                    'description' => $lang['description'],
                    'success_msg' => $lang['success_msg'],
                    'error_msg' => $lang['error_msg'],
                );
                $lang = $this->db->table('form_lang')->select('id')->where('id_form', $id_form)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($lang) && !empty($lang['id'])) {
                    $result = $this->db->table('form_lang')->set($data)->where('id_form', $id_form)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('form_lang')->insert($data);
                }
            }
        }
    }

    /**
     * Zapis pol formularza — algorytm DWUPRZEBIEGOWY.
     *
     * Warunki („pokaz gdy") wskazuja w formularzu admina nie ID z bazy, tylko
     * KLUCZE LOKALNE wierszy i opcji (`f12` / `n1a2b3c`, `o44` / `nonew1`),
     * bo pole moze byc dodane AJAX-em i nie mies jeszcze ID. Dlatego:
     *   przebieg 1 — upsert pol i opcji, budowa map klucz => ID,
     *   przebieg 2 — przepisanie kluczy na realne ID i zapis warunkow.
     *
     * $fields_present to sentinel z widoku admina. Bez niego POST bez sekcji
     * pol (blad JS, zwinieta sekcja) skasowalby cala konfiguracje formularza.
     */
    private function saveFormFields($id_form, $fields, $fields_present = false)
    {
        helper('url'); // mb_url_title() dla slugow opcji

        $ids = array();
        $field_key_to_id = array();  // 'f12' | 'n1a2b3c' => 12
        $option_key_to_id = array(); // 'o44' | 'nonew1'  => 44
        $pending = array();          // id_field => ['parent_key', 'parent_values', 'order']

        // ---------- PRZEBIEG 1: upsert pol i opcji ----------
        if(!empty($fields)) {
            foreach($fields as $field) {
                $type = !empty($field['type']) && in_array($field['type'], self::FIELD_TYPES, true) ? $field['type'] : 'text';

                $data = array(
                    'id_form' => $id_form,
                    'validation' => in_array($type, self::VALIDATABLE_TYPES, true) && !empty($field['validation']) ? $field['validation'] : '',
                    'type' => $type,
                    'order' => (int) $field['order'],
                    'required' => !empty($field['required']) ? 1 : 0,
                    'publish' => !empty($field['publish']) ? 1 : 0,
                    'max_files' => $type === 'file' ? max(1, (int) (!empty($field['max_files']) ? $field['max_files'] : 1)) : 1,
                    'max_file_size' => $type === 'file' ? max(0, (int) (!empty($field['max_file_size']) ? $field['max_file_size'] : 0)) : 0,
                    // parent_field / parent_values celowo pominiete — patrz przebieg 2
                );

                if(!empty($field['id']) && !empty($this->db->table('form_field')->select('id')->where('id', $field['id'])->where('id_form', $id_form)->get()->getResultArray())) {
                    $this->db->table('form_field')->set($data)->where('id', $field['id'])->update();
                    $id_field = (int) $field['id'];
                } else {
                    $this->db->table('form_field')->insert($data);
                    $id_field = (int) $this->db->insertID();
                }

                $ids[] = $id_field;
                if(!empty($field['key'])) {
                    $field_key_to_id[$field['key']] = $id_field;
                }
                $field_key_to_id['f' . $id_field] = $id_field;

                $this->saveFormFieldLang($id_field, $field['lang']);

                // Opcje synchronizujemy WYLACZNIE dla typu `select`. Gdyby robic to
                // bezwarunkowo, przypadkowa zmiana typu (select -> text) skasowalaby
                // definicje opcji nieodwracalnie; tak zostaja i wracaja po cofnieciu.
                if($type === 'select') {
                    $this->saveFieldOptions($id_field, !empty($field['option']) ? $field['option'] : array(), $option_key_to_id);
                }

                // `has_parent_key` odroznia "uzytkownik wybral — brak —" (klucz jest
                // w POSCIE, pusty) od "klucza w ogole nie bylo". Bez tego rozroznienia
                // niepelny POST — inna sciezka kodu, uciety formularz, bot — kasowal
                // WSZYSTKIE warunki formularza, bo przebieg 2 zapisuje parent_field
                // dla kazdego pola z $pending.
                $pending[$id_field] = array(
                    'has_parent_key' => array_key_exists('parent_key', $field),
                    'parent_key' => !empty($field['parent_key']) ? $field['parent_key'] : '',
                    'parent_values' => !empty($field['parent_values']) ? (array) $field['parent_values'] : array(),
                    'order' => (int) $field['order'],
                );
            }
        }

        // ---------- Kasowanie pol usunietych z DOM ----------
        if($fields_present) {
            $query = $this->db->table('form_field')->select('id')->where('id_form', $id_form);
            if(!empty($ids)) {
                $query->whereNotIn('id', $ids);
            }
            $fields_list = $query->get()->getResultArray();
            foreach($fields_list as $field) {
                $this->deleteFieldCascade($field['id']);
            }
            // Osierocone warunki: pola wskazujace na skasowanego rodzica.
            if(!empty($ids)) {
                $this->db->table('form_field')
                    ->set(array('parent_field' => 0, 'parent_values' => ''))
                    ->where('id_form', $id_form)
                    ->whereNotIn('parent_field', array_merge(array(0), $ids))
                    ->update();
            }
        }

        // ---------- PRZEBIEG 2: przepisanie kluczy na realne ID ----------
        foreach($pending as $id_field => $p) {
            // Klucza nie bylo w POSCIE — zostawiamy warunek nietkniety. Zerowanie
            // ma znaczyc "uzytkownik wybral — brak —", a nie "pole nie dojechalo".
            if(empty($p['has_parent_key'])) {
                continue;
            }

            $parent_field = 0;
            $parent_values = '';

            if(!empty($p['parent_key']) && isset($field_key_to_id[$p['parent_key']])) {
                $candidate = (int) $field_key_to_id[$p['parent_key']];
                $row = $this->db->table('form_field')->select('id,type,`order`')->where('id', $candidate)->get()->getRowArray();

                // Rodzic musi istniec, byc selectem, stac PRZED dzieckiem i nie tworzyc cyklu.
                if(!empty($row) && $row['type'] === 'select' && (int) $row['order'] < $p['order'] && !$this->wouldCreateCycle($candidate, $id_field)) {
                    $values = array();
                    foreach($p['parent_values'] as $option_key) {
                        if(isset($option_key_to_id[$option_key])) {
                            $values[] = (int) $option_key_to_id[$option_key];
                        }
                    }
                    // Opcje musza nalezec DO tego rodzica.
                    if(!empty($values)) {
                        $owned = $this->db->table('form_field_option')->select('id')->where('id_field', $candidate)->whereIn('id', $values)->get()->getResultArray();
                        $values = array_map('intval', array_column($owned, 'id'));
                    }
                    if(!empty($values)) {
                        $parent_field = $candidate;
                        $parent_values = implode(',', $values);
                    }
                }
            }

            // Utrata warunku bywa niezamierzona — np. gdy JS panelu nie zdazyl
            // wypelnic `select.parent-select` opcjami, przez co przyszedl pusty,
            // nieodroznialny od swiadomego "— brak —". Zapisujemy to w logu,
            // zeby taka strata przestala byc cicha.
            if($parent_field === 0) {
                $before = $this->db->table('form_field')->select('parent_field')->where('id', $id_field)->get()->getRowArray();
                if(!empty($before['parent_field'])) {
                    log_message('warning', 'FormModel: pole ' . $id_field . ' traci warunek (parent_field '
                        . $before['parent_field'] . ' -> 0). Jesli to nie byla swiadoma zmiana w panelu, '
                        . 'sprawdz czy formularz wyslal `parent_key`.');
                }
            }

            $this->db->table('form_field')
                ->set(array('parent_field' => $parent_field, 'parent_values' => $parent_values))
                ->where('id', $id_field)
                ->update();
        }
    }

    /**
     * Zabezpieczenie przed cyklem w lancuchu warunkow (recznie spreparowany POST,
     * przyszly reorder). Idzie w gore od kandydata na rodzica.
     */
    private function wouldCreateCycle($parent_id, $child_id, $depth = 0)
    {
        if($depth > 10) {
            return true;
        }
        if((int) $parent_id === (int) $child_id) {
            return true;
        }
        $row = $this->db->table('form_field')->select('parent_field')->where('id', $parent_id)->get()->getRowArray();
        if(empty($row) || empty($row['parent_field'])) {
            return false;
        }
        return $this->wouldCreateCycle((int) $row['parent_field'], $child_id, $depth + 1);
    }

    /**
     * Upsert opcji jednego selecta + kasowanie opcji usunietych z DOM.
     * Mapa $option_key_to_id jest uzupelniana dla przebiegu 2 w saveFormFields().
     */
    private function saveFieldOptions($id_field, $options, &$option_key_to_id)
    {
        $ids = array();
        $order = 0;

        foreach((array) $options as $option) {
            $names = !empty($option['lang']) ? array_column($option['lang'], 'name') : array();
            // Pomijamy wiersze calkiem puste (admin kliknal „dodaj" i nic nie wpisal).
            if(empty($option['id']) && trim(implode('', $names)) === '') {
                continue;
            }

            $data = array(
                'id_field' => $id_field,
                'order' => $order++,
                'publish' => 1,
            );

            if(!empty($option['id']) && !empty($this->db->table('form_field_option')->select('id')->where('id', $option['id'])->where('id_field', $id_field)->get()->getResultArray())) {
                // slug NIE jest regenerowany dla istniejacej opcji — musi byc stabilny.
                $this->db->table('form_field_option')->set($data)->where('id', $option['id'])->update();
                $id_option = (int) $option['id'];
            } else {
                $base = reset($names);
                $data['slug'] = !empty($option['slug'])
                    ? mb_substr($option['slug'], 0, 100)
                    : mb_substr(mb_url_title(!empty($base) ? $base : 'opcja', '-', true), 0, 100);
                $this->db->table('form_field_option')->insert($data);
                $id_option = (int) $this->db->insertID();
            }

            $ids[] = $id_option;
            if(!empty($option['key'])) {
                $option_key_to_id[$option['key']] = $id_option;
            }
            $option_key_to_id['o' . $id_option] = $id_option;

            $this->saveFieldOptionLang($id_option, !empty($option['lang']) ? $option['lang'] : array());
        }

        $query = $this->db->table('form_field_option')->select('id')->where('id_field', $id_field);
        if(!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $removed = $query->get()->getResultArray();
        foreach($removed as $option) {
            $this->db->table('form_field_option_lang')->where('id_option', $option['id'])->delete();
            $this->db->table('form_field_option')->where('id', $option['id'])->delete();
        }
    }

    private function saveFieldOptionLang($id_option, $lang_data)
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_option' => $id_option,
                    'id_lang' => $id_lang,
                    'name' => !empty($lang['name']) ? $lang['name'] : '',
                );
                $row = $this->db->table('form_field_option_lang')->select('id')->where('id_option', $id_option)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($row) && !empty($row['id'])) {
                    $this->db->table('form_field_option_lang')->set($data)->where('id_option', $id_option)->where('id_lang', $id_lang)->update();
                } else {
                    $this->db->table('form_field_option_lang')->insert($data);
                }
            }
        }
    }

    /**
     * Kasowanie pola wraz z tlumaczeniami i opcjami. Publiczne, bo uzywa tego
     * takze FormAdmin::deletePageModule() przy usuwaniu calego bloku.
     */
    public function deleteFieldCascade($id_field)
    {
        $options = $this->db->table('form_field_option')->select('id')->where('id_field', $id_field)->get()->getResultArray();
        foreach($options as $option) {
            $this->db->table('form_field_option_lang')->where('id_option', $option['id'])->delete();
        }
        $this->db->table('form_field_option')->where('id_field', $id_field)->delete();
        $this->db->table('form_field_lang')->where('id_field', $id_field)->delete();
        $this->db->table('form_field')->where('id', $id_field)->delete();
    }

    private function saveFormFieldLang($id_field, $lang_data)
    {
        if(!empty($lang_data)) {
            foreach($lang_data as $id_lang=>$lang) {
                $data = array(
                    'id_field' => $id_field,
                    'id_lang' => $id_lang,
                    'name' => $lang['name'],
					'description'=>$lang['description']
                );
                $field = $this->db->table('form_field_lang')->select('id')->where('id_field', $id_field)->where('id_lang', $id_lang)->get()->getRowArray();
                if(!empty($field) && !empty($field['id'])) {
                    $result = $this->db->table('form_field_lang')->set($data)->where('id_field', $id_field)->where('id_lang', $id_lang)->update();
                } else {
                    $result = $this->db->table('form_field_lang')->insert($data);
                }
            }
        }
    }
}
