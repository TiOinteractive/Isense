<?php
helper(['url', 'isense']);
$d = $data ?? [];
$align = ($d['align'] ?? 'center') === 'left' ? 'text-left' : 'text-center';
$bg = ($d['bg'] ?? 'white') === 'gray' ? 'bg-[#F5F5F7]' : 'bg-white';
?>
<section class="<?= $bg ?> py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto <?= $align ?>">
            <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['heading']) ?></h2><?php endif; ?>
            <?= $d['body'] ?? '' ?>
        </div>
    </div>
</section>
