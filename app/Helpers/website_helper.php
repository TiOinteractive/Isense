<?php

/**
 * Uniwersalna funkcja konwertująca tablicę na strukturę Schema.org
 * Każdy klucz tablicy staje się parametrem Schema, wartość klucza staje się wartością Schema
 */
function arrayToSchema($data, $options = []) {
    // Domyślne opcje
    $defaultOptions = [
        'skipEmpty' => true,           // Pomijaj puste wartości
        'convertDates' => true,        // Automatyczna konwersja dat
        'convertNumbers' => true,      // Automatyczna konwersja liczb
        'timezone' => 'Europe/Warsaw', // Strefa czasowa dla dat
        'dateFields' => ['datePublished', 'dateModified', 'dateCreated'], // Pola z datami
        'numberFields' => ['width', 'height', 'wordCount', 'position'], // Pola numeryczne
        'urlFields' => ['url', '@id', 'sameAs'], // Pola URL
        // Pola które mają pozostać tablicami (bez tego wycięcie pustego elementu
        // rozjeżdża klucze i json_encode robi z listy obiekt {"0":...})
        'preserveArrays' => ['keywords', 'sameAs', 'image', 'contactPoint', 'areaServed', 'openingHoursSpecification', 'dayOfWeek', 'availableLanguage', 'knowsAbout', 'mainEntity', 'itemListElement', 'offers']
    ];
    
    $options = array_merge($defaultOptions, $options);
    
    if (!is_array($data)) {
        return $data;
    }
    
    $result = [];
    
    foreach ($data as $key => $value) {
        // Pomijanie pustych wartości jeśli włączone
        if ($options['skipEmpty'] && isEmpty($value)) {
            continue;
        }
        
        // Rekursywne przetwarzanie zagnieżdżonych tablic
        if (is_array($value)) {
            // Sprawdź czy to tablica z pojedynczymi elementami które powinny pozostać tablicami
            if (in_array($key, $options['preserveArrays'])) {
                $result[$key] = processArrayValue($value, $options);
            } else {
                $result[$key] = arrayToSchema($value, $options);
            }
        } else {
            // Przetwarzanie pojedynczych wartości
            $result[$key] = processValue($key, $value, $options);
        }
    }
    
    return $result;
}

/**
 * Przetwarzanie pojedynczej wartości na podstawie klucza
 */
function processValue($key, $value, $options) {
    // Konwersja dat
    if ($options['convertDates'] && in_array($key, $options['dateFields'])) {
        return convertDate($value, $options['timezone']);
    }
    
    // Konwersja liczb
    if ($options['convertNumbers'] && in_array($key, $options['numberFields'])) {
        return convertNumber($value);
    }
    
    // Przetwarzanie URL-i
    if (in_array($key, $options['urlFields'])) {
        return processUrl($value);
    }
    
    // Zwrócenie wartości bez zmian
    return $value;
}

/**
 * Przetwarzanie wartości tablicowych
 */
function processArrayValue($array, $options) {
    if (!is_array($array)) {
        return $array;
    }
    
    $result = [];
    foreach ($array as $item) {
        if (is_array($item)) {
            $result[] = arrayToSchema($item, $options);
        } else {
            $result[] = $item;
        }
    }
    
    return $result;
}

/**
 * Sprawdzanie czy wartość jest pusta
 */
function isEmpty($value) {
    if (is_string($value)) {
        return trim($value) === '';
    }
    if (is_array($value)) {
        return empty($value);
    }
    return $value === null || $value === '';
}

/**
 * Konwersja daty do formatu ISO 8601
 */
function convertDate($dateString, $timezone = 'Europe/Warsaw') {
    if (isEmpty($dateString)) {
        return null;
    }
    
    try {
        $date = new DateTime($dateString);
        $date->setTimezone(new DateTimeZone($timezone));
        return $date->format('c'); // ISO 8601 z timezone
    } catch (Exception $e) {
        return $dateString; // Zwróć oryginalną wartość w przypadku błędu
    }
}

