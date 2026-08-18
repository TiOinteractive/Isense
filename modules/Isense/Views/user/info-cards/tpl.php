<?php
helper(['url', 'isense']);
$d = $data ?? [];
$cards = $d['cards'] ?? [];
$cols = $d['columns'] ?? '4';
$colClass = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-2 lg:grid-cols-3', '4' => 'md:grid-cols-2 lg:grid-cols-4'][$cols] ?? 'md:grid-cols-2 lg:grid-cols-4';
$bg = ($d['bg'] ?? 'gray') === 'white' ? 'bg-white' : 'bg-[#F5F5F7]';
?>
<section class="<?= $bg ?> py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-12 text-center"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <div class="grid grid-cols-1 <?= $colClass ?> gap-8">
            <?php foreach ($cards as $c): ?>
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-[#3b81f7]/10 rounded-2xl flex items-center justify-center mx-auto mb-6"><?= isense_icon($c['icon'] ?: 'check-circle', 'w-8 h-8 text-[#3b81f7]') ?></div>
                    <h3 class="text-xl font-semibold text-[#1D1D1F] mb-3"><?= esc($c['title'] ?? '') ?></h3>
                    <p class="text-[#6E6E73]"><?= esc($c['text'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
