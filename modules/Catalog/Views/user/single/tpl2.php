<?php
/* 
Catalog tpl - kościoły
*/
?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<section class="catalog-single catalog-<?=$data['id'];?>">
    <div class="container">
        <div class="column-row">
            
			
		<?php if(empty($mobile)):?>		
			<div class="col col-sidebar left">
                <?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 4, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'catalog', 'submenu_levels' => 0, 'options' => []]) ?>
				<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 20, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
		<?php else:?>
	 <div class="col col-content">	
   <div class="catalog-list"> 
	<div class="search-engine-2 hight-fields">
        <form action="" method="get" class="catalog-form">
            <div class="search-top" style="padding-top:0px;">
				<div class="filter-box filter-cat">
                    <select class="field" name="cat" readonly="readonly">
                        <option value="">Wybierz kategorię</option>
                        <?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 4, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'catalog_mobile', 'submenu_levels' => 0, 'options' => []]) ?>
                    </select>
                </div>
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>
</div>
        <?php endif;?>		
            <div class="col col-content">
                <?php if(!empty($data['photo'])): ?>
                    <div class="photo">
                        <a href="/image/r/1920/1080/<?=$data['photo']['path']; ?>" data-thumb="/image/c/120/70/<?=$data['photo']['path'];?>" title="<?=esc($data['photo']['caption'] ? $data['photo']['caption']: $data['name']); ?><?php if(!empty($data['photo']['author'])):?>, <?=esc($data['photo']['author']);?><?php endif; ?>, Rzeszów" rel="lightbox">
                            <picture>
                                <source srcset="/image/r/800/380/<?=$data['photo']['path']; ?>" media="(max-width: 800px)">
                                <img src="/image/c/1260/600/<?=$data['photo']['path']; ?>" alt="<?=esc($data['photo']['caption'] ? $data['photo']['caption']: $data['name']); ?><?php if(!empty($data['photo']['author'])):?>, <?=esc($data['photo']['author']);?><?php endif; ?>, Rzeszów" />
                            </picture>
                        </a>
                        <?php if(!empty($data['photo']['caption'])): ?>
                            <div class="photo-caption">
                                <?=$data['photo']['caption']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if(!empty($data['photos'])): ?>
                    <div class="thumbnails-box">
                        <?php foreach($data['photos'] as $p=>$photo): ?>
                            <?php if($p < 4): ?>
                                <div class="thumbnail">
                                    <a href="/image/r/1920/1080/<?=$photo['path']; ?>" data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['name']); ?><?php if(!empty($photo['author'])):?>, <?=esc($photo['author']);?><?php endif; ?>, Rzeszów" rel="lightbox">
                                        <picture>
                                            <img src="/image/c/400/240/<?=$photo['path']; ?>" alt="<?=esc($photo['caption'] ? $photo['caption']: $data['name']); ?><?php if(!empty($photo['author'])):?>, <?=esc($photo['author']);?><?php endif; ?>, Rzeszów" />
                                        </picture>
                                        <?php if($p == 3 && count($data['photos']) > 4): ?>
                                            <span class="plus"><strong>+<?=count($data['photos']) - 4; ?></strong></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <a href="/image/r/1920/1080/<?=$photo['path']; ?>" data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['name']); ?><?php if(!empty($photo['author'])):?>, <?=esc($photo['author']);?><?php endif; ?>, Rzeszów" rel="lightbox"></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="column-row">
                    <div class="col col-content">
                        <h1 class="name"><?=$data['name']; ?></h1>
                        <div class="parameters">
                            <div class="l">
                                <?php if(!empty($data['address'])): ?>
                                    <div class="param">
                                        <div class="ico">
                                            <svg viewBox="0 0 256 256"><rect fill="none" height="256" width="256"></rect><line fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="8" x1="56" x2="200" y1="232" y2="232"></line><circle cx="128" cy="104" fill="none" r="32" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="8"></circle><path d="M208,104c0,72-80,128-80,128S48,176,48,104a80,80,0,0,1,160,0Z" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="8"></path></svg>
                                        </div>
                                        <div class="value">
                                            <label><?=lang('Catalog.user.Address'); ?>:</label>
                                            <p><?=str_replace(PHP_EOL, '<br />', $data['address']); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($data['phone'])): ?>
                                    <div class="param">
                                        <div class="ico">
                                            <svg viewBox="0 0 48 48"><title/><g data-name="8-Email" id="_8-Email"><path d="M45,7H3a3,3,0,0,0-3,3V38a3,3,0,0,0,3,3H45a3,3,0,0,0,3-3V10A3,3,0,0,0,45,7Zm-.64,2L24,24.74,3.64,9ZM2,37.59V10.26L17.41,22.17ZM3.41,39,19,23.41l4.38,3.39a1,1,0,0,0,1.22,0L29,23.41,44.59,39ZM46,37.59,30.59,22.17,46,10.26Z"/></g></svg>
                                        </div>
                                        <div class="value">
                                            <label><?=lang('Catalog.user.Phone'); ?>:</label>
                                            <p>
                                                <?php
                                                    $phones = explode(',', $data['phone']);
                                                ?>
                                                <?php foreach($phones as $k=>$p): ?><?php if($k): ?>, <?php endif; ?><a href="tel:<?=$p; ?>" title="<?=$p; ?>"><?=$p; ?></a><?php endforeach; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($data['website'])): ?>
                                    <div class="param">
                                        <div class="ico">
                                            <svg viewBox="0 0 128 128"><g><path d="M64,126c34.2,0,62-27.8,62-62S98.2,2,64,2S2,29.8,2,64S29.8,126,64,126z M16,88.7l25.2-0.2c2.8,10.1,7.5,19.9,13.9,28.7   C38,114.4,23.7,103.5,16,88.7z M47.6,47H79c2.3,11,2.3,22.3,0.2,33.3l-31.6,0.2C45.3,69.4,45.3,58,47.6,47z M63.3,114.9   c-6.3-8.1-10.9-17-13.7-26.4l27.5-0.2C74.2,97.7,69.6,106.7,63.3,114.9z M71.3,117.5c6.6-9,11.3-18.9,14.1-29.3l26.9-0.2   C104.5,103.7,89.3,115,71.3,117.5z M118,64c0,5.6-0.9,11-2.4,16l-28.3,0.2c2-11,1.9-22.2-0.2-33.2h28.1C117,52.3,118,58.1,118,64z    M111.8,39H85.2c-2.9-10-7.5-19.7-13.9-28.5C89,12.9,103.9,23.8,111.8,39z M76.9,39H49.7c2.9-9.2,7.4-17.9,13.6-25.9   C69.5,21.1,74,29.8,76.9,39z M55.1,10.8C48.8,19.5,44.2,29,41.4,39H16.2C23.9,24.3,38.1,13.6,55.1,10.8z M39.5,47   c-2.1,11.1-2.1,22.4-0.1,33.5l-26.7,0.2C10.9,75.4,10,69.8,10,64c0-5.9,1-11.7,2.8-17H39.5z"/></g></svg>
                                        </div>
                                        <div class="value">
                                            <label><?=lang('Catalog.user.Website'); ?>:</label>
                                            <p><a href="<?= str_contains($data['website'], 'http') ? '' : 'http://'; ?><?=$data['website']; ?>" title="<?=$data['website']; ?>" target="_blank" rel="nofollow"><?=$data['website']; ?></a></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="r">
                                <?php if(!empty($data['email'])): ?>
                                    <div class="param">
                                        <div class="ico">
                                            <svg viewBox="0 0 48 48"><title/><g data-name="8-Email" id="_8-Email"><path d="M45,7H3a3,3,0,0,0-3,3V38a3,3,0,0,0,3,3H45a3,3,0,0,0,3-3V10A3,3,0,0,0,45,7Zm-.64,2L24,24.74,3.64,9ZM2,37.59V10.26L17.41,22.17ZM3.41,39,19,23.41l4.38,3.39a1,1,0,0,0,1.22,0L29,23.41,44.59,39ZM46,37.59,30.59,22.17,46,10.26Z"/></g></svg>
                                        </div>
                                        <div class="value">
                                            <label><?=lang('Catalog.user.Email'); ?>:</label>
                                            <p>
                                                <?php
                                                    $emailes = explode(',', $data['email']);
                                                ?>
                                                <?php foreach($emailes as $k=>$m): ?><?php if($k): ?>, <?php endif; ?><a href="mailto:<?=$m; ?>" title="<?=$m; ?>"><?=$m; ?></a><?php endforeach; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($data['open_hours'])): ?>
                                    <div class="param">
                                        <div class="ico">
                                            <svg viewBox="0 0 512 512"><g><path d="M256,48C141.1,48,48,141.1,48,256s93.1,208,208,208c114.9,0,208-93.1,208-208S370.9,48,256,48z M256,446.7   c-105.1,0-190.7-85.5-190.7-190.7c0-105.1,85.5-190.7,190.7-190.7c105.1,0,190.7,85.5,190.7,190.7   C446.7,361.1,361.1,446.7,256,446.7z"></path><polygon points="256,256 160,256 160,273.3 273.3,273.3 273.3,128 256,128  "></polygon></g></svg>
                                        </div>
                                        <div class="value">
                                            <label><?=lang('Catalog.user.MassTimes'); ?>:</label>
                                            <p><?=str_replace(PHP_EOL, '<br />', $data['open_hours']); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if(!empty($data['content'])): ?>
                            <div class="content"><?=$data['content']; ?></div>
                        <?php endif; ?>
                        <?php if(!empty($data['cords']) && $data['cords'] != '0,0'): ?>
                            <div class="map-container" id="map"></div>
                        <?php endif; ?>
                        <?php if(!empty($data['tags'])): ?>
                            <div class="tags">
                                <div class="title">
                                    <h3><?=lang('User.other.Tags'); ?></h3>
                                </div>
                                <?php foreach($data['tags'] as $tag): ?>
                                    <div class="tag"><a href="/<?=!empty($global_links['search_tags']) ? $global_links['search_tags'] : ''; ?>/g/t/<?=$tag['tag']; ?>" title="<?=esc($tag['tag']); ?>"><?=$tag['tag']; ?></a></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col col-sidebar right">
                        <?php if(!empty($data['id_sidebar'])): ?>
                            <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $data['id_sidebar'], 'id_lang' => $id_lang, 'locale' => $locale]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>