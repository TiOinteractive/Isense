<?php
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');
$repairs = ['Wymiana wyświetlacza / LCD', 'Wymiana baterii', 'Naprawa po zalaniu', 'Odblokowanie simlockowe', 'Wymiana złącza ładowania', 'Naprawa NFC', 'Wymiana przycisku HOME', 'Naprawa płyty głównej', 'Wymiana aparatu', 'Wymiana głośnika / mikrofonu', 'Wymiana obudowy', 'Odzyskiwanie danych', 'Wymiana szkła tylnego', 'Aktualizacja oprogramowania'];
?>
<section class="bg-[#F5F5F7] py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6">Naprawimy Twojego iPhone!</h2>
                <p class="text-lg text-[#6E6E73] mb-8">Naprawy iPhone wykonujemy zarówno przy klientach w naszym serwisie, jak i w ramach usługi door-to-door — odbieramy kurierem z całej Polski. Dysponujemy częściami do wszystkich modeli iPhone, od najstarszych po najnowsze.</p>
                <div class="grid grid-cols-2 gap-3 mb-8">
                    <?php foreach ($repairs as $repair): ?>
                        <div class="flex items-center gap-2">
                            <?= isense_icon('check-circle', 'w-4 h-4 text-[#3b81f7] flex-shrink-0') ?>
                            <span class="text-sm text-[#1D1D1F]"><?= esc($repair) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= site_url('naprawy/iphone') ?>" class="inline-flex items-center gap-2 text-[#3b81f7] font-semibold hover:gap-4 transition-all">
                    Dowiedz się więcej
                    <?= isense_icon('arrow-right', 'w-5 h-5') ?>
                </a>
            </div>
            <div class="rounded-md overflow-hidden">
                <img src="<?= $assets ?>/img/iphone.png" alt="Naprawa iPhone" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>
