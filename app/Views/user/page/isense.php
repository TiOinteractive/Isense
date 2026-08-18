<?php
/*
iSense — strona główna (bloki CMS)
*/
// Chrome (nagłówek/stopka) z partiali iSense; treść = bloki modułu Isense. CSS/JS przez Isense::assets().
helper(['url', 'isense']);
?>
<div class="isense-theme min-h-screen flex flex-col bg-white text-[#1D1D1F]">
    <?= view('isense/partials/header') ?>
    <main class="flex-1">
        <?php if (!empty($content)): ?>
            <?php foreach ($content as $cont): ?>
                <?php if (!empty($cont) && !empty($cont['template'])): ?>
                    <?= view($cont['template'], [
                        'id_cont'  => $cont['id'],
                        'title'    => $cont['title'],
                        'subtitle' => $cont['subtitle'],
                        'url'      => $cont['url'],
                        'data'     => !empty($cont['data']) ? $cont['data'] : null,
                    ]); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
    <?= view('isense/partials/footer') ?>
</div>
