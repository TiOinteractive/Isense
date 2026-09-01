<?php
helper(['url', 'isense']);
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');
$announcement = isense_announcement();
// Logotyp — panel → Ustawienia → „Logo"; bez ustawienia wracamy do pliku statycznego
$logo = isense_logo() ?: $assets . '/img/logo.png';
// Telefon — panel → Ustawienia → „Numer telefonu"
$phone = isense_phone();
// Górne menu — zarządzane w panelu → Menu → „Menu górne" (id 1)
$topMenu = isense_menu(1);
// Przyciski CTA — panel → Menu → „Nagłówek — przyciski" (id 9). Każda pozycja
// tego menu renderuje się jako osobny przycisk, w trzech miejscach naraz.
$buttonsMenu = 9;
// Awaryjne ikony dla pozycji bez ikony ustawionej w panelu (dopasowanie po nazwie)
$fallbackIcons = [
    'słuchawk' => 'headphones',
    'sluchawk' => 'headphones',
    'airpods'  => 'headphones',
    'beats'    => 'headphones',
];
$childIcon = static function (array $child) use ($fallbackIcons): string {
    if (! empty($child['icon'])) {
        return $child['icon'];
    }
    $name = mb_strtolower($child['name'] ?? '');
    foreach ($fallbackIcons as $needle => $icon) {
        if (mb_strpos($name, $needle) !== false) {
            return $icon;
        }
    }

    return '';
};
?>
<div class="relative z-50">
    <!-- Pasek ogłoszeniowy (zarządzalny: panel → Ustawienia → „Pasek ogłoszeniowy") -->
    <?php if (! empty($announcement['enabled'])): ?>
        <div data-announcement class="bg-[#3b81f7] text-white text-[24px] py-2 px-4 w-full relative">
            <div class="max-w-[1300px] mx-auto flex items-center justify-center">
                <p class="text-center [&_a]:underline [&_a]:font-semibold [&_a:hover]:text-white/80"><?= $announcement['text'] ?></p>
            </div>
            <button type="button" data-announcement-close class="absolute right-4 top-1/2 -translate-y-1/2 text-white/60 hover:text-white transition-colors" aria-label="Zamknij">
                <?= isense_icon('x', 'w-3.5 h-3.5') ?>
            </button>
        </div>
    <?php endif; ?>

    <!-- Biały górny pasek -->
    <div class="bg-white pb-8 border-b border-[#E5E5EA]">
        <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
            <div class="flex items-center justify-between py-8 text-[#1D1D1F] text-sm">
                <a href="<?= site_url('/') ?>" class="hidden md:flex items-center flex-shrink-0">
                    <?= isense_img($logo, 'iSense logo', 'h-[56px] w-auto object-contain', ['sizes' => '240px', 'loading' => 'eager']) ?>
                    <span class="text-[25px] text-[#1D1D1F] font-semibold ml-6">Serwis i naprawa sprzętu Apple</span>
                </a>
                <a href="<?= site_url('/') ?>" class="flex md:hidden">
                    <?= isense_img($logo, 'iSense logo', 'h-[45px] w-auto object-contain', ['sizes' => '200px', 'loading' => 'eager']) ?>
                </a>
                <div class="flex items-center gap-4">
                    <a href="tel:<?= esc(isense_tel(), 'attr') ?>" class="flex items-center gap-2 text-[20px] font-semibold hover:text-[#3b81f7] transition-colors">
                        <?= isense_icon('phone', 'w-[22px] h-[22px]') ?>
                        <?= esc($phone) ?>
                    </a>
                    <?= view_cell('\App\Libraries\IsenseMenu::buttons', ['id_menu' => $buttonsMenu, 'variant' => 'desktop']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Pasek nawigacji -->
    <div class="sticky top-0 z-50 -mt-8">
        <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
            <div class="rounded shadow-[0_4px_16px_rgba(0,0,0,0.18)]" style="background: linear-gradient(to bottom, #909090 0%, #5e5e5e 40%, #424242 100%);">
                <div class="px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Desktop nav -->
                        <nav class="hidden lg:flex items-center gap-1 mx-auto">
                            <?php foreach ($topMenu as $item): ?>
                                <?php if ($item['class'] === 'home'): ?>
                                    <a href="<?= $item['url'] ?>" class="px-3 py-2 text-white hover:text-[#3b81f7] transition-all rounded" aria-label="<?= esc($item['name'], 'attr') ?>">
                                        <?= isense_icon($item['icon'] ?: 'home', 'w-5 h-5') ?>
                                    </a>
                                <?php elseif (! empty($item['children'])): ?>
                                    <div class="relative" data-dropdown>
                                        <button type="button" data-dropdown-toggle class="flex items-center gap-1 px-4 py-2 text-white hover:text-[#3b81f7] transition-all font-bold text-[15px] rounded uppercase">
                                            <?= esc($item['name']) ?>
                                            <?= isense_icon('chevron-down', 'w-3.5 h-3.5 transition-transform duration-200', ['data-dropdown-caret' => '']) ?>
                                        </button>
                                        <div data-dropdown-menu class="hidden absolute top-full left-0 mt-1 w-52 rounded-lg overflow-hidden shadow-2xl border border-[#E5E5EA] bg-white z-50">
                                            <?php foreach ($item['children'] as $child): ?>
                                                <a href="<?= $child['url'] ?>"<?= $child['target'] ? ' target="' . esc($child['target'], 'attr') . '"' : '' ?> class="flex items-center gap-3 px-4 py-3 text-[#1D1D1F] hover:bg-[#F5F5F7] hover:text-[#3b81f7] transition-colors text-sm font-medium border-b border-[#F0F0F0] last:border-0">
                                                    <?php $icon = $childIcon($child); ?>
                                                    <?php if ($icon): ?><?= isense_icon($icon, 'w-4 h-4 text-[#3b81f7] flex-shrink-0') ?><?php endif; ?>
                                                    <?= esc($child['name']) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= $item['url'] ?>"<?= $item['target'] ? ' target="' . esc($item['target'], 'attr') . '"' : '' ?> class="px-4 py-2 text-white hover:text-[#3b81f7] transition-all font-bold text-[15px] rounded uppercase"><?= esc($item['name']) ?></a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </nav>

                        <!-- Mobile burger -->
                        <button type="button" data-mobile-toggle class="lg:hidden p-2 ml-auto" aria-label="Menu">
                            <span data-mobile-icon-open><?= isense_icon('menu', 'w-6 h-6 text-white') ?></span>
                            <span data-mobile-icon-close class="hidden"><?= isense_icon('x', 'w-6 h-6 text-white') ?></span>
                        </button>
                    </div>

                    <!-- Mobile menu -->
                    <div data-mobile-menu class="hidden lg:hidden py-4 space-y-1 border-t border-[#aaaaaa]">
                        <?php foreach ($topMenu as $item): ?>
                            <?php if (! empty($item['children'])): ?>
                                <div>
                                    <button type="button" data-mobile-apple-toggle class="w-full flex items-center justify-between py-2 text-sm font-medium text-white">
                                        <?= esc($item['name']) ?>
                                        <?= isense_icon('chevron-down', 'w-4 h-4 transition-transform duration-200', ['data-mobile-apple-caret' => '']) ?>
                                    </button>
                                    <div data-mobile-apple-menu class="hidden pl-4 space-y-1 pb-2">
                                        <?php foreach ($item['children'] as $child): ?>
                                            <a href="<?= $child['url'] ?>"<?= $child['target'] ? ' target="' . esc($child['target'], 'attr') . '"' : '' ?> class="block py-1.5 text-sm text-white/80 hover:text-white"><?= esc($child['name']) ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="<?= $item['url'] ?>"<?= $item['target'] ? ' target="' . esc($item['target'], 'attr') . '"' : '' ?> class="block py-2 text-sm font-medium text-white"><?= esc($item['name']) ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?= view_cell('\App\Libraries\IsenseMenu::buttons', ['id_menu' => $buttonsMenu, 'variant' => 'mobile']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobilny sticky CTA -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-[#D2D2D7] p-3 z-40">
        <?= view_cell('\App\Libraries\IsenseMenu::buttons', ['id_menu' => $buttonsMenu, 'variant' => 'sticky']) ?>
    </div>
</div>
