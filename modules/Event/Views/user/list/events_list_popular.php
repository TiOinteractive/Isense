<?php
/*
  Najblizsze imprezy po dniu 2
 */
?>
<?php if (!empty($data)): ?>
    <section class="section-<?= $id_cont; ?> closest-events-list events-list">
        <div class="container">
            <?php if (!empty($title)): ?> 
                <div class="title entertainment-title">
                    <h2 class="h-1"><?php if ($url): ?><a href="<?= $url; ?>" title="<?= esc($title); ?>"><?php endif; ?><?= $title; ?><?php if ($url): ?></a><?php endif; ?></h2>
                    <?php if (!empty($subtitle)): ?>
                        <h3 class="h-2"><?= $subtitle; ?></h3>
                    <?php endif; ?>
                </div>	
            <?php endif; ?>   
            <div class="column-row">
                <div class="col col-sidebar left">
                    <?= view('\Modules\Event\Views\user/list\_events_list_types.php') ?>
                </div>
                <div class="col col-content">

                    <?php
                        $today = date('Y-m-d');
                        $today_time = strtotime(date('Y-m-d'));
                        $selected_date = !empty($data['filters']['d']) ? $data['filters']['d'] : '';
                    ?>
                    <?= view('\Modules\Event\Views\user/list\_events_list_search.php', array('today_time' => $today_time)) ?>

                    
                    <?php if (!empty($data['list'])): ?>
                        <div class="list-2">
                            <?php foreach ($data['list'] as $date_key => $event): ?>
                                <div class="event-item event-item-<?= $event['id']; ?>">
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
                                                    <a href="/<?= $event['link']; ?>" title="<?= esc($event['name']); ?>">
                                                        <picture>
                                                            <img src="/image/c/600/400/<?=!empty($event['photo']['path']) ? $event['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?= esc($event['name']); ?>" class="trans400" />
                                                        </picture>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($event['patronage']): ?>
                                                    <div class="patronage"><?=lang('Users.user.Patronage'); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="info">
                                            <?php if (!empty($event['type_name'])): ?>
                                                <div class="type">
                                                    <a href="/<?= (!empty($global_links['calendar']) ? $global_links['calendar'] : '/') . '/g/t/' . $event['type_slug']; ?>" title="<?= esc($event['type_name']); ?>"><?= $event['type_name']; ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <h3 class="name"><a href="/<?= $event['link']; ?>" title="<?= esc($event['name']); ?>"><?= $event['name']; ?></a></h3>
                                            <div class="details">
                                                <div class="date">
                                                    <?php if (!empty($event['date'])): ?>
                                                        <div class="date-cont">
                                                            <?php if(empty($event['date_end'])): ?>
                                                                <strong><?=date('d', strtotime($event['date_start'])); ?></strong>
                                                                <span><?=lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                            <?php elseif(time()>=strtotime($event['date_start']) && time()<strtotime($event['date_end'] . ' + 1 day')): ?>
                                                                <strong><?=date('d'); ?></strong>
                                                                <span><?=lang('Admin.months_short_names.' . date('F')); ?></span>
                                                            <?php else: ?>
                                                                <strong><?=date('d', strtotime($event['date_start'])); ?></strong>
                                                                <span><?=lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="place">
                                                    <?php if (!empty($event['place']) || !empty($event['custom_place'])): ?>
                                                        <p class="label"><?=lang('Users.user.Place'); ?></p>
                                                        <p class="value">
                                                            <?php if (!empty($event['place']) && !empty($event['place_link'])): ?><a href="<?= $event['place_link']; ?>" title="<?= esc($event['place']); ?>"><?php endif; ?>
                                                            <?= !empty($event['place']) ? $event['place'] : $event['custom_place']; ?>
                                                            <?php if (!empty($event['place_link'])): ?></a><?php endif; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="hours">
                                                    <?php if (!empty($event['hours'])): ?>
                                                        <p class="label"><?=lang('Users.user.Hour'); ?></p>
                                                        <p class="value"><?php foreach($event['hours'] as $k=>$hour): ?><?php if($k): ?>, <?php endif; ?><span><?=$hour; ?></span><?php endforeach; ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="tickets">
                                                    <?php if (!empty($event['price'])): ?>
                                                        <p class="label"><?=lang('Users.user.Tickets'); ?></p>
                                                        <p class="value"><?=$event['price']; ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($event['for_kids'])): ?>
                                                <div class="for-kids">
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg> <span><?=lang('Users.user.ForKids'); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($data['pager'])): ?>
                            <?= $data['pager']->links('event-' . $id_cont, 'front_entertainment'); ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="no-results"><?=lang('Catalog.user.NoResults'); ?></p>
                    <?php endif; ?>
                </div>
            </div>



            
        </div> 	
    </section>
<?php endif; ?>