<?php $assets = $assets ?? rtrim(base_url('assets/isense'), '/'); ?>
<section style="background-color:#ffffff;" class="py-8 lg:py-10">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-3 leading-tight">Naprawy sprzętu Apple</h2>
            <p class="text-xl lg:text-2xl text-[#3b81f7] font-medium italic">– dla innych to wyzwanie …dla nas przyjemność.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 max-w-5xl mx-auto">
            <div class="flex flex-col items-center text-center">
                <div class="relative h-72 w-full flex items-end justify-center mb-6">
                    <img src="<?= $assets ?>/img/intro-technician.png" alt="Serwisant Apple" class="h-full w-auto object-contain">
                </div>
                <h3 class="text-2xl font-bold text-[#1D1D1F] mb-4">Zepsuty sprzęt Apple?</h3>
                <p class="text-[#6E6E73] leading-relaxed">Nasi doświadczeni serwisanci naprawią Twojego iPhone, iPada, iMaca czy też Macbooka używając tylko najlepszych części.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="relative h-72 w-full flex items-end justify-center mb-6">
                    <img src="<?= $assets ?>/img/intro-courier.png" alt="Naprawa wysyłkowa — kurier" class="h-full w-auto object-contain">
                </div>
                <h3 class="text-2xl font-bold text-[#1D1D1F] mb-4">Mieszkasz poza Warszawą?</h3>
                <p class="text-[#6E6E73] leading-relaxed">Odległość nie stanowi dla nas problemu — zepsuty sprzęt odbierze od Ciebie kurier i dostarczy naprawiony, a Ty możesz poświęcić się pracy.</p>
            </div>
        </div>
    </div>
</section>
