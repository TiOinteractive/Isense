<?php
/*
iSense — Strona treści
*/
helper(['url', 'isense']);
$isAssets = rtrim(base_url('assets/isense'), '/');
$seg = explode('/', trim(uri_string(), '/'));
$slug = end($seg);

$svc = function ($items) {
    $o = '<ul class="space-y-3 text-[#6E6E73] mb-6">';
    foreach ($items as $it) {
        $o .= '<li class="flex items-start gap-3"><span class="mt-1 text-[#3b81f7]">' . isense_icon('check-circle', 'w-5 h-5') . '</span><span>' . $it . '</span></li>';
    }
    return $o . '</ul>';
};

$P = [
    'odzyskiwanie-danych' => [
        'title' => 'Odzyskiwanie danych',
        'lead'  => 'Odzyskujemy zdjęcia, kontakty i dokumenty nawet z uszkodzonego lub zalanego sprzętu Apple.',
        'body'  => '<p class="text-lg text-[#6E6E73] leading-relaxed mb-6">Utrata danych to jeden z najbardziej stresujących problemów. W iSense od 2008 roku ratujemy dane z iPhone, iPad, MacBook i iMac — także w sytuacjach, które inne serwisy uznają za beznadziejne.</p>'
            . '<h2 class="text-2xl font-bold text-[#1D1D1F] mt-8 mb-4">Z jakich sytuacji odzyskujemy dane?</h2>'
            . $svc(['Urządzenie po zalaniu, które nie włącza się', 'Uszkodzona płyta główna lub kość pamięci', 'Zablokowany system po nieudanej aktualizacji', 'Uszkodzony dysk SSD w MacBooku / iMacu', 'Przypadkowo usunięte pliki i zdjęcia']),
    ],
    'wymiany-sprzetu' => [
        'title' => 'Wymiany sprzętu (odkup)',
        'lead'  => 'Nie opłaca się naprawiać? Odkupimy Twój sprzęt Apple lub wymienimy go na inny.',
        'body'  => '<p class="text-lg text-[#6E6E73] leading-relaxed mb-6">Jeśli naprawa nie ma sensu ekonomicznego, zaproponujemy uczciwą wycenę odkupu lub wymiany. Każde urządzenie wyceniamy indywidualnie, biorąc pod uwagę model i stan techniczny.</p>'
            . $svc(['Wycena odkupu od ręki po bezpłatnej diagnozie', 'Możliwość dopłaty do sprawnego urządzenia', 'Bezpieczne czyszczenie danych z odkupionego sprzętu', 'Rozliczenie gotówką lub rabatem na naprawę']),
    ],
    'simlock' => [
        'title' => 'Simlock i odblokowania',
        'lead'  => 'Zdejmiemy blokadę operatora i pomożemy w odblokowaniu iPhone.',
        'body'  => '<p class="text-lg text-[#6E6E73] leading-relaxed mb-6">Oferujemy usługi odblokowania simlock oraz pomoc przy blokadach systemowych. Doradzimy najlepsze i zgodne z prawem rozwiązanie dla Twojego urządzenia.</p>'
            . $svc(['Zdjęcie blokady operatora (simlock)', 'Pomoc przy blokadzie aktywacji', 'Doradztwo w sprawie iCloud / Find My', 'Diagnostyka przed wykonaniem usługi']),
    ],
    'ekspertyzy' => [
        'title' => 'Ekspertyzy serwisowe',
        'lead'  => 'Profesjonalne ekspertyzy techniczne sprzętu Apple na potrzeby reklamacji i ubezpieczeń.',
        'body'  => '<p class="text-lg text-[#6E6E73] leading-relaxed mb-6">Sporządzamy szczegółowe ekspertyzy techniczne urządzeń Apple. Przydają się przy reklamacjach, sporach gwarancyjnych oraz zgłoszeniach ubezpieczeniowych.</p>'
            . $svc(['Ocena stanu technicznego urządzenia', 'Ustalenie przyczyny usterki', 'Dokumentacja fotograficzna i opis', 'Dokument gotowy do przedłożenia w reklamacji']),
    ],
    'naprawy-gwarancyjne' => [
        'title' => 'Naprawy gwarancyjne',
        'lead'  => 'Na każdą naprawę i użyte części udzielamy gwarancji — minimum 90 dni.',
        'body'  => '<p class="text-lg text-[#6E6E73] leading-relaxed mb-6">Jakość naszej pracy potwierdzamy gwarancją. Na większość napraw udzielamy 12 miesięcy gwarancji obejmującej zarówno usługę, jak i wymienione części.</p>'
            . $svc(['Minimum 90 dni gwarancji na każdą naprawę', 'Do 12 miesięcy na większość usług', 'Gwarancja na usługę i na części', 'Szybka obsługa zgłoszeń gwarancyjnych']),
    ],
    'polityka-prywatnosci' => [
        'title' => 'Polityka prywatności',
        'lead'  => 'Informacja o przetwarzaniu danych osobowych w serwisie iSense.',
        'body'  => '<p class="text-[#6E6E73] leading-relaxed mb-4">Niniejsza polityka prywatności ma charakter informacyjny i stanowi szablon do uzupełnienia o dane administratora oraz szczegóły przetwarzania zgodne z RODO.</p>'
            . '<h2 class="text-2xl font-bold text-[#1D1D1F] mt-8 mb-4">1. Administrator danych</h2><p class="text-[#6E6E73] leading-relaxed mb-4">Administratorem danych jest iSense, ul. Dobra 56/66, 00-312 Warszawa. Kontakt: dobra@isense.pl.</p>'
            . '<h2 class="text-2xl font-bold text-[#1D1D1F] mt-8 mb-4">2. Cel przetwarzania</h2><p class="text-[#6E6E73] leading-relaxed mb-4">Dane przetwarzamy w celu realizacji zlecenia serwisowego, kontaktu oraz obsługi reklamacji.</p>'
            . '<h2 class="text-2xl font-bold text-[#1D1D1F] mt-8 mb-4">3. Prawa użytkownika</h2><p class="text-[#6E6E73] leading-relaxed mb-4">Masz prawo dostępu do danych, ich sprostowania, usunięcia oraz ograniczenia przetwarzania.</p>'
            . '<p class="text-sm text-[#86868B] mt-8">Treść do uzupełnienia przez administratora o pełne zapisy zgodne z obowiązującymi przepisami.</p>',
    ],
    'regulamin' => [
        'title' => 'Regulamin serwisu',
        'lead'  => 'Zasady świadczenia usług serwisowych iSense.',
        'body'  => '<p class="text-[#6E6E73] leading-relaxed mb-4">Niniejszy regulamin ma charakter szablonu do uzupełnienia o pełne warunki świadczenia usług.</p>'
            . '<h2 class="text-2xl font-bold text-[#1D1D1F] mt-8 mb-4">1. Przyjęcie sprzętu</h2><p class="text-[#6E6E73] leading-relaxed mb-4">Sprzęt przyjmujemy do serwisu po bezpłatnej diagnozie. O kosztach naprawy informujemy przed jej rozpoczęciem.</p>'
            . '<h2 class="text-2xl font-bold text-[#1D1D1F] mt-8 mb-4">2. Realizacja naprawy</h2><p class="text-[#6E6E73] leading-relaxed mb-4">Naprawę wykonujemy po akceptacji wyceny przez klienta. Większość zleceń realizujemy w 24–48 godzin roboczych.</p>'
            . '<h2 class="text-2xl font-bold text-[#1D1D1F] mt-8 mb-4">3. Gwarancja</h2><p class="text-[#6E6E73] leading-relaxed mb-4">Na wykonane naprawy udzielamy minimum 90 dni gwarancji na usługę i użyte części.</p>'
            . '<p class="text-sm text-[#86868B] mt-8">Treść do uzupełnienia przez usługodawcę o pełne warunki, odpowiedzialność i procedury reklamacyjne.</p>',
    ],
];

