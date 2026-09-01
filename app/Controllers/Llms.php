<?php

namespace App\Controllers;

use App\Models\SettingsModel;

/**
 * /llms.txt — mapa serwisu dla modeli językowych, budowana z zawartości CMS.
 *
 * PO CO: plik leżał w public/ jako statyczny tekst pisany ręcznie i rozjechał
 * się z serwisem — wskazywał osiem adresów, które nigdy nie powstały
 * (/naprawy/*, /simlock, /odzyskiwanie-danych), stronę nieopublikowaną
 * (/status-naprawy), a wszystkie linki prowadziły na inną domenę niż ta, z
 * której plik był serwowany. llms.txt czytają modele językowe, więc każdy
 * martwy adres to wprost zła odpowiedź o serwisie. Teraz nie ma czego
 * aktualizować ręcznie: strona dodana, ukryta albo przeniesiona w panelu
 * zmienia ten plik przy najbliższym żądaniu.
 *
 * ŹRÓDŁA (nic nie jest wpisane na sztywno):
 *   - domena       → base_url(), czyli app.baseURL z .env danego środowiska;
 *   - lista stron  → tabela `page` (tylko publish = 1 i no_index = 0), adresy z
 *                    `links`, wiec zmiana adresu w panelu przechodzi tu sama;
 *   - sekcje       → drzewo stron (`page`.`re_id`): strona z opublikowanymi
 *                    podstronami zostaje sekcja, reszta ladu je w sekcji zbiorczej;
 *   - opisy pozycji→ `page_meta_lang`.`description` (panel → strona → SEO);
 *   - dane firmy   → tabela `settings` (adres, telefon, e-mail, godziny...).
 *
 * Strona bez opisu SEO trafia do pliku jako sam link — to nie blad, tylko
 * material do uzupelnienia w panelu.
 *
 * UWAGA: trasa dziala tylko dopoki w public/ NIE MA pliku llms.txt — regula
 * front controllera w .htaccess przepuszcza zadanie do PHP wylacznie dla
 * nieistniejacych plikow (RewriteCond %{REQUEST_FILENAME} !-f).
 */
class Llms extends BaseController
{
    /** Naglowek sekcji zbiorczej dla stron bez podstron. */
    private const FLAT_SECTION = 'Pozostałe strony';

    public function index()
    {
        helper('url');
        $db = \Config\Database::connect();

        $lang = $db->table('language')->select('id')->where('default', 1)->get()->getRowArray();
        $idLang = ! empty($lang) ? (int) $lang['id'] : 1;
        $base   = rtrim(base_url(), '/');

        $pages    = $this->pages($db, $idLang);
        $settings = (new SettingsModel())->getSettings($idLang);

        $this->response->setContentType('text/plain');

        return view('llms', [
            'name'     => $this->text($settings['company_name'] ?? '') ?: ($this->text($settings['company_short_name'] ?? '') ?: 'Serwis'),
            'intro'    => $this->text($settings['meta_description'] ?? ''),
            'sections' => $this->sections($pages, $base),
            'company'  => $this->company($settings),
            'sitemap'  => $base . '/sitemap.xml',
        ]);
    }

    /**
     * /llms-full.txt — to samo zestawienie plus pelna tresc kazdej strony, zeby
     * model mial caly serwis w jednym pobraniu, bez chodzenia po podstronach.
     * Tresc czytamy z blokow modulu Isense (JSON w `isense_section_lang`), wiec
     * obejmuje dokladnie to, co redaktor widzi w panelu.
     */
    public function full()
    {
        helper('url');
        $db = \Config\Database::connect();

        $lang   = $db->table('language')->select('id')->where('default', 1)->get()->getRowArray();
        $idLang = ! empty($lang) ? (int) $lang['id'] : 1;
        $base   = rtrim(base_url(), '/');

        $pages    = $this->pages($db, $idLang);
        $settings = (new SettingsModel())->getSettings($idLang);

        // Kolejnosc jak w llms.txt (strona glowna, potem sekcje z drzewa stron),
        // zeby oba pliki czytalo sie tak samo.
        $ordered = [];
        foreach ($pages as $p) {
            if ($p['link'] === '') {
                $ordered[] = $this->item($p, $base);
            }
        }
        foreach ($this->sections($pages, $base) as $section) {
            foreach ($section['items'] as $item) {
                $ordered[] = $item;
            }
        }

        $documents = [];
        foreach ($ordered as $item) {
            $item['body'] = $this->pageContent($db, $item['id'], $idLang);
            $documents[]  = $item;
        }

        $this->response->setContentType('text/plain');

        return view('llms_full', [
            'name'      => $this->text($settings['company_name'] ?? '') ?: 'Serwis',
            'intro'     => $this->text($settings['meta_description'] ?? ''),
            'documents' => $documents,
            'company'   => $this->company($settings),
            'sitemap'   => $base . '/sitemap.xml',
            'index_url' => $base . '/llms.txt',
        ]);
    }

