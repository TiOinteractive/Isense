<div id="home-bigbox">
<?php if(!empty($news) && !empty($news[0])): ?>
 <section id="news-home-default">
    <?php if(!empty($news[0]['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
        <?php
            if(!empty($news[0]['photo']['path']) && !empty($news[0]['photo']['crop_dimension'])) {
                $news[0]['photo']['crop_dimension'] = json_decode($news[0]['photo']['crop_dimension'], true); 
                $news[0]['photo']['path'] = $news[0]['photo']['crop_dimension']['width'] . '/' . $news[0]['photo']['crop_dimension']['height'] . '/' . $news[0]['photo']['crop_dimension']['x'] . '/' . $news[0]['photo']['crop_dimension']['y'] . '/' . $news[0]['photo']['path'];
            }	
        ?>
        <a <?php if(!empty($news[0]['link_redirect'])): ?> onclick="CountNews(<?=$news[0]['id'];?>);"<?php endif; ?> href="<?php if(!$news[0]['link_redirect']): ?>/<?php endif; ?><?=$news[0]['link'];?>" title="<?=esc($news[0]['title']);?>" class="link"<?php if(!empty($news[0]['link_external'])): ?> target="_blank"<?php endif; ?>></a>
        <picture>
            <source srcset="/image/c/800/580/<?=!empty($news[0]['photo']['path']) ? $news[0]['photo']['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
            <img src="/image/c/1600/580/<?=!empty($news[0]['photo']['path']) ? $news[0]['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($news[0]['title']);?> <?=esc($news[0]['photo']['caption']);?> <?=esc($news[0]['photo']['author']);?>">
        </picture>
    <?php endif; ?>
    <div class="container caption">
         <?php if(!empty($news[0]['header'])):?>
             <h2><?=$news[0]['header'];?></h2>
         <?php elseif(!empty($news[0]['info_page']['header']) && !empty($news[0]['info_page']['link'])):?> 
             <h2><a href="/<?=$news[0]['info_page']['link'];?>" title="<?=esc($news[0]['info_page']['header']);?>"><?=$news[0]['info_page']['header'];?></a></h2>	
         <?php elseif(!empty($news[0]['info_page']['name']) && !empty($news[0]['info_page']['link'])):?> 
             <h2><a href="<?php if(!$news[0]['link_redirect']): ?>/<?php endif; ?><?=$news[0]['info_page']['link'];?>" title="<?=esc($news[0]['info_page']['name']);?>"<?php if(!empty($news[0]['link_external'])): ?> target="_blank"<?php endif; ?>><?=$news[0]['info_page']['name'];?></a></h2>
         <?php endif;?>                      
         <h1><?=$news[0]['title'];?></h1>
     </div>		   
 </section>
<?php endif; ?>
    
<?= view_cell('\Modules\Banner\Libraries\Banner::showBanner', ['id_zone' => 4, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'tpl2']); ?>
    
 <section id="news-home-column"> 
  <div class="container top-news-title">
	<div class="title resinet-title">
     <?php if(!empty($title)): ?><h2><?php if(!empty($url)): ?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if(!empty($url)): ?></a><?php endif; ?></h2><?php endif; ?>
  </div>
  <?= view_cell('\App\Libraries\Page::showMenu', ['id_menu' => 5, 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'news-home', 'submenu_levels' => 0, 'options' => ['mode' => 'external_active_submenu']]) ?>
  </div>
<div class="container flex">
    <div class="left-column">
        <?php if(!empty($news)):?>
            <div class="news-list">
                <?php foreach($news as $k=>$item):?>
                       <?php if($k<11 && $k>0):?>
                        <div class="item">
                            <?php if(!empty($item['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                <?php 
                                     if(!empty($item['photo']['path']) && !empty($item['photo']['crop_dimension'])) {
                                         $item['photo']['crop_dimension'] = json_decode($item['photo']['crop_dimension'], true); 
                                         $item['photo']['path'] = $item['photo']['crop_dimension']['width'] . '/' . $item['photo']['crop_dimension']['height'] . '/' . $item['photo']['crop_dimension']['x'] . '/' . $item['photo']['crop_dimension']['y'] . '/' . $item['photo']['path'];
                                     }	
                                 ?>
                                <figure>
                                    <a <?php if(!empty($item['link_redirect'])): ?> onclick="CountNews(<?=$item['id'];?>);"<?php endif; ?> href="<?php if(!$item['link_redirect']): ?>/<?php endif; ?><?=$item['link'];?>" title="<?=esc($item['title']);?>"<?php if(!empty($item['link_external'])): ?> target="_blank"<?php endif; ?>>
                                        <img src="/image/c/460/300/<?=!empty($item) && !empty($item['photo'])&& !empty($item['photo']['path']) ? $item['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($item['title']);?> <?=esc($item['photo']['caption']);?> <?=esc($item['photo']['author']);?>">
                                    </a>
                                </figure>	
                            <?php endif; ?>
                            <div class="box">
                                <?php if(!empty($item['header'])):?>
                                    <h3><?=$item['header'];?></h3>
                                <?php elseif(!empty($item['info_page']['header']) && !empty($item['info_page']['link'])):?> 
                                    <h3><a href="/<?=$item['info_page']['link'];?>" title="<?=esc($item['info_page']['header']);?>"><?=$item['info_page']['header'];?></a></h3>
                                <?php elseif(!empty($item['info_page']['name']) && !empty($item['info_page']['link'])):?> 
                                    <h3><a href="/<?=$item['info_page']['link'];?>" title="<?=esc($item['info_page']['name']);?>"><?=$item['info_page']['name'];?></a></h3>
                                <?php endif;?>
                              <h2><a <?php if(!empty($item['link_redirect'])): ?> onclick="CountNews(<?=$item['id'];?>);"<?php endif; ?> href="<?php if(!$item['link_redirect']): ?>/<?php endif; ?><?=$item['link'];?>" title="<?=esc($item['title']);?>"<?php if(!empty($item['link_external'])): ?> target="_blank"<?php endif; ?>><?=$item['title'];?></a></h2>
                            </div>
                         </div>
                       <?php endif;?>
                <?php endforeach;?>
            </div>
	 <?php endif;?>
        <?php if(empty($mobile)): ?>
         <?= view_cell('\Modules\News\Libraries\News::showMostReadList', ['id_lang' => $id_lang, 'locale' => $locale, 'config' => array('no' => 6, 'mostreaddays' => 14, 'option' => 'home')]); ?>
        <?php endif; ?>
    </div>
    <div class="right-column">
	  <?php if(!empty($news)):?>
	   <div class="news-other">
	    <?php foreach($news as $k=>$item):?>
                <?php if($k>10):?>
                    <div class="news-small">
                        <?php if(!empty($item['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                            <?php 
                                 if(!empty($item['photo']['path']) && !empty($item['photo']['crop_dimension'])) {
                                     $item['photo']['crop_dimension'] = json_decode($item['photo']['crop_dimension'], true); 
                                     $item['photo']['path'] = $item['photo']['crop_dimension']['width'] . '/' . $item['photo']['crop_dimension']['height'] . '/' . $item['photo']['crop_dimension']['x'] . '/' . $item['photo']['crop_dimension']['y'] . '/' . $item['photo']['path'];
                                 }	
                             ?>
                            <figure>
                                <a <?php if(!empty($item['link_redirect'])): ?> onclick="CountNews(<?=$item['id'];?>);"<?php endif; ?> href="<?php if(!$item['link_redirect']): ?>/<?php endif; ?><?=$item['link'];?>" title="<?=esc($item['title']);?>">
                                    <img src="/image/c/160/100/<?=!empty($item['photo']['path']) ? $item['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($item['title']);?> <?=esc($item['photo']['caption']);?> <?=esc($item['photo']['author']);?>">
                                </a>
                            </figure>
                        <?php endif; ?>
                        <div class="box">		
                            <?php if(!empty($item['header'])):?>
                                <h4><?=$item['header'];?></h4>
                            <?php elseif(!empty($item['info_page']['header']) && !empty($item['info_page']['link'])):?> 
                                <h4><a href="/<?=$item['info_page']['link'];?>" title="<?=esc($item['info_page']['header']);?>"><?=$item['info_page']['header'];?></a></h4> 
                            <?php elseif(!empty($item['info_page']['name']) && !empty($item['info_page']['link'])):?> 
                                <h4><a href="/<?=$item['info_page']['link'];?>" title="<?=esc($item['info_page']['name']);?>"><?=$item['info_page']['name'];?></a></h4>
                            <?php endif;?>
                            <h3><a <?php if(!empty($item['link_redirect'])): ?> onclick="CountNews(<?=$item['id'];?>);"<?php endif; ?> href="<?php if(!$item['link_redirect']): ?>/<?php endif; ?><?=$item['link'];?>" title="<?=esc($item['title']);?>"><?=$item['title'];?></a></h3>
		        </div>  
                    </div>
		   <?php endif;?>
	   <?php endforeach;?>
	  </div> 
	 <?php endif;?>
        <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 7, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
        <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 9, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
	   <?= view_cell('\Modules\Survey\Libraries\Survey::showSurvey', ['id_lang' => $id_lang, 'locale' => $locale]) ?>
	   <?= view_cell('\Modules\Banner\Libraries\Banner::showBanner', ['id_zone' => 2 ,'id_lang' => $id_lang, 'locale' => $locale]) ?>
	   <?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 21, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
	</div>
        <?php if(!empty($mobile)): ?>
         <?= view_cell('\Modules\News\Libraries\News::showMostReadList', ['id_lang' => $id_lang, 'locale' => $locale, 'config' => array('no' => 6, 'mostreaddays' => 14, 'option' => 'home')]); ?>
        <?php endif; ?>
</div>



</section>
</div>