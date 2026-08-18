<?php
/*
  News list
 */
?>
<section class="section-<?= $id_cont; ?> news-list">
    <div class="container">
        <?php if(!empty($title)): ?> 
        <div class="title resinet-title">
            <h2><?php if(!empty($url)): ?><a href="<?= $url; ?>"><?php endif; ?><?= $title; ?><?php if(!empty($url)): ?></a><?php endif; ?></h2>
            <?php if(!empty($subtitle)): ?>
            <h3><?= $subtitle; ?></h3>
            <?php endif; ?>
        </div>	
        <?php endif; ?> 
        <div class="entertainment-row">
            <div class="col col-content">
                <?php if(!empty($data) && !empty($data['list'])): ?>
                <div class="resinet-news-other">
                    <div class="list" >
                        <?php foreach($data['list'] as $k => $news): ?>
                        <?php if($k==0):?>
                        <div class="news-item news-item-<?= $news['id']; ?> news-big">
                            <div class="photo">
                                <div class="photo-cont">
                                    <?php if(!empty($news['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                        <?php
                                            if(!empty($news['photo']['path']) && !empty($news['photo']['crop_dimension'])) {
                                                $news['photo']['crop_dimension']=json_decode($news['photo']['crop_dimension'], true); 
                                                $news['photo']['path'] = $news['photo']['crop_dimension']['width'] . '/' . $news['photo']['crop_dimension']['height'] . '/' . $news['photo']['crop_dimension']['x'] . '/' . $news['photo']['crop_dimension']['y'] . '/' . $news['photo']['path'];
                                                
                                            }
                                        ?>
                                        <a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?= $news['link']; ?>" title="<?= esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>>
                                            <picture>
                                                <source srcset="/image/c/460/300/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                                <img src="/image/c/1000/650/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?= esc($news['title']); ?>" class="trans400">
                                            </picture>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="box">
                                    <?php if(!empty($news['header'])):?>
                                        <h3><?= $news['header']; ?></h3>
                                    <?php elseif(!empty($news['info_page']['header']) and !empty($news['info_page']['link'])):?> 
                                        <h3><a href="/<?= $news['info_page']['link']; ?>" title="<?= esc($news['info_page']['header']); ?>"><?= $news['info_page']['header']; ?></a></h3>
                                    <?php elseif(!empty($news['info_page']['name']) and !empty($news['info_page']['link'])):?> 
                                        <h3><a href="/<?= $news['info_page']['link']; ?>" title="<?= esc($news['info_page']['name']); ?>"><?= $news['info_page']['name']; ?></a></h3>
                                    <?php endif;?>
                                    <h2 ><a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?= $news['link']; ?>" title="<?= esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?= $news['title']; ?></a></h2>
                                </div>		
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="news-item news-item-<?= $news['id']; ?>">
                            <div class="news-item-cont">
                                <div class="photo">
                                    <div class="photo-cont">
                                        <?php if(!empty($news['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                            <?php
                                                if(!empty($news['photo']['path']) && !empty($news['photo']['crop_dimension'])) {
                                                    $news['photo']['crop_dimension']=json_decode($news['photo']['crop_dimension'], true); 
                                                    $news['photo']['path'] = $news['photo']['crop_dimension']['width'] . '/' . $news['photo']['crop_dimension']['height'] . '/' . $news['photo']['crop_dimension']['x'] . '/' . $news['photo']['crop_dimension']['y'] . '/' . $news['photo']['path'];
                                                }
                                            ?>
                                            <a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?= $news['link']; ?>" title="<?= esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>>
                                                <picture>
                                                    <source srcset="/image/c/460/300/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                                    <img src="/image/c/800/520/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?= esc($news['title']); ?>" class="trans400">
                                                </picture>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="info">
                                    <?php if(!empty($news['header'])): ?>
                                        <h4 class="header"><?= $news['header']; ?></h4>
                                    <?php elseif(!empty($news['info_page']['header']) &&!empty($news['info_page']['link'])): ?> 
                                        <h4 class="header"><a href="/<?= $news['info_page']['link']; ?>" title="<?= esc($news['info_page']['header']); ?>"><?= $news['info_page']['header']; ?></a></h4>
                                    <?php elseif(!empty($news['info_page']['name']) &&!empty($news['info_page']['link'])): ?> 
                                        <h4 class="header"><a href="/<?= $news['info_page']['link']; ?>" title="<?= esc($news['info_page']['name']); ?>"><?= $news['info_page']['name']; ?></a></h4>
                                    <?php endif; ?>
                                        <h3 class="name"><a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?= $news['link']; ?>" title="<?= esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?= $news['title']; ?></a></h3>
                                    <?php if(!empty($news['introduction'])): ?>
                                        <p class="introduction"><?= word_limiter($news['introduction'], 34, '...'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php endforeach; ?>
                    </div>
                    <?php if(!empty($data['pager'])): ?>
                    <?= $data['pager']->links('news-' . $id_cont, 'front_entertainment'); ?>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="no-list-results"><?= lang('News.NoListResult'); ?></div>
                <?php endif;?>
            </div>
            <div class="col col-sidebar">
                <?php if(empty($id_sidebar)) { $id_sidebar=5; }?>
                <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
    </div>
</section>
