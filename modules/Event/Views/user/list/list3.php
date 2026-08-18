<?php
/* 
Najbliższe imprezy - strona główna - wersja 3
 */
?>
<?php if(!empty($data) && !empty($data['list'])): ?>
    <section class="section-<?=$id_cont; ?> home-list event-calendar-list">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title resinet-title">
                   <h2 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                    <?php if(!empty($subtitle)): ?>
                        <h3 class="h-2"><?=$subtitle; ?></h3>
                    <?php endif; ?>
                    <?php if($url):?><a class="more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainment.SeeAll')); ?>"><?=lang('User.entertainment.SeeAll'); ?></a><?php endif; ?>
               </div>	
            <?php endif; ?>         
            <div class="list list-3">
                <?php foreach($data['list'] as $event): ?>
                    <div class="event-item event-item-<?=$event['id']; ?>">
                        <div class="event-item-cont">
                            <div class="photo">
                                <div class="photo-cont">
                                    <?php if(!empty($event['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
					<?php
                                            if(!empty($event['photo']['path']) && !empty($event['photo']['crop_dimension'])) {
                                                $event['photo']['crop_dimension']=json_decode($event['photo']['crop_dimension'], true); 
                                                $event['photo']['path'] = $event['photo']['crop_dimension']['width'] . '/' . $event['photo']['crop_dimension']['height'] . '/' . $event['photo']['crop_dimension']['x'] . '/' . $event['photo']['crop_dimension']['y'] . '/' . $event['photo']['path'];
                                            }
                                        ?>
                                        <a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>">
                                            <picture>
                                                <img src="/image/c/400/400/<?=!empty($event['photo']['path']) ? $event['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($event['name']); ?>" class="trans400" />
                                            </picture>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info">
                                <div class="date">
                                    <?php if(empty($event['date_end'])): ?>
                                        <?=date('d.m.Y', strtotime($event['date_start'])); ?>
                                    <?php else: ?>
                                        <?=date('d.m.Y', strtotime($event['date_start'])); ?> - <?=date('d.m.Y', strtotime($event['date_end'])); ?>
                                    <?php endif; ?>
                                </div>
                                <h3><a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></h3>
                                <h4><?=!empty($event['place']) ? $event['place'] : $event['custom_place']; ?></h4>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>