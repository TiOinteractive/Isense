<?php
/* 
Instagram
*/

if(!empty($data['list'])):
?>
<section id="home-instagram">
 <div class="container">
  <div class="title resinet-title"><h2><?php if(!empty($url)):?><a href="<?=$url;?>" target="_blank"><?php endif;?><?=$title;?><?php if(!empty($url)):?></a><?php endif;?></h2></div>
    <div class="lists"> 
	  <?php foreach($data['list'] as $item):?>
	    <div class="item">
		  <figure><a href="<?=$item['link'];?>" target="_blank" title="<?=esc($item['caption']);?>"><img src="<?=$item['thumb'];?>" alt="<?=$item['caption'];?>"></a>
		  <?php /*
		  <div class="cnt trans400">
		    <i class="fa-solid fa-heart"></i> <?=$item['likes'];?> <i class="fa-solid fa-comment"></i> <?=$item['comments'];?>
		  </div>
		  */?>
		  </figure>
		</div>
	 <?php endforeach;?>
    </div>
  </div>
</section>  
<?php endif;?>