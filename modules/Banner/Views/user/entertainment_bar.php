<?php
/* 
Rozrywka - pasek
*/
?>
<section class="<?php if(!empty($id_cont)): ?>section-<?=$id_cont; ?><?php endif; ?> banners-type-bar">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
        <?php if(!empty($data) && !empty($data['banners'])): ?>
            <div class="banner-zone banner-zone-<?=$data['id']; ?>">
                <?php foreach($data['banners'] as $banner): ?>
                    <div class="banner type-bar">
                        <?php if(!empty($banner['url'])): ?><a href="<?=$banner['url']; ?>" title="<?=esc($banner['name']); ?>" target="_blank"><?php endif; ?>
                            <span class="l"><?=$banner['name']; ?></span>
                            <span class="r" style="background-image: url('/image/<?=$banner['path']; ?>');"><?=$banner['caption']; ?></span>
                        <?php if(!empty($banner['url'])): ?></a><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>