    /**
     * Tresc strony: bloki po kolei, z kazdego same pola tekstowe. Bloki trzymaja
     * dane jako JSON o dowolnej glebokosci (sekcja → items → pola), stad
     * rekurencja i odsiew kluczy technicznych w textFields().
     */
    private function pageContent($db, int $idPage, int $idLang): array
    {
        if (! is_dir(ROOTPATH . 'modules/Isense') || ! class_exists('\Modules\Isense\Models\IsenseSectionModel')) {
            return [];
        }
        $blocks = $db->table('page_content')
            ->select('id')->where('id_page', $idPage)->orderBy('order', 'ASC')
            ->get()->getResultArray();

        $model = new \Modules\Isense\Models\IsenseSectionModel();
        $lines = [];
        foreach ($blocks as $b) {
            foreach ($this->textFields($model->getFields((int) $b['id'], $idLang)) as $line) {
                // Ten sam naglowek potrafi sie powtorzyc miedzy blokami (np. CTA)
                // — powtorzenie pod rzad nic nie wnosi, a psuje czytelnosc.
                if (end($lines) !== $line) {
                    $lines[] = $line;
                }
            }
        }

        return $lines;
    }

    /**
     * Klucze techniczne bloku — ikony, sciezki, klasy CSS, a takze etykiety
     * przyciskow ("Zobacz szczegoly"), ktore w zrzucie tresci sa samym szumem.
     */
    private const SKIP_FIELDS = '/(^|_)(icon|img|image|images|photo|photos|url|href|link|slug|base|class|css|svg|color|bg|background|target|id|key|type|variant|style|align|ratio|sizes|loading|fetchpriority|video|map|anchor|recipient|order|width|height|side|position|cols|columns|count|label|button|btn|cta|currency|unit|symbol|prefix|suffix)$/i';

    /** Wartosci ustawien ukladu — pojedyncze slowa-przelaczniki, nie tresc. */
    private const SKIP_VALUES = '/^(left|right|center|top|bottom|none|auto|default|yes|no|true|false|on|off|light|dark|small|medium|large|full|half)$/i';

    /** Rekurencyjnie wyciaga z tablicy pol bloku same wartosci tekstowe. */
    private function textFields(array $fields): array
    {
        $out = [];
        foreach ($fields as $key => $value) {
            if (is_string($key) && preg_match(self::SKIP_FIELDS, $key)) {
                continue;
            }
            if (is_array($value)) {
                $out = array_merge($out, $this->textFields($value));
                continue;
            }
            if (! is_string($value)) {
                continue;
            }
            // Sciezki, adresy i nazwy plikow — resztki po polach technicznych
            // o nietypowej nazwie.
            if ($value === '' || preg_match('#^(https?://|/|\#)#', $value) || preg_match('/\.(png|jpe?g|webp|svg|gif)$/i', $value)) {
                continue;
            }
            // Liczby (liczba kolumn, licznik) i przelaczniki ukladu.
            if (is_numeric($value) || preg_match(self::SKIP_VALUES, $value)) {
                continue;
            }
            foreach (preg_split('/\R/u', $this->paragraphs($value)) as $line) {
                $line = trim(preg_replace('/[ \t]+/u', ' ', $line));
                if ($line !== '') {
                    $out[] = $line;
                }
            }
        }

        return $out;
    }

