<?php
/*
  Partners
 */
?>
<?php if(!empty($data) && !empty($data['banners'])): ?>
    <div class="parnters banner-section<?php if (!empty($id_cont)): ?> section-<?= $id_cont; ?><?php endif; ?>" data-page-cont="<?php if (!empty($id_cont)): ?><?= $id_cont; ?><?php endif; ?>">
        <div class="container">
            <?php if (!empty($data['name'])): ?>
                <h3><?= $data['name']; ?></h3>
            <?php endif; ?>
            <div class="list banner-zone banner-zone-<?= $data['id']; ?>">
                <?php foreach ($data['banners'] as $k=>$banner): ?>
                    <div class="partner">
                         <?php if (!empty($banner['url'])): ?><a class="banner-count-clicks" href="<?=$banner['url']; ?>" title="<?=esc($banner['caption']); ?>"<?php if(!empty($banner['url_target'])): ?> target="<?=$banner['url_target']; ?>"<?php endif; ?><?php if(!empty($banner['url_rel'])): ?> rel="<?=$banner['url_rel']; ?>"<?php endif; ?> data-id="<?=$banner['id']; ?>"><?php endif; ?>
                            <picture>
                                <?php if(!empty($banner['m_path'])): ?><source srcset="/image/<?=$banner['m_path']; ?>" media="(max-width: 800px)"><?php endif; ?>
                                <img src="/image/r/400/300/<?=$banner['path']; ?>" alt="<?=esc($banner['caption']); ?>" />
                            </picture>
                        <?php if (!empty($banner['url'])): ?></a><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>