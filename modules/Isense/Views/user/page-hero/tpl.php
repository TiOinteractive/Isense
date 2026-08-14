<?php
helper(['url', 'isense']);
$d = $data ?? [];
$variant = $d['variant'] ?? 'light';
$dark = $variant === 'dark';
/* "contact" = kompaktowy, jasny hero bez zdjęcia w tle. */
if ($variant === 'contact') {
    ?>
    <section class="bg-[#F5F5F7] py-14 lg:py-20">
        <div class="max-w-[1300px] mx-auto px-4 lg:px-12 text-center">
            <?php if (!empty($d['eyebrow'])): ?><p class="text-[#3b81f7] font-semibold text-sm uppercase tracking-widest mb-3"><?= esc($d['eyebrow']) ?></p><?php endif; ?>
            <h1 class="text-4xl lg:text-5xl font-bold text-[#1D1D1F] mb-4"><?= esc($d['title'] ?? '') ?></h1>
            <?php if (!empty($d['subtitle'])): ?><p class="text-lg text-[#6E6E73] max-w-2xl mx-auto"><?= esc($d['subtitle']) ?></p><?php endif; ?>
        </div>
    </section>
    <?php
    return;
}
$bg = trim($d['bg'] ?? '');
$overlay = $dark ? 'rgba(29, 29, 31, 0.8), rgba(29, 29, 31, 0.8)' : 'rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)';
if ($bg) {
    $secClass = 'relative py-16 lg:py-24 bg-cover bg-center';
    $style = "background-image: linear-gradient({$overlay}), url('" . esc($bg, 'attr') . "');";
} else {
    $secClass = $dark ? 'relative py-16 lg:py-24 bg-[#1D1D1F]' : 'relative py-16 lg:py-24 bg-gradient-to-b from-[#F5F5F7] to-white';
    $style = '';
}
?>
<section class="<?= $secClass ?>"<?= $style ? ' style="' . $style . '"' : '' ?>>
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <?php if (!empty($d['eyebrow'])): ?><p class="text-[19px] font-bold text-[#3b81f7] mb-2"><?= esc($d['eyebrow']) ?></p><?php endif; ?>
            <h1 class="text-4xl lg:text-6xl font-bold <?= $dark ? 'text-white' : 'text-[#1D1D1F]' ?> mb-6"><?= esc($d['title'] ?? '') ?></h1>
            <?php if (!empty($d['subtitle'])): ?><p class="text-lg lg:text-xl <?= $dark ? 'text-[#86868B]' : 'text-[#6E6E73]' ?>"><?= esc($d['subtitle']) ?></p><?php endif; ?>
        </div>
    </div>
</section>
