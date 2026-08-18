<?php
/* 
Lista miejsc - sidebar
 */
?>
<?php if(!empty($data)): ?>
    <section class="section-<?=$id_cont; ?> places-selected sidebar">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title entertainment-title">
                   <h2 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                    <?php if(!empty($subtitle)): ?>
                        <h3 class="h-2"><?=$subtitle; ?></h3>
                    <?php endif; ?>
               </div>	
            <?php endif; ?>         
            <?php if(!empty($data['places'])): ?>
                <div class="list">
                    <?php foreach($data['places'] as $place): ?>
                        <div class="place-item">
                            <h3><a href="/<?=$place['link']; ?>" title="<?=esc($place['name']); ?>"><?=$place['name']; ?></a></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div> 	
    </section>
<?php endif; ?>