/**
 * Konwersja do liczby
 */
function convertNumber($value) {
    if (isEmpty($value)) {
        return null;
    }
    
    if (is_numeric($value)) {
        return is_float($value) ? (float)$value : (int)$value;
    }
    
    return $value;
}

/**
 * Przetwarzanie URL-i
 */
function processUrl($url) {
    if (isEmpty($url)) {
        return null;
    }
    
    // Dodanie protokołu jeśli brakuje
    if (is_string($url) && !preg_match('/^https?:\/\//', $url)) {
        return 'https://' . ltrim($url, '/');
    }
    
    return $url;
}

/**
 * Funkcja generująca kompletną strukturę JSON-LD dla listy artykułów
 * @param array $articlesArray - tablica z artykułami
 * @param array $schemaData - tablica z danymi Schema zawierająca WebSite, Organization i CollectionPage
 */
function generateCompleteJsonLd($schemaData = []) {
    
    $graph = [];
    if(!empty($schemaData)) {
        foreach($schemaData as $data) {
            $graph[] = arrayToSchema($data);
        } 
    }
    
    // Kompletna struktura JSON-LD
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => $graph
    ];
    
    return $jsonLd;
}

/**
 * Generowanie gotowego kodu HTML z JSON-LD
 * @param array $articlesArray - tablica z artykułami
 * @param array $schemaData - tablica z danymi Schema zawierająca WebSite, Organization i CollectionPage
 */
function generateJsonLdHtml($schemaData = [], $minify = true, $prettyPrint = false) {
    $jsonLd = generateCompleteJsonLd($schemaData);
    
    // Wybór flag JSON na podstawie opcji minifikacji
    if ($minify) {
        // Minifikacja - bez spacji, bez pretty print
        $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        $jsonString = json_encode($jsonLd, $jsonFlags);
        
        // Dodatkowa minifikacja - usunięcie wszystkich niepotrzebnych spacji
        $jsonString = minifyJson($jsonString);
        
        return '<script type="application/ld+json">' . $jsonString . '</script>';
    } else {
        // Bez minifikacji - z formatowaniem lub bez
        $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        if ($prettyPrint) {
            $jsonFlags |= JSON_PRETTY_PRINT;
        }
        
        $jsonString = json_encode($jsonLd, $jsonFlags);
        
        if ($prettyPrint) {
            return '<script type="application/ld+json">' . "\n" . $jsonString . "\n" . '</script>';
        } else {
            return '<script type="application/ld+json">' . $jsonString . '</script>';
        }
    }
}

/**
 * Dodatkowa funkcja do głębszej minifikacji JSON
 * @param string $json - JSON string do minifikacji
 * @return string - zminifikowany JSON
 */
function minifyJson($json) {
    // Usuń wszystkie niepotrzebne spacje poza tymi w wartościach stringów
    $minified = '';
    $inString = false;
    $escaped = false;
    
    for ($i = 0; $i < strlen($json); $i++) {
        $char = $json[$i];
        
        if ($escaped) {
            $minified .= $char;
            $escaped = false;
            continue;
        }
        
        if ($char === '\\') {
            $minified .= $char;
            $escaped = true;
            continue;
        }
        
        if ($char === '"') {
            $minified .= $char;
            $inString = !$inString;
            continue;
        }
        
        if ($inString) {
            $minified .= $char;
            continue;
        }
        
        // Poza stringami - usuń zbędne spacje i nowe linie
        if (in_array($char, [' ', "\t", "\n", "\r"])) {
            continue;
        }
        
        $minified .= $char;
    }
    
    return $minified;
}

/**
 * Funkcja pomocnicza do generowania JSON-LD w trybie deweloperskim (z formatowaniem)
 * @param array $schemaData - tablica z danymi Schema
 */
function generateJsonLdHtmlDev($schemaData = []) {
    return generateJsonLdHtml($schemaData, false, true);
}

