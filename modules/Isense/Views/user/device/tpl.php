<?php
helper(['url', 'isense']);
$d = $data ?? [];
$side = ($d['image_side'] ?? 'right') === 'left' ? 'left' : 'right';
$cols = ($d['columns'] ?? '2') === '1' ? 'space-y-3' : 'grid grid-cols-2 gap-3';
$bg = ($d['bg'] ?? 'gray') === 'white' ? 'bg-white' : 'bg-[#F5F5F7]';
$repairs = $d['repairs'] ?? [];
$paras = preg_split('/\n\s*\n/', trim($d['body'] ?? ''));

$imgCol = '<div class="rounded-md overflow-hidden' . ($side === 'left' ? ' order-2 lg:order-1' : '') . '">'
    . isense_img($d['image'] ?? '', $d['heading'] ?? '', 'w-full h-full object-cover', ['sizes' => '(min-width: 1024px) 50vw, 100vw'])
    . '</div>';
ob_start(); ?>
<div class="<?= $side === 'left' ? 'order-1 lg:order-2' : '' ?>">
    <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['heading'] ?? '') ?></h2>
    <?php foreach ($paras as $p): if (trim($p) === '') continue; ?>
        <p class="text-lg text-[#6E6E73] mb-4"><?= esc($p) ?></p>
    <?php endforeach; ?>
    <div class="<?= $cols ?> mb-8 mt-4">
        <?php foreach ($repairs as $r): ?>
            <div class="flex items-center gap-2"><?= isense_icon('check-circle', 'w-4 h-4 text-[#3b81f7] flex-shrink-0') ?><span class="text-sm text-[#1D1D1F]"><?= esc($r['text'] ?? '') ?></span></div>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($d['link_label'])): ?>
        <a href="<?= esc($d['link_url'] ?? '#', 'attr') ?>" class="inline-flex items-center gap-2 text-[#3b81f7] font-semibold hover:gap-4 transition-all"><?= esc($d['link_label']) ?><?= isense_icon('arrow-right', 'w-5 h-5') ?></a>
    <?php endif; ?>
</div>
<?php $textCol = ob_get_clean(); ?>
<section class="<?= $bg ?> py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <?php if ($side === 'left'): echo $imgCol . $textCol; else: echo $textCol . $imgCol; endif; ?>
        </div>
    </div>
</section>
