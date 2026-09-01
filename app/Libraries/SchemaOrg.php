<?php

namespace App\Libraries;

/**
 * Budowa grafu schema.org (WebSite + LocalBusiness) dla <head>.
 *
 * Do wersji 35c1ca4 blok siedzial literalem w Page::getDefaultMetatags() i opisywal
 * portal informacyjny RESinet.pl z Rzeszowa (NewsMediaOrganization, adres przy
 * Twardowskiego, areaServed = Podkarpackie). Tutaj wszystko pochodzi z ustawien
 * panelu, wiec zmiana adresu czy godzin nie wymaga deployu.
 *
 * Zwracana tablica ma ksztalt, ktorego oczekuje generateJsonLdHtml*() z
 * website_helper.php: lista wezlow, kazdy przepuszczany przez arrayToSchema().
 * Ten helper wycina puste wartosci (skipEmpty), wiec nieuzupelnione ustawienie
 * po prostu znika z grafu zamiast wypuszczac pusty string.
 */
class SchemaOrg {

    /** Mapa skrotow z pola `opening_hours_spec` na nazwy dni schema.org. */
    private const DAYS = [
        'mo' => 'Monday',
        'tu' => 'Tuesday',
        'we' => 'Wednesday',
        'th' => 'Thursday',
        'fr' => 'Friday',
        'sa' => 'Saturday',
        'su' => 'Sunday',
    ];

    /**
     * Caly graf: ['website' => [...], 'organization' => [...]].
     * @param array $settings wynik SettingsModel::getSettings($id_lang)
     * @param array $language wiersz z tabeli `language` (lang_code / iso_code)
     */
    public function graph($settings, $language = array()) {
        return [
            'website'      => $this->websiteNode($settings, $language),
            'organization' => $this->organizationNode($settings, $language),
        ];
    }

    public function websiteNode($settings, $language = array()) {
        return [
            '@type'      => 'WebSite',
            '@id'        => base_url() . '#website',
            'url'        => base_url(),
            'name'       => $this->str($settings, 'company_name'),
            'description' => $this->str($settings, 'meta_description'),
            'inLanguage' => $this->langCode($language),
            'publisher'  => ['@id' => base_url() . '#organization'],
        ];
    }

    public function organizationNode($settings, $language = array()) {
        $node = [
            // LocalBusiness, a nie "ComputerRepairService": tego drugiego typu schema.org
            // nie ma w slowniku (krazy po poradnikach SEO, ale https://schema.org/ComputerRepairService
            // zwraca 404 — dla napraw istnieja tylko AutoRepair i MotorcycleRepair).
            // Nierozpoznany typ walidator odrzucal, a przy okazji unieważnial kazda wlasciwosc,
            // ktora na ten wezel wskazuje: WebSite.publisher i Service.provider.
            // Branze niesie opis, slogan i wezly Service, nie sama nazwa typu.
            '@type'       => 'LocalBusiness',
            '@id'         => base_url() . '#organization',
            'url'         => base_url(),
            'name'        => $this->str($settings, 'company_name'),
            'alternateName' => $this->str($settings, 'company_short_name'),
            'description' => $this->str($settings, 'meta_description'),
            'slogan'      => $this->str($settings, 'slogan'),
            'telephone'   => $this->str($settings, 'phone'),
            'email'       => $this->str($settings, 'email'),
            'priceRange'  => $this->str($settings, 'price_range'),
            'foundingDate' => $this->str($settings, 'founding_date'),
            'vatID'       => $this->str($settings, 'nip'),
            'address'     => $this->address($settings),
            'geo'         => $this->geo($settings),
            'openingHoursSpecification' => $this->parseOpeningHours($this->str($settings, 'opening_hours_spec')),
            'areaServed'  => $this->areaServed($settings),
            'sameAs'      => $this->sameAs($settings),
        ];

        $logo = $this->logoUrl($settings);
        if ($logo !== '') {
            $node['logo']  = ['@type' => 'ImageObject', 'url' => $logo];
            $node['image'] = $logo;
        }

        // Jeden punkt kontaktu dla klientow — poprzednie 'editorial'/'advertising'
        // to byly role redakcji portalu.
        $phone = $this->str($settings, 'phone');
        $email = $this->str($settings, 'email');
        if ($phone !== '' || $email !== '') {
            $node['contactPoint'] = [[
                '@type'             => 'ContactPoint',
                'contactType'       => 'customer service',
                'telephone'         => $phone,
                'email'             => $email,
                'availableLanguage' => ['Polish'],
            ]];
        }

        return $node;
    }

