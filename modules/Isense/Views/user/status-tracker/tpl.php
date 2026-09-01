<?php
helper(['url', 'isense']);
$d = $data ?? [];
$bg = trim($d['bg'] ?? '');
$heroClass = $bg ? 'relative py-16 lg:py-24 bg-cover bg-center' : 'relative py-16 lg:py-24 bg-gradient-to-b from-[#F5F5F7] to-white';
$heroStyle = $bg ? "background-image: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('" . esc($bg, 'attr') . "');" : '';
?>
<!-- Hero + wyszukiwarka -->
<section class="<?= $heroClass ?>"<?= $heroStyle ? ' style="' . $heroStyle . '"' : '' ?>>
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl lg:text-6xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['title'] ?? '') ?></h1>
            <?php if (!empty($d['subtitle'])): ?><p class="text-lg lg:text-xl text-[#6E6E73] mb-8"><?= esc($d['subtitle']) ?></p><?php endif; ?>
            <form data-status-form method="get" action="<?= site_url('isense/status') ?>" class="max-w-xl mx-auto">
                <div class="flex gap-2">
                    <input type="text" name="order" required placeholder="np. ORD-12345" class="flex-1 px-6 py-4 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:ring-2 focus:ring-[#3b81f7]/20 text-lg">
                    <button type="submit" class="bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium flex items-center gap-2"><?= isense_icon('search', 'w-5 h-5') ?>Sprawdź</button>
                </div>
            </form>
            <?php if (!empty($d['hint1'])): ?><p class="mt-6 text-sm text-[#6E6E73]"><?= esc($d['hint1']) ?></p><?php endif; ?>
            <?php if (!empty($d['hint2']) || !empty($d['hint2_number'])): ?>
                <p class="mt-2 text-sm text-[#6E6E73]"><?= esc($d['hint2'] ?? '') ?><?php if (!empty($d['hint2_number'])): ?> <span class="font-mono font-medium text-[#3b81f7]"><?= esc($d['hint2_number']) ?></span><?php endif; ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Wynik wyszukiwania statusu (wypełniany po wpisaniu numeru zlecenia) -->
<section data-status-section class="bg-white py-16 lg:py-24 hidden">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div data-status-result></div>
    </div>
</section>
