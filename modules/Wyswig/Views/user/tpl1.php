<?php
/*
  Wyswig tpl
 */
?>
<section class="section section-<?= $id_cont; ?> section-wyswig">
    <div class="container">
        <?php if (!empty($title)): ?>
            <h2 class="head"><span><?= $title; ?></span></h2>
        <?php endif; ?>
        <?php if (!empty($subtitle)): ?>
            <h3><?= $subtitle; ?></h3>
        <?php endif; ?>
        <?php if (!empty($data) && !empty($data['content'])): ?>
            <div class="wyswig wyswig-<?= $data['id']; ?>">
                <?= $data['content']; ?>
            </div>
        <?php endif; ?>
    </div>
</section>