<?php
/*
  Isense repair tpl H2
 */
?>

<section class="section section-<?= $id_cont; ?> section-wyswig repair-2<?= !empty($data['image']) ? ' with-image' : ''; ?>"<?= !empty($data['bg_image']) ? ' style="background-image:url(\'' . esc($data['bg_image'], 'attr') . '\');"' : ''; ?>>
    <div class="container">
        <div class="cols">
            <div class="col-text">
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
            <?php if (!empty($data['image'])): ?>
                <div class="col-image">
                    <img src="<?= esc($data['image'], 'attr'); ?>" alt="<?= esc(!empty($title) ? strip_tags($title) : '', 'attr'); ?>" />
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>