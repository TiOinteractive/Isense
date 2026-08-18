<?php
helper(['url', 'isense']);
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');

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

$col = static function (string $title, array $items) {
    echo '<div><h3 class="font-semibold mb-4 text-white">' . esc($title) . '</h3><ul class="space-y-2 text-sm text-[#86868B]">';
    foreach ($items as $it) {
        $t = ! empty($it['target']) ? ' target="' . esc($it['target'], 'attr') . '"' : '';
        echo '<li><a href="' . esc($it['url'], 'attr') . '"' . $t . ' class="hover:text-white transition-colors">' . esc($it['name']) . '</a></li>';
    }
    echo '</ul></div>';
};
$linkGrid = static function (string $title, array $items) {
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
                    <img src="<?= $assets ?>/img/logo-footer.png" alt="iSense logo" class="h-10 w-auto object-contain brightness-0 invert">
                </a>
                <ul class="space-y-3 text-sm text-[#86868B]">
                    <li class="flex items-start gap-2">
                        <?= isense_icon('map-pin', 'w-4 h-4 flex-shrink-0 mt-0.5 text-[#3b81f7]') ?>
                        <span>ul. Dobra 56/66, Budynek Biblioteki UW<br>(minus 1, lok. nr A32), 00-312 Warszawa</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <?= isense_icon('phone', 'w-4 h-4 flex-shrink-0 text-[#3b81f7]') ?>
                        <a href="tel:+48504806905" class="hover:text-white transition-colors">+48 504 806 905</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <?= isense_icon('mail', 'w-4 h-4 flex-shrink-0 text-[#3b81f7]') ?>
                        <a href="mailto:dobra@isense.pl" class="hover:text-white transition-colors">dobra@isense.pl</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <?= isense_icon('clock', 'w-4 h-4 flex-shrink-0 text-[#3b81f7]') ?>
                        <span>Pon–Pt: 9:00–19:00</span>
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
            <div class="grid grid-cols-2 gap-8">
                <?php $col('Oferta', $menuOferta); ?>
                <?php $col('Usługi', $menuUslugi); ?>
            </div>

            <!-- Firma + Szybkie linki -->
            <div class="grid grid-cols-2 gap-8">
                <?php $col('Firma', $menuFirma); ?>
                <?php $col('Szybkie linki', $menuSzybkie); ?>
            </div>
        </div>

        <!-- Dolny pas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pt-12">
            <?php $linkGrid('Serwis iPhone', $menuSeoIphone); ?>
            <?php $linkGrid('Serwis iPad', $menuSeoIpad); ?>
            <?php $linkGrid('Serwis MacBook', $menuSeoMacbook); ?>
        </div>

        <!-- Bottom bar -->
        <div class="border-t border-[#2C2C2E] mt-12 pt-8 text-center text-sm text-[#86868B]">
            <p>© 2026 iSense. Wszystkie prawa zastrzeżone. Nie jesteśmy autoryzowanym serwisem Apple Inc.</p>
        </div>
    </div>
</footer>
