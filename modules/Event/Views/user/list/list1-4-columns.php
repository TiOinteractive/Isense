<?php
/* 
Najbliższe imprezy - 4 kolumny
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
            <div class="list list-1 columns-4">
                <?php foreach($data['list'] as $event): ?>
                    <div class="event-item event-item-<?=$event['id']; ?>">
                        <div class="event-item-cont">
                            <div class="photo">
                                <?php if(!empty($event['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                    <?php
                                        if(!empty($event['photo']['path']) && !empty($event['photo']['crop_dimension'])) {
                                            $event['photo']['crop_dimension']=json_decode($event['photo']['crop_dimension'], true); 
                                            $event['photo']['path'] = $event['photo']['crop_dimension']['width'] . '/' . $event['photo']['crop_dimension']['height'] . '/' . $event['photo']['crop_dimension']['x'] . '/' . $event['photo']['crop_dimension']['y'] . '/' . $event['photo']['path'];
                                        }
                                    ?>
                                    <a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>">
                                        <picture>
                                            <img src="/image/c/600/400/<?=!empty($event['photo']['path']) ? $event['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($event['name']); ?>" class="trans400" />
                                        </picture>
                                    </a>
                                        <?php if(!empty($event['type_name'])): ?>
                                            <div class="type">
                                                <a href="/<?= $global_links['calendar'] . '/g/t/' . $event['type_slug']; ?>" title="<?=esc($event['type_name']); ?>"><?=$event['type_name']; ?></a>
                                            </div>
                                        <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="info">
                                <div class="date">
                                    <?php if(empty($event['date_end'])): ?>
                                        <strong><?=date('d', strtotime($event['date_start'])); ?></strong>
                                        <span><?=lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                    <?php elseif($event['date_start'] > date('Y-m-d')): ?>
                                        <strong><?=date('d', strtotime($event['date_start'])); ?></strong>
                                        <span><?=lang('Admin.months_short_names.' . date('F')); ?></span>
                                    <?php else: ?>
                                        <strong><?=date('d'); ?></strong>
                                        <span><?=lang('Admin.months_short_names.' . date('F')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3><a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></h3>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>