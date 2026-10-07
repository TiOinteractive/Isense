<?php
helper(['url', 'isense']);
$d = $data ?? [];
$columns = $d['columns'] ?? [];
$bg = ($d['bg'] ?? 'white') === 'gray' ? 'bg-[#F5F5F7]' : 'bg-white';
$count = count($columns);
$cc = [1 => '', 2 => 'md:grid-cols-2', 3 => 'md:grid-cols-2 lg:grid-cols-3'][min($count, 3)] ?? 'md:grid-cols-2 lg:grid-cols-3';

/* Kazda nowa linia = osobny akapit. Tekst jest escapowany, a dopiero potem
   **pogrubienie** zamieniane na <strong> — edytor nie wstawi wlasnego HTML-a. */
$paragraphs = function ($text) {
    $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $text)), 'strlen');
    $out = [];
    foreach ($lines as $line) {
        $out[] = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', esc($line));
    }
    return $out;
};
$lead = $paragraphs($d['lead'] ?? '');
?>
<section class="<?= $bg ?> py-16 lg:py-24">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <?php if (!empty($d['heading']) || !empty($lead)): ?>
            <div class="max-w-3xl mx-auto text-center mb-12">
                <?php if (!empty($d['heading'])): ?><h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6"><?= esc($d['heading']) ?></h2><?php endif; ?>
                <?php foreach ($lead as $para): ?><p class="text-[#6E6E73] leading-relaxed mb-4"><?= $para ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 <?= $cc ?> gap-12">
            <?php foreach ($columns as $col): ?>
                <div>
                    <?php if (!empty($col['title'])): ?><h3 class="text-2xl font-semibold text-[#1D1D1F] mb-6"><?= esc($col['title']) ?></h3><?php endif; ?>
                    <div class="space-y-4">
                        <?php foreach ($paragraphs($col['text'] ?? '') as $para): ?><p class="text-[#6E6E73] leading-relaxed"><?= $para ?></p><?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
