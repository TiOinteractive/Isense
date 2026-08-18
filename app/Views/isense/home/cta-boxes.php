<?php
$boxes = [
    ['icon' => 'clipboard-list', 'title' => 'Zapytaj o wycenę naprawy', 'description' => 'Opisz usterkę, dołącz zdjęcia — nasz serwisant odpowie w ciągu kilku godzin z wyceną i oceną możliwości naprawy.', 'cta' => 'Wyślij zapytanie', 'link' => 'kontakt'],
    ['icon' => 'package-search', 'title' => 'Zamów odbiór sprzętu', 'description' => 'Wypełnij formularz, a kurier odbierze Twój sprzęt bezpośrednio od Ciebie. Weryfikujemy zlecenie przed wysyłką — zero ryzyka.', 'cta' => 'Zamów odbiór', 'link' => 'naprawa-z-odbiorem'],
    ['icon' => 'refresh-cw', 'title' => 'Trade-In starego sprzętu', 'description' => 'Wymień stary sprzęt Apple na gotówkę lub rabat na naprawę. Wyceniamy każde urządzenie indywidualnie.', 'cta' => 'Sprawdź wartość', 'link' => 'trade-in'],
];
?>
<section class="relative z-10 -mt-14 pb-0">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($boxes as $box): ?>
                <a href="<?= site_url($box['link']) ?>" class="group flex flex-col bg-white border border-[#E5E5EA] hover:border-[#3b81f7] rounded-sm p-7 shadow-xl transition-all hover:-translate-y-1">
                    <div class="flex justify-center mb-5">
                        <?= isense_icon($box['icon'], 'w-9 h-9 text-[#3b81f7]', ['stroke-width' => '1.5']) ?>
                    </div>
                    <h3 class="text-[#1D1D1F] font-semibold text-base text-center mb-2"><?= esc($box['title']) ?></h3>
                    <p class="text-[#6E6E73] text-sm leading-relaxed text-center mb-6 flex-1"><?= esc($box['description']) ?></p>
                    <div class="flex items-center justify-center gap-2 text-[#3b81f7] font-medium text-sm group-hover:gap-4 transition-all mt-auto">
                        <?= esc($box['cta']) ?>
                        <?= isense_icon('arrow-right', 'w-4 h-4') ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
