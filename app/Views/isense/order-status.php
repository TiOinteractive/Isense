<?php
helper(['isense']);
$o = $order ?? [];
$timeline = $o['timeline'] ?? [];
$n = count($timeline);
?>
<div class="max-w-4xl mx-auto">
    <div class="bg-[#F5F5F7] rounded-2xl p-8 mb-12">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div><div class="text-sm text-[#6E6E73] mb-1">Numer zlecenia</div><div class="font-mono font-semibold text-[#1D1D1F] text-lg"><?= esc($o['number'] ?? '') ?></div></div>
            <div><div class="text-sm text-[#6E6E73] mb-1">Urządzenie</div><div class="font-semibold text-[#1D1D1F] text-lg"><?= esc($o['device'] ?? '') ?></div></div>
            <div><div class="text-sm text-[#6E6E73] mb-1">Usługa</div><div class="font-semibold text-[#1D1D1F] text-lg"><?= esc($o['service'] ?? '') ?></div></div>
            <?php if (!empty($o['status'])): ?>
                <div><div class="text-sm text-[#6E6E73] mb-1">Status</div><div class="inline-block px-3 py-1 bg-[#3b81f7]/10 text-[#3b81f7] rounded-full text-sm font-medium"><?= esc($o['status']) ?></div></div>
            <?php endif; ?>
        </div>
    </div>
    <?php if (!empty($timeline)): ?>
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-[#1D1D1F] mb-8">Status naprawy</h2>
            <div class="space-y-6">
                <?php foreach ($timeline as $i => $s): $done = ($s['state'] ?? '') === 'done'; $cur = ($s['state'] ?? '') === 'current'; ?>
                    <div class="flex gap-6">
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center <?= $done ? 'bg-green-100 border-2 border-green-500' : ($cur ? 'bg-[#3b81f7]/10 border-2 border-[#3b81f7]' : 'bg-[#F5F5F7] border-2 border-[#D2D2D7]') ?>">
                                <?= isense_icon($s['icon'] ?: 'package', 'w-7 h-7 ' . ($done ? 'text-green-600' : ($cur ? 'text-[#3b81f7]' : 'text-[#6E6E73]'))) ?>
                            </div>
                            <?php if ($i < $n - 1): ?><div class="w-0.5 flex-1 min-h-12 <?= $done ? 'bg-green-500' : 'bg-[#D2D2D7]' ?>"></div><?php endif; ?>
                        </div>
                        <div class="flex-1 pb-8">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold mb-1 <?= $cur ? 'text-[#3b81f7]' : 'text-[#1D1D1F]' ?>"><?= esc($s['name'] ?? '') ?></h3>
                                    <div class="text-sm text-[#6E6E73]"><?= esc($s['date'] ?? '') ?></div>
                                </div>
                                <?php if ($done): ?><div class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Wykonano</div>
                                <?php elseif ($cur): ?><div class="px-3 py-1 bg-[#3b81f7]/10 text-[#3b81f7] rounded-full text-xs font-medium">W trakcie</div><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($o['estimated'])): ?>
        <div class="bg-gradient-to-br from-[#3b81f7] to-[#2563eb] rounded-2xl p-8 text-white">
            <div class="flex items-center gap-4">
                <?= isense_icon('truck', 'w-12 h-12') ?>
                <div><div class="text-sm opacity-90 mb-1">Szacowana data zakończenia</div><div class="text-2xl font-bold"><?= esc($o['estimated']) ?></div></div>
            </div>
        </div>
    <?php endif; ?>
    <div class="mt-8 text-center">
        <p class="text-[#6E6E73] mb-4">Masz pytania dotyczące naprawy?</p>
        <a href="tel:+48504806905" class="inline-block text-[#3b81f7] font-medium hover:underline">Zadzwoń: +48 504 806 905</a>
    </div>
</div>
