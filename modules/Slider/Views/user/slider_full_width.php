<?php
/* 
Slider pełna szerokość 
*/

function numberToRomanRepresentation($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}


?>
<section class="section-<?=$id_cont; ?> slider-<?=$data['id'];?>">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
    </div>
    <?php if(!empty($data) && !empty($data['slides'])): ?>
        <div class="slider-list">
            <?php foreach($data['slides'] as $slide): ?>
                <div class="item<?php if(!empty($slide['video_url']) || !empty($slide['video'])): ?> video-item<?php endif; ?>">
                    <?php if($slide['url']): ?><a href="<?=$slide['url']; ?>" title="<?=esc($slide['title']); ?>" class="link"><?php endif; ?><?php if($slide['url']): ?></a><?php endif; ?>
                    <?php if(!empty($slide['video'])): ?>
                        <video autoplay muted loop>
                            <source src="/video/<?=$slide['video']; ?>" type="<?=$slide['video_mime']; ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php elseif(!empty($slide['video_url'])): ?>
                        <?php if(!empty($slide['video_source'])): ?>
                            <div id="<?=$slide['video_source']; ?>-<?=$slide['external_id']; ?>" class="video-external <?=$slide['video_source']; ?> auto loop" data-id="<?=$slide['external_id']; ?>" data-url="<?=$slide['video_url']; ?>">
                            </div>
                        <?php else: ?>
                            <video autoplay muted loop>
                                <source src="<?=$slide['video_url']; ?>" type="<?=$slide['video_mime']; ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                    <?php endif; ?>
                    <picture>
                        <?php if(!empty($slide['mphoto'])): ?><source srcset="/image/c/800/400/<?=$slide['mphoto']; ?>" media="(max-width: 800px)"><?php endif; ?>
                        <img src="/image/c/1920/600/<?=$slide['photo']; ?>" alt="<?=esc($slide['title']); ?>" />
                    </picture>
					<div class="mask"></div>
                    <div class="caption">
                        <?php if(!empty($slide['title'])): ?><h3><?=$slide['title']; ?></h3><?php endif; ?>
                        <?php if(!empty($slide['caption'])): ?><h4><?=$slide['caption']; ?></h4><?php endif; ?>
                        <?php if(!empty($slide['description'])): ?><p><?=$slide['description']; ?></p><?php endif; ?>
						<div class="pasek_wielokat">
							<svg viewBox="0 0 242.4871130596428 280"><path d="M121.2435565298214 0L242.4871130596428 70L242.4871130596428 210L121.2435565298214 280L0 210L0 70Z"></path></svg>
						    <svg viewBox="0 0 242.4871130596428 280" class="orange"><path d="M121.2435565298214 0L242.4871130596428 70L242.4871130596428 210L121.2435565298214 280L0 210L0 70Z"></path></svg>
						</div>
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
                </div>
            <?php endforeach; ?>
        </div>
		<ol class="carousel-indicators"> 
			  <?php foreach($data['slides'] as $k=>$slide): ?>
				<li data-slide-to="<?=$k;?>" class="<?php if($k==0) { echo ' active';} ?> line">  
				<svg xmlns="http://www.w3.org/2000/svg"  class="hexagon_outside" viewBox="0 0 16 16">
					<path d="M14 4.577v6.846L8 15l-6-3.577V4.577L8 1l6 3.577zM8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"/>
				</svg> 
				<svg xmlns="http://www.w3.org/2000/svg"  class="hexagon_inside" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866L8.5.134z"/>
				</svg>		   
				   <span><?=numberToRomanRepresentation(($k+1));?></span>
				</li>
              <?php endforeach; ?> 
          </ol>
    <?php endif; ?>
</section>