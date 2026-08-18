<?php
helper(['url', 'isense']);
$d = $data ?? [];
$items = $d['items'] ?? [];
$base = trim($d['base'] ?? '', '/');
?>
<section class="bg-[#F5F5F7] py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <?php if (!empty($d['lead'])): ?><p class="text-[#6E6E73] mb-10"><?= esc($d['lead']) ?></p><?php endif; ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            <?php foreach ($items as $m): $slug = trim($m['slug'] ?? '', '/'); $href = site_url($base . ($slug !== '' ? '/' . $slug : '')); ?>
                <a href="<?= esc($href, 'attr') ?>" class="flex items-center justify-between bg-white px-5 py-4 rounded border border-[#D2D2D7] hover:border-[#3b81f7] hover:shadow-md transition-all group">
                    <span class="font-medium text-[#1D1D1F] group-hover:text-[#3b81f7] transition-colors text-sm"><?= esc($m['name'] ?? '') ?></span>
                    <?= isense_icon('arrow-right', 'w-4 h-4 text-[#6E6E73] group-hover:text-[#3b81f7] transition-colors flex-shrink-0') ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
