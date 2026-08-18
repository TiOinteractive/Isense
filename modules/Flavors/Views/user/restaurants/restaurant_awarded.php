<?php
/* 
Restauracje wyróżnione home
*/
?>
<?php if(!empty($data['list'])):?>
<section class="section-<?=$id_cont; ?> flavor-home-awarded">
        <?php if(!empty($title)): ?>
            <h2 class="title"><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
	<div class="list">
       <?php foreach($data['list'] as $place):?>
          <div class="item">
		    <div class="inside">
					 <figure>
						<a href="/<?=$place['link'];?>" title="<?=$place['name'];?>, <?=$place['address'];?>, <?=$place['city'];?>">
							<?php if(!empty($place['photo']['path'])):?>
								<img src="/image/c/350/200/<?=$place['photo']['path'];?>" alt="<?php if(!empty($place['photo']['caption'])):?><?=esc($place['photo']['caption']);?><?php else:?><?=esc($place['name']);?><?php endif;?>">
							<?php else:?>
								<img src="/image/c/350/200/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($place['name']);?>">
							<?php endif;?>
						</a>
						 <span class="ico_award">Wyróżniony</span>
					</figure>
					<div class="flex">
					<div class="left">
					
						<div class="cat">
							<?php if(!empty($place['category']['name'])):?><a href="/<?=$place['category']['link'];?>" title="<?=esc($place['category']['name']);?>"><?=$place['category']['name'];?></a><?php endif;?>
						</div>
					<header>
						<h2><a href="/<?=$place['link'];?>"><?=$place['name'];?></a></h2>
					</header>	
					  <div class="address">
					   <?php if(!empty($place['address'])):?><?php if(!empty($place['address']) and substr($place['address'],0,3)!='ul.' and substr($place['address'],0,3)!='Al.'):?>ul.<?php endif;?> <?=$place['address'];?><?php else:?><?=$place['city'];?><?php endif;?>
					  </div>
					</div>
					<div class="rating">
						<?php if(!empty($place['avg']['rating'])):?>
						<div class="rate"><span><?=number_format($place['avg']['rating'], 1, '.', '');?></span>/5</div>
						<div id="rate_lokal_<?=$place['id'];?>" class="rate_lokal_single"><img alt="1" src="/assets/gfx/star-on-big.png" title=""></div>
						<?php endif;?>
					</div> 
					</div>
			</div>
          </div>
       <?php endforeach;?>
    </div>
</section>		
<?php endif;?>