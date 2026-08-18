<?php
/*
iSense — Cennik
*/
helper(['url', 'isense']);
$isAssets = rtrim(base_url('assets/isense'), '/');
$devices = [
    ['slug' => 'iphone',    'name' => 'iPhone',   'icon' => 'smartphone', 'img' => 'iphone.png', 'desc' => 'Wyświetlacze, baterie, płyty główne, aparaty i więcej.'],
    ['slug' => 'ipad',      'name' => 'iPad',     'icon' => 'tablet',     'img' => 'ipad.png',   'desc' => 'Ekrany dotykowe, baterie, złącza, naprawy po zalaniu.'],
    ['slug' => 'imac',      'name' => 'iMac',     'icon' => 'monitor',    'img' => 'imac.png',   'desc' => 'Matryce, dyski SSD, pamięci RAM, płyty główne.'],
    ['slug' => 'macbook',   'name' => 'MacBook',  'icon' => 'laptop',     'img' => 'imac.png',   'desc' => 'Klawiatury, baterie, flexgate, grafika, dyski.'],
    ['slug' => 'zasilacze', 'name' => 'MagSafe',  'icon' => 'zap',        'img' => 'hero.png',   'desc' => 'Zasilacze i ładowarki MagSafe — naprawa i wymiana.'],
];
?>
<?= view('user/page/_isense_open') ?>

<!-- Hero -->
<section class="bg-gradient-to-b from-[#F5F5F7] to-white py-16 lg:py-20">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-[#1D1D1F] mb-5">Cennik napraw</h1>
            <p class="text-lg lg:text-xl text-[#6E6E73]">Bezpłatna diagnoza • Gwarancja min. 90 dni • Oryginalne części Apple. Wybierz urządzenie, aby zobaczyć szczegółowy cennik.</p>
        </div>
    </div>
</section>

<!-- Karty urządzeń -->
<section class="bg-white py-14 lg:py-20">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($devices as $d): ?>
                <a href="<?= site_url('naprawy/' . $d['slug']) ?>" class="group flex flex-col bg-white border border-[#E5E5EA] hover:border-[#3b81f7] rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="h-44 bg-[#F5F5F7] overflow-hidden">
                        <img src="<?= $isAssets ?>/img/<?= $d['img'] ?>" alt="Serwis <?= esc($d['name'], 'attr') ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-10 h-10 bg-[#3b81f7]/10 rounded-lg flex items-center justify-center flex-shrink-0"><?= isense_icon($d['icon'], 'w-5 h-5 text-[#3b81f7]') ?></span>
                            <h2 class="text-xl font-bold text-[#1D1D1F]">Serwis <?= esc($d['name']) ?></h2>
                        </div>
                        <p class="text-[#6E6E73] text-sm leading-relaxed mb-5 flex-1"><?= esc($d['desc']) ?></p>
                        <span class="inline-flex items-center gap-2 text-[#3b81f7] font-semibold text-sm group-hover:gap-4 transition-all mt-auto">Zobacz cennik <?= isense_icon('arrow-right', 'w-4 h-4') ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Wycena indywidualna -->
<section class="bg-[#F5F5F7] py-14 lg:py-20">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl lg:text-3xl font-bold text-[#1D1D1F] mb-4">Nie znalazłeś swojej usterki?</h2>
            <p class="text-[#6E6E73] mb-8">Opisz problem, a przygotujemy indywidualną wycenę. Diagnoza jest zawsze bezpłatna i bez zobowiązań.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= site_url('kontakt') ?>" class="inline-flex items-center justify-center gap-2 bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium">Wyceń naprawę <?= isense_icon('arrow-right', 'w-5 h-5') ?></a>
                <a href="tel:+48504806905" class="inline-flex items-center justify-center gap-2 bg-white border-2 border-[#1D1D1F] text-[#1D1D1F] px-8 py-4 rounded-lg hover:bg-[#F5F5F7] transition-colors font-medium"><?= isense_icon('phone', 'w-5 h-5') ?> +48 504 806 905</a>
            </div>
        </div>
    </div>
</section>

<?= view('user/page/_isense_close') ?>
