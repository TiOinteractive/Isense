<?php
helper(['url', 'isense']);
$d = $data ?? [];
$cards = $d['cards'] ?? [];
$cols = $d['columns'] ?? '3';
$cc = ['2' => 'sm:grid-cols-2', '3' => 'sm:grid-cols-2 lg:grid-cols-3', '4' => 'sm:grid-cols-2 lg:grid-cols-4'][$cols] ?? 'sm:grid-cols-2 lg:grid-cols-3';
$imgBase = rtrim(base_url('assets/isense/img'), '/');
?>
<section class="bg-white py-14 lg:py-20">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-3 text-center"><?= esc($d['heading']) ?></h2><?php endif; ?>
        <?php if (!empty($d['lead'])): ?><p class="text-[#6E6E73] mb-10 text-center max-w-3xl mx-auto"><?= esc($d['lead']) ?></p><?php endif; ?>
        <div class="grid grid-cols-1 <?= $cc ?> gap-6">
            <?php foreach ($cards as $c): $url = $c['url'] ?? '#'; $href = preg_match('~^(https?:|/|tel:|mailto:)~', $url) ? $url : site_url($url); ?>
                <a href="<?= esc($href, 'attr') ?>" class="group flex flex-col bg-white border border-[#E5E5EA] hover:border-[#3b81f7] rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all hover:-translate-y-1">
                    <?php if (!empty($c['img'])): ?>
                        <div class="h-44 bg-[#F5F5F7] overflow-hidden"><?= isense_img($imgBase . '/' . $c['img'], $c['title'] ?? '', 'w-full h-full object-cover', ['sizes' => '(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw']) ?></div>
                    <?php endif; ?>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <?php if (!empty($c['icon'])): ?><span class="w-10 h-10 bg-[#3b81f7]/10 rounded-lg flex items-center justify-center flex-shrink-0"><?= isense_icon($c['icon'], 'w-5 h-5 text-[#3b81f7]') ?></span><?php endif; ?>
                            <h2 class="text-xl font-bold text-[#1D1D1F]"><?= esc($c['title'] ?? '') ?></h2>
                        </div>
                        <?php if (!empty($c['desc'])): ?><p class="text-[#6E6E73] text-sm leading-relaxed mb-5 flex-1"><?= esc($c['desc']) ?></p><?php endif; ?>
                        <span class="inline-flex items-center gap-2 text-[#3b81f7] font-semibold text-sm group-hover:gap-4 transition-all mt-auto"><?= esc($c['link_label'] ?? 'Zobacz więcej') ?> <?= isense_icon('arrow-right', 'w-4 h-4') ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
