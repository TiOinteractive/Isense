<?php
/* 
Klienci Slider tpl
*/
$z=0;
?>
<section class="<?php if(!empty($id_cont)): ?>section-<?=$id_cont; ?><?php endif; ?> klienci-slider">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
		<?php if(!empty($data) && !empty($data['banners'])): ?>
		<div class="banner-slider">
		  <div class="list-banners">
		    <div class="banner-slide">
			 <div class="inside">
		     <?php foreach($data['banners'] as $banner): $z++; ?>
			   <div class="item">
			         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
					 </svg>
					 <?php if(!empty($banner['url'])): ?><a href="<?=$banner['url']; ?>" title="<?=esc($banner['caption']); ?>"><?php endif; ?>
                        <picture>
                            <?php if(!empty($banner['m_path'])): ?><source srcset="/image/<?=$banner['m_path']; ?>" media="(max-width: 800px)"><?php endif; ?>
                            <img src="/image/<?=$banner['path']; ?>" alt="<?=esc($banner['caption']); ?>" />
                        </picture>
                        <?php if(!empty($banner['url'])): ?></a><?php endif; ?>
				</div>		
		         <?php if(($z%6)==0 and $z<count($data['banners'])):?>
				   </div></div><div class="banner-slide"><div class="inside">
				 <?php endif; ?>
		     <?php endforeach; ?>
			 </div>
			  </div>
		  </div>
		  <div class="controls">	 
			<a class="prev carousel-control" href="#">
				<svg xmlns="http://www.w3.org/2000/svg" class="hex" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
				</svg>
				<svg class="back_arrow" viewBox="0 0 512 512"><path d="M213.7,256L213.7,256L213.7,256L380.9,81.9c4.2-4.3,4.1-11.4-0.2-15.8l-29.9-30.6c-4.3-4.4-11.3-4.5-15.5-0.2L131.1,247.9  c-2.2,2.2-3.2,5.2-3,8.1c-0.1,3,0.9,5.9,3,8.1l204.2,212.7c4.2,4.3,11.2,4.2,15.5-0.2l29.9-30.6c4.3-4.4,4.4-11.5,0.2-15.8  L213.7,256z"></path></svg>
			</a>
			<a class="next carousel-control" href="#">
				<svg xmlns="http://www.w3.org/2000/svg" class="hex" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"></path>
				</svg>	
				<svg class="next_arrow" viewBox="0 0 512 512"><path d="M298.3,256L298.3,256L298.3,256L131.1,81.9c-4.2-4.3-4.1-11.4,0.2-15.8l29.9-30.6c4.3-4.4,11.3-4.5,15.5-0.2l204.2,212.7  c2.2,2.2,3.2,5.2,3,8.1c0.1,3-0.9,5.9-3,8.1L176.7,476.8c-4.2,4.3-11.2,4.2-15.5-0.2L131.3,446c-4.3-4.4-4.4-11.5-0.2-15.8  L298.3,256z"></path></svg>
			</a>
		</div>
		</div>  
		<?php endif; ?>
    </div>
</section>