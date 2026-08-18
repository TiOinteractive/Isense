<?php
/* 
Polecamy/Patronat - strona główna
 */
?>
<?php if(!empty($data) && !empty($data['list'])): ?>
    <section class="section-<?=$id_cont; ?> home-list recommended-patronage-list">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title">
                   <h2><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                    <?php if(!empty($subtitle)): ?>
                        <h3><?=$subtitle; ?></h3>
                    <?php endif; ?>
                    <?php if($url):?><a class="more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainemnt.SeeAll')); ?>"><?=lang('User.entertainemnt.SeeAll'); ?></a><?php endif; ?>
               </div>	
            <?php endif; ?>         
            <div class="list list-1">
                <?php foreach($data['list'] as $item): ?>
                    <div class="recommended-patronage-item item-<?=$item['type']; ?>-<?=$item['id']; ?>">
                        <div class="recommended-patronage-item-cont">
                            <div class="photo">
                                <div class="photo-cont">
                                    <?php if($item['photo']): ?>
                                        <a href="/<?=$item['link']; ?>" title="<?=esc($item['name']); ?>">
                                            <picture>
                                                <img src="/image/c/600/400/<?=$item['photo']; ?>" alt="<?=esc($item['name']); ?>" class="trans400" />
                                            </picture>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="info">
                                <?php if($item['patronage']): ?>
                                    <p class="label patronage"><?=lang('User.entertainment.Patronage'); ?></p>
                                <?php elseif($item['recommended']): ?>
                                    <p class="label recommended"><?=lang('User.entertainment.Recommended'); ?></p>
                                <?php endif; ?>
                                <h3><a href="/<?=$item['link']; ?>" title="<?=esc($item['name']); ?>"><?=$item['name']; ?></a></h3>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>