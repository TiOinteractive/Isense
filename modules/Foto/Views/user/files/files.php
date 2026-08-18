<?php
/* 
Lista zdjęć
*/


if(!empty($data) && !empty($data['list'])): ?>
<section class="section-<?=$id_cont; ?> home-photos-list photos-list">
    <div class="container">
    	<?php if(!empty($title)): ?> 
	 <div class="title">
            <h2><?=$title; ?></h2>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
	</div>	
    <?php endif; ?>         
 <div class="list">
                <?php foreach($data['list'] as $gal): ?>
                <div class="photo-item item-<?=$gal['id']; ?>">
                    <div class="photo">
                        <?php if($gal['photo']): ?>
                                <picture>
                                    <a href="/<?=$gal['link']; ?>" title="<?=esc($gal['name']); ?>"> 								
										<source srcset="/image/c/400/400/<?=$gal['photo']; ?>" media="(max-width: 800px)">
										<img src="/image/c/350/350/<?=$gal['photo']; ?>" alt="<?=esc($gal['name']); ?>" class="trans400" />
									</a>	
                                </picture>
								<div class="info">
									<?php if(!empty($gal['name'])):?><h3><a href="/<?=$gal['link']; ?>" title="<?=esc($gal['name']); ?>"><?=$gal['name']; ?></a></h3><?php endif;?>
								</div>
						<?php endif; ?>		
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if(!empty($data['pager'])): ?>
                <?=$data['pager']->links('gallery-' . $id_cont, 'front_full'); ?>
            <?php endif; ?>
</section>
<?php endif; ?>