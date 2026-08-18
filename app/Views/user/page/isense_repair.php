<?php
/*
iSense — Naprawy (kategoria)
*/
helper(['url']);

// ─────────────────────────────────────────────
// Wykrycie kategorii z adresu URL
// naprawy/iphone | naprawy/ipad | naprawy/imac | naprawy/macbook | naprawy/zasilacze
// ─────────────────────────────────────────────
$seg      = explode('/', trim(uri_string(), '/'));
$category = end($seg); // 'iphone' | 'ipad' | 'imac' | 'macbook' | 'zasilacze'
$isAssets = rtrim(base_url('assets/isense'), '/');

// Obraz hero dla kategorii
$catImages = [
    'iphone'    => 'iphone.png',
    'ipad'      => 'ipad.png',
    'imac'      => 'imac.png',
    'macbook'   => 'imac.png',
    'zasilacze' => 'hero.png',
];

// Pasek atutów wspólny dla wszystkich kategorii (sharedFeatures z TSX)
$sharedFeatures = [
    ['icon' => 'shield',       'title' => 'Min. 90 dni gwarancji',   'desc' => 'Na każdą naprawę i użyte części'],
    ['icon' => 'check-circle', 'title' => 'Bezpłatna diagnoza',      'desc' => 'Bez zobowiązań, zawsze za darmo'],
    ['icon' => 'star',         'title' => 'Oryginalne części Apple', 'desc' => 'Pełna funkcjonalność po naprawie'],
    ['icon' => 'package',      'title' => 'Naprawa wysyłkowa',       'desc' => 'Odbiór kurierski z całej Polski'],
    ['icon' => 'clock',        'title' => 'Naprawa w 24–48h',        'desc' => 'Większość zleceń dzień po dniu'],
    ['icon' => 'wrench',       'title' => 'Serwis od 2008 roku',     'desc' => 'Ponad 15 lat doświadczenia'],
];

// Statystyki iSense (wspólne — sekcja "Dlaczego iSense?")
$brandStats = [
    ['val' => '2008',   'label' => 'Rok założenia'],
    ['val' => '15+',    'label' => 'Lat doświadczenia'],
    ['val' => '48h',    'label' => 'Średni czas naprawy'],
    ['val' => '90 dni', 'label' => 'Gwarancja min.'],
];

