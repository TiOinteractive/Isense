<?php

namespace App\Libraries;

/**
 * View cells motywu iSense oparte na module Menu.
 *
 * Wolane z widokow przez view_cell() — konwencja string-based, taka sama jak
 * \App\Libraries\Page::showMenu czy \Modules\Foto\Libraries\Foto::showCats:
 * klucze tablicy parametrow odpowiadaja nazwom argumentow metody.
 */
class IsenseMenu
{
    /**
     * Przyciski CTA nagłówka — każda pozycja menu to osobny przycisk.
     * Edytowalne w panelu → Menu (etykieta, adres, kolejność).
     *
     * @param int    $id_menu id menu z panelu
     * @param string $variant miejsce na stronie: desktop | mobile | sticky
     */
    public function buttons(int $id_menu, string $variant = 'desktop'): string
    {
        // isense_menu() rozwiązuje adresy stron przez site_url(), więc helper
        // url też musi być załadowany — cell bywa wołany poza kontrolerem.
        helper(['url', 'isense']);
        $buttons = isense_menu($id_menu);
        if (empty($buttons)) {
            // Pusty string zamiast pustego kontenera — inaczej zostawialibysmy
            // w ukladzie odstep po nieistniejacych przyciskach.
            return '';
        }

        return view('isense/partials/menu-buttons', [
            'buttons' => $buttons,
            'variant' => $variant,
        ]);
    }

    /**
     * Pozioma lista linkow (uzywana w sekcji „Popularne dzialy" strony 404).
     * Adresy pochodza z modulu Menu, wiec nie moga rozjechac sie ze stronami.
     *
     * Pozycje bez rozwiazanego adresu (url '#') sa pomijane — na stronie bledu
     * link donikad byloby druga slepa uliczka. Zagniezdzenia sa splaszczane:
     * lista jest jednopoziomowa, wiec dzieci renderuja sie obok rodzicow.
     *
     * @param int    $id_menu id menu z panelu
     * @param string $heading naglowek nad lista (pusty = bez naglowka)
     */
    public function inlineLinks(int $id_menu, string $heading = ''): string
    {
        helper(['url', 'isense']);

        $flatten = static function (array $items) use (&$flatten): array {
            $out = [];
            foreach ($items as $item) {
                if ($item['url'] !== '#') {
                    $out[] = $item;
                }
                $out = array_merge($out, $flatten($item['children'] ?? []));
            }

            return $out;
        };

        $links = $flatten(isense_menu($id_menu));
        if ($links === []) {
            // Pusty string zamiast pustej ramki z naglowkiem i kreska nad nia.
            return '';
        }

        return view('isense/partials/menu-inline-links', [
            'links'   => $links,
            'heading' => $heading,
        ]);
    }
}