    /**
     * FAQPage z sekcji pytan i odpowiedzi (element `faq` modulu Isense).
     *
     * Uwaga: od wrzesnia 2023 Google pokazuje rozwiniecia FAQ w wynikach praktycznie
     * tylko witrynom rzadowym i medycznym. Wezel zostaje po to, zeby wyszukiwarki
     * i modele jezykowe rozumialy tresc strony, a nie dla rich resultow.
     *
     * @param array  $items lista ['question' => ..., 'answer' => ...]
     * @param string $url   adres strony, na ktorej sekcja stoi
     */
    public function faqNode($items, $url = '') {
        $questions = array();
        foreach ((array) $items as $item) {
            $question = isset($item['question']) ? trim((string) $item['question']) : '';
            $answer   = isset($item['answer']) ? trim((string) $item['answer']) : '';
            // Polowa pary nie tworzy sensownego Question — pomijamy cala pozycje.
            if ($question === '' || $answer === '') {
                continue;
            }
            $questions[] = [
                '@type'          => 'Question',
                'name'           => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $answer,
                ],
            ];
        }

        if (empty($questions)) {
            return array();
        }

        return [
            '@type'      => 'FAQPage',
            '@id'        => ($url !== '' ? $url : base_url()) . '#faq',
            'mainEntity' => $questions,
        ];
    }

    /**
     * Usluga swiadczona przez firme. `provider` celuje w wezel organizacji przez @id,
     * wiec graf zostaje spojny bez powtarzania NAP-u.
     *
     * @param array $params ['name', 'url', 'description', 'serviceType', 'areaServed', 'offerCatalog']
     */
    public function serviceNode($params = array()) {
        $name = isset($params['name']) ? trim((string) $params['name']) : '';
        if ($name === '') {
            return array();
        }
        $url = isset($params['url']) ? trim((string) $params['url']) : '';

        $node = [
            '@type'       => 'Service',
            '@id'         => ($url !== '' ? $url : base_url()) . '#service',
            'name'        => $name,
            'url'         => $url,
            'description' => isset($params['description']) ? trim((string) $params['description']) : '',
            'serviceType' => isset($params['serviceType']) ? trim((string) $params['serviceType']) : '',
            'provider'    => ['@id' => base_url() . '#organization'],
            'areaServed'  => [[
                '@type' => 'Country',
                'name'  => ! empty($params['areaServed']) ? $params['areaServed'] : 'Polska',
            ]],
        ];

        if (! empty($params['offerCatalog'])) {
            $node['hasOfferCatalog'] = $params['offerCatalog'];
        }

        return $node;
    }

    /**
     * OfferCatalog z drzewa cennika (PricingCategoryModel::getTreeForFront()):
     * kategoria → usluga → model z cena. Przy jednej kategorii katalog jest plaski,
     * przy kilku — kazda kategoria dostaje wlasny zagniezdzony OfferCatalog.
     *
     * Cena musi byc surowa liczba; sformatowane "460 zl" z Pricing::formatPrice()
     * jest dla czlowieka i schema.org go nie przyjmie.
     *
     * @param array  $categories drzewo kategorii z modulu Pricing
     * @param string $name       nazwa katalogu (zwykle tytul strony)
     * @param string $currency   kod ISO waluty
     */
    public function offerCatalogNode($categories, $name = '', $currency = 'PLN') {
        $groups = array();
        foreach ((array) $categories as $category) {
            $offers = $this->categoryOffers($category, $currency);
            if (empty($offers)) {
                continue;
            }
            $groups[] = [
                'name'   => isset($category['name']) ? trim((string) $category['name']) : '',
                'offers' => $offers,
            ];
        }

        if (empty($groups)) {
            return array();
        }

        // Jedna kategoria = jeden poziom; zagniezdzanie mialoby wtedy pusta wartosc informacyjna.
        if (count($groups) === 1) {
            return [
                '@type'           => 'OfferCatalog',
                'name'            => $name !== '' ? $name : $groups[0]['name'],
                'itemListElement' => $groups[0]['offers'],
            ];
        }

        $items = array();
        foreach ($groups as $group) {
            $items[] = [
                '@type'           => 'OfferCatalog',
                'name'            => $group['name'],
                'itemListElement' => $group['offers'],
            ];
        }

        return [
            '@type'           => 'OfferCatalog',
            'name'            => $name,
            'itemListElement' => $items,
        ];
    }

    /** Oferty jednej kategorii cennika: po jednej na model z ustalona cena. */
    private function categoryOffers($category, $currency) {
        $offers = array();
        foreach ((array) ($category['services'] ?? array()) as $service) {
            $service_name = isset($service['name']) ? trim((string) $service['name']) : '';
            foreach ((array) ($service['models'] ?? array()) as $model) {
                $price = isset($model['price']) ? $model['price'] : null;
                // NULL/0 znaczy "wycena indywidualna" — Offer bez ceny wprowadzalby w blad.
                if (! is_numeric($price) || (float) $price <= 0) {
                    continue;
                }
                $model_name = isset($model['name']) ? trim((string) $model['name']) : '';
                $offers[] = [
                    '@type'         => 'Offer',
                    'name'          => trim($service_name . ($model_name !== '' ? ' — ' . $model_name : '')),
                    'price'         => $this->price($price),
                    'priceCurrency' => $currency,
                    'availability'  => 'https://schema.org/InStock',
                    'itemOffered'   => [
                        '@type' => 'Service',
                        'name'  => $service_name,
                    ],
                ];
            }
        }

        return $offers;
    }

    /** "460.00" → "460", "99.50" → "99.50"; kropka dziesietna, bez separatora tysiecy. */
    private function price($price) {
        $value = (float) $price;
        // Grosze zostaja tylko wtedy, gdy sa niezerowe — jak w formatPrice() dla czlowieka.
        $decimals = (fmod($value, 1.0) === 0.0) ? 0 : 2;

        return number_format($value, $decimals, '.', '');
    }

    /**
     * PostalAddress w calosci z kluczy address_* panelu. Wyswietlany, wieloliniowy
     * `address` zostaje tekstem dla czlowieka i nie trafia tutaj.
     */
    private function address($settings) {
        $address = [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $this->str($settings, 'address_street'),
            'postalCode'      => $this->str($settings, 'address_postal_code'),
            'addressLocality' => $this->str($settings, 'address_city'),
            'addressRegion'   => $this->str($settings, 'address_region'),
            'addressCountry'  => $this->str($settings, 'address_country'),
        ];

        // Bez ulicy i miasta adres nie niesie informacji — lepiej go nie emitowac.
        if ($address['streetAddress'] === '' && $address['addressLocality'] === '') {
            return array();
        }

        return $address;
    }

    private function geo($settings) {
        $lat = $this->str($settings, 'geo_lat');
        $lng = $this->str($settings, 'geo_lng');
        if (! is_numeric($lat) || ! is_numeric($lng)) {
            return array();
        }

        // Wartosci zostaja stringami (schema.org dopuszcza Number albo Text).
        // Rzutowanie na float dawalo na produkcji "52.241900000000001" — json_encode
        // uzywa serialize_precision z php.ini, a ono bywa ustawione na 17.
        return [
            '@type'     => 'GeoCoordinates',
            'latitude'  => $lat,
            'longitude' => $lng,
        ];
    }

    /** Lista po przecinku, np. "Warszawa, Polska" → dwa wezly Place. */
    private function areaServed($settings) {
        $areas = array();
        foreach (explode(',', $this->str($settings, 'area_served')) as $name) {
            $name = trim($name);
            if ($name !== '') {
                $areas[] = ['@type' => 'Place', 'name' => $name];
            }
        }

        return $areas;
    }

    private function sameAs($settings) {
        $links = array();
        foreach (['facebook', 'youtube', 'instagram', 'twitter', 'tiktok'] as $key) {
            $url = $this->str($settings, $key);
            if ($url !== '') {
                $links[] = $url;
            }
        }

        return $links;
    }

    /**
     * URL logotypu: najpierw plik z panelu, potem logo SVG, na koncu statyczna
     * grafika motywu — zeby wezel nigdy nie wypuscil pustego 'url'.
     */
    private function logoUrl($settings) {
        if (! empty($settings['logo']['path'])) {
            return base_url('image/original/' . $settings['logo']['path']);
        }
        $svg = $this->str($settings, 'logo_svg');
        if ($svg !== '') {
            return $svg;
        }

        return '';
    }

    /**
     * Parser pola `opening_hours_spec` — format maszynowy, jedna regula w linii:
     *   Mo-Fr 09:00-19:00
     *   Sa 10:00-14:00
     *   Mo,We,Fr 09:00-17:00
     * Osobne od wyswietlanego `opening_hours`, bo tamto jest tekstem po polsku
     * i jego parsowanie byloby zgadywanka.
     */
    private function parseOpeningHours($spec) {
        $rules = array();
        foreach (preg_split('/[\r\n;]+/', (string) $spec) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            if (! preg_match('/^([A-Za-z,\-]+)\s+(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})$/', $line, $m)) {
                continue;
            }
            $days = $this->parseDays($m[1]);
            if (empty($days)) {
                continue;
            }
            $rules[] = [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => $days,
                'opens'     => $this->time($m[2]),
                'closes'    => $this->time($m[3]),
            ];
        }

        return $rules;
    }

    /** "Mo-Fr" / "Mo,We" / "Sa" → ['Monday', ...]. */
    private function parseDays($input) {
        $order = array_values(self::DAYS);
        $days  = array();
        foreach (explode(',', $input) as $part) {
            $part = trim($part);
            if (strpos($part, '-') !== false) {
                list($from, $to) = array_map('trim', explode('-', $part, 2));
                $from = self::DAYS[strtolower(substr($from, 0, 2))] ?? null;
                $to   = self::DAYS[strtolower(substr($to, 0, 2))] ?? null;
                if ($from === null || $to === null) {
                    continue;
                }
                $i   = array_search($from, $order, true);
                $end = array_search($to, $order, true);
                // Zakres moze przechodzic przez niedziele (np. Sa-Mo).
                while (true) {
                    $days[] = $order[$i];
                    if ($i === $end) {
                        break;
                    }
                    $i = ($i + 1) % count($order);
                }
            } else {
                $day = self::DAYS[strtolower(substr($part, 0, 2))] ?? null;
                if ($day !== null) {
                    $days[] = $day;
                }
            }
        }

        return array_values(array_unique($days));
    }

    /** "9:00" → "09:00" (schema.org oczekuje formatu HH:MM). */
    private function time($value) {
        return sprintf('%02d:%s', (int) strtok($value, ':'), substr($value, strpos($value, ':') + 1));
    }

    private function langCode($language) {
        if (! empty($language['iso_code'])) {
            return $language['iso_code'];
        }

        return ! empty($language['lang_code']) ? $language['lang_code'] : '';
    }

    /** Ustawienie jako string — klucze plikowe (logo, favicon) sa tablicami. */
    private function str($settings, $key) {
        $value = $settings[$key] ?? null;

        return (is_scalar($value) && trim((string) $value) !== '') ? trim((string) $value) : '';
    }
}