$p = $P[$slug] ?? ['title' => 'iSense', 'lead' => '', 'body' => '<p class="text-[#6E6E73]">Strona w przygotowaniu.</p>'];
$isLegal = in_array($slug, ['polityka-prywatnosci', 'regulamin'], true);
?>
<?= view('user/page/_isense_open') ?>

<section class="bg-gradient-to-b from-[#F5F5F7] to-white py-16 lg:py-20">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-[#1D1D1F] mb-5"><?= esc($p['title']) ?></h1>
            <?php if (!empty($p['lead'])): ?><p class="text-lg lg:text-xl text-[#6E6E73]"><?= esc($p['lead']) ?></p><?php endif; ?>
        </div>
    </div>
</section>

<section class="bg-white py-14 lg:py-20">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto"><?= $p['body'] ?></div>
    </div>
</section>

<?php if (!$isLegal): ?>
<section class="bg-[#F5F5F7] py-14 lg:py-20">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl lg:text-3xl font-bold text-[#1D1D1F] mb-4">Potrzebujesz tej usługi?</h2>
            <p class="text-[#6E6E73] mb-8">Napisz do nas lub zadzwoń — bezpłatna diagnoza i wycena bez zobowiązań.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= site_url('kontakt') ?>" class="inline-flex items-center justify-center gap-2 bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium">Skontaktuj się <?= isense_icon('arrow-right', 'w-5 h-5') ?></a>
                <a href="tel:+48504806905" class="inline-flex items-center justify-center gap-2 bg-white border-2 border-[#1D1D1F] text-[#1D1D1F] px-8 py-4 rounded-lg hover:bg-[#F5F5F7] transition-colors font-medium"><?= isense_icon('phone', 'w-5 h-5') ?> +48 504 806 905</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?= view('user/page/_isense_close') ?>
