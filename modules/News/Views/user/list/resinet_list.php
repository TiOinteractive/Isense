<?php
/* 
Resinet - Lista aktualności Rozrywka - strona główna
 */
?>
<?php if(!empty($data) && !empty($data['list'])): ?>
    <section class="section-<?=$id_cont; ?> news-list resinet-news-list">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title resinet-title title-with-menu">
                   <h2><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                    <ul>
                        <li><a href="/rozrywka/aktualnosci">Newsy</a></li>
                        <li><a href="/rozrywka/miejsca/kluby-i-puby">Kluby, puby</a></li>
                        <li><a href="/rozrywka/miejsca/galerie-i-muzea">Galerie, muzea</a></li>
                        <li><a href="/rozrywka/miejsca/teatry">Teatry</a></li>
                        <li><a href="/rozrywka/miejsca/instytucje-kultury">Instytucje kultury</a></li>
                        <li><a href="/rozrywka/miejsca/dla-dzieci">Dla dzieci</a></li>
                    </ul>
               </div>	
            <?php endif; ?>         
            <div class="list">
                <?php foreach($data['list'] as $news): ?>
                    <div class="news-item news-item-<?=$news['id']; ?>">
                        <div class="news-item-cont">
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
                                            <img src="/image/c/800/520/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($news['title']); ?>" class="trans400">
                                        </picture>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="info">
                                <p class="header"><?=!empty($news['header']) ? $news['header'] : lang('News.user.EntertainmentAndCulture'); ?></p>
                                <h3><a<?php if(!empty($news['link_redirect'])): ?> onclick="CountNews(<?=$news['id'];?>);"<?php endif; ?> href="<?php if(!$news['link_redirect']): ?>/<?php endif; ?><?=$news['link']; ?>" title="<?=esc($news['title']); ?>"<?php if(!empty($news['link_external'])): ?> target="_blank"<?php endif; ?>><?=$news['title']; ?></a></h3>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>