<?php
/* 
RESinet - Najchętniej czytane
*/

if(!empty($data['list'])):
?>
	<div id="home-mostread">
		<h2><?php if(!empty($url)):?><a href="<?=$url;?>"><?php endif; ?><?=$title;?><?php if(!empty($url)): ?></a><?php endif; ?></h2>
		<div class="list-news">
		  <?php foreach($data['list'] as $k=>$item):?>
			 <div class="item">
			   <div class="cnt"><?=$k+1;?></div>
			   <div class="box">
				 <h3><a href="/<?=$item['link'];?>" title="<?=esc($item['title']);?>"><?=$item['title'];?></a></h3>
			   </div>
			 </div>
		  <?php endforeach; ?>
		</div>	
	</div>
<?php endif;?>
