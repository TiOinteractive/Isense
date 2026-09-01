<?php

namespace Modules\Isense\Libraries;

use Modules\Isense\Models\IsenseSectionModel;

/**
 * Front-end sekcji strony głównej iSense (bloki treści CMS).
 *
 * Każdy blok strony wskazuje na "element" modułu (element_slug = typ sekcji: hero, cta-boxes, ...).
 * index() ładuje zapisane pola sekcji (JSON) i zwraca je do szablonu
 * modules/Isense/Views/user/<element_slug>/tpl.php. Gdy brak danych — zwraca sensowne domyślne,
 * dzięki czemu świeżo dodany blok od razu wygląda zgodnie z projektem.
 */
class Isense
{
    public $locale;
    public $global_links;
    public $settings;
    public $is_mobile;

    protected $model;

    public function __construct()
    {
        $this->model = new IsenseSectionModel();
    }

    public function index($content, $id_lang, $slug = '', $link = null)
    {
        $fields = $this->model->getFields($content['id'], $id_lang);
        $data = array_merge($this->defaults($slug), $fields);
        $data['id']       = $content['id'];
        $data['section']  = $slug;
        $data['template'] = 'tpl.php'; // plik w modules/Isense/Views/user/<slug>/tpl.php
        return $data;
    }

    /**
     * Dane strukturalne bloku. Wołane przez Home::index() dla każdego bloku treści;
     * kontrakt wymaga zwrócenia CAŁEJ tablicy $metatags, nie tylko własnego fragmentu.
     *
     * Dziś odpowiada tylko sekcja FAQ — jej pytania i odpowiedzi to jedyne pola iSense,
     * które mają wprost odpowiednik w schema.org. Pozostałe sekcje są dekoracją strony.
     */
    public function getContentMetaTags($content, $metatags, $data, $settings, $language)
    {
        if (empty($data['section']) || $data['section'] !== 'faq' || empty($data['items'])) {
            return $metatags;
        }

        $url  = ! empty($metatags['canonical']) ? $metatags['canonical'] : current_url();
        $node = (new \App\Libraries\SchemaOrg())->faqNode($data['items'], $url);
        if (! empty($node)) {
            $metatags['microdata']['faq'] = $node;
        }

        return $metatags;
    }

    /** CSS/JS iSense dokładane do strony tylko gdy występuje blok iSense. */
    public function assets($element_slug = '', $tpl = '', $id = 0, $data = [])
    {
        $css = ['/assets/isense/css/isense.css'];
        if ($element_slug === 'hero') {
            $css[] = '/assets/isense/css/hero.css';
        }

        return [
            'css' => $css,
            'js'  => ['/assets/isense/js/isense.js'],
        ];
    }

