<?= $this->extend('isense/layout') ?>

<?= $this->section('content') ?>
<?php helper(['url', 'isense']); ?>

<!-- Breadcrumb + nagłówek -->
<section class="bg-[#F5F5F7] py-8 lg:py-12">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <a href="<?= site_url('naprawy/' . $category) ?>" class="inline-flex items-center gap-2 text-[#6E6E73] hover:text-[#3b81f7] transition-colors mb-6">
            <?= isense_icon('arrow-left', 'w-4 h-4') ?> Powrót do listy urządzeń
        </a>
        <h1 class="text-3xl lg:text-5xl font-bold text-[#1D1D1F]"><?= esc($model_name) ?></h1>
    </div>
</section>

<!-- Cennik -->
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-5xl mx-auto">
            <div class="mb-12">
                <h2 class="text-2xl lg:text-3xl font-bold text-[#1D1D1F] mb-4">Cennik napraw</h2>
                <?php if (!empty($services_intro)): ?><p class="text-lg text-[#6E6E73]"><?= esc($services_intro) ?></p><?php endif; ?>
            </div>

            <?php if (!empty($services)): ?>
                <!-- Tabela (desktop) -->
                <div class="hidden md:block overflow-hidden rounded-2xl border border-[#D2D2D7]">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#F5F5F7]">
                                <th class="text-left p-6 text-sm font-semibold text-[#6E6E73] uppercase tracking-wider">Usługa</th>
                                <th class="text-left p-6 text-sm font-semibold text-[#6E6E73] uppercase tracking-wider">Cena</th>
                                <th class="text-left p-6 text-sm font-semibold text-[#6E6E73] uppercase tracking-wider">Czas realizacji</th>
                                <th class="text-left p-6 text-sm font-semibold text-[#6E6E73] uppercase tracking-wider">Gwarancja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $s): ?>
                                <tr class="border-t border-[#D2D2D7] hover:bg-[#F5F5F7] transition-colors">
                                    <td class="p-6 font-medium text-[#1D1D1F]"><?= esc($s['name']) ?></td>
                                    <td class="p-6 text-2xl font-bold text-[#3b81f7]"><?= esc($s['price']) ?></td>
                                    <td class="p-6 text-[#6E6E73]"><div class="flex items-center gap-2"><?= isense_icon('clock', 'w-4 h-4') ?><?= esc($s['time']) ?></div></td>
                                    <td class="p-6 text-[#6E6E73]"><div class="flex items-center gap-2"><?= isense_icon('shield', 'w-4 h-4') ?><?= esc($s['warranty']) ?></div></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Karty (mobile) -->
                <div class="md:hidden space-y-4">
                    <?php foreach ($services as $s): ?>
                        <div class="bg-[#F5F5F7] rounded-2xl p-6 border border-[#D2D2D7]">
                            <h3 class="font-semibold text-[#1D1D1F] mb-4"><?= esc($s['name']) ?></h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center"><span class="text-sm text-[#6E6E73]">Cena:</span><span class="text-2xl font-bold text-[#3b81f7]"><?= esc($s['price']) ?></span></div>
                                <div class="flex justify-between items-center"><span class="text-sm text-[#6E6E73]">Czas realizacji:</span><span class="text-sm text-[#1D1D1F]"><?= esc($s['time']) ?></span></div>
                                <div class="flex justify-between items-center"><span class="text-sm text-[#6E6E73]">Gwarancja:</span><span class="text-sm text-[#1D1D1F]"><?= esc($s['warranty']) ?></span></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Karty informacyjne -->
            <div class="grid md:grid-cols-3 gap-6 mt-12">
                <div class="bg-[#F5F5F7] rounded-2xl p-6"><?= isense_icon('check-circle', 'w-10 h-10 text-[#3b81f7] mb-4') ?><h3 class="font-semibold text-[#1D1D1F] mb-2">Oryginalne części</h3><p class="text-sm text-[#6E6E73]">Używamy wyłącznie oryginalnych części Apple lub dedykowanych zamienników.</p></div>
                <div class="bg-[#F5F5F7] rounded-2xl p-6"><?= isense_icon('shield', 'w-10 h-10 text-[#3b81f7] mb-4') ?><h3 class="font-semibold text-[#1D1D1F] mb-2">Gwarancja jakości</h3><p class="text-sm text-[#6E6E73]">Pełna gwarancja na wszystkie wykonane naprawy.</p></div>
                <div class="bg-[#F5F5F7] rounded-2xl p-6"><?= isense_icon('clock', 'w-10 h-10 text-[#3b81f7] mb-4') ?><h3 class="font-semibold text-[#1D1D1F] mb-2">Szybka realizacja</h3><p class="text-sm text-[#6E6E73]">Większość napraw wykonujemy w ciągu 48 godzin.</p></div>
            </div>

            <!-- CTA -->
            <div class="mt-12 text-center">
                <a href="<?= site_url('kontakt') ?>" class="inline-block bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium text-lg">Wyceń naprawę online</a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
