<?php
helper(['url', 'isense']);
$d = $data ?? [];
$heading = $title ?: ($d['heading'] ?? '');
$eyebrow = $d['eyebrow'] ?? '';
$ctaLabel = $d['cta_label'] ?? 'Zleć naprawę wysyłkową';
$ctaUrl   = $d['cta_url'] ?? '#naprawa-wysylkowa';
$bg       = $d['bg'] ?? '/assets/isense/img/hero.png';
?>
<section class="relative w-full overflow-hidden -mt-8 pb-20" style="min-height:480px;height:68vh;max-height:720px;">
    <img src="<?= esc($bg, 'attr') ?>" alt="<?= esc($heading, 'attr') ?>" class="absolute inset-0 w-full h-full object-cover">
    <div class="relative z-10 h-full flex items-center">
        <div class="max-w-[1300px] mx-auto px-4 lg:px-12 w-full">
            <div class="max-w-2xl">
                <h1 class="font-bold mb-10">
                    <?php if ($eyebrow): ?><span class="text-[19px] text-[#3b81f7] leading-[22px] block mb-2"><?= esc($eyebrow) ?></span><?php endif; ?>
                    <span class="text-[38px] text-[#1D1D1F] leading-[44px] block"><?= esc($heading) ?></span>
                </h1>
                <?php if ($ctaLabel): ?>
                    <a href="<?= esc($ctaUrl, 'attr') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-8 py-4 rounded hover:bg-[#2563eb] transition-colors font-medium text-lg shadow-lg">
                        <?= esc($ctaLabel) ?>
                        <?= isense_icon('arrow-right', 'w-5 h-5') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
