<?php
/**
 * Pozioma lista linkow — widok cella \App\Libraries\IsenseMenu::inlineLinks.
 * Uzywana w sekcji „Popularne dzialy" strony 404: pozycje pochodza z modulu Menu,
 * panel steruje etykieta, adresem i kolejnoscia, nie stylem.
 *
 * Zmienne celowo nazwane $links/$heading — Config\View ma $saveData = true, wiec
 * dane wyciekaja miedzy wywolaniami view(), a widoki motywu uzywaja $item/$child.
 */
// UWAGA: CSS jest prekompilowany (brak build stepu — patrz CLAUDE.md), wiec wolno
// uzywac wylacznie klas, ktore juz sa w public/assets/isense/css/isense.css.
?>
<div class="mt-14 pt-10 border-t border-[#E5E5EA]">
    <?php if ($heading !== ''): ?>
        <p class="text-sm text-[#6E6E73] mb-4"><?= esc($heading) ?></p>
    <?php endif; ?>
    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm font-medium">
        <?php foreach ($links as $link): ?>
            <?php $blank = ($link['target'] ?? '') === '_blank'; ?>
            <a href="<?= esc($link['url'], 'attr') ?>"<?= $blank ? ' target="_blank" rel="noopener noreferrer"' : '' ?> class="text-[#3b81f7] hover:text-[#2563eb] transition-colors"><?= esc($link['name']) ?></a>
        <?php endforeach; ?>
    </div>
</div>
