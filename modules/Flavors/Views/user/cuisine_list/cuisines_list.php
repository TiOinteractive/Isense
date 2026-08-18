<?php
/* 
Lista kuchni
*/
?>
<section class="cuisine_list">
  <?php if(!empty($title)): ?>
  <header>
    <h1><?=$title;?></h1>
  </header>
 <?php endif;?>
 <?php if(!empty($data['list'])):?>
   <div class="list">
      <?php foreach($data['list'] as $cuisine):
	   if($cuisine['count']>0):
	  ?>
	    <div class="cuisine">
		 <div class="inside">
		  <figure>
		    <?php if(!empty($cuisine['photo']['path'])):?>
			 <a href="/<?=$cuisine['link'];?>"><img src="/image/r/50/50/<?=$cuisine['photo']['path'];?>" alt="<?=$cuisine['name'];?>" /></a>
			<?php else: ?>
			 <a href="/<?=$cuisine['link'];?>" title="<?=$cuisine['name'];?>"><?=$cuisine['ico_svg'];?></a>
			<?php endif;?>
		  </figure>
		  <div class="box">
		    <h2><a href="/<?=$cuisine['link'];?>"><?=$cuisine['name'];?></a></h2>
			<p><?=word_limiter($cuisine['description'],20);?></p>
		  </div>
		 </div>
		</div>
	  <?php 
	  endif;
	  endforeach;?>
   </div>
 <?php endif;?>
 </section>