<?php
/* 
Gallery tpl
*/

?>
<section class="<?php if(!empty($data['id'])): ?>gallery-<?=$data['id']; ?><?php endif; ?> gallery-single">
<?php if(!empty($data['photo'])): ?>
<div id="gallery-top">
      <figure style="background-image:url('/image/c/1600/500/<?=$data['photo']['path']; ?>');">
	  <div class="maska"></div>
	</figure>
    <h1><?=$data['name']; ?></h1>  
</div>
<?php endif; ?>
<div class="inside-white">
  <div class="container">
      <?php if(!empty($data['content'])): ?>
            <div class="content"><?=$data['content']; ?></div>
      <?php endif; ?>
       <?php if(!empty($data['photos'])): ?>
	   <div class="photos">
                        <?php foreach($data['photos'] as $photo): ?>
                            <div class="photo">
                                <a href="/image/r/1920/1080/<?=$photo['path']; ?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['name']); ?>" rel="lightbox">
                                    <picture>
                                        <source srcset="/image/c/500/500/<?=$photo['path']; ?>" media="(max-width: 800px)">
                                        <img src="/image/c/800/800/<?=$photo['path']; ?>" alt="<?=esc($photo['caption'] ? $photo['caption']: $data['name']); ?>" />
                                    </picture>
                                </a>
                            </div>
                        <?php endforeach; ?>
		</div>				
       <?php endif; ?>
  </div>
<svg id="realizacja_hex_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path></svg>
<svg id="realizacja_hex_2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path></svg>
<svg id="realizacja_hex_3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path></svg>
<svg id="realizacja_hex_4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path></svg>
<svg id="realizacja_hex_5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path></svg>
  
 <?php
 if(!empty($data['next']) or !empty($data['prev'])):?>
  <div class="gallery-nextprev">
	   <div class="aleft"><?php if(!empty($data['prev'])):?><a href="/<?=$data['prev']['link'];?>"> <svg xmlns="http://www.w3.org/2000/svg" class="hex" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path></svg><svg class="back_arrow" viewBox="0 0 512 512"><path d="M213.7,256L213.7,256L213.7,256L380.9,81.9c4.2-4.3,4.1-11.4-0.2-15.8l-29.9-30.6c-4.3-4.4-11.3-4.5-15.5-0.2L131.1,247.9  c-2.2,2.2-3.2,5.2-3,8.1c-0.1,3,0.9,5.9,3,8.1l204.2,212.7c4.2,4.3,11.2,4.2,15.5-0.2l29.9-30.6c4.3-4.4,4.4-11.5,0.2-15.8  L213.7,256z"></path></svg> <?=$data['prev']['name'];?></a><?php endif;?></div>
	 <div class="aright"><?php if(!empty($data['next'])):?><a  href="/<?=$data['next']['link'];?>"><?=$data['next']['name'];?> <svg xmlns="http://www.w3.org/2000/svg" class="hex" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path></svg><svg class="next_arrow" viewBox="0 0 512 512"><path d="M298.3,256L298.3,256L298.3,256L131.1,81.9c-4.2-4.3-4.1-11.4,0.2-15.8l29.9-30.6c4.3-4.4,11.3-4.5,15.5-0.2l204.2,212.7  c2.2,2.2,3.2,5.2,3,8.1c0.1,3-0.9,5.9-3,8.1L176.7,476.8c-4.2,4.3-11.2,4.2-15.5-0.2L131.3,446c-4.3-4.4-4.4-11.5-0.2-15.8  L298.3,256z"></path></svg></a><?php endif;?></div>
  </div>
 <?php endif; ?> 
</div>
</section>