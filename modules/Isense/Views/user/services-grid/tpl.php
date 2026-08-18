<?php
helper(['url', 'isense']);
$d = $data ?? [];
$items = $d['items'] ?? [];
?>
<section class="bg-[#F5F5F7] py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <?php if (!empty($d['lead'])): ?><p class="text-[#6E6E73] mb-10"><?= esc($d['lead']) ?></p><?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($items as $s): ?>
                <div class="bg-white border border-[#E5E5EA] rounded p-5 flex gap-4 items-start hover:shadow-md transition-shadow">
                    <?= isense_icon('check-circle', 'w-5 h-5 text-[#3b81f7] flex-shrink-0 mt-0.5') ?>
                    <div>
                        <p class="font-semibold text-[#1D1D1F] text-sm mb-1"><?= esc($s['name'] ?? '') ?></p>
                        <p class="text-xs text-[#6E6E73]"><?= esc($s['desc'] ?? '') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
