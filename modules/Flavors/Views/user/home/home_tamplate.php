<?php
/* 
RESinet - strona główna
*/
?>
<section id="home-smaki">
 <div class="container">
  <div class="title resinet-title"><h2><?php if(!empty($url)):?><a href="<?=$url;?>"><?php endif; ?><?=$title;?><?php if(!empty($url)):?></a><?php endif; ?></h2>
    <ul>	
            <li> 
                <a href="/rzeszowskie-smaki/lokale" title="Lokale">Lokale</a>	
            </li>
        		
            <li> 
                <a href="/rzeszowskie-smaki/kuchnie" title="Kuchnie">Kuchnie</a>	
            </li>
        	<?php /*	
            <li> 
                <a href="/rzeszowskie-smaki/ranking-lokali" title="Ranking lokali">Ranking lokali</a>	
            </li>
        	*/ ?>	
            <li> 
                <a href="/rzeszowskie-smaki/mapa-lokali" title="Mapa lokali">Mapa lokali</a>	
            </li>
   </ul>
  </div>
  <div class="column-flex">
    <div class="left">
         <?php if(!empty($data) && !empty($data['list'])): ?>
           <div class="list">
					    <?php foreach($data['list'] as $k=>$news): ?>
					      <?php if($k==0):?>
						  <div class="news-item news-item-<?=$news['id']; ?> news-big">
						    <div class="photo">
										<div class="photo-cont">
											<?php if($news['photo']): ?>
												<a href="<?=$news['link']; ?>" title="<?=esc($news['title']); ?>">
													<picture>
														<source srcset="/image/c/400/260/<?=$news['photo']; ?>" media="(max-width: 800px)">
														<img src="/image/c/900/560/<?=$news['photo']; ?>" alt="<?=esc($news['title']); ?>" class="trans400">
													</picture>
												</a>
											<?php endif; ?>
										</div>
								<div class="box">
								  <h3><a href="/<?=$news['link'];?>" title="<?=esc($news['title']);?>">Rzeszowskie smaki</a></h3>
								  <h2><a href="/<?=$news['link'];?>" title="<?=esc($news['title']);?>"><?=$news['title'];?></a></h2>
								</div>		
							</div>
						  </div>
						  <?php else: ?>
								 <div class="news-item news-item-<?=$news['id']; ?>">
								<div class="news-item-cont">
									<div class="photo">
										<div class="photo-cont">
											<?php if($news['photo']): ?>
												<a href="<?=$news['link']; ?>" title="<?=esc($news['title']); ?>">
													<picture>
														<source srcset="/image/c/400/260/<?=$news['photo']; ?>" media="(max-width: 800px)">
														<img src="/image/c/600/400/<?=$news['photo']; ?>" alt="<?=esc($news['title']); ?>" class="trans400">
													</picture>
												</a>
											<?php endif; ?>
										</div>
										<div class="box">
										  <h3><a href="/<?=$news['link'];?>" title="<?=esc($news['title']);?>">Rzeszowskie smaki</a></h3>
										  <h2><a href="/<?=$news['link'];?>" title="<?=esc($news['title']);?>"><?=$news['title'];?></a></h2>
										</div>	
									</div>
								</div>
							</div>
					      <?php endif;?>
				        <?php endforeach; ?>
                </div>
         <?php endif; ?>
    </div>
	<div class="right">
      <?php if(!empty($data['config']['menu'])): ?>
	  <?=view_cell('\App\Libraries\Page::showMenu', ['id_menu' => $data['config']['menu'], 'id_lang' => $id_lang, 'locale' => $locale, 'template' => 'gastro-home-resinet', 'submenu_levels' => 1, 'options' => ['mode' => 'external_active_submenu']]); ?>
	  <?php endif; ?>
    </div>
  </div>
  </div>
</section>  