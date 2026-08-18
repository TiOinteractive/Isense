<?php
helper(['url', 'isense']);
$d = $data ?? [];
$items = $d['items'] ?? [];
$num = ($d['style'] ?? 'icon') === 'number';
$cols = $d['columns'] ?? '3';
$cc = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-3', '4' => 'md:grid-cols-2 lg:grid-cols-4'][$cols] ?? 'md:grid-cols-3';
$bg = ($d['bg'] ?? 'white') === 'gray' ? 'bg-[#F5F5F7]' : 'bg-white';
?>
<section class="<?= $bg ?> py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-12 text-center"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <div class="grid grid-cols-1 <?= $cc ?> gap-8 max-w-4xl mx-auto">
            <?php foreach ($items as $i => $it): ?>
                <div class="text-center">
                    <div class="w-16 h-16 <?= $num ? 'bg-[#3b81f7] rounded-full' : 'bg-[#3b81f7]/10 rounded-2xl' ?> flex items-center justify-center mx-auto mb-4">
                        <?php if ($num): ?><span class="text-2xl font-bold text-white"><?= $i + 1 ?></span><?php else: ?><?= isense_icon($it['icon'] ?: 'check-circle', 'w-8 h-8 text-[#3b81f7]') ?><?php endif; ?>
                    </div>
                    <h3 class="font-semibold text-[#1D1D1F] mb-2"><?= esc($it['title'] ?? '') ?></h3>
                    <p class="text-sm text-[#6E6E73]"><?= esc($it['text'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
