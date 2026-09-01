<?php
helper(['url', 'isense']);
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');

// Dane kontaktowe — panel → Ustawienia. Fallbacki trzymaja dotychczasowe
// wartosci, zeby puste pole w panelu nie zostawilo w stopce dziury.
$address = isense_setting('address', "ul. Dobra 56/66, Budynek Biblioteki UW\n(minus 1, lok. nr A32), 00-312 Warszawa");
$email   = isense_setting('email', 'dobra@isense.pl');
$hours   = isense_setting('opening_hours', 'Pon–Pt: 9:00–19:00');
$company = isense_setting('company_name', 'iSense');

// Logotyp na ciemnym tle. „Logo w trybie ciemnym" jest juz jasne, wiec idzie
// bez filtra; zwykle logo (grafika pod jasne tlo) i plik statyczny wymagaja
// odwrocenia na bialo.
$logoDark  = isense_logo('logo_dark');
$logo      = $logoDark ?: (isense_logo() ?: $assets . '/img/logo-footer.png');
$logoClass = $logoDark ? '' : ' brightness-0 invert';

// Ikony społecznościowe — adresy z konfiguracji (Ustawienia → Portale społecznościowe).
// Renderowane tylko te, które mają ustawiony URL.
$socialLinks = [];
foreach ([
    ['name' => 'facebook',  'icon' => 'facebook',  'label' => 'Facebook'],
    ['name' => 'instagram', 'icon' => 'instagram', 'label' => 'Instagram'],
    ['name' => 'youtube',   'icon' => 'youtube',   'label' => 'YouTube'],
    ['name' => 'tiktok',    'icon' => 'tiktok',    'label' => 'TikTok'],
    ['name' => 'twitter',   'icon' => 'twitter',   'label' => 'X / Twitter'],
    ['name' => 'linkedin',  'icon' => 'linkedin',  'label' => 'LinkedIn'],
] as $s) {
    $url = isense_setting($s['name']);
    if ($url !== '') {
        $socialLinks[] = ['url' => $url, 'icon' => $s['icon'], 'label' => $s['label']];
    }
}

// Kolumny stopki — zarządzane w panelu → Menu (Stopka — Oferta/Usługi/Firma/Szybkie linki)
$menuOferta  = isense_menu(2);
$menuUslugi  = isense_menu(3);
$menuFirma   = isense_menu(4);
$menuSzybkie = isense_menu(5);

// Dolne siatki SEO stopki — zarządzane w panelu → Menu (Stopka — Serwis iPhone/iPad/MacBook)
$menuSeoIphone  = isense_menu(6);
$menuSeoIpad    = isense_menu(7);
$menuSeoMacbook = isense_menu(8);

