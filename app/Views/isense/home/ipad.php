<?php
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');
$repairs = ['Wymiana baterii', 'Naprawa po zalaniu', 'Wymiana wyświetlacza / ekranu dotykowego', 'Odzyskiwanie danych'];
?>
<section class="bg-[#F5F5F7] py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6">Profesjonalny serwis iPadów</h2>
                <p class="text-lg text-[#6E6E73] mb-4">Serwis czynny od poniedziałku do soboty. Działamy od 2008 roku i cieszymy się uznaniem portalu MyApple.pl. Obsługujemy zarówno klientów indywidualnych, jak i firmy z całej Polski.</p>
                <p class="text-lg text-[#6E6E73] mb-8">Naprawiamy wszystkie generacje iPada. Każde zlecenie poprzedzone jest bezpłatną diagnozą, a o kosztach informujemy z wyprzedzeniem.</p>
                <div class="space-y-3 mb-8">
                    <?php foreach ($repairs as $repair): ?>
                        <div class="flex items-center gap-2">
                            <?= isense_icon('check-circle', 'w-4 h-4 text-[#3b81f7] flex-shrink-0') ?>
                            <span class="text-sm text-[#1D1D1F]"><?= esc($repair) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= site_url('naprawy/ipad') ?>" class="inline-flex items-center gap-2 text-[#3b81f7] font-semibold hover:gap-4 transition-all">
                    Dowiedz się więcej
                    <?= isense_icon('arrow-right', 'w-5 h-5') ?>
                </a>
            </div>
            <div class="rounded-md overflow-hidden">
                <img src="<?= $assets ?>/img/ipad.png" alt="Serwis iPad" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>
