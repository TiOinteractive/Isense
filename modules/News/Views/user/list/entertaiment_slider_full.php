<?php
/*
  Rozrywka - Slider aktualności full
 */
?>
<?php if (!empty($data) && !empty($data['list'])): ?>
    <div class="entertainment-slider-full">
        <div class="container">
            <div class="list">
                <?php foreach ($data['list'] as $news): ?>
                    <div class="news-item news-item-<?= $news['id']; ?>">
                        <div class="inside">	
                            <div class="photo">
                                <?php if(!empty($news['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                    <?php    
                                        if(!empty($news['photo']['path']) && !empty($news['photo']['crop_dimension'])) {
                                            $news['photo']['crop_dimension']=json_decode($news['photo']['crop_dimension'], true); 
                                            $news['photo']['path'] = $news['photo']['crop_dimension']['width'] . '/' . $news['photo']['crop_dimension']['height'] . '/' . $news['photo']['crop_dimension']['x'] . '/' . $news['photo']['crop_dimension']['y'] . '/' . $news['photo']['path'];
                                        }
                                    ?>
                                    <a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if (!$news['link_redirect']): ?>/<?php endif; ?><?= $news['link']; ?>" title="<?= esc($news['title']); ?>"<?php if (!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>>
                                        <picture>
                                            <source srcset="/image/c/460/300/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                            <img src="/image/c/1000/650/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?= esc($news['title']); ?>" class="trans400">
                                        </picture>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="info">
                                <h3><?= $news['header']; ?></h3>
                                <h2><a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if (!$news['link_redirect']): ?>/<?php endif; ?><?= $news['link']; ?>" title="<?= esc($news['title']); ?>"<?php if (!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?= $news['title']; ?></a></h2>
                                <p><?= character_limiter($news['introduction'], 160); ?></p>
                                <a href="<?php if (!$news['link_redirect']): ?>/<?php endif; ?><?= $news['link']; ?>" title="<?= esc($news['title']); ?>"<?php if (!empty($news['link_external'])): ?> target="_blank"<?php endif; ?> class="more"><?= lang('News.ReadMore'); ?></a>
                            </div>
                        </div>	
                    </div>
                <?php endforeach; ?> 
            </div>
        </div>
    </div>
<?php endif; ?>