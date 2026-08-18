<?php
/*
  Linki
 */
?>
<div id="commercial-links" class="banner-section<?php if (!empty($id_cont)): ?> section-<?= $id_cont; ?><?php endif; ?>" data-page-cont="<?php if (!empty($id_cont)): ?><?= $id_cont; ?><?php endif; ?>">
    <div class="container">
        <?php if (!empty($title)): ?>
            <h3><?= $title; ?></h3>
        <?php endif; ?>
            <?php if (!empty($data) && !empty($data['banners'])): ?>
            <div class="list flex banner-zone banner-zone-<?= $data['id']; ?>">
                <?php 
                    $in_box = ceil(count($data['banners']) / 3);
                ?>
                <div class="box">
                    <ul>
                    <?php foreach ($data['banners'] as $k=>$banner): ?>
                        <li>
                             <?php if (!empty($banner['url'])): ?><a class="banner-count-clicks" href="<?=$banner['url']; ?>" title="<?=esc($banner['caption']); ?>"<?php if(!empty($banner['url_target'])): ?> target="<?=$banner['url_target']; ?>"<?php endif; ?><?php if(!empty($banner['url_rel'])): ?> rel="<?=$banner['url_rel']; ?>"<?php endif; ?> data-id="<?=$banner['id']; ?>"><?php endif; ?>
                                <span class="name trans400"><?=$banner['name']; ?></span>
                            <?php if (!empty($banner['url'])): ?></a><?php endif; ?>
                        </li>
                        <?php if($k + 1 != count($data['banners']) && ($k + 1) % $in_box == 0): ?></ul></div><div class="box"><ul><?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>