// Pusta lista = menu odpublikowane albo bez pozycji — nie renderujemy samego naglowka.
$col = static function (string $title, array $items) {
    if (empty($items)) {
        return;
    }
    echo '<div><h3 class="font-semibold mb-4 text-white">' . esc($title) . '</h3><ul class="space-y-2 text-sm text-[#86868B]">';
    foreach ($items as $it) {
        $t = ! empty($it['target']) ? ' target="' . esc($it['target'], 'attr') . '"' : '';
        echo '<li><a href="' . esc($it['url'], 'attr') . '"' . $t . ' class="hover:text-white transition-colors">' . esc($it['name']) . '</a></li>';
    }
    echo '</ul></div>';
};
$linkGrid = static function (string $title, array $items) {
    if (empty($items)) {
        return;
    }
    echo '<div><h3 class="font-semibold mb-4 text-white">' . esc($title) . '</h3><div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-[#86868B]">';
    foreach ($items as $it) {
        $t = ! empty($it['target']) ? ' target="' . esc($it['target'], 'attr') . '"' : '';
        echo '<a href="' . esc($it['url'], 'attr') . '"' . $t . ' class="hover:text-white transition-colors truncate">' . esc($it['name']) . '</a>';
    }
    echo '</div></div>';
};
?>
<footer class="bg-[#1D1D1F] text-white">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12 py-16">

        <!-- Górny pas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-12 border-b border-[#2C2C2E]">
            <!-- Logo & kontakt -->
            <div>
                <a href="<?= site_url('/') ?>" class="flex items-center mb-6">
                    <?= isense_img($logo, $company, 'h-10 w-auto object-contain' . $logoClass, ['sizes' => '180px']) ?>
                </a>
                <ul class="space-y-3 text-sm text-[#86868B]">
                    <li class="flex items-start gap-2">
                        <?= isense_icon('map-pin', 'w-4 h-4 flex-shrink-0 mt-0.5 text-[#3b81f7]') ?>
                        <span><?= nl2br(esc($address)) ?></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <?= isense_icon('phone', 'w-4 h-4 flex-shrink-0 text-[#3b81f7]') ?>
                        <a href="tel:<?= esc(isense_tel(), 'attr') ?>" class="hover:text-white transition-colors"><?= esc(isense_phone()) ?></a>
                    </li>
                    <li class="flex items-center gap-2">
                        <?= isense_icon('mail', 'w-4 h-4 flex-shrink-0 text-[#3b81f7]') ?>
                        <a href="mailto:<?= esc($email, 'attr') ?>" class="hover:text-white transition-colors"><?= esc($email) ?></a>
                    </li>
                    <li class="flex items-center gap-2">
                        <?= isense_icon('clock', 'w-4 h-4 flex-shrink-0 text-[#3b81f7]') ?>
                        <span><?= nl2br(esc($hours)) ?></span>
                    </li>
                </ul>
                <?php if (! empty($socialLinks)): ?>
                    <div class="flex gap-3 mt-6">
                        <?php foreach ($socialLinks as $s): ?>
                            <a href="<?= esc($s['url'], 'attr') ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 bg-[#2C2C2E] rounded-lg flex items-center justify-center hover:bg-[#3b81f7] transition-colors" aria-label="<?= esc($s['label'], 'attr') ?>"><?= isense_icon($s['icon'], 'w-4 h-4') ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Oferta + Usługi -->
            <?php if ($menuOferta || $menuUslugi): ?>
                <div class="grid grid-cols-2 gap-8">
                    <?php $col('Oferta', $menuOferta); ?>
                    <?php $col('Usługi', $menuUslugi); ?>
                </div>
            <?php endif; ?>

            <!-- Firma + Szybkie linki -->
            <?php if ($menuFirma || $menuSzybkie): ?>
                <div class="grid grid-cols-2 gap-8">
                    <?php $col('Firma', $menuFirma); ?>
                    <?php $col('Szybkie linki', $menuSzybkie); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Dolny pas -->
        <?php $hasSeoGrid = $menuSeoIphone || $menuSeoIpad || $menuSeoMacbook; ?>
        <?php if ($hasSeoGrid): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pt-12">
                <?php $linkGrid('Serwis iPhone', $menuSeoIphone); ?>
                <?php $linkGrid('Serwis iPad', $menuSeoIpad); ?>
                <?php $linkGrid('Serwis MacBook', $menuSeoMacbook); ?>
            </div>
        <?php endif; ?>

        <?php /* Bez siatki SEO kreska dolnego paska stykalaby sie z kreska gornego — zostaje jedna. */ ?>
        <!-- Bottom bar -->
        <div class="<?= $hasSeoGrid ? 'border-t border-[#2C2C2E] mt-12 pt-8' : 'pt-8' ?> text-center text-sm text-[#86868B]">
            <?php /* Rok z daty biezacej — inaczej stopka zestarzeje sie 1 stycznia. */ ?>
            <p>© <?= date('Y') ?> <?= esc($company) ?>. Wszystkie prawa zastrzeżone. Nie jesteśmy autoryzowanym serwisem Apple Inc.</p>
        </div>
    </div>
</footer>
