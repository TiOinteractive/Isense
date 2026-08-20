<?php

/**
 * Limity uploadu dla modulu Form.
 *
 * Ladowane przez helper('form_limits'). Nazwa celowo NIE brzmi `form_helper`,
 * bo taki plik istnieje juz w rdzeniu CodeIgnitera i oba zostalyby zaladowane.
 */

if (!function_exists('form_ini_bytes')) {
    /**
     * Zamienia zapis z php.ini ('2M', '512K', '1G') na bajty.
     */
    function form_ini_bytes($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return 0;
        }
        $unit = strtolower($value[strlen($value) - 1]);
        $number = (int) $value;
        switch ($unit) {
            case 'g': $number *= 1024;
            case 'm': $number *= 1024;
            case 'k': $number *= 1024;
        }
        return $number;
    }
}

if (!function_exists('form_php_upload_max_kb')) {
    /**
     * Twardy sufit narzucony przez PHP: mniejsza z wartosci
     * upload_max_filesize / post_max_size. 0 = brak limitu w php.ini.
     */
    function form_php_upload_max_kb()
    {
        $upload = form_ini_bytes(ini_get('upload_max_filesize'));
        $post = form_ini_bytes(ini_get('post_max_size'));

        $limits = array_filter(array($upload, $post));
        if (empty($limits)) {
            return 0;
        }
        return (int) floor(min($limits) / 1024);
    }
}

if (!function_exists('form_effective_max_kb')) {
    /**
     * Efektywny limit pojedynczego pliku w KB.
     *
     * Konfiguracja pola moze limit TYLKO obnizyc, nigdy podniesc — nad nia stoi
     * Config\Images::$maxFileSize, a nad wszystkim twardy sufit z php.ini.
     */
    function form_effective_max_kb($field_kb = 0)
    {
        $config = new \Config\Images();
        $global = (int) $config->maxFileSize;
        if ($global <= 0) {
            $global = 5120;
        }

        $limit = (int) $field_kb > 0 ? min((int) $field_kb, $global) : $global;

        $php_kb = form_php_upload_max_kb();
        if ($php_kb > 0) {
            $limit = min($limit, $php_kb);
        }
        return (int) $limit;
    }
}
