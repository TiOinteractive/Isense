<?php helper(['url', 'isense']); $features = $data['features'] ?? []; ?>
<section class="bg-white py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <?php foreach ($features as $f): ?>
                <div class="flex flex-col items-center text-center bg-[#F5F5F7] rounded-sm p-8">
                    <div class="w-24 h-24 bg-[#3b81f7] rounded-full flex items-center justify-center mb-6 flex-shrink-0">
                        <?= isense_img($f['icon'] ?? '', $f['title'] ?? '', 'w-14 h-14 object-contain brightness-0 invert', ['sizes' => '56px']) ?>
                    </div>
                    <h3 class="text-lg font-bold text-[#1D1D1F] mb-3 leading-[1.575]"><?= esc($f['title'] ?? '') ?></h3>
                    <p class="text-[#6E6E73] text-sm leading-relaxed"><?= esc($f['description'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
