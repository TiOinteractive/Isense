<?php
/* 
Resinet - Sidebar listing
 */
?>
<div class="resinet-news-sidebar listing sidebar">
    <?php if(!empty($data) && !empty($data['list'])): ?>
        <?php if(!empty($title)): ?> 
            <div class="title resinet-title-sidebar">
               <h2><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                <?php if(!empty($subtitle)): ?>
                    <h3 class="h-2"><?=$subtitle; ?></h3>
                <?php endif; ?>
           </div>	
        <?php endif; ?>         
        <div class="list">
            <?php foreach($data['list'] as $k=>$news): ?>
                <div class="news-item news-item-<?=$news['id']; ?>">
                    <div class="news-item-cont">
                        <div class="count"><?=$k+1; ?></div>
                        <div class="photo">
                            <div class="photo-cont">
                                <?php if(!empty($news['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                    <?php
                                        if(!empty($news['photo']['path']) && !empty($news['photo']['crop_dimension'])) {
                                            $news['photo']['crop_dimension']=json_decode($news['photo']['crop_dimension'], true); 
                                            $news['photo']['path'] = $news['photo']['crop_dimension']['width'] . '/' . $news['photo']['crop_dimension']['height'] . '/' . $news['photo']['crop_dimension']['x'] . '/' . $news['photo']['crop_dimension']['y'] . '/' . $news['photo']['path'];
                                        }
                                    ?>
                                    <a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?=$news['link']; ?>" title="<?=esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>>
                                        <picture>
                                            <source srcset="/image/c/460/300/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                            <img src="/image/c/800/520/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($news['title']); ?>" class="trans400">
                                        </picture>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="info">
                            <h3 class="name"><a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?=$news['link']; ?>" title="<?=esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?=$news['title']; ?></a></h3>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>