<?php
/* 
Restaurant list
*/
?>
<div id="restaurant_list">
	<?php foreach($restaurants as $restaurant):?>
        <article id="<?=$restaurant['id'];?>" class="restaurant<?php if(!empty($restaurant['awarded'])):?> awarded<?php endif;?>">
		    <div class="inside">
					 <figure>
						<a href="/<?=$restaurant['link'];?>" title="<?=$restaurant['name'];?>, <?=$restaurant['address'];?>, <?=$restaurant['city'];?>">
							<?php if(!empty($restaurant['photo']['path'])):?>
								<img src="/image/c/350/250/<?=$restaurant['photo']['path'];?>" alt="<?php if(!empty($restaurant['photo']['caption'])):?><?=esc($restaurant['photo']['caption']);?><?php else:?><?=esc($restaurant['name']);?><?php endif;?>">
							<?php else:?>
								<img src="/image/c/350/250/flavors/20231213/brak_zdjecia.jpg" alt="<?=esc($restaurant['name']);?>">
							<?php endif;?>
						</a>
						<?php if(!empty($restaurant['awarded'])):?>
						 <span class="ico_award">Wyróżniony</span>
						<?php endif;?>
					</figure>
					<div class="box">
						<div class="flex">
						    <div class="left"> 
								<div class="cat">
									<?php if(!empty($restaurant['category']['name'])):?><a href="/<?=$restaurant['category']['link'];?>" title="<?=esc($restaurant['category']['name']);?>"><?=$restaurant['category']['name'];?></a><?php endif;?>
								</div>
								<header>
									<h2><a href="/<?=$restaurant['link'];?>"><?=$restaurant['name'];?></a></h2>
								</header>	
								<div class="address">
								   <?php if(!empty($restaurant['address']) and substr($restaurant['address'],0,3)!='ul.' and substr($restaurant['address'],0,3)!='Al.'):?>ul.<?php endif;?> <?=$restaurant['address'];?>
								</div>
							</div>	
							<div class="rating">
								<?php if(!empty($restaurant['avg']['rating'])):?>
									<div class="rate"><span><?=number_format($restaurant['avg']['rating'], 1, '.', '');?></span>/5</div>
									<div id="rate_lokal" class="rate_lokal_single"><img alt="1" src="/assets/gfx/star-on-big.png" title=""></div>
								<?php endif;?>
							</div> 
					    </div>
					</div>
			</div>
		</article>
	<?php endforeach; ?>
</div>

