<?php
/* 
Ranking restauracji - sidebar
*/
?>
<section class="ranking">
  <?php if(!empty($title)): ?>
  <header>
    <h1><?php if(!empty($url)):?><a href="<?=$url;?>" title="<?=esc($title); ?>"><?php endif;?><?=$title;?><?php if(!empty($url)):?></a><?php endif;?></h1>
  </header>
 <?php endif;?>
 
 <div class="list">
     <?php if(!empty($data['ranking'])):?>
			<?php foreach($data['ranking'] as $k=>$rank):?>
              <div class="rank rate">
						<div class="flex">
						<h2><a href="/<?=$rank['link'];?>" title="<?=$rank['name'];?>"><span><?=$k+1;?>.</span> <?=$rank['name'];?></a></h2>
						<?php if(!empty($rank['rate'])):?><div class="rate"><span><?=number_format($rank['rate'], 1, '.', '');?></span></div><?php endif;?>
						</div>
						<div class="flex">
						<?php if(!empty($rank['category']['name'])):?><h3><a href="/<?=$rank['category']['link'];?>"><?=$rank['category']['name'];?></a></h3><?php endif;?>
						<?php if(!empty($rank['rate'])):?>
						<div class="rating">
							<div id="rate_lokal_<?=$rank['id'];?>" class="rate_lokal" data-rate="<?=number_format($rank['rate'], 1, '.', '');?>"></div>
						</div> 
						<?php endif;?>
					</div>
			  </div>
            <?php endforeach;?>
    <?php endif;?>
 </div>
 </section>