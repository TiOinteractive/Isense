<?php
helper(['url', 'isense']);
$d = $data ?? [];
$items = $d['items'] ?? [];
?>
<section class="bg-gradient-to-br from-[#1D1D1F] to-[#2C2C2E] py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-white mb-12 text-center"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <?php foreach ($items as $it): ?>
                <div class="text-center">
                    <div class="text-4xl lg:text-6xl font-bold text-[#3b81f7] mb-2" data-countup><?= esc($it['number'] ?? '') ?></div>
                    <div class="text-[#86868B]"><?= esc($it['label'] ?? '') ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
