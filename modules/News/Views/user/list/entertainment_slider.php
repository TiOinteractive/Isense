<?php
/* 
Rozrywka - Slider aktualności
 */
?>
<div class="entertainment-boxes">
    <div class="container">
        <div class="entertainment-row">
            <div class="col col2-8">
                <?php if(!empty($data) && !empty($data['list'])): ?>
                    <section class="section-<?=$id_cont; ?> news-list entertainment-news-slider">
                        <div class="container">
                            <?php if(!empty($title)): ?> 
                                <div class="title entertainment-title">
                                   <h2 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                                    <?php if(!empty($subtitle)): ?>
                                        <h3 class="h-2"><?=$subtitle; ?></h3>
                                    <?php endif; ?>
                                    <?php if($url):?><a class="more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainemnt.SeeAll')); ?>"><?=lang('User.entertainemnt.SeeAll'); ?></a><?php endif; ?>
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
                                                        <img src="/image/c/1000/650/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($news['title']); ?>" class="trans400">
                                                    </picture>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="slide-gradient"></div>
                                        <div class="info">
                                            <h2><a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?=$news['link']; ?>" title="<?=esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?=$news['title']; ?></a></h2>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if(!empty($data['pager'])): ?>
                                <?=$data['pager']->links('news-' . $id_cont, 'front_entertainment'); ?>
                            <?php elseif($url):?>
                                <div class="btn-box">
                                    <a class="btn more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainemnt.SeeAll')); ?>"><?=lang('User.entertainemnt.MoreNews'); ?></a>
                                </div>
                            <?php endif; ?>
                        </div> 	
                    </section>
                <?php endif; ?>
            </div>
            <div class="col col2-4">
                <?php if(!empty($id_sidebar)): ?>
                    <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>