/**
 * Funkcja pomocnicza do generowania JSON-LD w trybie produkcyjnym (minifikowany)
 * @param array $schemaData - tablica z danymi Schema
 */
function generateJsonLdHtmlProd($schemaData = []) {
    return generateJsonLdHtml($schemaData, true);
}

/**
 * Generuje schema BreadcrumbList w JSON-LD na podstawie tablicy okruszków.
 * Pierwszy element listy to zawsze strona główna.
 * @param array  $breadcrumbs - tablica [['name'=>'...','link'=>'/...'], ...]
 * @param string $siteName    - nazwa strony głównej (np. company_name z ustawień)
 * @param string $baseUrl     - adres bazowy z trailing slash (base_url())
 */
function generateBreadcrumbListJsonLd($breadcrumbs, $siteName = 'Strona główna', $baseUrl = '') {
    $base = rtrim($baseUrl, '/');
    $items = [[
        '@type'    => 'ListItem',
        'position' => 1,
        'name'     => $siteName,
        'item'     => $base . '/',
    ]];
    foreach ($breadcrumbs as $i => $crumb) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $i + 2,
            'name'     => $crumb['name'],
            'item'     => $base . $crumb['link'],
        ];
    }
    $jsonLd = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
    return '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}




/**
 * METODA 1: Rekursywna funkcja głębokiego łączenia (ZALECANA)
 * Łączy tablice rekursywnie, zachowując strukturę zagnieżdżonych elementów
 */
function deepArrayMerge($array1, $array2) {
    $merged = $array1;
    
    foreach ($array2 as $key => $value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            // Jeśli oba elementy to tablice - łącz rekursywnie
            $merged[$key] = deepArrayMerge($merged[$key], $value);
        } else {
            // W przeciwnym razie nadpisz wartość
            $merged[$key] = $value;
        }
    }
    
    return $merged;
}

/**
 * METODA 2: Wersja dla wielu tablic naraz
 * Pozwala łączyć więcej niż 2 tablice jednocześnie
 */
function deepArrayMergeMultiple(...$arrays) {
    $result = [];
    
    foreach ($arrays as $array) {
        if (is_array($array)) {
            $result = deepArrayMerge($result, $array);
        }
    }
    
    return $result;
}
/**
 * Sortuje alfabetycznie (A-Z) drzewo stron z PageModel::getPagesStructure().
 *
 * Rekurencyjnie, wiec podstrony w kazdej galezi tez ida po nazwie, a struktura
 * drzewa (klucz 'list', poziom zagniezdzenia) zostaje bez zmian.
 *
 * Collator z ext-intl (twarde wymaganie CI4) ustawia polskie znaki na wlasciwych
 * miejscach — zwykle strcmp/strcasecmp wrzuciloby "Ł" i "Ż" za "Z", bo porownuje
 * bajty UTF-8. Locale bierzemy z aktualnego jezyka panelu, ktory CI4 ustawia
 * przez Locale::setDefault(). Gdyby intl nie bylo, jest fallback na strcasecmp.
 *
 * Uwaga: kolejnosc z kolumny page.order (uzywana przez getPagesStructure) jest
 * tu nadpisywana. Panel nie ma UI do recznego ustawiania kolejnosci stron, wiec
 * nie odbiera to nikomu zapisanego ukladu.
 */
function sortPagesAlphabetically(array $pages, $collator = null) {
    if (empty($pages)) {
        return $pages;
    }
    if ($collator === null && class_exists('Collator')) {
        $collator = collator_create(locale_get_default());
    }
    usort($pages, function ($a, $b) use ($collator) {
        $x = (string) (isset($a['name']) ? $a['name'] : '');
        $y = (string) (isset($b['name']) ? $b['name'] : '');
        return $collator ? $collator->compare($x, $y) : strcasecmp($x, $y);
    });
    foreach ($pages as $k => $page) {
        if (!empty($page['list'])) {
            $pages[$k]['list'] = sortPagesAlphabetically($page['list'], $collator);
        }
    }
    return $pages;
}
