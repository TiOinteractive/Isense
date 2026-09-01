<?php

/**
 * Atrybuty pol formularza po stronie przegladarki (modul Form).
 *
 * Ladowane przez helper('form_field'). Nazwa celowo NIE brzmi `form_helper`,
 * bo taki plik istnieje juz w rdzeniu CodeIgnitera i oba zostalyby zaladowane.
 *
 * PO CO: widoki renderowaly kazde pole jako <input type="text"> bez `required`,
 * `autocomplete` i `inputmode`. Skutki: na telefonie zadne pole nie przelaczalo
 * klawiatury na numeryczna/mailowa, autouzupelnianie danych kontaktowych nie
 * dzialalo (WCAG 1.3.5 Identify Input Purpose), a blad byl widoczny dopiero po
 * odpowiedzi serwera.
 *
 * Zrodlem prawdy jest kolumna `form_field.validation` ustawiana w panelu — ta
 * sama, ktora Libraries/Form::ajax() zamienia na reguly serwerowe. Dzieki temu
 * podpowiedzi przegladarki i walidacja serwera nie moga sie rozjechac.
 *
 * WAZNE: `pattern` jest CELOWO luzniejszy od reguly serwerowej (np. NIP nie ma
 * tu sumy kontrolnej). Walidacja kliencka ma prowadzic uzytkownika, a nie byc
 * druga implementacja regul — decyduje zawsze CustomRules po stronie PHP.
 */

if (!function_exists('form_field_validation_map')) {
    /**
     * Mapa `validation` -> atrybuty HTML.
     *
     * `type` dotyczy wylacznie pol typu `text` (textarea i number maja swoj typ).
     * `title` trafia do dymka przegladarki przy niezgodnosci z `pattern`.
     */
    function form_field_validation_map()
    {
        return array(
            // 'name' i 'address' nie dodaja zadnej reguly serwerowej — sa
            // wylacznie znacznikiem (patrz switch w Libraries/Form::ajax()).
            'name' => array('autocomplete' => 'name'),
            'address' => array('autocomplete' => 'street-address'),
            'email' => array(
                'type' => 'email',
                'autocomplete' => 'email',
                'inputmode' => 'email',
            ),
            'phone' => array(
                'type' => 'tel',
                'autocomplete' => 'tel',
                'inputmode' => 'tel',
                // Serwer (valid_phone) usuwa spacje, kropki, myslniki i nawiasy,
                // a potem wymaga 7-14 cyfr — tu dopuszczamy te same znaki.
                // UWAGA: przegladarka kompiluje `pattern` z flaga `v`, w ktorej
                // nawiasy, kropka i myslnik MUSZA byc w klasie znakow
                // wyescapowane. Bez tego wyrazenie sie nie kompiluje i caly
                // atrybut jest po cichu ignorowany — pole przyjmowaloby wtedy
                // dowolny tekst.
                'pattern' => '[+]?[0-9 \(\)\.\-]{7,20}',
                'title' => 'Form.hint.Phone',
            ),
            'zip_code' => array(
                'autocomplete' => 'postal-code',
                'inputmode' => 'numeric',
                'pattern' => '[0-9]{2}-?[0-9]{3}',
                'title' => 'Form.hint.ZipCode',
            ),
            'nip' => array(
                'inputmode' => 'numeric',
                // Bez sumy kontrolnej — te sprawdza valid_nip na serwerze.
                // Myslnik wyescapowany — patrz uwaga o fladze `v` przy 'phone'.
                'pattern' => '[0-9 \-]{10,13}',
                'title' => 'Form.hint.NIP',
            ),
            'regon' => array(
                'inputmode' => 'numeric',
                'pattern' => '[0-9]{9}',
                'title' => 'Form.hint.Regon',
            ),
            'pesel' => array(
                'inputmode' => 'numeric',
                'pattern' => '[0-9]{11}',
                'title' => 'Form.hint.Pesel',
            ),
        );
    }
}

if (!function_exists('form_field_input_type')) {
    /**
     * Typ <input> dla pola tekstowego: 'email' / 'tel' / 'text'.
     */
    function form_field_input_type($field)
    {
        $map = form_field_validation_map();
        $key = !empty($field['validation']) ? $field['validation'] : '';
        return isset($map[$key]['type']) ? $map[$key]['type'] : 'text';
    }
}

if (!function_exists('form_field_attrs')) {
    /**
     * Atrybuty autouzupelniania, klawiatury mobilnej i wymagalnosci.
     *
     * @param array  $field    wiersz z form_field (+ name/description z *_lang)
     * @param string $tag      'input' | 'textarea' | 'select' | 'file' | 'checkbox'
     * @return string gotowy do wklejenia w znacznik (zaczyna sie od spacji)
     */
    function form_field_attrs($field, $tag = 'input')
    {
        $map = form_field_validation_map();
        $key = !empty($field['validation']) ? $field['validation'] : '';
        $rules = isset($map[$key]) ? $map[$key] : array();

        $attrs = array();
        foreach (array('autocomplete', 'inputmode', 'pattern') as $name) {
            if (empty($rules[$name])) {
                continue;
            }
            // `pattern` obowiazuje tylko dla <input> o typie tekstowym —
            // w <textarea> i <select> jest ignorowany, wiec go nie emitujemy.
            if ($name === 'pattern' && $tag !== 'input') {
                continue;
            }
            $attrs[$name] = $rules[$name];
        }
        if (!empty($rules['title']) && isset($attrs['pattern'])) {
            $attrs['title'] = lang($rules['title']);
        }

        if (!empty($field['required'])) {
            $attrs['aria-required'] = 'true';
            if (empty($field['parent_field'])) {
                $attrs['required'] = 'required';
            } else {
                // Pole warunkowe startuje ukryte (style="display:none"). Gdyby
                // dostalo `required` juz w HTML, przegladarka odmowilaby wyslania
                // formularza z bledem, ktorego nie ma jak pokazac — assets/js/form.js
                // przenosi ten znacznik na `required` dopiero, gdy pole sie pojawi.
                $attrs['data-required'] = '1';
            }
        }

        $out = '';
        foreach ($attrs as $name => $value) {
            $out .= ' ' . $name . '="' . esc($value, 'attr') . '"';
        }
        return $out;
    }
}
