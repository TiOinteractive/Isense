<?php
helper(['url', 'isense']);
$d = $data ?? [];
$anchor = $d['anchor'] ?? 'cennik';
/* "bg_image" = zdjęcie w tle całej sekcji, jedna kolumna tekstu. "split" = poprzedni układ (tekst + osobny obrazek produktu obok), nadal używany
   na pozostałych stronach napraw (iPad, MacBook, iMac, Watch). */
$bgLayout = ($d['layout'] ?? 'split') === 'bg_image';
$title = trim(($d['title'] ?? '') . ' ' . ($d['title2'] ?? ''));
?>
<?php if ($bgLayout): ?>
    <section class="relative pt-16 lg:pt-24 pb-16 lg:pb-24"<?= !empty($d['bg_image']) ? ' style="background-image:url(\'' . esc($d['bg_image'], 'attr') . '\');background-size:cover;background-position:right center;background-repeat:no-repeat;"' : '' ?>>
        <div class="relative max-w-[1300px] mx-auto px-4 lg:px-12">
            <div class="max-w-[640px]">
                <?php if (!empty($d['eyebrow'])): ?><p class="text-[#3b81f7] font-semibold text-sm uppercase tracking-widest mb-3"><?= esc($d['eyebrow']) ?></p><?php endif; ?>
                <h1 class="text-5xl lg:text-6xl font-bold text-[#1D1D1F] mb-6 leading-tight"><?= esc($title) ?></h1>
                <?php if (!empty($d['lead'])): ?><p class="text-[#1D1D1F]/70 mb-4 leading-relaxed"><?= esc($d['lead']) ?></p><?php endif; ?>
                <?php if (!empty($d['lead2'])): ?><p class="text-[#1D1D1F]/60 mb-8 leading-relaxed"><?= esc($d['lead2']) ?></p><?php endif; ?>
                <div class="flex flex-wrap gap-4">
                    <a href="#<?= esc($anchor, 'attr') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-7 py-3.5 rounded font-semibold hover:bg-[#2563eb] transition-colors shadow">Zobacz cennik <?= isense_icon('arrow-right', 'w-5 h-5') ?></a>
                    <a href="<?= site_url('naprawa-z-odbiorem') ?>" class="inline-flex items-center gap-2 bg-white text-[#1D1D1F] border border-[#1D1D1F] px-7 py-3.5 rounded font-semibold hover:bg-[#F5F5F7] transition-colors">Naprawa wysyłkowa</a>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="bg-[#F5F5F7] py-10 lg:py-16">
        <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <?php if (!empty($d['eyebrow'])): ?><p class="text-[#3b81f7] font-semibold text-sm uppercase tracking-widest mb-3"><?= esc($d['eyebrow']) ?></p><?php endif; ?>
                    <h1 class="text-4xl lg:text-5xl font-bold text-[#1D1D1F] mb-6 leading-tight"><?= esc($d['title'] ?? '') ?><?php if (!empty($d['title2'])): ?><br><?= esc($d['title2']) ?><?php endif; ?></h1>
                    <?php if (!empty($d['lead'])): ?><p class="text-lg text-[#6E6E73] mb-4 leading-relaxed"><?= esc($d['lead']) ?></p><?php endif; ?>
                    <?php if (!empty($d['lead2'])): ?><p class="text-[#6E6E73] mb-8 leading-relaxed"><?= esc($d['lead2']) ?></p><?php endif; ?>
                    <div class="flex flex-wrap gap-4">
                        <a href="#<?= esc($anchor, 'attr') ?>" class="inline-flex items-center gap-2 bg-[#3b81f7] text-white px-7 py-3.5 rounded font-semibold hover:bg-[#2563eb] transition-colors shadow">Zobacz cennik <?= isense_icon('arrow-right', 'w-5 h-5') ?></a>
                        <a href="<?= site_url('naprawa-z-odbiorem') ?>" class="inline-flex items-center gap-2 bg-white text-[#1D1D1F] border border-[#1D1D1F] px-7 py-3.5 rounded font-semibold hover:bg-[#F5F5F7] transition-colors">Naprawa wysyłkowa</a>
                    </div>
                </div>
                <?php if (!empty($d['image'])): ?>
                    <div class="flex justify-center"><img src="<?= esc($d['image'], 'attr') ?>" alt="<?= esc('Serwis ' . ($d['title2'] ?? ''), 'attr') ?>" class="w-full max-w-md object-contain drop-shadow-xl"></div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
