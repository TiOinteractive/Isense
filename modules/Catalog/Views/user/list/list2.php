<?php
/* 
Lista publikacji katalogu w wierszach
 */
?>
<div class="catalog-list-page">
    <div class="container">
        <div class="column-row">
            <?php if(empty($mobile)):?>	
			<div class="col col-sidebar left">
                <?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 4, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'catalog', 'submenu_levels' => 0, 'options' => []]) ?>
				<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 20, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
			<?php endif; ?>
            <div class="col col-content">
                <?php if(!empty($data) && !empty($data['list'])): ?>
                    <section class="section-<?=$id_cont; ?> catalog-list-rows">
                        <?php if(!empty($title)): ?> 
                            <div class="title resinet-title">
                               <h1 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h1>
                                <?php if(!empty($subtitle)): ?>
                                    <h3 class="h-2"><?=$subtitle; ?></h3>
                                <?php endif; ?>
                           </div>	
                        <?php endif; ?>         
                        <?= view('\Modules\Catalog\Views\user/list\_list_search.php') ?>
                        <div class="list">
                            <?php foreach($data['list'] as $catalog): ?>
                                <div class="catalog-item catalog-item-<?=$catalog['id']; ?>">
                                    <div class="catalog-item-cont">
                                        <div class="photo">
                                            <div class="photo-cont">
                                                <?php if(!empty($catalog['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                    <?php if(!empty($catalog['link'])): ?><a href="/<?=$catalog['link']; ?>" title="<?=esc($catalog['name']); ?>"><?php endif; ?>
                                                        <picture>
                                                            <source srcset="/image/c/400/260/<?=!empty($catalog['path']) ? $catalog['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                                            <img src="/image/c/600/400/<?=!empty($catalog['path']) ? $catalog['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($catalog['name']); ?>" class="trans400" />
                                                        </picture>
                                                    <?php if(!empty($catalog['link'])): ?></a><?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="info">
                                            <h2><?php if(!empty($catalog['link'])): ?><a href="/<?=$catalog['link']; ?>" title="<?=esc($catalog['name']); ?>"><?php endif; ?><?=$catalog['name']; ?><?php if(!empty($catalog['link'])): ?></a><?php endif; ?></h2>
                                            <?php if(!empty($catalog['address'])): ?>
                                                <p><?=str_replace(PHP_EOL, '<br />', $catalog['address']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="parameters">
                                            <div class="l">
                                                <?php if(!empty($catalog['phone'])): ?>
                                                    <div class="param">
                                                        <label><?=lang('Catalog.user.Phone'); ?>:</label>
                                                        <p>
                                                            <?php
                                                                $phones = explode(',', $catalog['phone']);
                                                            ?>
                                                            <?php foreach($phones as $k=>$p): ?><?php if($k): ?>, <?php endif; ?><a href="tel:<?=$p; ?>" title="<?=$p; ?>"><?=$p; ?></a><?php endforeach; ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(!empty($catalog['website'])): ?>
                                                    <div class="param">
                                                        <label><?=lang('Catalog.user.Website'); ?>:</label>
                                                        <p><a href="<?= str_contains($catalog['website'], 'http') ? '' : 'http://'; ?><?=$catalog['website']; ?>" title="<?=$catalog['website']; ?>" target="_blank" rel="nofollow"><?=$catalog['website']; ?></a></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="r">
                                                <?php if(!empty($catalog['email'])): ?>
                                                    <div class="param">
                                                        <label><?=lang('Catalog.user.Email'); ?>:</label>
                                                        <p>
                                                            <?php
                                                                $emailes = explode(',', $catalog['email']);
                                                            ?>
                                                            <?php foreach($emailes as $k=>$m): ?><?php if($k): ?>, <?php endif; ?><a href="mailto:<?=$m; ?>" title="<?=$m; ?>"><?=$m; ?></a><?php endforeach; ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if((!empty($catalog['cords']) && $catalog['cords'] != '0,0') || !empty($catalog['address'])): ?>
                                                    <div class="param">
                                                        <label><?=lang('Catalog.user.Location'); ?>:</label>
                                                        <p><a href="https://www.google.com/maps/search/?api=1&query=<?=!empty($catalog['cords']) && $catalog['cords'] != '0,0' ? $catalog['cords'] : $catalog['address']; ?>" title="<?=$catalog['name']; ?>" target="_blank" rel="nofollow"><?=lang('Catalog.user.SeeOnMap'); ?></a></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if(!empty($data['pager'])): ?>
                            <?=$data['pager']->links('catalog-' . $id_cont, 'front_entertainment'); ?>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>