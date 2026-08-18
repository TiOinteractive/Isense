<?php
/* 
Banner tpl 2
*/
?>
<section class="banner-box">
    <div class="container">
        <?php if(!empty($data) && !empty($data['banners'])): ?>
            <div class="banner-zone banner-zone-<?=$data['id']; ?>">
                <?php foreach($data['banners'] as $banner): ?>
                    <div class="banner">
                        <?php if(!empty($banner['url'])): ?><a href="<?=$banner['url']; ?>" title="<?=esc($banner['caption']); ?>" target="_blank"><?php endif; ?>
                        <picture>
                            <?php if(!empty($banner['m_path'])): ?><source srcset="/image/<?=$banner['m_path']; ?>" media="(max-width: 800px)"><?php endif; ?>
                            <img src="/image/<?=$banner['path']; ?>" alt="<?=esc($banner['caption']); ?>" />
                        </picture>
                        <?php if(!empty($banner['url'])): ?></a><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
