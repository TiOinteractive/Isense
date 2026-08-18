<?php
helper(['url', 'isense']);
$d = $data ?? [];
$stats = $d['stats'] ?? [];
$phone = $d['phone'] ?? '';
$tel = preg_replace('/[^0-9+]/', '', $phone);
?>
<section class="bg-white py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['heading']) ?></h2><?php endif; ?>
                <?php foreach (['para1', 'para2', 'para3'] as $i => $k): if (empty($d[$k])) continue; ?>
                    <p class="text-[#6E6E73] leading-relaxed <?= $i === 2 ? 'mb-8' : 'mb-4' ?>"><?= esc($d[$k]) ?></p>
                <?php endforeach; ?>
                <?php if ($phone !== ''): ?>
                    <a href="tel:<?= esc($tel, 'attr') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-7 py-3.5 rounded font-semibold hover:bg-[#2563eb] transition-colors"><?= isense_icon('phone', 'w-5 h-5') ?> <?= esc($phone) ?></a>
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <?php foreach ($stats as $stat): ?>
                    <div class="bg-[#F5F5F7] rounded p-6 text-center border border-[#E5E5EA]">
                        <p class="text-4xl font-bold text-[#3b81f7] mb-1"><?= esc($stat['val'] ?? '') ?></p>
                        <p class="text-sm text-[#6E6E73]"><?= esc($stat['label'] ?? '') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
