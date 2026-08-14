<?php
helper(['url', 'isense']);
$d = $data ?? [];
$buttons = $d['buttons'] ?? [];
$variant = $d['bg'] ?? 'gray';
$dark = $variant === 'dark';
/* Wariant "promo" = końcowe, jasne CTA stron napraw ("Wyceń naprawę nawet w 60 sekund"),
   osobny od "dark" (który zostaje prawdziwie czarny, np. "Preferujesz kontakt telefoniczny?"). */
$final = $variant === 'promo';
$bg = $final ? 'bg-[#F5F5F7]' : ($dark ? 'bg-[#1D1D1F]' : ($variant === 'white' ? 'bg-white' : 'bg-[#F5F5F7]'));
$btnClass = static function ($style) use ($final, $dark) {
    if ($final) {
        return $style === 'outline'
            ? 'inline-flex items-center justify-center gap-2 bg-white border border-[#D2D2D7] text-[#1D1D1F] px-8 py-4 rounded hover:bg-[#EBEBEB] transition-colors font-semibold text-lg'
            : 'inline-flex items-center justify-center gap-2 bg-[#3b81f7] text-white px-8 py-4 rounded hover:bg-[#2563eb] transition-colors font-semibold text-lg';
    }
    return $style === 'outline'
        ? 'inline-flex items-center justify-center gap-2 bg-white border-2 ' . ($dark ? 'border-white' : 'border-[#1D1D1F]') . ' text-[#1D1D1F] px-8 py-4 rounded-lg hover:bg-[#F5F5F7] transition-colors font-medium'
        : 'inline-flex items-center justify-center gap-2 bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium';
};
$btnIcon = static function ($style) use ($final) {
    if (!$final) {
        return ['', 'after'];
    }
    return $style === 'outline' ? ['package', 'before'] : ['arrow-right', 'after'];
};
?>
<section class="<?= $bg ?> <?= $final ? 'py-12' : 'py-16 lg:py-24' ?>">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12 <?= $final ? 'text-center' : '' ?>">
        <div class="<?= $final ? '' : 'max-w-3xl mx-auto text-center' ?>">
            <h2 class="text-3xl lg:text-4xl font-bold <?= $dark ? 'text-white' : 'text-[#1D1D1F]' ?> mb-<?= $final ? '4' : '6' ?>"><?= esc($d['heading'] ?? '') ?></h2>
            <?php if (!empty($d['text'])): ?><p class="text-lg <?= $dark ? 'text-[#86868B]' : 'text-[#6E6E73]' ?> mb-8"><?= esc($d['text']) ?></p><?php endif; ?>
            <?php if (!empty($buttons)): ?>
                <div class="flex flex-wrap gap-4 justify-center">
                    <?php foreach ($buttons as $b): ?>
                        <?php $style = $b['style'] ?? 'primary'; [$icon, $pos] = $btnIcon($style); ?>
                        <a href="<?= esc($b['url'] ?? '#', 'attr') ?>" class="<?= $btnClass($style) ?>">
                            <?php if ($icon && $pos === 'before'): ?><?= isense_icon($icon, 'w-5 h-5') ?><?php endif; ?>
                            <?= esc($b['label'] ?? '') ?>
                            <?php if ($icon && $pos === 'after'): ?><?= isense_icon($icon, 'w-5 h-5') ?><?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
