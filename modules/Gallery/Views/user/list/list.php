<?php
/* 
Gallery list
*/
?>
<section class="section-<?=$id_cont; ?> gallery-list-page">
      <?php if(!empty($title)): ?>
            <div class="container"> <h2><?=$title; ?></h2></div>
        <?php endif; ?>
	<?php if(!empty($data) && !empty($data['list'])): ?>
  
  
   <?php foreach($data['list'] as $gallery): ?>
  
  <div class="gallery-item gallery-item-<?=$gallery['id']; ?>">
		    <a href="/<?=$gallery['link']; ?>" title="<?=esc($gallery['name']);?>" class="link"></a>
		     <div class="photo">
                            <picture>
                                <source srcset="/image/c/800/360/<?=$gallery['photo']; ?>" media="(max-width: 800px)">
                                <img src="/image/c/1600/500/<?=$gallery['photo']; ?>" alt="<?=esc($gallery['name']); ?>" />
								<div class="mask trans400"></div>
                            </picture>
             </div>
			<div class="info">
               <h3><?= lang('User.other.client'); ?></h3>
			   <h2><?=$gallery['name']; ?></h2>
             </div>
	<svg xmlns="http://www.w3.org/2000/svg" class="hex_orange" viewBox="0 0 16 16">
		<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
	</svg> 
	
	 <svg xmlns="http://www.w3.org/2000/svg" class="hex_big" viewBox="0 0 16 16">
		<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
	</svg> 				
	 <svg xmlns="http://www.w3.org/2000/svg" class="hex_small" viewBox="0 0 16 16">
		<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
	</svg> 	 
	 <?php if(!empty($gallery['introduction'])): ?><div class="info2"><h3><?=$gallery['introduction']; ?> </h3></div><?php endif; ?> 		 
		 </div>
  
  
   <?php endforeach; ?>
  
		<?php if(!empty($data['pager'])): ?>
			<div class="container">  
			  <?=$data['pager']->links('gallery-' . $id_cont, 'front_full'); ?>
			</div>            
		<?php endif; ?>
    <?php endif; ?>

</section>