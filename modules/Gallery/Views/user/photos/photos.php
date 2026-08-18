<?php
/*
  Zdjęcia
 */
?>
<section class="section-<?= $id_cont; ?> gallery-photos">
    <?php if (!empty($title)): ?>
        <div class="container"> <h2><?= $title; ?></h2></div>
    <?php endif; ?>
    <?php if (!empty($data['photos'])): ?>
        <div class="photos">
            <?php foreach ($data['photos'] as $photo): ?>
                <div class="photo">
                    <picture>
                        <source srcset="/image/c/200/200/<?= $photo['path']; ?>" media="(max-width: 800px)">
                        <img src="/image/c/400/400/<?= $photo['path']; ?>" alt="<?= esc($photo['caption']); ?>" />
                        <div class="mask trans400"></div>
                    </picture>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (!empty($data['pager'])): ?>
            <div class="container">  
                <?= $data['pager']->links('gallery-photos-' . $id_cont, 'front_full'); ?>
            </div>            
        <?php endif; ?>
    <?php endif; ?>
</section>