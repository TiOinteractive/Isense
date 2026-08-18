<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>

<section class="tags-search">
    <div class="container">
        <div class="column-row">
            <div class="col col-content">
                <div class="title resinet-title">
                    <h1><?=lang('Tags.user.TagsSearch'); ?><?php if(!empty($data['search_tag'])): ?>: "<?=$data['search_tag']; ?>"<?php endif; ?></h1>
                </div>
                <div class="search-tags-list">
                    <?php if(!empty($data['list'])): ?>
                        <?php if(!empty($data['list']['news'])): ?>
                            <div class="list news-search-list">
                                <div class="title">
                                    <h2><?=lang('Tags.user.News'); ?></h2>
                                </div>
                                <?php foreach($data['list']['news'] as $news): ?>
                                    <div class="item news-item news-item-<?=$news['id']; ?>">
                                        <div class="item-cont news-item-cont">
                                            <div class="photo">
                                                <div class="photo-cont">
                                                    <?php if(!empty($news['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                        <?php
                                                            if(!empty($news['photo']['path']) && !empty($news['crop_dimension'])) {
                                                                $news['crop_dimension']=json_decode($news['crop_dimension'], true); 
                                                                $news['photo']['path'] = $news['crop_dimension']['width'] . '/' . $news['crop_dimension']['height'] . '/' . $news['crop_dimension']['x'] . '/' . $news['crop_dimension']['y'] . '/' . $news['photo']['path'];
                                                            }
                                                        ?>
                                                        <a href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?=$news['link']; ?>" title="<?=esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>>
                                                            <picture>
                                                                <source srcset="/image/c/460/300/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                                                <img src="/image/c/800/520/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($news['title']); ?>" class="trans400" />
                                                            </picture>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <?php if(!empty($news['header'])):?>
                                                    <h4 class="header"><?=$news['header']; ?></h4>
                                                <?php elseif(!empty($news['info_page']['header']) && !empty($news['info_page']['link'])):?> 
                                                    <h4 class="header"><a href="/<?=$news['info_page']['link'];?>" title="<?=esc($news['info_page']['header']);?>"><?=$news['info_page']['header'];?></a></h4>	
                                                <?php elseif(!empty($news['info_page']['name']) && !empty($news['info_page']['link'])):?> 
                                                    <h4 class="header"><a href="/<?=$news['info_page']['link'];?>" title="<?=esc($news['info_page']['name']);?>"><?=$news['info_page']['name'];?></a></h4>
                                                <?php endif; ?>
                                                <h3 class="name"><a href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?=$news['link']; ?>" title="<?=esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?=$news['title']; ?></a></h3>
                                                <?php if(!empty($news['introduction'])):?>
                                                    <p class="introduction"><?=word_limiter($news['introduction'], 32, '...'); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php if(!empty($data['news_pager'])): ?>
                                    <?=$data['news_pager']->links('news', 'front_entertainment'); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($data['list']['events'])): ?>
                            <div class="list events-search-list">
                                <div class="title">
                                    <h2><?=lang('Tags.user.Events'); ?></h2>
                                </div>
                                <?php foreach($data['list']['events'] as $event): ?>
                                    <div class="item event-item event-item-<?=$event['id']; ?>">
                                        <div class="item-cont event-item-cont">
                                            <div class="photo">
                                                <div class="photo-cont">
                                                    <?php if(!empty($event['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                        <?php
                                                            if(!empty($event['photo']['path']) && !empty($event['photo']['crop_dimension'])) {
                                                                $event['photo']['crop_dimension']=json_decode($event['photo']['crop_dimension'], true); 
                                                                $event['photo']['path'] = $event['photo']['crop_dimension']['width'] . '/' . $event['photo']['crop_dimension']['height'] . '/' . $event['photo']['crop_dimension']['x'] . '/' . $event['photo']['crop_dimension']['y'] . '/' . $event['photo']['path'];
                                                            }
                                                        ?>
                                                        <a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>">
                                                            <picture>
                                                                <img src="/image/c/600/400/<?=!empty($event['photo']['path']) ? $event['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($event['name']); ?>" class="trans400" />
                                                            </picture>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($event['patronage']): ?>
                                                        <div class="patronage"><?= lang('Users.user.Patronage'); ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($event['for_kids'])): ?>
                                                        <div class="for-kids">
                                                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <?php if(!empty($event['type_name'])): ?>
                                                    <div class="type">
                                                        <a href="<?=(!empty($global_links['calendar']) ? $global_links['calendar'] : '/') . '/g/t/' . $event['type_slug']; ?>" title="<?=esc($event['type_name']); ?>"><?=$event['type_name']; ?></a>
                                                    </div>
                                                <?php endif; ?>
                                                <h3 class="name"><a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></h3>
                                                <div class="date">
                                                    <?php if (!empty($event['date'])): ?>
                                                        <?php if($event['date']==date("Y-m-d")):?>
                                                        <strong><?=lang('Event.Today'); ?></strong>
                                                        <?php elseif($event['date']==date("Y-m-d",strtotime('+1day'))): ?>
                                                        <strong><?=lang('Event.Tomorrow'); ?></strong>
                                                        <?php elseif($event['date']==date("Y-m-d",strtotime('+2day'))): ?>
                                                        <strong><?=lang('Event.AfterTomorrow'); ?></strong>
                                                        <?php else:?>
                                                        <span><?= $event['date']; ?></span>
                                                        <?php endif;?>	
                                                    <?php else: ?>
                                                        <span><?= date("d.m.Y", strtotime($event['date_start'])); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php if(!empty($data['event_pager'])): ?>
                                    <?=$data['event_pager']->links('event', 'front_entertainment'); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['list']['catalog'])): ?>
                            <div class="list catalog-search-list">
                                <div class="title">
                                    <h2><?=lang('Tags.user.Catalog'); ?></h2>
                                </div>
                                <?php foreach($data['list']['catalog'] as $catalog): ?>
                                    <div class="item catalog-item catalog-item-<?=$catalog['id']; ?>">
                                        <div class="item-cont catalog-item-cont">
                                            <div class="photo">
                                                <div class="photo-cont">
                                                    <?php if(!empty($catalog['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                        <a href="<?=$catalog['link']; ?>" title="<?=esc($catalog['name']); ?>">
                                                            <picture>
                                                                <img src="/image/c/400/260/<?=!empty($catalog['photo']['path']) ? $catalog['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($catalog['name']); ?>" class="trans400" />
                                                            </picture>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <?php if(!empty($catalog['info_page']['name']) && !empty($catalog['info_page']['link'])):?> 
                                                    <h4 class="header"><a href="/<?=$catalog['info_page']['link'];?>" title="<?=esc($catalog['info_page']['name']);?>"><?=$catalog['info_page']['name'];?></a></h4>
                                                <?php endif; ?>
                                                <h3 class="name"><a href="<?=$catalog['link']; ?>" title="<?=esc($catalog['name']); ?>"><?=$catalog['name']; ?></a></h3>
                                                <?php if(!empty($catalog['address'])):?>
                                                    <p class="address"><?=$catalog['address']; ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php if(!empty($data['catalog_pager'])): ?>
                                    <?=$data['catalog_pager']->links('catalog', 'front_entertainment'); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['list']['restaurants'])): ?>
                            <div class="list catalog-search-list">
                                <div class="title">
                                    <h2><?=lang('Tags.user.Restaurants'); ?></h2>
                                </div>
                                <?php foreach($data['list']['restaurants'] as $restaurant): ?>
                                    <div class="item restaurant-item restaurant-item-<?=$restaurant['id']; ?>">
                                        <div class="item-cont restaurant-item-cont">
                                            <div class="photo">
                                                <div class="photo-cont">
                                                    <?php if(!empty($restaurant['photo']['path']) || (!empty($settings['no_photo_flavor']) && !empty($settings['no_photo_flavor']['path']))): ?>
                                                        <a href="<?=$restaurant['link']; ?>" title="<?=esc($restaurant['name']); ?>">
                                                            <picture>
                                                                <img src="/image/c/650/430/<?=!empty($restaurant['photo']['path']) ? $restaurant['photo']['path'] : $settings['no_photo_flavor']['path']; ?>" alt="<?=esc($restaurant['name']); ?>" class="trans400" />
                                                            </picture>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="info">
                                                <?php if(!empty($restaurant['category']['name']) && !empty($restaurant['category']['link'])):?> 
                                                    <h4 class="category"><a href="/<?=$restaurant['category']['link'];?>" title="<?=esc($restaurant['category']['name']);?>"><?=$restaurant['category']['name'];?></a></h4>
                                                <?php endif; ?>
                                                <h3 class="name"><a href="/<?=$restaurant['link']; ?>" title="<?=esc($restaurant['name']); ?>"><?=$restaurant['name']; ?></a></h3>
                                                <?php if(!empty($restaurant['address']) || !empty($restaurant['city'])):?>
                                                    <p class="address"><?=$restaurant['address']; ?><?php if(!empty($restaurant['address']) && !empty($restaurant['city'])): ?><br /><?php endif; ?><?=$restaurant['city']; ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php if(!empty($data['restaurants_pager'])): ?>
                                    <?=$data['restaurants_pager']->links('restaurant', 'front_entertainment'); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    
                    <?php else: ?>
                        <p class="no-results"><?=lang('Tags.user.SearchNoResults'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col col-sidebar">
                <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => 5, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
    </div>
</section>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>