// ─────────────────────────────────────────────
// Dane kategorii
// ─────────────────────────────────────────────
$CATS = [

    // ============ iPhone (pełne dane z TSX) ============
    'iphone' => [
        'eyebrow'      => 'Apple Independent Repair Provider',
        'title'        => 'Serwis i naprawa',
        'title2'       => 'iPhone',
        'lead'         => 'Naprawy sprzętu Apple — dla innych to wyzwanie, dla nas przyjemność. Specjalizujemy się w naprawach iPhone z serii 15 i nowszych.',
        'lead2'        => 'Bezpłatna diagnoza i co najmniej 90 dni gwarancji na wszystkie naprawy. Wycenę otrzymasz nawet w 60 sekund.',
        'servicesTitle'=> 'Zakres napraw iPhone',
        'servicesLead' => 'Obsługujemy wszystkie rodzaje usterek — od prostych wymian po skomplikowane naprawy płyt głównych.',
        'services'     => [
            ['name' => 'Wymiana panela przedniego',      'desc' => 'Z kalibracją True Tone i wszystkich czujników'],
            ['name' => 'Wymiana baterii',                 'desc' => 'Z kalibracją i przywróceniem pełnej pojemności'],
            ['name' => 'Wymiana tylnej szybki',           'desc' => 'Dotyczy modeli z aluminiową/tytanową ramką'],
            ['name' => 'Naprawa po zalaniu',              'desc' => 'Diagnostyka i naprawa układów elektronicznych'],
            ['name' => 'Naprawa płyty głównej',           'desc' => 'Mikronaprawa BGA, wymiana układów scalonych'],
            ['name' => 'Wymiana aparatu',                 'desc' => 'Główny, ultraszerokokątny, teleobiektyw, przedni'],
            ['name' => 'Odzyskiwanie danych',             'desc' => 'Nawet z niedziałającego lub mokrego urządzenia'],
            ['name' => 'Simlock / odblokowanie',          'desc' => 'Zdejmowanie blokady operatora, iCloud Unlock'],
            ['name' => 'Wymiana złącza ładowania',        'desc' => 'USB-C — we wszystkich modelach serii 15 i nowszych'],
            ['name' => 'Naprawa Face ID',                 'desc' => 'Przywrócenie działania czujnika twarzy'],
            ['name' => 'Wymiana głośnika / mikrofonu',    'desc' => 'Dolny, słuchawkowy, mikrofon główny i dodatkowy'],
            ['name' => 'Naprawy programowe',              'desc' => 'Aktualizacje, błędy systemu, restore, DFU'],
        ],
        'pricingTitle' => 'Cennik napraw iPhone',
        'pricingLead'  => 'Ceny obejmują robociznę i części. Diagnoza zawsze bezpłatna.',
        'pricing'      => [
            [
                'group'  => 'iPhone 15 / 15 Pro / 15 Plus / 15 Pro Max',
                'models' => ['15', '15 Pro', '15 Plus', '15 Pro Max'],
                'rows'   => [
                    ['service' => 'Panel przedni z kalibracją', 'prices' => ['1 549 zł', '1 849 zł', '1 849 zł', '2 199 zł']],
                    ['service' => 'Bateria z kalibracją',       'prices' => ['499 zł', '499 zł', '499 zł', '499 zł']],
                    ['service' => 'Naprawy programowe',         'prices' => ['od 149 zł', 'od 149 zł', 'od 149 zł', 'od 149 zł']],
                    ['service' => 'Diagnoza',                   'prices' => ['bezpłatna*', 'bezpłatna*', 'bezpłatna*', 'bezpłatna*']],
                ],
            ],
        ],
        'modelsTitle'  => 'Wybierz swój model',
        'modelsLead'   => 'Kliknij model, aby zobaczyć szczegółowy cennik napraw.',
        'models'       => [
            ['name' => 'iPhone 15 Pro Max', 'slug' => 'iphone-15-pro-max'],
            ['name' => 'iPhone 15 Pro',     'slug' => 'iphone-15-pro'],
            ['name' => 'iPhone 15 Plus',    'slug' => 'iphone-15-plus'],
            ['name' => 'iPhone 15',         'slug' => 'iphone-15'],
        ],
        'faqLead'      => 'Najczęściej zadawane pytania o serwis iPhone',
        'faqs'         => [
            ['question' => 'Ile trwa naprawa iPhone?', 'answer' => 'Większość napraw realizujemy w ciągu 24–48 godzin. Proste usługi jak wymiana baterii czy panela przedniego często wykonujemy tego samego dnia. Naprawa płyty głównej może trwać do 5 dni roboczych.'],
            ['question' => 'Jakiej jakości części używacie?', 'answer' => 'Używamy wyłącznie oryginalnych paneli Apple i baterii Apple. Dzięki temu zachowana jest pełna funkcjonalność: True Tone, Face ID, ProMotion. Każda naprawa jest kalibrowana sprzętowo.'],
            ['question' => 'Czy udzielacie gwarancji?', 'answer' => 'Na wszystkie naprawy udzielamy co najmniej 90 dni gwarancji obejmującej zarówno usługę, jak i wymienione części. Na większość napraw gwarancja wynosi 12 miesięcy.'],
            ['question' => 'Czy diagnoza jest płatna?', 'answer' => 'Diagnoza jest zawsze bezpłatna i nie wiąże się z żadnymi zobowiązaniami. Jeśli po wycenie nie zdecydujesz się na naprawę, po prostu zwrócimy Ci urządzenie.'],
            ['question' => 'Czy mogę nadać iPhone kurierem?', 'answer' => 'Tak. Oferujemy pełną obsługę wysyłkową door-to-door dla klientów z całej Polski. Możemy zamówić bezpłatny odbiór kurierski lub możesz nadać paczkę samodzielnie.'],
            ['question' => 'Naprawiacie starsze modele iPhone?', 'answer' => 'Specjalizujemy się w naprawach iPhone z serii 15 i nowszych. Jeśli masz inne urządzenie, skontaktuj się z nami — chętnie doradzimy.'],
        ],
    ],

    // ============ iPad (pełne dane z TSX) ============
    'ipad' => [
        'eyebrow'      => 'Apple Independent Repair Provider',
        'title'        => 'Serwis i naprawa',
        'title2'       => 'iPad',
        'lead'         => 'Naprawiamy wszystkie generacje iPada — od modeli Air i mini po profesjonalne iPad Pro. Bezpłatna diagnoza i co najmniej 90 dni gwarancji na każdą naprawę.',
        'lead2'        => 'Obsługujemy klientów indywidualnych i firmowych z całej Polski — w naszym punkcie w Warszawie Śródmieście lub wysyłkowo door-to-door.',
        'servicesTitle'=> 'Zakres napraw iPad',
        'servicesLead' => 'Kompleksowy serwis — od wymiany szkła po naprawę płyty głównej.',
        'services'     => [
            ['name' => 'Wymiana szybki frontowej',        'desc' => 'Oryginalne szkło, precyzyjna kalibracja dotyku'],
            ['name' => 'Wymiana matrycy / wyświetlacza',  'desc' => 'Pełny moduł LCD lub OLED z kalibracją'],
            ['name' => 'Wymiana baterii',                 'desc' => 'Przywrócenie pełnej pojemności i czasu pracy'],
            ['name' => 'Naprawa po zalaniu',              'desc' => 'Oczyszczanie płyty, naprawa układów elektronicznych'],
            ['name' => 'Wymiana złącza ładowania',        'desc' => 'Lightning lub USB-C w zależności od generacji'],
            ['name' => 'Simlock / odblokowanie',          'desc' => 'Zdejmowanie blokady operatora'],
            ['name' => 'Naprawy programowe',              'desc' => 'Restore, aktualizacje, błędy systemu iPadOS'],
            ['name' => 'Odzyskiwanie danych',             'desc' => 'Nawet z uszkodzonego lub mokrego urządzenia'],
            ['name' => 'Wymiana przycisku Home',          'desc' => 'Touch ID z zachowaniem funkcjonalności'],
            ['name' => 'Naprawa płyty głównej',           'desc' => 'Mikronaprawa BGA, wymiana układów scalonych'],
            ['name' => 'Wymiana aparatu',                 'desc' => 'Przedni i tylny moduł aparatu'],
            ['name' => 'Naprawy gwarancyjne',             'desc' => 'Ekspertyzy i naprawy z tytułu gwarancji'],
        ],
        'pricingTitle' => 'Cennik napraw iPad',
        'pricingLead'  => 'Ceny obejmują robociznę i części. Diagnoza zawsze bezpłatna.',
        'pricing'      => [
            [
                'group'  => 'iPad Air / iPad mini',
                'models' => ['iPad Air', 'iPad mini'],
                'rows'   => [
                    ['service' => 'Wymiana szybki frontowej', 'prices' => ['550 zł', '500 zł']],
                    ['service' => 'Wymiana matrycy',          'prices' => ['550 zł', '500 zł']],
                    ['service' => 'Simlock',                  'prices' => ['od 100 zł', 'od 100 zł']],
                    ['service' => 'Naprawy programowe',       'prices' => ['od 70 zł', 'od 70 zł']],
                    ['service' => 'Diagnoza',                 'prices' => ['bezpłatna*', 'bezpłatna*']],
                ],
            ],
        ],
        'modelsTitle'  => 'Wybierz swój model',
        'modelsLead'   => 'Kliknij model, aby zobaczyć szczegółowy cennik napraw.',
        'models'       => [
            ['name' => 'iPad Pro 13" M4',    'slug' => 'ipad-pro-13-m4'],
            ['name' => 'iPad Pro 11" M4',    'slug' => 'ipad-pro-11-m4'],
            ['name' => 'iPad Air 13" M2',    'slug' => 'ipad-air-13-m2'],
            ['name' => 'iPad Air 11" M2',    'slug' => 'ipad-air-11-m2'],
            ['name' => 'iPad mini 7',        'slug' => 'ipad-mini-7'],
            ['name' => 'iPad 10. generacji', 'slug' => 'ipad-10'],
        ],
        'faqLead'      => 'Najczęściej zadawane pytania o serwis iPad',
        'faqs'         => [
            ['question' => 'Ile trwa naprawa iPada?', 'answer' => 'Większość napraw realizujemy w ciągu 24–48 godzin. Wymiana szybki lub matrycy często odbywa się tego samego dnia.'],
            ['question' => 'Czy naprawiacie iPady Pro z wyświetlaczem Liquid Retina XDR?', 'answer' => 'Tak, naprawiamy wszystkie modele iPad Pro, w tym z wyświetlaczem mini-LED i OLED. Używamy oryginalnych modułów Apple.'],
            ['question' => 'Czy diagnoza jest płatna?', 'answer' => 'Diagnoza jest zawsze bezpłatna i bez zobowiązań. Jeśli po wycenie rezygnujesz, po prostu odbierasz urządzenie.'],
            ['question' => 'Czy mogę wysłać iPada kurierem?', 'answer' => 'Tak, obsługujemy klientów z całej Polski wysyłkowo. Możemy zamówić bezpłatny odbiór kurierski lub przyjąć paczkę nadaną przez klienta.'],
            ['question' => 'Czy udzielacie gwarancji na naprawy iPada?', 'answer' => 'Na wszystkie naprawy udzielamy co najmniej 90 dni gwarancji na usługę i wymienione części.'],
        ],
    ],

    // ============ iMac (services/models/faqs z TSX; cennik uzupełniony analogicznie) ============
    'imac' => [
        'eyebrow'      => 'Serwis Apple',
        'title'        => 'Serwis i naprawa',
        'title2'       => 'iMac',
        'lead'         => 'Naprawy sprzętu Apple — dla innych to wyzwanie, dla nas przyjemność. Bezpłatna diagnoza i co najmniej 90 dni gwarancji na każdą naprawę iMaca.',
        'lead2'        => 'Naprawiamy wszystkie generacje iMac — w tym modele z chipem Apple Silicon M1, M3 i M4. Serwis stacjonarny w Warszawie lub wysyłkowo z całej Polski. W obrębie Warszawy — możliwy dojazd do klienta.',
        'servicesTitle'=> 'Zakres napraw iMac',
        'servicesLead' => 'Kompleksowy serwis — od wymiany matrycy po naprawę płyty głównej i odzyskiwanie danych.',
        'services'     => [
            ['name' => 'Wymiana matrycy / ekranu',        'desc' => 'Oryginalna matryca 4K lub 5K Retina z kalibracją'],
            ['name' => 'Naprawa płyty głównej',           'desc' => 'Mikronaprawa BGA, wymiana układów scalonych'],
            ['name' => 'Wymiana dysku SSD',               'desc' => 'Rozbudowa pamięci masowej do 2 TB i więcej'],
            ['name' => 'Rozbudowa RAM',                   'desc' => 'Zwiększenie pamięci operacyjnej (modele z gniazdem DIMM)'],
            ['name' => 'Wymiana wentylatora / chłodzenia','desc' => 'Naprawa przegrzewającego się iMaca'],
            ['name' => 'Czyszczenie z kurzu',             'desc' => 'Profesjonalne czyszczenie układu chłodzenia'],
            ['name' => 'Naprawa po zalaniu',              'desc' => 'Oczyszczanie i naprawa układów elektronicznych'],
            ['name' => 'Odzyskiwanie danych',             'desc' => 'Nawet z całkowicie niedziałającego dysku'],
            ['name' => 'Naprawy programowe / macOS',      'desc' => 'Reinstalacja systemu, konfiguracja, migracja danych'],
            ['name' => 'Wymiana zasilacza',               'desc' => 'Oryginalne zasilacze Apple do modeli iMac'],
            ['name' => 'Naprawa głośników',               'desc' => 'Wymiana uszkodzonych głośników wewnętrznych'],
            ['name' => 'Ekspertyzy ubezpieczeniowe',      'desc' => 'Dokumentacja uszkodzeń na potrzeby ubezpieczyciela'],
        ],
        'pricingTitle' => 'Cennik napraw iMac',
        'pricingLead'  => 'Ceny orientacyjne — ostateczna wycena po bezpłatnej diagnozie. Ceny obejmują robociznę i części.',
        'pricing'      => [
            [
                'group'  => 'iMac 24" (Apple Silicon) / iMac 27" 5K',
                'models' => ['24" M-series', '27" 5K'],
                'rows'   => [
                    ['service' => 'Wymiana matrycy / ekranu', 'prices' => ['od 1 200 zł', 'od 1 900 zł']],
                    ['service' => 'Wymiana dysku SSD',        'prices' => ['od 450 zł', 'od 450 zł']],
                    ['service' => 'Naprawa płyty głównej',    'prices' => ['od 600 zł', 'od 800 zł']],
                    ['service' => 'Naprawy programowe / macOS','prices' => ['od 120 zł', 'od 120 zł']],
                    ['service' => 'Diagnoza',                 'prices' => ['bezpłatna*', 'bezpłatna*']],
                ],
            ],
        ],
        'modelsTitle'  => 'Wybierz swój model',
        'modelsLead'   => 'Kliknij model, aby zobaczyć szczegółowy cennik napraw.',
        'models'       => [
            ['name' => 'iMac 24" M4 (2024)',   'slug' => 'imac-24-m4'],
            ['name' => 'iMac 24" M3 (2023)',   'slug' => 'imac-24-m3'],
            ['name' => 'iMac 24" M1 (2021)',   'slug' => 'imac-24-m1'],
            ['name' => 'iMac 27" 5K (2020)',   'slug' => 'imac-27-5k-2020'],
            ['name' => 'iMac Pro 27" 5K',      'slug' => 'imac-pro-27'],
        ],
        'faqLead'      => 'Najczęściej zadawane pytania o serwis iMac',
        'faqs'         => [
            ['question' => 'Czy możecie naprawić iMac u mnie w domu / biurze?', 'answer' => 'Tak, w obrębie Warszawy oferujemy serwis z dojazdem do klienta. Dla klientów z innych miast najwygodniejsza jest wysyłka — zapewniamy profesjonalne opakowanie na czas transportu.'],
            ['question' => 'Czy naprawiacie iMaki z chipem M1/M3/M4?', 'answer' => 'Tak, naprawiamy wszystkie modele iMac, w tym najnowsze z chipami Apple Silicon. Posiadamy specjalistyczne narzędzia do diagnostyki i naprawy układów M-series.'],
            ['question' => 'Ile kosztuje naprawa iMaca?', 'answer' => 'Ceny są ustalane indywidualnie po bezpłatnej diagnozie. Skontaktuj się z nami — wstępną wycenę możemy podać nawet w 60 sekund po opisaniu usterki.'],
            ['question' => 'Czy diagnoza jest płatna?', 'answer' => 'Diagnoza jest zawsze bezpłatna i bez zobowiązań. Jeśli po wycenie rezygnujesz z naprawy, zwrócimy Ci urządzenie bez żadnych kosztów.'],
            ['question' => 'Jak długo trwa naprawa iMaca?', 'answer' => 'Proste naprawy (czyszczenie, wymiana SSD) realizujemy w 1–2 dni. Naprawa płyty głównej lub wymiana matrycy może trwać 3–5 dni roboczych.'],
        ],
    ],

    // ============ MacBook (services/models/faqs z TSX; cennik uzupełniony analogicznie) ============
    'macbook' => [
        'eyebrow'      => 'Serwis Apple',
        'title'        => 'Serwis i naprawa',
        'title2'       => 'MacBook',
        'lead'         => 'Naprawy sprzętu Apple — dla innych to wyzwanie, dla nas przyjemność. Bezpłatna diagnoza i co najmniej 90 dni gwarancji na każdą naprawę.',
        'lead2'        => 'Serwisujemy MacBook, MacBook Air i MacBook Pro — w tym najnowsze modele z chipem Apple Silicon M1–M4. Ceny ustalamy indywidualnie po bezpłatnej diagnozie. Wstępną wycenę możemy podać nawet w 60 sekund.',
        'servicesTitle'=> 'Zakres napraw MacBook',
        'servicesLead' => 'Kompleksowy serwis — od wymiany klawiatury po mikronaprawy płyty głównej.',
        'services'     => [
            ['name' => 'Wymiana matrycy / ekranu',        'desc' => 'Retina, True Tone, mini-LED — oryginalne moduły Apple'],
            ['name' => 'Wymiana klawiatury',              'desc' => 'Naprawa lub wymiana układu klawiatury, w tym Butterfly'],
            ['name' => 'Wymiana baterii',                 'desc' => 'Oryginalna bateria z kalibracją i pełnym cyklem'],
            ['name' => 'Naprawa płyty głównej',           'desc' => 'Mikronaprawa BGA, naprawa układów scalonych'],
            ['name' => 'Wymiana dysku SSD',               'desc' => 'Rozbudowa lub wymiana pamięci masowej'],
            ['name' => 'Naprawa po zalaniu',              'desc' => 'Oczyszczanie i naprawa układów elektronicznych'],
            ['name' => 'Naprawa flexgate',                'desc' => 'Naprawa kabla wyświetlacza MacBook Pro 13" (2016–2019)'],
            ['name' => 'Wymiana głośników',               'desc' => 'Oryginalne zestawy głośnikowe Apple'],
            ['name' => 'Wymiana TouchBar',               'desc' => 'Naprawa lub wymiana paska dotykowego'],
            ['name' => 'Czyszczenie z kurzu',             'desc' => 'Profesjonalna konserwacja układu chłodzenia'],
            ['name' => 'Odzyskiwanie danych',             'desc' => 'Nawet z uszkodzonego dysku SSD'],
            ['name' => 'Naprawy programowe / macOS',      'desc' => 'Reinstalacja systemu, konfiguracja, migracja'],
        ],
        'pricingTitle' => 'Cennik napraw MacBook',
        'pricingLead'  => 'Ceny orientacyjne — ostateczna wycena po bezpłatnej diagnozie. Ceny obejmują robociznę i części.',
        'pricing'      => [
            [
                'group'  => 'MacBook Air / MacBook Pro',
                'models' => ['MacBook Air', 'MacBook Pro'],
                'rows'   => [
                    ['service' => 'Wymiana matrycy / ekranu', 'prices' => ['od 1 100 zł', 'od 1 600 zł']],
                    ['service' => 'Wymiana baterii',          'prices' => ['od 549 zł', 'od 649 zł']],
                    ['service' => 'Wymiana klawiatury',       'prices' => ['od 650 zł', 'od 850 zł']],
                    ['service' => 'Naprawa płyty głównej',    'prices' => ['od 600 zł', 'od 900 zł']],
                    ['service' => 'Naprawy programowe / macOS','prices' => ['od 120 zł', 'od 120 zł']],
                    ['service' => 'Diagnoza',                 'prices' => ['bezpłatna*', 'bezpłatna*']],
                ],
            ],
        ],
        'modelsTitle'  => 'Wybierz swój model',
        'modelsLead'   => 'Kliknij model, aby zobaczyć szczegółowy cennik napraw.',
        'models'       => [
            ['name' => 'MacBook Pro 16" M4 (2024)', 'slug' => 'macbook-pro-16-m4'],
            ['name' => 'MacBook Pro 14" M4 (2024)', 'slug' => 'macbook-pro-14-m4'],
            ['name' => 'MacBook Air 15" M3 (2024)', 'slug' => 'macbook-air-15-m3'],
            ['name' => 'MacBook Air 13" M3 (2024)', 'slug' => 'macbook-air-13-m3'],
            ['name' => 'MacBook Pro 16" M3 (2023)', 'slug' => 'macbook-pro-16-m3'],
            ['name' => 'MacBook Pro 14" M3 (2023)', 'slug' => 'macbook-pro-14-m3'],
            ['name' => 'MacBook Air 15" M2 (2023)', 'slug' => 'macbook-air-15-m2'],
            ['name' => 'MacBook Air 13" M2 (2022)', 'slug' => 'macbook-air-13-m2'],
        ],
        'faqLead'      => 'Najczęściej zadawane pytania o serwis MacBook',
        'faqs'         => [
            ['question' => 'Ile kosztuje naprawa MacBooka?', 'answer' => 'Ceny ustalamy indywidualnie po bezpłatnej diagnozie. Wstępną wycenę możemy podać nawet w 60 sekund — wystarczy opisać usterkę i model urządzenia.'],
            ['question' => 'Czy naprawiacie MacBooki z chipem Apple Silicon?', 'answer' => 'Tak, naprawiamy wszystkie modele MacBook z chipami M1, M2, M3 i M4. Posiadamy specjalistyczne narzędzia i oryginalne części Apple.'],
            ['question' => 'Co to jest flexgate i czy da się go naprawić?', 'answer' => 'Flexgate to charakterystyczna usterka MacBook Pro 13" z lat 2016–2019, polegająca na uszkodzeniu kabla wyświetlacza. Naprawiamy tę usterkę — bez wymiany całego ekranu.'],
            ['question' => 'Czy diagnoza jest płatna?', 'answer' => 'Diagnoza jest zawsze bezpłatna. Jeśli po wycenie rezygnujesz z naprawy, zwracamy urządzenie bez żadnych kosztów.'],
            ['question' => 'Jak długo trwa naprawa MacBooka?', 'answer' => 'Prostsze naprawy (bateria, klawiatura) wykonujemy w 1–2 dni. Naprawa płyty głównej lub wymiana matrycy może trwać 3–5 dni roboczych.'],
            ['question' => 'Czy mogę wysłać MacBooka kurierem?', 'answer' => 'Tak. Oferujemy pełną obsługę wysyłkową door-to-door dla klientów z całej Polski. Zapewniamy bezpieczne opakowanie na czas transportu.'],
        ],
    ],

    // ============ Zasilacze MagSafe (pełne dane z TSX; bez listy modeli) ============
    'zasilacze' => [
        'eyebrow'      => 'Serwis Apple',
        'title'        => 'Naprawa zasilaczy',
        'title2'       => 'MagSafe',
        'lead'         => 'Naprawiamy zasilacze MagSafe — dla innych to wyzwanie, dla nas przyjemność. Uratujemy Twój zasilacz i zaoszczędzisz kilkaset złotych na zakupie nowego.',
        'lead2'        => 'Serwisujemy MagSafe 1, MagSafe 2 oraz nowe zasilacze USB-C. Bezpłatna diagnoza i co najmniej 90 dni gwarancji na każdą naprawę.',
        'servicesTitle'=> 'Zakres napraw zasilaczy',
        'servicesLead' => 'Naprawiamy usterki uznawane za niemożliwe do naprawienia w innych serwisach.',
        'services'     => [
            ['name' => 'Wymiana kabla MagSafe 1',         'desc' => 'Naprawa zasilacza MagSafe 1 (60W / 85W) — oszczędność do kilkuset złotych'],
            ['name' => 'Wymiana kabla MagSafe 2',         'desc' => 'Naprawa zasilacza MagSafe 2 (45W / 60W / 85W)'],
            ['name' => 'Adapter wtyczki europejskiej',    'desc' => 'Wymiana lub dokupienie oryginalnego adaptera EU'],
            ['name' => 'Kabel zasilający do gniazdka',    'desc' => 'Przewód IEC C7 do zasilacza Apple'],
            ['name' => 'Bezpłatna diagnoza',              'desc' => 'Sprawdzamy zasilacz bez żadnych opłat i bez zobowiązań'],
            ['name' => 'Naprawa USB-C',                   'desc' => 'Serwis zasilaczy USB-C 30W, 61W, 87W, 96W, 140W'],
        ],
        'pricingTitle' => 'Cennik napraw zasilaczy MagSafe',
        'pricingLead'  => 'Stałe ceny, bez ukrytych kosztów. Diagnoza zawsze bezpłatna.',
        'pricing'      => [
            [
                'group'  => 'Zasilacze MagSafe',
                'models' => ['Cena'],
                'rows'   => [
                    ['service' => 'Wymiana kabla MagSafe 1',        'prices' => ['150 zł']],
                    ['service' => 'Wymiana kabla MagSafe 2',        'prices' => ['170 zł']],
                    ['service' => 'Adapter wtyczki europejskiej',   'prices' => ['30 zł']],
                    ['service' => 'Kabel zasilający do gniazdka',   'prices' => ['70 zł']],
                    ['service' => 'Diagnoza zasilacza',             'prices' => ['bezpłatna']],
                ],
            ],
        ],
        'modelsTitle'  => '',
        'modelsLead'   => '',
        'models'       => [],
        'faqLead'      => 'Najczęściej zadawane pytania o naprawy zasilaczy',
        'faqs'         => [
            ['question' => 'Co to jest MagSafe?', 'answer' => 'MagSafe to magnetyczne złącze zasilające stosowane w MacBookach. Dzięki magnesowi łatwo się odpina i chroni laptop przed upadkiem przy przypadkowym pociągnięciu kabla.'],
            ['question' => 'Czy warto naprawiać zasilacz MagSafe zamiast kupować nowy?', 'answer' => 'Zdecydowanie tak. Nowy oryginalny zasilacz Apple kosztuje 300–600 zł, a naprawa kabla to koszt 150–170 zł. Naprawiamy usterki, które inne serwisy uważają za niemożliwe do naprawienia.'],
            ['question' => 'Ile trwa naprawa zasilacza?', 'answer' => 'Naprawa kabla MagSafe zajmuje zazwyczaj 1 dzień roboczy. W wielu przypadkach realizujemy naprawę tego samego dnia.'],
            ['question' => 'Czy mogę wysłać zasilacz kurierem?', 'answer' => 'Tak. Przyjmujemy zasilacze wysyłkowo z całej Polski. Skontaktuj się z nami przed wysyłką, żebyśmy mogli potwierdzić opłacalność naprawy dla Twojego modelu.'],
            ['question' => 'Czy naprawiacie zasilacze USB-C?', 'answer' => 'Tak — serwisujemy nowe zasilacze USB-C Apple (30W, 61W, 87W, 96W, 140W) stosowane w MacBookach Pro i Air z chipem Apple Silicon.'],
        ],
    ],
];

