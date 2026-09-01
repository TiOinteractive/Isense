<?php

/**
 * isense_helper — pomocnicze funkcje front-endu iSense.
 *
 * Ikony pochodzą z zestawu lucide (https://lucide.dev), osadzone jako inline SVG,
 * aby nie wymagać zewnętrznego JS. Kolor dziedziczony przez `currentColor`,
 * grubość/wypełnienie sterowane klasami Tailwind (np. text-[#3b81f7], fill-[#3b81f7]).
 */

if (! function_exists('isense_icon')) {
    /**
     * Zwraca inline SVG ikony lucide.
     *
     * @param string $name  nazwa ikony (patrz $paths poniżej)
     * @param string $class klasy CSS nakładane na <svg>
     * @param array  $attrs dodatkowe atrybuty (np. ['stroke-width' => '1.5'])
     */
    function isense_icon(string $name, string $class = 'w-5 h-5', array $attrs = []): string
    {
        static $paths = [
            'arrow-right'   => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'arrow-left'    => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
            'chevron-down'  => '<path d="m6 9 6 6 6-6"/>',
            'chevron-left'  => '<path d="m15 18-6-6 6-6"/>',
            'chevron-right' => '<path d="m9 18 6-6-6-6"/>',
            'phone'         => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
            'menu'          => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
            'x'             => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
            'home'          => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'smartphone'    => '<rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/>',
            'tablet'        => '<rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><line x1="12" x2="12.01" y1="18" y2="18"/>',
            'monitor'       => '<rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>',
            'laptop'        => '<path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16"/>',
            'headphones'    => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
            'watch'         => '<circle cx="12" cy="12" r="6"/><polyline points="12 10 12 12 13 13"/><path d="m16.13 7.66-.81-4.05a2 2 0 0 0-2-1.61h-2.68a2 2 0 0 0-2 1.61l-.78 4.05"/><path d="m7.88 16.36.8 4a2 2 0 0 0 2 1.61h2.72a2 2 0 0 0 2-1.61l.81-4.05"/>',
            'zap'           => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
            'clipboard-list' => '<rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>',
            'package-search' => '<path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/><circle cx="18.5" cy="15.5" r="2.5"/><path d="M20.27 17.27 22 19"/>',
            'refresh-cw'    => '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>',
            'check-circle'  => '<path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/>',
            'star'          => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
            'quote'         => '<path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/><path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>',
            'facebook'      => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
            'instagram'     => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>',
            'youtube'       => '<path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/>',
            'twitter'       => '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>',
            'tiktok'        => '<path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/>',
            'linkedin'      => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/>',
            'mail'          => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
            'map-pin'       => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
            'clock'         => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'send'          => '<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/>',
            'award'         => '<path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/>',
            'users'         => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
            'target'        => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
            'heart'         => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
            'shield'        => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
            'truck'         => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
            'package'       => '<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><polyline points="3.29 7 12 12 20.71 7"/><path d="m7.5 4.27 9 5.15"/>',
            'search'        => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
            'wrench'        => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
            'credit-card'   => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
            'banknote'      => '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
            'battery'       => '<rect width="16" height="10" x="2" y="7" rx="2" ry="2"/><line x1="22" x2="22" y1="11" y2="13"/>',
            'circle-help'   => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
            'chevron-right-c' => '<path d="m9 18 6-6-6-6"/>',
            'trending-up'   => '<path d="M16 7h6v6"/><path d="m22 7-8.5 8.5-5-5L2 17"/>',
            'gift'          => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
            'dollar-sign'   => '<line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
        ];

        $body = $paths[$name] ?? '';

        $extra = '';
        foreach ($attrs as $k => $v) {
            $extra .= ' ' . $k . '="' . esc($v, 'attr') . '"';
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"'
            . ' fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"'
            . ' class="' . esc($class, 'attr') . '"' . $extra . ' aria-hidden="true">' . $body . '</svg>';
    }
}

if (! function_exists('isense_img')) {
    /**
     * Renderuje <img> dla grafiki motywu iSense z wariantami WebP.
     *
     * Warianty (public/assets/isense/img/opt/*.webp) i manifest generuje
     * `php spark isense:images` — helper tylko sklada je w srcset/sizes.
     * Zrodlowe PNG/JPG zostaja na dysku jako material wyjsciowy i jako
     * fallback: gdy grafiki nie ma w manifescie (swiezo wgrany plik albo
     * sciezka z CMS-a spoza tego katalogu), wychodzi zwykly <img> na oryginal.
     *
     * @param string $src   nazwa pliku ('hero.png') albo pelna sciezka/URL
     * @param string $alt   tekst alternatywny
     * @param string $class klasy CSS
     * @param array  $opts  sizes, loading ('lazy'|'eager'), fetchpriority
     */
    function isense_img(string $src, string $alt = '', string $class = '', array $opts = []): string
    {
        if (trim($src) === '') {
            return '';
        }

        $sizes    = $opts['sizes'] ?? '';
        $loading  = $opts['loading'] ?? 'lazy';
        $priority = $opts['fetchpriority'] ?? '';

        $file  = isense_img_basename($src);
        $entry = $file === null ? null : (isense_img_manifest()[$file] ?? null);

        $attrs = ' alt="' . esc($alt, 'attr') . '"';
        if ($class !== '') {
            $attrs .= ' class="' . esc($class, 'attr') . '"';
        }
        $attrs .= ' loading="' . esc($loading, 'attr') . '" decoding="async"';
        if ($priority !== '') {
            $attrs .= ' fetchpriority="' . esc($priority, 'attr') . '"';
        }

        if ($entry === null) {
            $url = preg_match('~^(https?:)?/~', $src) ? $src : base_url('assets/isense/img/' . $src);

            return '<img src="' . esc($url, 'attr') . '"' . $attrs . '>';
        }

        $base   = rtrim(base_url('assets/isense/img'), '/') . '/';
        $srcset = [];
        foreach ($entry['srcset'] as [$w, $path]) {
            $srcset[] = $base . $path . ' ' . $w . 'w';
        }
        // src = najwiekszy wariant: przegladarka bez obslugi srcset dostaje
        // pelna rozdzielczosc, a nie przypadkowa miniature.
        $largest = $base . end($entry['srcset'])[1];

        return '<img src="' . esc($largest, 'attr') . '"'
            . ' srcset="' . esc(implode(', ', $srcset), 'attr') . '"'
            . ($sizes !== '' ? ' sizes="' . esc($sizes, 'attr') . '"' : '')
            . ' width="' . (int) $entry['w'] . '" height="' . (int) $entry['h'] . '"'
            . $attrs . '>';
    }

    /**
     * Sam URL wariantu — dla tel CSS (background-image), gdzie nie ma srcset.
     * Zwraca najmniejszy wariant o szerokosci >= $targetWidth (albo najwiekszy
     * dostepny), a dla grafik spoza manifestu — oryginalny adres.
     */
    function isense_img_url(string $src, int $targetWidth = 1600): string
    {
        $file  = isense_img_basename($src);
        $entry = $file === null ? null : (isense_img_manifest()[$file] ?? null);
        if ($entry === null) {
            return preg_match('~^(https?:)?/~', $src) ? $src : base_url('assets/isense/img/' . $src);
        }

        $base   = rtrim(base_url('assets/isense/img'), '/') . '/';
        $chosen = end($entry['srcset'])[1];
        foreach ($entry['srcset'] as [$w, $path]) {
            if ($w >= $targetWidth) {
                $chosen = $path;
                break;
            }
        }

        return $base . $chosen;
    }

    /** Nazwa pliku, jesli $src wskazuje na katalog grafik motywu; inaczej null. */
    function isense_img_basename(string $src): ?string
    {
        $src = trim($src);
        if ($src === '') {
            return null;
        }
        if (! str_contains($src, '/')) {
            return $src;
        }

        return str_contains($src, '/assets/isense/img/') ? basename(parse_url($src, PHP_URL_PATH) ?: '') : null;
    }

    /** Manifest wariantow; wczytywany raz na request. */
    function isense_img_manifest(): array
    {
        static $manifest = null;
        if ($manifest === null) {
            $path     = FCPATH . 'assets/isense/img/opt/manifest.json';
            $manifest = is_file($path) ? (json_decode((string) file_get_contents($path), true) ?: []) : [];
        }

        return $manifest;
    }
}

if (! function_exists('isense_settings')) {
    /**
     * Cała tablica ustawień serwisu dla bieżącego języka — dokładnie to samo
     * źródło, co zmienna $settings w widokach CMS-a (SettingsModel::getSettings).
     * Wartości per-język są już spłaszczone do stringów, a logo/favicon/meta_photo
     * to tablice rekordu z tio_files (klucz 'path').
     *
     * Widoki motywu iSense nie dostają $settings od kontrolera — partiale są
     * włączane z pięciu miejsc przez dwa kontrolery — więc zamiast przepychać
     * tablicę przez wszystkie te ścieżki, biorą ją stąd. Jedno zapytanie na
     * request, wynik trzymany statycznie.
     */
    function isense_settings(): array
    {
        static $settings = null;
        if ($settings === null) {
            $settings = (new \App\Models\SettingsModel())->getSettings(isense_lang_id());
        }

        return $settings;
    }
}

if (! function_exists('isense_setting')) {
    /**
     * Pojedyncze ustawienie z tablicy isense_settings(). Klucze plikowe
     * (logo, favicon…) są tablicami — dla nich zwracamy $default, bo funkcja
     * ma kontrakt stringowy; od nich jest isense_logo().
     */
    function isense_setting(string $name, string $default = ''): string
    {
        $val = isense_settings()[$name] ?? null;
        if ($val === null || $val === '' || ! is_scalar($val)) {
            return $default;
        }

        return (string) $val;
    }
}

if (! function_exists('isense_phone')) {
    /**
     * Numer telefonu serwisu (panel → Ustawienia → Numer telefonu).
     * Fallback trzyma dotychczasowy numer, żeby pusta wartość w panelu
     * nie skasowała telefonu z nagłówka i stopki.
     */
    function isense_phone(): string
    {
        return isense_setting('phone', '+48 504 806 905');
    }
}

if (! function_exists('isense_tel')) {
    /** Ten sam numer w formacie do href="tel:" — bez spacji, myślników i nawiasów. */
    function isense_tel(): string
    {
        return preg_replace('/[^0-9+]/', '', isense_phone());
    }
}

if (! function_exists('isense_logo')) {
    /**
     * URL logotypu z ustawień (panel → Ustawienia → Logo / Logo na ciemnym tle).
     * Zwraca pusty string, gdy logo nie jest ustawione — wtedy widok bierze plik statyczny.
     */
    function isense_logo(string $name = 'logo'): string
    {
        $path = trim((string) (isense_settings()[$name]['path'] ?? ''));

        return $path === '' ? '' : base_url('image/original/' . $path);
    }
}

if (! function_exists('isense_menu')) {
    /**
     * Zwraca drzewo menu CMS (zarządzane w panelu → Menu) z rozwiązanymi URL-ami.
     * Każda pozycja: ['name','url','target','icon','class','children'].
     * type 'page' → URL z powiązanej strony (id_target); type 'own' → własny URL.
     * Menu odpublikowane w panelu (menu.publish = 0) zwraca pustą tablicę.
     */
    function isense_menu(int $id_menu, ?int $id_lang = null): array
    {
        $id_lang = $id_lang ?? isense_lang_id();
        $db      = \Config\Database::connect();

        $menu = $db->table('menu')->select('id')
            ->where('id', $id_menu)->where('publish', 1)
            ->get()->getRowArray();
        if ($menu === null) {
            return [];
        }

        return isense_menu_branch($db, $id_menu, 0, $id_lang);
    }

    function isense_menu_branch($db, int $id_menu, int $id_parent, int $id_lang): array
    {
        $rows = $db->table('menu_item mi')
            ->join('menu_item_lang mil', 'mil.id_menu_item = mi.id')
            ->select('mi.id, mi.id_target, mi.type, mi.svg, mi.class, mi.target, mil.name, mil.url')
            ->where('mi.id_menu', $id_menu)
            ->where('mi.id_parent', $id_parent)
            ->where('mil.id_lang', $id_lang)
            ->orderBy('mi.order', 'ASC')
            ->get()->getResultArray();

        $out = [];
        foreach ($rows as $r) {
            $url = '#';
            if ($r['type'] === 'own') {
                $url = ($r['url'] !== '' && $r['url'] !== null) ? $r['url'] : '#';
            } elseif (! empty($r['id_target'])) {
                $link = $db->table('links l')
                    ->join('page_lang pl', 'pl.id_link = l.id')
                    ->join('page p', 'p.id = pl.id_page')
                    ->select('l.link')
                    ->where('p.id', $r['id_target'])->where('pl.id_lang', $id_lang)->where('p.publish', 1)
                    ->get()->getRowArray();
                if ($link !== null) {
                    $url = ($link['link'] === '' || $link['link'] === null) ? site_url('/') : site_url($link['link']);
                }
            }
            $out[] = [
                'name'     => $r['name'],
                'url'      => $url,
                'target'   => $r['target'] ?: '',
                'icon'     => $r['svg'] ?: null,
                'class'    => $r['class'] ?: null,
                'children' => isense_menu_branch($db, $id_menu, (int) $r['id'], $id_lang),
            ];
        }
        return $out;
    }
}

if (! function_exists('isense_lang_id')) {
    /** Id bieżącego języka (z locale requestu; fallback: język domyślny / 1). */
    function isense_lang_id($db = null): int
    {
        static $id = null;
        if ($id !== null) {
            return $id;
        }
        $db = $db ?? \Config\Database::connect();
        $locale = service('request')->getLocale();
        $row = $db->table('language')->select('id')
            ->groupStart()->where('lang_code', $locale)->orWhere('slug', $locale)->groupEnd()
            ->get()->getRowArray();
        if (! empty($row)) {
            return $id = (int) $row['id'];
        }
        $def = $db->table('language')->select('id')->where('`default`', 1)->get()->getRowArray();
        return $id = (int) ($def['id'] ?? 1);
    }
}

if (! function_exists('isense_announcement')) {
    /**
     * Zwraca dane paska ogłoszeniowego z ustawień: ['enabled' => bool, 'text' => string].
     * Zarządzane w panelu: Ustawienia → „Pasek ogłoszeniowy".
     */
    function isense_announcement(): array
    {
        // isense_settings() rozwiązuje już wersję językową announcement_text,
        // więc pasek nie potrzebuje własnych zapytań.
        $enabled = isense_setting('announcement_enabled') !== '' && isense_setting('announcement_enabled') !== '0';
        $text    = $enabled ? trim(isense_setting('announcement_text')) : '';

        return ['enabled' => $enabled && $text !== '', 'text' => $text];
    }
}
