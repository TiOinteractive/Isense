<?php
/* 
Resinet - sidebar
*/
?>
<?php if(!empty($data) && !empty($data['list'])): ?>
    <section class="section-<?=$id_cont; ?> resinet-events-sidebar events-list sidebar">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title resinet-title-sidebar">
                   <h2 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                    <?php if(!empty($subtitle)): ?>
                        <h3 class="h-2"><?=$subtitle; ?></h3>
                    <?php endif; ?>
               </div>	
            <?php endif; ?>         
            <div class="list list-1">
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
                                                <img src="/image/c/600/400/<?=!empty($event['photo']['path']) ? $event['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($event['name']); ?>" class="trans400">
                                            </picture>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($event['patronage']): ?>
                                        <div class="patronage"><?= lang('Users.user.Patronage'); ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($event['for_kids'])): ?>
                                        <div class="for-kids">
                                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info">
                                <?php if(!empty($event['type_name'])): ?>
                                    <div class="type">
                                        <a href="<?=(!empty($global_links['calendar']) ? $global_links['calendar'] : '/') . '/g/t/' . $event['type_slug']; ?>" title="<?=esc($event['type_name']); ?>"><?=$event['type_name']; ?></a>
                                    </div>
                                <?php endif; ?>
                                <h3 class="name"><a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></h3>
                                <div class="date">
                                    <?php if (empty($event['date_end'])): ?>
                                        <?php if($event['date_start']==date("Y-m-d")):?>
                                        <strong><?=lang('Event.Today'); ?></strong>
                                        <?php elseif($event['date_start']==date("Y-m-d",strtotime('+1day'))): ?>
                                        <strong><?=lang('Event.Tomorrow'); ?></strong>
                                        <?php elseif($event['date_start']==date("Y-m-d",strtotime('+2day'))): ?>
                                        <strong><?=lang('Event.AfterTomorrow'); ?></strong>
                                        <?php else:?>
                                        <span><?= date("d.m.Y", strtotime($event['date_start'])); ?></span>
                                        <?php endif;?>	
                                    <?php else: ?>
                                        <?php $toti = strtotime(date('Y-m-d')); if(strtotime($event['date_start']) <= $toti && strtotime($event['date_end']) >= $toti): ?>
                                        <strong><?=lang('Event.Today'); ?></strong>
                                        <?php elseif(!empty($event['short_date'])): ?>
                                        <span><?=$event['short_date']; ?></span>
                                        <?php else: ?>
                                        <span><?= date("d.m.Y", strtotime($event['date_start'])); ?> - <?= date("d.m.Y", strtotime($event['date_end'])); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>