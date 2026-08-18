<?php
$assets = $assets ?? rtrim(base_url('assets/isense'), '/');
$repairs = ['Wymiana baterii', 'Wymiana / rozbudowa RAM', 'Wymiana dysku SSD / HDD', 'Naprawa po zalaniu', 'Wymiana wyświetlacza', 'Wymiana klawiatury', 'Naprawa GPU / karty graficznej', 'Naprawa płyty głównej', 'Odzyskiwanie danych', 'Wymiana wentylatorów / chłodzenie', 'Diagnostyka i czyszczenie systemu'];
?>
<section class="bg-white py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="rounded-md overflow-hidden order-2 lg:order-1">
                <img src="<?= $assets ?>/img/imac.png" alt="Serwis MacBook i iMac" class="w-full h-full object-cover">
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6">Serwis komputerów iMac oraz Macbook</h2>
                <p class="text-lg text-[#6E6E73] mb-8">Naprawiamy wszystkie modele MacBook — od 11" MacBook Air po Mac Pro — oraz iMac w każdej wersji. Co miesiąc wymieniamy baterie, dyski i pamięci RAM, naprawiamy szkody powodziowe, wymieniamy uszkodzone GPU i płyty główne.</p>
                <div class="grid grid-cols-2 gap-3 mb-8">
                    <?php foreach ($repairs as $repair): ?>
                        <div class="flex items-center gap-2">
                            <?= isense_icon('check-circle', 'w-4 h-4 text-[#3b81f7] flex-shrink-0') ?>
                            <span class="text-sm text-[#1D1D1F]"><?= esc($repair) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= site_url('naprawy/macbook') ?>" class="inline-flex items-center gap-2 text-[#3b81f7] font-semibold hover:gap-4 transition-all">
                    Dowiedz się więcej
                    <?= isense_icon('arrow-right', 'w-5 h-5') ?>
                </a>
            </div>
        </div>
    </div>
</section>
