<?php
/* 
Lista publikacji katalogu
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
         <?php endif;?>
		<div class="col col-content">
                <section class="section-<?=$id_cont; ?> catalog-list">
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
                        <?php if(!empty($data) && !empty($data['list'])): ?>
                            <?php foreach($data['list'] as $catalog): ?>
                                <div class="catalog-item catalog-item-<?=$catalog['id']; ?>">
                                    <div class="catalog-item-cont">
                                        <div class="photo">
                                            <?php if(!empty($catalog['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                <a href="/<?=$catalog['link']; ?>" title="<?=esc($catalog['name']); ?>">
                                                    <picture>
                                                        <source srcset="/image/c/400/260/<?=!empty($catalog['path']) ? $catalog['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                                        <img src="/image/c/600/400/<?=!empty($catalog['path']) ? $catalog['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($catalog['name']); ?>" class="trans400" />
                                                    </picture>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="info">
                                            <h2><a href="/<?=$catalog['link']; ?>" title="<?=esc($catalog['name']); ?>"><?=$catalog['name']; ?></a></h2>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-results"><?=lang('Catalog.user.NoResults'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if(!empty($data['pager'])): ?>
                        <?=$data['pager']->links('catalog-' . $id_cont, 'front_entertainment'); ?>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</div>