    /** Domyślne pola per typ sekcji. */
    protected function defaults($slug): array
    {
        $a = '/assets/isense/img';
        switch ($slug) {
            case 'hero':
                return [
                    'eyebrow'   => 'Apple Independent Repair Provider',
                    'heading'   => 'Niezależny dostawca usług naprawczych',
                    'cta_label' => 'Zleć naprawę wysyłkową',
                    'cta_url'   => '#naprawa-wysylkowa',
                    'bg'        => $a . '/hero.png',
                ];
            case 'cta-boxes':
                return ['boxes' => [
                    ['icon' => 'clipboard-list', 'title' => 'Zapytaj o wycenę naprawy', 'description' => 'Opisz usterkę, dołącz zdjęcia — nasz serwisant odpowie w ciągu kilku godzin z wyceną i oceną możliwości naprawy.', 'cta' => 'Wyślij zapytanie', 'link' => '/kontakt'],
                    ['icon' => 'package-search', 'title' => 'Zamów odbiór sprzętu', 'description' => 'Wypełnij formularz, a kurier odbierze Twój sprzęt bezpośrednio od Ciebie. Weryfikujemy zlecenie przed wysyłką — zero ryzyka.', 'cta' => 'Zamów odbiór', 'link' => '/naprawa-z-odbiorem'],
                    ['icon' => 'refresh-cw', 'title' => 'Trade-In starego sprzętu', 'description' => 'Wymień stary sprzęt Apple na gotówkę lub rabat na naprawę. Wyceniamy każde urządzenie indywidualnie.', 'cta' => 'Sprawdź wartość', 'link' => '/trade-in'],
                ]];
            case 'intro':
                return [
                    'heading'  => 'Naprawy sprzętu Apple',
                    'subtitle' => '– dla innych to wyzwanie …dla nas przyjemność.',
                    'cards'    => [
                        ['image' => $a . '/intro-technician.png', 'title' => 'Zepsuty sprzęt Apple?', 'text' => 'Nasi doświadczeni serwisanci naprawią Twojego iPhone, iPada, iMaca czy też Macbooka używając tylko najlepszych części.'],
                        ['image' => $a . '/intro-courier.png', 'title' => 'Mieszkasz poza Warszawą?', 'text' => 'Odległość nie stanowi dla nas problemu — zepsuty sprzęt odbierze od Ciebie kurier i dostarczy naprawiony, a Ty możesz poświęcić się pracy.'],
                    ],
                ];
            case 'device':
                return [
                    'heading'     => 'Naprawimy Twojego iPhone!',
                    'body'        => 'Naprawy iPhone wykonujemy zarówno przy klientach w naszym serwisie, jak i w ramach usługi door-to-door — odbieramy kurierem z całej Polski. Dysponujemy częściami do wszystkich modeli iPhone, od najstarszych po najnowsze.',
                    'image'       => $a . '/iphone.png',
                    'image_side'  => 'right',
                    'columns'     => '2',
                    'bg'          => 'gray',
                    'link_label'  => 'Dowiedz się więcej',
                    'link_url'    => '/naprawy/iphone',
                    'repairs'     => [['text' => 'Wymiana wyświetlacza / LCD'], ['text' => 'Wymiana baterii'], ['text' => 'Naprawa po zalaniu'], ['text' => 'Wymiana złącza ładowania']],
                ];
            case 'location-trust':
                return [
                    'heading'   => 'Odwiedź osobiście nasz serwis<br>lub skorzystaj z opcji door-to-door',
                    'lead'      => 'Czekamy na Ciebie w naszym punkcie serwisowym na warszawskim Powiślu w podziemiach biblioteki Uniwersytetu Warszawskiego.',
                    'body'      => "Obsługujemy klientów indywidualnych jak i korporacyjnych z całej Polski.\n\nZdając się na nasz serwis door-to-door masz gwarancję, że Twój sprzęt zostanie zdiagnozowany i naprawiony przez serwisanta wyspecjalizowanego w danej gamie produktowej.",
                    'closing'   => 'W iSense zawsze otrzymasz gwarancję na wykonaną naprawę i użyte części.',
                    'cta_label' => 'Dowiedz się więcej',
                    'cta_url'   => '/kontakt',
                    'options'   => [
                        ['image' => $a . '/loc-serwis.png', 'title' => 'Serwis Warszawa Śródmieście', 'description' => "ul. Dobra 56/66, Budynek Biblioteki UW\n(minus 1, lok. nr A32), 00-312 Warszawa\nPon–Pt 9:00–19:00"],
                        ['image' => $a . '/loc-sklep.png', 'title' => 'Sklep stacjonarny Warszawa Śródmieście', 'description' => 'Odwiedź nas osobiście. Bezpłatna diagnoza na miejscu, wycena od ręki.'],
                        ['image' => $a . '/loc-wysylka.png', 'title' => 'Naprawa wysyłkowa', 'description' => 'Wyślij sprzęt kurierem lub zamów bezpłatny odbiór. Naprawiamy i odsyłamy na terenie całej Polski.'],
                    ],
                ];
            case 'four-features':
                return ['features' => [
                    ['icon' => $a . '/feature-diagnoza.png', 'title' => 'Zawsze bezpłatna diagnoza', 'description' => 'Nie zgaduj, co się stało — zapraszamy na bezpłatną diagnozę. Sprawdzimy Twój sprzęt, określimy usterkę i przedstawimy wycenę, zanim podejmiesz decyzję.'],
                    ['icon' => $a . '/feature-doswiadczenie.png', 'title' => 'Wieloletnie doświadczenie', 'description' => 'Serwisujemy sprzęt Apple nieprzerwanie od 2008 roku. Zaufały nam jednostki administracji publicznej, ministerstwa i międzynarodowe koncerny.'],
                    ['icon' => $a . '/feature-serwis.png', 'title' => 'Wyspecjalizowany serwis', 'description' => 'Naprawiamy wyłącznie sprzęt Apple. 80% urządzeń naprawiamy w ciągu 48 godzin roboczych. Używamy tylko dedykowanych części.'],
                    ['icon' => $a . '/feature-serwisanci.png', 'title' => 'Doświadczeni serwisanci', 'description' => 'Nasi serwisanci naprawią Twojego iPhone\'a, iPada, iMaca czy MacBooka, korzystając wyłącznie z najlepszych części.'],
                ]];
            case 'testimonials':
                return [
                    'heading'  => 'Klienci o nas',
                    'subtitle' => 'Opinie naszych klientów',
                    'items'    => [
                        ['name' => 'Łukasz', 'text' => 'Poinformowali mnie o bezpłatnej wymianie płyty głównej w ramach akcji serwisowej Apple. Inne serwisy wyceniały tę samą naprawę na 1 900–2 400 zł. Niesamowita oszczędność!'],
                        ['name' => 'Paweł', 'text' => 'To są magicy! Myślałem, że dane są bezpowrotnie stracone. Uratowali wszystko w ciągu zaledwie 2 dni. Niesamowita robota!'],
                        ['name' => 'Janek', 'text' => 'Zawsze iSense, bo tu jest sens naprawiać apple\'owe sprzęty! Kilka lat korzystam z ich usług — zawsze szybko i w rozsądnej cenie.'],
                        ['name' => 'Anna', 'text' => 'iPhone\'a odratowali, gdy dwa autoryzowane serwisy twierdziły, że naprawa jest niemożliwa. Polecam z całego serca!'],
                    ],
                ];
            // ── Sekcje wielokrotne (podstrony) ──
            case 'page-hero':
                return [
                    'variant'  => 'light',   // light | dark | contact (kompaktowy, bez zdjęcia)
                    'bg'       => '',        // opcjonalne tło (ścieżka do obrazu)
                    'eyebrow'  => '',
                    'title'    => 'Tytuł strony',
                    'subtitle' => 'Krótki opis pod tytułem.',
                ];
            case 'text-image':
                return [
                    'heading'    => 'Nasza historia',
                    'body'       => "iSense powstało z pasji do technologii Apple i chęci zapewnienia profesjonalnych usług naprawczych na najwyższym poziomie.\n\nNieprzerwanie od 2008 roku specjalizujemy się wyłącznie w serwisie produktów Apple. Ta specjalizacja pozwala nam doskonale znać każdy model i zapewnić najwyższą jakość napraw.\n\nZaufały nam jednostki administracji publicznej, ministerstwa, znane międzynarodowe koncerny i agencje reklamowe — a także klienci indywidualni z całej Polski.",
                    'image'      => '/assets/isense/img/loc-serwis.png',
                    'image_side' => 'right',
                    'bg'         => 'white',
                ];
            case 'info-cards':
                return [
                    'heading' => 'Nasze wartości',
                    'columns' => '4',
                    'bg'      => 'gray',
                    'cards'   => [
                        ['icon' => 'award', 'title' => 'Jakość', 'text' => 'Używamy tylko dedykowanych części i stosujemy najwyższe standardy napraw.'],
                        ['icon' => 'users', 'title' => 'Profesjonalizm', 'text' => 'Nasz zespół to doświadczeni serwisanci z pasją do sprzętu Apple.'],
                        ['icon' => 'target', 'title' => 'Precyzja', 'text' => 'Każda naprawa wykonywana z dbałością o najmniejsze detale.'],
                        ['icon' => 'heart', 'title' => 'Pasja', 'text' => 'Kochamy to, co robimy — i widać to w efektach naszej pracy.'],
                    ],
                ];
            case 'stats':
                return [
                    'heading' => 'iSense w liczbach',
                    'items'   => [
                        ['number' => '2008', 'label' => 'rok założenia'],
                        ['number' => '80%', 'label' => 'napraw w 48h roboczych'],
                        ['number' => '100%', 'label' => 'sprzęt Apple'],
                        ['number' => '1h', 'label' => 'wymiana szybki iPhone'],
                    ],
                ];
            case 'richtext':
                return [
                    'heading' => 'Dlaczego tylko Apple?',
                    'body'    => '<p class="text-lg text-[#6E6E73] mb-8">Specjalizacja w jednej marce pozwala nam osiągnąć najwyższy poziom wiedzy i doświadczenia. Znamy każdy model, każdą generację, każdy typowy problem. To przekłada się na szybsze diagnozy, skuteczniejsze naprawy i większą satysfakcję naszych klientów.</p><p class="text-lg text-[#6E6E73]">Jesteśmy pasjonatami produktów Apple i traktujemy każde urządzenie z należytą starannością — tak, jakby było naszym własnym.</p>',
                    'align'   => 'center',
                    'bg'      => 'white',
                ];
            case 'cta':
                return [
                    'heading' => 'Dołącz do naszych zadowolonych klientów',
                    'text'    => 'Skontaktuj się z nami już dziś i doświadcz profesjonalnego serwisu Apple.',
                    'bg'      => 'gray',
                    'buttons' => [
                        ['label' => 'Wyceń naprawę', 'url' => '/kontakt', 'style' => 'primary'],
                        ['label' => 'Skontaktuj się', 'url' => '/kontakt', 'style' => 'outline'],
                    ],
                ];
            case 'contact':
                return [
                    'left_heading'  => 'Dane kontaktowe',
                    'address'       => "ul. Dobra 56/66, Budynek Biblioteki UW\n(minus 1, lok. nr A32), 00-312 Warszawa",
                    'phone'         => '+48 504 806 905',
                    'email'         => 'dobra@isense.pl',
                    'hours'         => "Poniedziałek – Piątek: 9:00 – 19:00\nSobota – Niedziela: Nieczynne",
                    'map'           => 'https://www.google.com/maps?q=Biblioteka+Uniwersytecka+w+Warszawie,+Dobra+56/66,+Warszawa&output=embed',
                    'map_link'      => 'https://maps.google.com/?q=ul.+Dobra+56/66+Warszawa',
                    'right_heading' => 'Napisz do nas',
                    'consent_text'  => 'Wyrażam zgodę na przetwarzanie moich danych osobowych, podanych w powyższym formularzu, wyłącznie w celu udzielenia odpowiedzi na wysłaną przeze mnie wiadomość. Podanie danych jest dobrowolne. Administrator umożliwia wgląd do własnych danych osobowych i zapewnia prawo ich poprawiania, jak i usunięcia.',
                    'recipient'     => '',
                    'subjects'      => [
                        ['label' => 'Wycena naprawy'],
                        ['label' => 'Status zlecenia'],
                        ['label' => 'Trade-In / Odkup'],
                        ['label' => 'Gwarancja'],
                        ['label' => 'Inne pytanie'],
                    ],
                ];
            case 'steps':
                return [
                    'heading' => 'Jak to działa?',
                    'style'   => 'icon',   // icon | number
                    'columns' => '3',
                    'items'   => [
                        ['icon' => 'package', 'title' => 'Krok 1', 'text' => 'Opis pierwszego kroku.'],
                        ['icon' => 'search', 'title' => 'Krok 2', 'text' => 'Opis drugiego kroku.'],
                        ['icon' => 'check-circle', 'title' => 'Krok 3', 'text' => 'Opis trzeciego kroku.'],
                    ],
                ];
            case 'status-tracker':
                return [
                    'title'        => 'Sprawdź status naprawy',
                    'subtitle'     => 'Wpisz numer zlecenia, aby zobaczyć aktualny status naprawy',
                    'hint1'        => 'Numer zlecenia znajdziesz w potwierdzeniu przyjęcia sprzętu (e-mail lub SMS)',
                    'hint2'        => 'Przykładowy numer testowy:',
                    'hint2_number' => 'ORD-12345',
                    // Zlecenia NIE są tu przechowywane — zarządza nimi moduł „Zlecenia" (/tiocms/orders).
                ];
            case 'trade-in':
                return [
                    'badge'        => 'Trade-In',
                    'heading'      => 'Oddaj stary sprzęt — odbierz rabat lub gotówkę',
                    'lead'         => 'Wycenimy Twoje urządzenie i zaproponujemy najlepszą ofertę odkupu lub rabatu na naprawę.',
                    'wizard_title' => 'Kreator wyceny Trade-In',
                    'currency'     => 'zł',
                    'round'        => '10',
                    'devices'      => [
                        ['type_label' => 'iPhone', 'models_text' => "iPhone 15 Pro Max | 4500\niPhone 15 Pro | 3900\niPhone 15 Plus | 3200\niPhone 15 | 2800"],
                        ['type_label' => 'iPad', 'models_text' => "iPad Pro 13\" M4 | 5000\niPad Pro 11\" M4 | 4200\niPad Air 13\" M2 | 3000\niPad Air 11\" M2 | 2500\niPad mini 7 | 2000"],
                        ['type_label' => 'MacBook', 'models_text' => "MacBook Pro 16\" M4 | 9000\nMacBook Pro 14\" M4 | 7500\nMacBook Air 15\" M3 | 5500\nMacBook Air 13\" M3 | 4800"],
                        ['type_label' => 'iMac', 'models_text' => "iMac 24\" M4 | 5500\niMac 24\" M3 | 4800\niMac 24\" M1 | 3500"],
                        ['type_label' => 'Apple Watch', 'models_text' => "Apple Watch Ultra 2 | 2800\nApple Watch Series 10 | 1800\nApple Watch Series 9 | 1400\nApple Watch SE (2. gen) | 900"],
                    ],
                    'conditions'   => [
                        ['label' => 'Idealny', 'description' => 'Jak nowy, bez śladów użytkowania', 'factor' => '100'],
                        ['label' => 'Dobry', 'description' => 'Drobne ślady użytkowania', 'factor' => '85'],
                        ['label' => 'Widoczne ślady', 'description' => 'Rysy, odpryski, ale w pełni sprawny', 'factor' => '65'],
                        ['label' => 'Uszkodzony', 'description' => 'Pęknięty ekran lub inne uszkodzenia', 'factor' => '35'],
                    ],
                    'cta1_label'   => 'Wymień na rabat przy naprawie',
                    'cta1_url'     => 'kontakt',
                    'cta2_label'   => 'Sprzedaj za gotówkę',
                    'cta2_url'     => 'kontakt',
                    'benefits'     => [
                        ['icon' => 'trending-up', 'title' => 'Najlepsza wycena na rynku', 'text' => 'Oferujemy konkurencyjne ceny za używany sprzęt Apple'],
                        ['icon' => 'gift', 'title' => 'Rabat przy naprawie', 'text' => 'Wykorzystaj wartość starego sprzętu jako rabat na naprawę'],
                        ['icon' => 'dollar-sign', 'title' => 'Gotówka lub przelew', 'text' => 'Wypłata od ręki lub szybki przelew bankowy'],
                    ],
                ];
            case 'pickup':
                return [
                    'badge'        => 'Door-to-Door',
                    'heading'      => 'Naprawa wysyłkowa',
                    'lead'         => 'Nie masz czasu przyjechać do serwisu? Skorzystaj z naprawy wysyłkowej — odbieramy kurierem z całej Polski, naprawiamy i odsyłamy z powrotem.',
                    'form_heading' => 'Formularz odbioru',
                    'recipient'    => '',
                    'benefits'     => [
                        ['icon' => 'package', 'title' => 'Bezpłatny odbiór i dostawa', 'text' => 'Kurier odbierze sprzęt i dostarczy po naprawie bez żadnych opłat'],
                        ['icon' => 'shield', 'title' => 'Bezpieczne opakowanie', 'text' => 'Zapewniamy profesjonalne zabezpieczenie sprzętu podczas transportu'],
                        ['icon' => 'check-circle', 'title' => 'Weryfikacja SMS', 'text' => 'Bezpieczny proces zamówienia z weryfikacją numeru telefonu'],
                    ],
                ];
            case 'link-cards':
                return [
                    'heading' => '', 'lead' => '', 'columns' => '3',
                    'cards' => [
                        ['img' => 'iphone.png', 'icon' => 'smartphone', 'title' => 'Serwis iPhone', 'desc' => 'Wyświetlacze, baterie, płyty główne, aparaty i więcej.', 'url' => 'naprawy/iphone', 'link_label' => 'Zobacz cennik'],
                    ],
                ];
            case 'repair-hero':
                return [
                    'eyebrow'  => 'Apple Independent Repair Provider',
                    'title'    => 'Serwis i naprawa', 'title2' => 'iPhone',
                    'lead'     => '', 'lead2' => '',
                    'image'    => '/assets/isense/img/iphone.png',
                    'layout'   => 'split', // split (obraz obok tekstu) | bg_image (zdjęcie w tle)
                    'bg_image' => '',
                    'anchor'   => 'cennik',
                ];
            case 'features-bar':
                return [
                    'items' => [
                        ['icon' => 'shield', 'title' => 'Min. 90 dni gwarancji', 'desc' => 'Na każdą naprawę i użyte części'],
                        ['icon' => 'check-circle', 'title' => 'Bezpłatna diagnoza', 'desc' => 'Bez zobowiązań, zawsze za darmo'],
                        ['icon' => 'star', 'title' => 'Oryginalne części Apple', 'desc' => 'Pełna funkcjonalność po naprawie'],
                        ['icon' => 'package', 'title' => 'Naprawa wysyłkowa', 'desc' => 'Odbiór kurierski z całej Polski'],
                        ['icon' => 'clock', 'title' => 'Naprawa w 24–48h', 'desc' => 'Większość zleceń dzień po dniu'],
                        ['icon' => 'wrench', 'title' => 'Serwis od 2008 roku', 'desc' => 'Ponad 15 lat doświadczenia'],
                    ],
                ];
            case 'services-grid':
                return [
                    'heading' => 'Zakres napraw', 'lead' => '',
                    'items' => [
                        ['name' => 'Wymiana wyświetlacza', 'desc' => 'Z kalibracją i przywróceniem pełnej funkcjonalności'],
                    ],
                ];
            case 'pricing-table':
                return [
                    'heading' => 'Cennik napraw', 'lead' => 'Ceny obejmują robociznę i części. Diagnoza zawsze bezpłatna.',
                    'note'    => '* Diagnoza bezpłatna przy zleceniu naprawy. Ceny mogą ulec zmianie — ostateczna wycena po diagnozie urządzenia.',
                    'layout'  => 'by_device', // by_device (karta na grupę modeli) | by_service (karta na usługę)
                    'groups'  => [
                        [
                            'group_title' => 'Modele',
                            'models_csv'  => 'Model',
                            'rows_text'   => "Diagnoza | bezpłatna*",
                        ],
                    ],
                ];
            case 'models-grid':
                return [
                    'heading' => 'Wybierz swój model', 'lead' => 'Kliknij model, aby zobaczyć szczegółowy cennik napraw.',
                    'base'    => 'naprawy/iphone',
                    'items'   => [
                        ['name' => 'iPhone 15 Pro Max', 'slug' => 'iphone-15-pro-max'],
                    ],
                    // Cennik pokazywany na stronie modelu (naprawy/<kat>/<model>). Format wiersza: Usługa | Cena | Czas | Gwarancja
                    'services_intro' => 'Wszystkie ceny zawierają oryginalne części i robociznę.',
                    'services_text'  => "Wymiana wyświetlacza | od 399 zł | 2-3h | 12 miesięcy\nWymiana baterii | od 199 zł | 1h | 12 miesięcy\nNaprawa po zalaniu | od 299 zł | 2-3 dni | 6 miesięcy\nWymiana złącza ładowania | od 249 zł | 2-4h | 12 miesięcy",
                ];
            case 'why-brand':
                return [
                    'heading' => 'Dlaczego iSense?',
                    'para1'   => 'Serwisujemy sprzęt Apple od 2008 roku. Zapewniamy dostęp do oryginalnych części Apple i pełną kalibrację sprzętową każdej naprawy.',
                    'para2'   => 'Jako Apple Independent Repair Provider mamy dostęp do oryginalnych części i narzędzi diagnostycznych Apple. Każda wymiana jest kalibrowana sprzętowo.',
                    'para3'   => 'Obsługujemy klientów indywidualnych i firmy z całej Polski — zarówno w naszym serwisie w Warszawie Śródmieście, jak i wysyłkowo door-to-door. Znajdziesz nas przy ul. Dobra 56/66, w Budynku Biblioteki UW (minus 1, lok. nr A32), 00-312 Warszawa.',
                    'phone'   => '+48 504 806 905',
                    'stats'   => [
                        ['val' => '2008', 'label' => 'Rok założenia'],
                        ['val' => '15+', 'label' => 'Lat doświadczenia'],
                        ['val' => '48h', 'label' => 'Średni czas naprawy'],
                        ['val' => '90 dni', 'label' => 'Gwarancja min.'],
                    ],
                ];
            case 'faq':
                return [
                    'heading' => 'FAQ', 'lead' => 'Najczęściej zadawane pytania',
                    'items' => [
                        ['question' => 'Ile trwa naprawa?', 'answer' => 'Większość napraw realizujemy w ciągu 24–48 godzin.'],
                    ],
                ];
            default:
                return [];
        }
    }
}
