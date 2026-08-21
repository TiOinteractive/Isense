<?php
/**
 * Przyciski CTA nagłówka — widok cella \App\Libraries\IsenseMenu::buttons.
 * Pozycje pochodzą z modułu Menu, wygląd jest stały per miejsce na stronie:
 * panel steruje etykietą, adresem i kolejnością, nie stylem.
 *
 * Zmienne celowo nazwane $buttons/$variant — Config\View ma $saveData = true,
 * więc dane wyciekają między wywołaniami view(), a nagłówek używa w pętlach
 * $item i $child.
 */
// UWAGA: CSS jest prekompilowany (brak build stepu — patrz CLAUDE.md), więc
// wolno używać wyłącznie klas, które już są w public/assets/isense/css/isense.css.
// Stąd `mt-2` na każdym przycisku mobilnym zamiast `space-y-2` na kontenerze —
// tej drugiej klasy w arkuszu nie ma.
$classes = [
    'desktop' => 'bg-white text-[#1D1D1F] border border-[#1D1D1F] px-5 py-1.5 rounded text-[20px] font-bold hover:bg-[#F5F5F7] transition-colors',
    'mobile'  => 'block bg-[#3b81f7] text-white px-5 py-3 rounded text-center text-sm font-bold mt-2',
    'sticky'  => 'block flex-1 bg-[#3b81f7] text-white py-3 rounded text-center text-sm font-bold',
];
$class = $classes[$variant] ?? $classes['desktop'];

// Kontener tylko tam, gdzie kilka przycisków musi się ułożyć w rzędzie.
// Desktop: rodzic w nagłówku ma już `flex items-center gap-4`.
// Mobile: przyciski są `block`, więc układają się same, odstęp daje `mt-2`.
$wrapper = $variant === 'sticky' ? 'flex gap-2' : '';
?>
<?php if ($wrapper !== ''): ?><div class="<?= $wrapper ?>"><?php endif; ?>
<?php foreach ($buttons as $button): ?>
    <?php $blank = ($button['target'] ?? '') === '_blank'; ?>
    <a href="<?= esc($button['url'], 'attr') ?>"<?= $blank ? ' target="_blank" rel="noopener noreferrer"' : '' ?> class="<?= $class ?>"><?= esc($button['name']) ?></a>
<?php endforeach; ?>
<?php if ($wrapper !== ''): ?></div><?php endif; ?>
