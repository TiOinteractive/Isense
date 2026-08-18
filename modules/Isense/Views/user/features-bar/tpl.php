<?php
helper(['url', 'isense']);
$d = $data ?? [];
$items = $d['items'] ?? [];
?>
<section class="bg-white py-10 lg:py-14 border-b border-[#E5E5EA]">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <?php foreach ($items as $f): ?>
                <div class="text-center">
                    <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-full flex items-center justify-center mx-auto mb-3"><?= isense_icon($f['icon'] ?: 'check-circle', 'w-6 h-6 text-[#3b81f7]') ?></div>
                    <p class="font-semibold text-[#1D1D1F] text-sm mb-1"><?= esc($f['title'] ?? '') ?></p>
                    <p class="text-xs text-[#6E6E73]"><?= esc($f['desc'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