    /** HTML z edytora do tekstu z zachowaniem podzialu na akapity i punkty list. */
    private function paragraphs(string $html): string
    {
        $html = preg_replace('#<(br|/p|/li|/h[1-6]|/tr)[^>]*>#i', "\n", $html);
        // Pozostale znaczniki na spacje, nie na nic: <span>✓</span>Tekst bez tego
        // sklejalby sie w "✓Tekst".
        $html = preg_replace('#<[^>]+>#', ' ', $html);

        return trim(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /**
     * Opublikowane i indeksowalne strony jezyka domyslnego, w kolejnosci z panelu.
     * `re_id` = strona nadrzedna (0 = poziom glowny) — na tym budujemy sekcje.
     */
    private function pages($db, int $idLang): array
    {
        $rows = $db->table('page p')
            ->join('page_lang pl', 'pl.id_page = p.id AND pl.id_lang = ' . $idLang)
            ->join('links l', 'l.id = pl.id_link')
            ->join('page_meta_lang pm', 'pm.id_page = p.id AND pm.id_lang = ' . $idLang, 'left')
            ->select('p.id, p.re_id, pl.name, l.link, pm.description')
            ->where('p.publish', 1)
            ->where('p.no_index', 0)
            ->orderBy('p.order', 'ASC')
            ->orderBy('pl.name', 'ASC')
            ->get()->getResultArray();

        $pages = [];
        foreach ($rows as $r) {
            $pages[(int) $r['id']] = [
                'id'          => (int) $r['id'],
                'parent'      => (int) $r['re_id'],
                'name'        => $this->text((string) $r['name']),
                'link'        => trim((string) $r['link'], '/'),
                'description' => $this->text((string) ($r['description'] ?? '')),
            ];
        }

        return $pages;
    }

    /**
     * Sekcje: strona majaca opublikowane podstrony otwiera sekcje (sama tez jest
     * jej pierwsza pozycja), pozostale strony poziomu glownego ida do sekcji
     * zbiorczej na koncu. Strone glowna pomijamy — to sama domena z naglowka.
     */
    private function sections(array $pages, string $base): array
    {
        $children = [];
        foreach ($pages as $p) {
            if ($p['parent'] !== 0 && isset($pages[$p['parent']])) {
                $children[$p['parent']][] = $p;
            }
        }

        $sections = [];
        $flat     = [];
        foreach ($pages as $p) {
            if ($p['link'] === '' || ($p['parent'] !== 0 && isset($pages[$p['parent']]))) {
                continue;
            }
            if (! empty($children[$p['id']])) {
                $items = [$this->item($p, $base)];
                foreach ($children[$p['id']] as $c) {
                    $items[] = $this->item($c, $base);
                }
                $sections[] = ['title' => $p['name'], 'items' => $items];
                continue;
            }
            $flat[] = $this->item($p, $base);
        }

        if (! empty($flat)) {
            $sections[] = ['title' => self::FLAT_SECTION, 'items' => $flat];
        }

        return $sections;
    }

    private function item(array $p, string $base): array
    {
        return [
            'id'          => $p['id'],
            'name'        => $p['name'],
            'url'         => $base . '/' . $p['link'],
            'description' => $p['description'],
        ];
    }

    /** Dane firmy z panelu; pozycje nieuzupelnione pomijamy. */
    private function company(array $settings): array
    {
        $address = $this->text($settings['address'] ?? '');
        if ($address === '') {
            $address = trim(implode(', ', array_filter([
                $this->text($settings['address_street'] ?? ''),
                trim($this->text($settings['address_postal_code'] ?? '') . ' ' . $this->text($settings['address_city'] ?? '')),
            ])), ', ');
        }

        $rows = [
            'Nazwa'            => $this->text($settings['company_name'] ?? ''),
            'Adres'            => $address,
            'Telefon'          => $this->text($settings['phone'] ?? ''),
            'E-mail'           => $this->text($settings['email'] ?? ''),
            'Godziny otwarcia' => $this->text($settings['opening_hours'] ?? '', '; '),
            'Rok założenia'    => $this->text($settings['founding_date'] ?? ''),
            'Zasięg'           => $this->text($settings['area_served'] ?? ''),
        ];

        return array_filter($rows, static fn ($v) => $v !== '');
    }

    /**
     * Wartosc z panelu do jednej linii: llms.txt jest lista, a pola bywaja
     * wielolinijkowe (adres, godziny) albo maja zapisane wprost "\n". Zdejmujemy
     * tez HTML, ktory mogl wejsc z edytora. $glue rozdziela sklejone linie —
     * przy godzinach otwarcia sama spacja zlepilaby dwa osobne wiersze w jeden
     * nieczytelny ciag.
     */
    private function text($value, string $glue = ' '): string
    {
        if (! is_scalar($value)) {
            return '';
        }
        $value = str_replace(['\r\n', '\n', '\r', "\r\n", "\n", "\r"], $glue, (string) $value);
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = trim(preg_replace('/\s+/u', ' ', $value));

        return trim($value, " {$glue}");
    }
}
