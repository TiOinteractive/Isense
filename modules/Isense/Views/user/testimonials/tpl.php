<?php
helper(['url', 'isense']);
$items = $data['items'] ?? [];
$total = count($items);
$stars = static function ($size) { $o = ''; for ($i = 0; $i < 5; $i++) { $o .= isense_icon('star', $size . ' fill-[#3b81f7] text-[#3b81f7]'); } return $o; };
?>
<section class="bg-[#F5F5F7] py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F] mb-4"><?= esc($data['heading'] ?? 'Klienci o nas') ?></h2>
            <div class="flex items-center justify-center gap-2 text-[#6E6E73]">
                <div class="flex gap-1"><?= $stars('w-5 h-5') ?></div>
                <span><?= esc($data['subtitle'] ?? '') ?></span>
            </div>
        </div>
        <div class="relative max-w-5xl mx-auto" data-carousel data-total="<?= $total ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($items as $i => $t): ?>
                    <div data-slide="<?= $i ?>" class="<?= $i < 2 ? '' : 'hidden' ?> bg-white rounded p-8 border border-[#D2D2D7] hover:shadow-xl transition-all relative">
                        <?= isense_icon('quote', 'w-10 h-10 text-[#3b81f7]/20 absolute top-6 right-6') ?>
                        <div class="flex gap-1 mb-4"><?= $stars('w-4 h-4') ?></div>
                        <p class="text-[#1D1D1F] leading-relaxed mb-6 italic">„<?= esc($t['text'] ?? '') ?>”</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#3b81f7] rounded-full flex items-center justify-center text-white font-bold"><?= esc(mb_substr($t['name'] ?? '?', 0, 1)) ?></div>
                            <span class="font-semibold text-[#1D1D1F]"><?= esc($t['name'] ?? '') ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($total > 2): ?>
            <div class="flex items-center justify-center gap-4 mt-8">
                <button type="button" data-carousel-prev class="w-12 h-12 rounded-full bg-white border border-[#D2D2D7] flex items-center justify-center hover:bg-[#3b81f7] hover:text-white hover:border-[#3b81f7] transition-all shadow-sm" aria-label="Poprzednia opinia"><?= isense_icon('chevron-left', 'w-5 h-5') ?></button>
                <span class="text-sm text-[#6E6E73]" data-carousel-status>1–2 / <?= $total ?></span>
                <button type="button" data-carousel-next class="w-12 h-12 rounded-full bg-white border border-[#D2D2D7] flex items-center justify-center hover:bg-[#3b81f7] hover:text-white hover:border-[#3b81f7] transition-all shadow-sm" aria-label="Następna opinia"><?= isense_icon('chevron-right', 'w-5 h-5') ?></button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
