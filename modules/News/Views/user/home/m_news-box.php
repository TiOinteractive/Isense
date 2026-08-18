<div id="home-bigbox">
<section id="news-home-box">
    <div class="container flex">
        <div class="left-column">
            <?php if(!empty($news[0]['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                <?php
                    if(!empty($news[0]['photo']['path']) && !empty($news[0]['photo']['crop_dimension'])) {
                        $news[0]['photo']['crop_dimension'] = json_decode($news[0]['photo']['crop_dimension'], true); 
                        $news[0]['photo']['path'] = $news[0]['photo']['crop_dimension']['width'] . '/' . $news[0]['photo']['crop_dimension']['height'] . '/' . $news[0]['photo']['crop_dimension']['x'] . '/' . $news[0]['photo']['crop_dimension']['y'] . '/' . $news[0]['photo']['path'];
                    }	
                ?>
                <figure>
                    <a <?php if(!empty($news[0]['link_redirect'])): ?> onclick="CountNews(<?=$news[0]['id'];?>);"<?php endif; ?> href="<?php if(!$news[0]['link_redirect']): ?>/<?php endif; ?><?=$news[0]['link'];?>" title="<?=esc($news[0]['title']);?>"<?php if(!empty($news[0]['link_external'])): ?> target="_blank"<?php endif; ?>>
                        <img src="/image/c/880/700/<?=!empty($news[0]['photo']['path']) ? $news[0]['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($news[0]['title']);?> <?=esc($news[0]['photo']['caption']);?> <?=esc($news[0]['photo']['author']);?>" />
                    </a>
                </figure>	
            <?php endif; ?>
            <div class="box">
                <?php if(!empty($news[0]['header'])):?>
                    <h3><?=$news[0]['header'];?></h3>
                <?php elseif(!empty($news[0]['info_page']['header']) && !empty($news[0]['info_page']['link'])):?> 
                    <h3><a href="/<?=$news[0]['info_page']['link'];?>" title="<?=esc($news[0]['info_page']['header']);?>"><?=$news[0]['info_page']['header'];?></a></h3>	
                <?php elseif(!empty($news[0]['info_page']['name']) && !empty($news[0]['info_page']['link'])):?> 
                    <h3><a href="/<?=$news[0]['info_page']['link'];?>" title="<?=esc($news[0]['info_page']['name']);?>"><?=$news[0]['info_page']['name'];?></a></h3>
                <?php endif;?>
                <h2><a <?php if(!empty($news[0]['link_redirect'])): ?> onclick="CountNews(<?=$news[0]['id'];?>);"<?php endif; ?> href="<?php if(!$news[0]['link_redirect']): ?>/<?php endif; ?><?=$news[0]['link'];?>" title="<?=esc($news[0]['title']);?>"<?php if(!empty($news[0]['link_external'])): ?> target="_blank"<?php endif; ?>><?=$news[0]['title'];?></a></h2>
	    </div>
        </div>
    </div>
</section>
<section id="news-home-column"> 
    <div class="container top-news-title">
	<div class="title resinet-title">
            <?php if(!empty($title)): ?><h2><?php if(!empty($url)): ?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if(!empty($url)): ?></a><?php endif; ?></h2><?php endif; ?>
        </div>
    </div>
    <div class="container flex">
        <div class="right-column">
            <?php if(!empty($news)):?>
                <div class="news-other">
                    <?php foreach($news as $k=>$item):?>
                        <?php if($k>1 && $k<8):?>
                            <div class="news-small">
                                <?php if(!empty($item['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                    <?php 
                                        if(!empty($item['photo']['path']) && !empty($item['photo']['crop_dimension'])) {
                                            $item['photo']['crop_dimension'] = json_decode($item['photo']['crop_dimension'], true); 
                                            $item['photo']['path'] = $item['photo']['crop_dimension']['width'] . '/' . $item['photo']['crop_dimension']['height'] . '/' . $item['photo']['crop_dimension']['x'] . '/' . $item['photo']['crop_dimension']['y'] . '/' . $item['photo']['path'];
                                        }	
                                    ?>
                                    <figure>
                                        <a <?php if(!empty($item['link_redirect'])): ?> onclick="CountNews(<?=$item['id'];?>);"<?php endif; ?> href="<?php if(!$item['link_redirect']): ?>/<?php endif; ?><?=$item['link'];?>" title="<?=esc($item['title']);?>"<?php if(!empty($item['link_external'])): ?> target="_blank"<?php endif; ?>>
                                            <img src="/image/c/160/120/<?=!empty($item['photo']['path']) ? $item['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($item['title']);?><?=esc($item['photo']['caption']);?> <?=esc($item['photo']['author']);?>" />
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
                                    <h3><a <?php if(!empty($item['link_redirect'])): ?> onclick="CountNews(<?=$item['id'];?>);"<?php endif; ?> href="<?php if(!$item['link_redirect']): ?>/<?php endif; ?><?=$item['link'];?>" title="<?=esc($item['title']);?>"<?php if(!empty($item['link_external'])): ?> target="_blank"<?php endif; ?>><?=$item['title'];?></a></h3>
                                </div>  
                            </div>
                       <?php endif;?>
                    <?php endforeach;?>
                </div> 
            <?php endif;?>
        </div>
        <?php if(!empty($mobile)): ?>
         <?= view_cell('\Modules\News\Libraries\News::showMostReadList', ['id_lang' => $id_lang, 'locale' => $locale, 'config' => array('no' => 6, 'mostreaddays' => 14, 'option' => 'home')]); ?>
        <?php endif; ?>
    </div>
</section>	
</div>