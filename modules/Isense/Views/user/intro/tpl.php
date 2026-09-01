<?php helper(['url', 'isense']); $cards = $data['cards'] ?? []; ?>
<section class="bg-white py-8 lg:py-10">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-3 leading-tight"><?= esc($data['heading'] ?? '') ?></h2>
            <p class="text-xl lg:text-2xl text-[#3b81f7] font-medium italic"><?= esc($data['subtitle'] ?? '') ?></p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 max-w-5xl mx-auto">
            <?php foreach ($cards as $card): ?>
                <div class="flex flex-col items-center text-center">
                    <div class="relative h-72 w-full flex items-end justify-center mb-6">
                        <?= isense_img($card['image'] ?? '', $card['title'] ?? '', 'h-full w-auto object-contain', ['sizes' => '288px']) ?>
                    </div>
                    <h3 class="text-2xl font-bold text-[#1D1D1F] mb-4"><?= esc($card['title'] ?? '') ?></h3>
                    <p class="text-[#6E6E73] leading-relaxed"><?= esc($card['text'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
