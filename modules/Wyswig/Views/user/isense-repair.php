<?php
/*
  Isense repair tpl
 */
?>

<section class="section section-<?= $id_cont; ?> section-wyswig repair"<?= !empty($data['bg_image']) ? ' style="background-image:url(\'' . esc($data['bg_image'], 'attr') . '\');"' : ''; ?>>
    <div class="container">
        <?php if (!empty($subtitle)): ?>
            <h3><?= $subtitle; ?></h3>
        <?php endif; ?>
        <?php if (!empty($title)): ?>
            <h2 class="head"><span><?= $title; ?></span></h2>
        <?php endif; ?>
        <div class="wyswig wyswig-<?= $data['id']; ?>">
            <?= $data['content']; ?>
        </div>
    </div>
</section>
