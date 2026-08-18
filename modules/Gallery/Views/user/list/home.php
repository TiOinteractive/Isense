<?php
/* 
Home list slider
*/
if(!empty($data) && !empty($data['list'])): ?>
<section class="section-<?=$id_cont; ?> home-gallery-slider">
<?php if(!empty($title)): ?>
          <div class="title">
		    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
			</svg>	
            <h2><a href="<?= view_cell('\App\Libraries\Link::getLinkByPageId', 'id_page=17, id_lang='.$id_lang) ?>"><?=$title; ?> <?php if(!empty($subtitle)): ?><br /><span class="trans400"><?=$subtitle; ?></span><?php endif; ?></a></h2>
		  </div>	
<?php endif; ?>
 
<div class="gallery-list">
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
			 <div class="controls">	 
				 <a class="prev carousel-control" href="#">
				  <svg class="hex" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
				</svg>
			      <svg class="back_arrow" viewBox="0 0 512 512"><path d="M213.7,256L213.7,256L213.7,256L380.9,81.9c4.2-4.3,4.1-11.4-0.2-15.8l-29.9-30.6c-4.3-4.4-11.3-4.5-15.5-0.2L131.1,247.9  c-2.2,2.2-3.2,5.2-3,8.1c-0.1,3,0.9,5.9,3,8.1l204.2,212.7c4.2,4.3,11.2,4.2,15.5-0.2l29.9-30.6c4.3-4.4,4.4-11.5,0.2-15.8  L213.7,256z"></path></svg>
				 </a>
				 <a class="next carousel-control" href="#">
				 <svg class="hex" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
				</svg>	
				<svg class="next_arrow" viewBox="0 0 512 512"><path d="M298.3,256L298.3,256L298.3,256L131.1,81.9c-4.2-4.3-4.1-11.4,0.2-15.8l29.9-30.6c4.3-4.4,11.3-4.5,15.5-0.2l204.2,212.7  c2.2,2.2,3.2,5.2,3,8.1c0.1,3-0.9,5.9-3,8.1L176.7,476.8c-4.2,4.3-11.2,4.2-15.5-0.2L131.3,446c-4.3-4.4-4.4-11.5-0.2-15.8  L298.3,256z"></path></svg>
				 </a>
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
</div>
</section>
<?php endif; ?>