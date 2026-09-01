<?php
helper(['url', 'isense']);
$d = $data ?? [];
$options = $d['options'] ?? [];
$paras = preg_split('/\n\s*\n/', trim($d['body'] ?? ''));
?>
<section class="bg-white py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="text-center mb-12 max-w-3xl mx-auto">
            <h2 class="text-[22.5px] lg:text-[36px] font-bold text-[#1D1D1F] mb-4 leading-tight"><?= $d['heading'] ?? '' ?></h2>
        </div>
        <div class="grid lg:grid-cols-2 gap-12 items-end">
            <div>
                <?php if (!empty($d['lead'])): ?><p class="text-[#1D1D1F] font-bold text-xl leading-relaxed mb-6"><?= esc($d['lead']) ?></p><?php endif; ?>
                <?php foreach ($paras as $p): if (trim($p) === '') continue; ?><p class="text-[#6E6E73] leading-relaxed mb-4"><?= esc($p) ?></p><?php endforeach; ?>
                <?php if (!empty($d['closing'])): ?><p class="text-[#1D1D1F] font-semibold leading-relaxed mb-8"><?= esc($d['closing']) ?></p><?php endif; ?>
                <?php if (!empty($d['cta_label'])): ?><a href="<?= esc($d['cta_url'] ?? '#', 'attr') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-6 py-3 rounded-sm font-semibold hover:bg-[#2563eb] transition-colors"><?= esc($d['cta_label']) ?><?= isense_icon('arrow-right', 'w-5 h-5') ?></a><?php endif; ?>
            </div>
            <div class="flex flex-col gap-4">
                <?php foreach ($options as $o): ?>
                    <div class="bg-[#F5F5F7] rounded overflow-hidden border border-[#E5E5EA] flex">
                        <div class="w-[120px] flex-shrink-0 overflow-hidden self-stretch">
                            <?= isense_img($o['image'] ?? '', $o['title'] ?? '', 'w-full h-full object-cover', ['sizes' => '120px']) ?>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="text-base font-semibold text-[#1D1D1F] mb-2"><?= esc($o['title'] ?? '') ?></h3>
                            <p class="text-xs text-[#6E6E73] leading-relaxed whitespace-pre-line flex-1"><?= esc($o['description'] ?? '') ?></p>
                            <div class="flex items-center gap-2 text-[#3b81f7] font-medium text-sm mt-3">Zobacz<?= isense_icon('arrow-right', 'w-4 h-4') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
