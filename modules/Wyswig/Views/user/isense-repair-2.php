<?php
/*
  Isense repair tpl H2
 */
?>

<section class="section section-<?= $id_cont; ?> section-wyswig repair-2"<?= !empty($data['bg_image']) ? ' style="background-image:url(\'' . esc($data['bg_image'], 'attr') . '\');"' : ''; ?>>
    <div class="container">
        <?php if (!empty($subtitle)): ?>
            <h2><?= $subtitle; ?></h2>
        <?php endif; ?>
        <?php if (!empty($title)): ?>
            <h1 class="head"><span><?= $title; ?></span></h1>
        <?php endif; ?>
        <div class="wyswig wyswig-<?= $data['id']; ?>">
            <?= $data['content']; ?>
        </div>
    </div>
</section>
