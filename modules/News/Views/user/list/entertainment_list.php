<?php
/* 
Rozrywka - Lista aktualności - strona główna
 */
?>
<div class="entertainment-news">
    <div class="container">
        <?php if(!empty($data) && !empty($data['list'])): ?>
            <section class="section-<?=$id_cont; ?> news-list entertainment-news-list">
                    <?php if(!empty($title)): ?> 
                        <div class="title resinet-title">
                           <h2 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                            <?php if(!empty($subtitle)): ?>
                                <h3 class="h-2"><?=$subtitle; ?></h3>
                            <?php endif; ?>
                            <?php if($url):?><a class="more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainment.SeeAll')); ?>"><?=lang('User.entertainment.SeeAll'); ?></a><?php endif; ?>
                       </div>	
                    <?php endif; ?>         
                    <div class="list">
                        <?php foreach($data['list'] as $news): ?>
                            <div class="news-item news-item-<?=$news['id']; ?>">
                                <div class="photo">
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
                                <div class="info">
                                    <?php if(!empty($news['header'])): ?>
                                        <h4><?= $news['header']; ?></h4>
                                    <?php elseif(!empty($news['info_page']['header']) && !empty($news['info_page']['link'])): ?>
                                        <h4><a href="/<?= $news['info_page']['link']; ?>" title="<?= esc($news['info_page']['header']); ?>"><?= $news['info_page']['header']; ?></a></h4>
                                    <?php elseif(!empty($news['info_page']['name']) && !empty($news['info_page']['link'])): ?> 
                                        <h4><a href="/<?= $news['info_page']['link']; ?>" title="<?= esc($news['info_page']['name']); ?>"><?= $news['info_page']['name']; ?></a></h4>
                                    <?php endif; ?>
                                    <h3><a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?=$news['link']; ?>" title="<?=esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?=$news['title']; ?></a></h3>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if(!empty($data['pager'])): ?>
                        <?=$data['pager']->links('news-' . $id_cont, 'front_entertainment'); ?>
                    <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
    <div class="col col-sidebar">
        <?php if(!empty($id_sidebar)): ?>
            <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
        <?php endif; ?>
    </div>
</div>