$d       = $CATS[$category] ?? $CATS['iphone'];
$catKey  = isset($CATS[$category]) ? $category : 'iphone';
$heroImg = $isAssets . '/img/' . ($catImages[$catKey] ?? 'iphone.png');
?>
<?= view('user/page/_isense_open') ?>

<!-- HERO kategorii -->
<section class="bg-[#F5F5F7] py-10 lg:py-16">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-[#3b81f7] font-semibold text-sm uppercase tracking-widest mb-3"><?= esc($d['eyebrow']) ?></p>
                <h1 class="text-4xl lg:text-5xl font-bold text-[#1D1D1F] mb-6 leading-tight"><?= esc($d['title']) ?><br><?= esc($d['title2']) ?></h1>
                <p class="text-lg text-[#6E6E73] mb-4 leading-relaxed"><?= esc($d['lead']) ?></p>
                <p class="text-[#6E6E73] mb-8 leading-relaxed"><?= esc($d['lead2']) ?></p>
                <div class="flex flex-wrap gap-4">
                    <a href="#cennik" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-7 py-3.5 rounded font-semibold hover:bg-[#2563eb] transition-colors shadow">
                        Zobacz cennik <?= isense_icon('arrow-right', 'w-5 h-5') ?>
                    </a>
                    <a href="<?= site_url('naprawa-z-odbiorem') ?>" class="inline-flex items-center gap-2 bg-white text-[#1D1D1F] border border-[#1D1D1F] px-7 py-3.5 rounded font-semibold hover:bg-[#F5F5F7] transition-colors">
                        Naprawa wysyłkowa
                    </a>
                </div>
            </div>
            <div class="flex justify-center">
                <img src="<?= esc($heroImg, 'attr') ?>" alt="<?= esc('Serwis ' . $d['title2'], 'attr') ?>" class="w-full max-w-md object-contain drop-shadow-xl">
            </div>
        </div>
    </div>
</section>

<!-- PASEK ATUTÓW -->
<section class="bg-white py-10 lg:py-14 border-b border-[#E5E5EA]">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <?php foreach ($sharedFeatures as $f): ?>
                <div class="text-center">
                    <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-full flex items-center justify-center mx-auto mb-3">
                        <?= isense_icon($f['icon'], 'w-6 h-6 text-[#3b81f7]') ?>
                    </div>
                    <p class="font-semibold text-[#1D1D1F] text-sm mb-1"><?= esc($f['title']) ?></p>
                    <p class="text-xs text-[#6E6E73]"><?= esc($f['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- USŁUGI -->
<section class="bg-[#F5F5F7] py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3"><?= esc($d['servicesTitle']) ?></h2>
        <p class="text-[#6E6E73] mb-10"><?= esc($d['servicesLead']) ?></p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($d['services'] as $s): ?>
                <div class="bg-white border border-[#E5E5EA] rounded p-5 flex gap-4 items-start hover:shadow-md transition-shadow">
                    <?= isense_icon('check-circle', 'w-5 h-5 text-[#3b81f7] flex-shrink-0 mt-0.5') ?>
                    <div>
                        <p class="font-semibold text-[#1D1D1F] text-sm mb-1"><?= esc($s['name']) ?></p>
                        <p class="text-xs text-[#6E6E73]"><?= esc($s['desc']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (! empty($d['pricing'])): ?>
<!-- CENNIK -->
<section id="cennik" class="bg-white py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3"><?= esc($d['pricingTitle']) ?></h2>
        <p class="text-[#6E6E73] mb-10"><?= esc($d['pricingLead']) ?></p>
        <div class="space-y-4">
            <?php foreach ($d['pricing'] as $group): ?>
                <div class="border border-[#E5E5EA] rounded overflow-hidden">
                    <div class="w-full flex items-center justify-between px-6 py-4 bg-[#F5F5F7] text-left">
                        <span class="font-bold text-[#1D1D1F]"><?= esc($group['group']) ?></span>
                        <?= isense_icon('chevron-down', 'w-5 h-5 text-[#6E6E73]') ?>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[#E5E5EA] bg-white">
                                    <th class="text-left px-6 py-3 text-[#6E6E73] font-medium w-1/3">Usługa</th>
                                    <?php foreach ($group['models'] as $m): ?>
                                        <th class="text-center px-4 py-3 text-[#1D1D1F] font-semibold"><?= esc($m) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($group['rows'] as $ri => $row): ?>
                                    <tr class="border-b border-[#F0F0F0] <?= ($ri % 2 === 0) ? 'bg-white' : 'bg-[#FAFAFA]' ?>">
                                        <td class="px-6 py-3 text-[#1D1D1F] font-medium"><?= esc($row['service']) ?></td>
                                        <?php foreach ($row['prices'] as $p): ?>
                                            <td class="px-4 py-3 text-center font-semibold text-[#3b81f7]"><?= esc($p) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="text-xs text-[#6E6E73] mt-4">* Diagnoza bezpłatna przy zleceniu naprawy. Ceny mogą ulec zmianie — ostateczna wycena po diagnozie urządzenia.</p>
    </div>
</section>
<?php endif; ?>

<?php if (! empty($d['models'])): ?>
<!-- MODELE -->
<section class="bg-[#F5F5F7] py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3"><?= esc($d['modelsTitle']) ?></h2>
        <p class="text-[#6E6E73] mb-10"><?= esc($d['modelsLead']) ?></p>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            <?php foreach ($d['models'] as $model): ?>
                <a href="<?= site_url('naprawy/' . $catKey . '/' . $model['slug']) ?>" class="flex items-center justify-between bg-white px-5 py-4 rounded border border-[#D2D2D7] hover:border-[#3b81f7] hover:shadow-md transition-all group">
                    <span class="font-medium text-[#1D1D1F] group-hover:text-[#3b81f7] transition-colors text-sm"><?= esc($model['name']) ?></span>
                    <?= isense_icon('arrow-right', 'w-4 h-4 text-[#6E6E73] group-hover:text-[#3b81f7] transition-colors flex-shrink-0') ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- DLACZEGO iSENSE -->
<section class="bg-white py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6">Dlaczego iSense?</h2>
                <p class="text-[#6E6E73] leading-relaxed mb-4">
                    Serwisujemy sprzęt Apple od 2008 roku. Zapewniamy dostęp do oryginalnych części Apple i pełną kalibrację sprzętową każdej naprawy.
                </p>
                <p class="text-[#6E6E73] leading-relaxed mb-4">
                    Jako <strong class="text-[#1D1D1F]">Apple Independent Repair Provider</strong> mamy dostęp do oryginalnych części i narzędzi diagnostycznych Apple. Każda wymiana jest kalibrowana sprzętowo.
                </p>
                <p class="text-[#6E6E73] leading-relaxed mb-8">
                    Obsługujemy klientów indywidualnych i firmy z całej Polski — zarówno w naszym serwisie w Warszawie Śródmieście, jak i wysyłkowo door-to-door. Znajdziesz nas przy ul. Dobra 56/66, w Budynku Biblioteki UW (minus 1, lok. nr A32), 00-312 Warszawa.
                </p>
                <a href="tel:+48504806905" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-7 py-3.5 rounded font-semibold hover:bg-[#2563eb] transition-colors">
                    <?= isense_icon('phone', 'w-5 h-5') ?> +48 504 806 905
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <?php foreach ($brandStats as $stat): ?>
                    <div class="bg-[#F5F5F7] rounded p-6 text-center border border-[#E5E5EA]">
                        <p class="text-4xl font-bold text-[#3b81f7] mb-1"><?= esc($stat['val']) ?></p>
                        <p class="text-sm text-[#6E6E73]"><?= esc($stat['label']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- FAQ (natywny <details>, bez JS) -->
<section class="bg-[#F5F5F7] py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3 text-center">FAQ</h2>
        <p class="text-[#6E6E73] text-center mb-10"><?= esc($d['faqLead']) ?></p>
        <div class="max-w-3xl mx-auto space-y-3">
            <?php foreach ($d['faqs'] as $faq): ?>
                <details class="border border-[#D2D2D7] rounded bg-white overflow-hidden group">
                    <summary class="cursor-pointer list-none flex items-center justify-between px-6 py-4 font-semibold text-[#1D1D1F] hover:bg-[#F5F5F7]">
                        <span><?= esc($faq['question']) ?></span>
                        <?= isense_icon('chevron-down', 'w-5 h-5 text-[#6E6E73] flex-shrink-0 transition-transform group-open:rotate-180') ?>
                    </summary>
                    <div class="px-6 pb-5 pt-4 text-[#6E6E73] leading-relaxed border-t border-[#E5E5EA]"><?= esc($faq['answer']) ?></div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-[#1D1D1F] py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Wyceń naprawę nawet w 60 sekund</h2>
        <p class="text-[#86868B] mb-8 text-lg">Bezpłatna diagnoza · Gwarancja · Oryginalne części Apple</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?= site_url('kontakt') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-8 py-4 rounded font-semibold hover:bg-[#2563eb] transition-colors text-lg">
                Wyceń naprawę online
                <?= isense_icon('arrow-right', 'w-5 h-5') ?>
            </a>
            <a href="<?= site_url('naprawa-z-odbiorem') ?>" class="inline-flex items-center gap-2 bg-white text-[#1D1D1F] border border-white px-8 py-4 rounded font-semibold hover:bg-[#F5F5F7] transition-colors text-lg">
                <?= isense_icon('package', 'w-5 h-5') ?>
                Naprawa wysyłkowa
            </a>
        </div>
    </div>
</section>

<?= view('user/page/_isense_close') ?>
