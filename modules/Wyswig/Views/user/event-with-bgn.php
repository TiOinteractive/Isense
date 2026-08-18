<?php
/*
  Wydarzenie z tłem
 */
?>
<section class="section section-<?= $id_cont; ?> section-wyswig event-with-bgn"<?php if(!empty($page['photo'])): ?> style="background-image:url('/image/<?=$page['photo'];?>');"<?php endif; ?>>
    <div class="container">
        <?php if (!empty($title)): ?>
            <h1 class="head"><span><?= $title; ?></span></h1>
        <?php endif; ?>
        <?php if (!empty($subtitle)): ?>
            <h2><?= $subtitle; ?></h2>
        <?php endif; ?>
        <?php if (!empty($data) && !empty($data['content'])): ?>
            <div class="wyswig wyswig-<?= $data['id']; ?>">
                <?= $data['content']; ?>
            </div>
        <?php endif; ?>
    </div>
</section>