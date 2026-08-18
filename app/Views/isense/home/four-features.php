<?php
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');
$features = [
    ['icon' => 'feature-diagnoza.png',      'alt' => 'Bezpłatna diagnoza',       'title' => 'Zawsze bezpłatna diagnoza',   'description' => 'Rozlałeś kawę na MacBooku? iPhone upadł na podłogę? A może uszkodzeniu uległ system w iPadzie? Nie zgaduj, co się stało — zapraszamy na bezpłatną diagnozę. Sprawdzimy Twój sprzęt, dokładnie określimy usterkę i przedstawimy wycenę naprawy, zanim podejmiesz decyzję.'],
    ['icon' => 'feature-doswiadczenie.png', 'alt' => 'Wieloletnie doświadczenie', 'title' => 'Wieloletnie doświadczenie',   'description' => 'Serwisujemy Wasz sprzęt Apple nieprzerwanie od 2008 roku. Przez ten czas zaufały nam jednostki administracji publicznej, ministerstwa, znane międzynarodowe koncerny, agencje reklamowe, a także Wasi ulubieni aktorzy. To doświadczenie procentuje przy każdej naprawie.'],
    ['icon' => 'feature-serwis.png',        'alt' => 'Wyspecjalizowany serwis',  'title' => 'Wyspecjalizowany serwis',    'description' => 'Naprawiamy wyłącznie sprzęt Apple, dlatego wiemy o nim więcej niż inni. 80% urządzeń naprawiamy w ciągu 48 godzin roboczych, a wymiana szybki w iPhonie zajmuje nam około godziny. Używamy tylko dedykowanych części — sprzęt wraca do pełnej sprawności i pierwotnego wyglądu.'],
    ['icon' => 'feature-serwisanci.png',    'alt' => 'Doświadczeni serwisanci',  'title' => 'Doświadczeni serwisanci',    'description' => 'Nasi serwisanci naprawią Twojego iPhone\'a, iPada, iMaca czy MacBooka, korzystając wyłącznie z najlepszych części. Problem ze słuchawkami Beats? Ktoś powiedział Ci, że nie da się ich naprawić? Zapraszamy — sprawdzimy, co możemy dla nich zrobić.'],
];
?>
<section class="bg-white py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <?php foreach ($features as $feature): ?>
                <div class="flex flex-col items-center text-center bg-[#F5F5F7] rounded-sm p-8">
                    <div class="w-24 h-24 bg-[#3b81f7] rounded-full flex items-center justify-center mb-6 flex-shrink-0">
                        <img src="<?= $assets ?>/img/<?= $feature['icon'] ?>" alt="<?= esc($feature['alt'], 'attr') ?>" class="w-14 h-14 object-contain brightness-0 invert">
                    </div>
                    <h3 class="text-lg font-bold text-[#1D1D1F] mb-3 leading-[1.575]"><?= esc($feature['title']) ?></h3>
                    <p class="text-[#6E6E73] text-sm leading-relaxed"><?= esc($feature['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
