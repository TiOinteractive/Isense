<?php
helper(['url', 'isense']);
$d = $data ?? [];
$side = ($d['image_side'] ?? 'right') === 'left' ? 'left' : 'right';
$bg = ($d['bg'] ?? 'white') === 'gray' ? 'bg-[#F5F5F7]' : 'bg-white';
$paras = preg_split('/\n\s*\n/', trim($d['body'] ?? ''));
$img = '<div class="rounded-3xl overflow-hidden' . ($side === 'left' ? ' order-1' : ' order-2') . '">'
    . isense_img($d['image'] ?? '', $d['heading'] ?? '', 'w-full h-full object-cover', ['sizes' => '(min-width: 1024px) 50vw, 100vw'])
    . '</div>';
ob_start(); ?>
<div class="<?= $side === 'left' ? 'order-2' : 'order-1' ?>">
    <h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['heading'] ?? '') ?></h2>
    <div class="space-y-4 text-lg text-[#6E6E73]">
        <?php foreach ($paras as $p): if (trim($p) === '') continue; ?><p><?= esc($p) ?></p><?php endforeach; ?>
    </div>
</div>
<?php $text = ob_get_clean(); ?>
<section class="<?= $bg ?> py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <?php echo $side === 'left' ? $img . $text : $text . $img; ?>
        </div>
    </div>
</section>
