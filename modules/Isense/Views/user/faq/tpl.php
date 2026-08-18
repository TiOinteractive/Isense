<?php
helper(['url', 'isense']);
$d = $data ?? [];
$items = $d['items'] ?? [];
?>
<section class="bg-[#F5F5F7] py-10 lg:py-14">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3 text-center"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <?php if (!empty($d['lead'])): ?><p class="text-[#6E6E73] text-center mb-10"><?= esc($d['lead']) ?></p><?php endif; ?>
        <div class="max-w-3xl mx-auto space-y-3">
            <?php foreach ($items as $faq): ?>
                <details class="border border-[#D2D2D7] rounded bg-white overflow-hidden group">
                    <summary class="cursor-pointer list-none flex items-center justify-between px-6 py-4 font-semibold text-[#1D1D1F] hover:bg-[#F5F5F7]">
                        <span><?= esc($faq['question'] ?? '') ?></span>
                        <?= isense_icon('chevron-down', 'w-5 h-5 text-[#6E6E73] flex-shrink-0 transition-transform group-open:rotate-180') ?>
                    </summary>
                    <div class="px-6 pb-5 pt-4 text-[#6E6E73] leading-relaxed border-t border-[#E5E5EA]"><?= esc($faq['answer'] ?? '') ?></div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
