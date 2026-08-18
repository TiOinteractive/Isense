<?php
/*
  Event tpl
 */
?>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 11, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<section class="event-single event-<?= $data['id']; ?>">
    <div class="container">
        <div class="entertainment-row">
            <div class="col col-content">
                <div class="title resinet-title">
                    <h2 class="h-1"><?= lang('User.entertainment.EventsAndNewsInRzeszow'); ?></h2>
                </div>
                <div class="event-header">
                    <?php if (!empty($data['photo'])): ?>

                        <?php
                        
                        $data['photo_method'] = 'c';
                        $data['photo_big'] = $data['photo'];
                        /*
                        if (!empty($data['crop_dimension'])) {
                            $data['crop_dimension'] = json_decode($data['crop_dimension']);
                            $data['photo'] = $data['crop_dimension']->width . '/' . $data['crop_dimension']->height . '/' . $data['crop_dimension']->x . '/' . $data['crop_dimension']->y . '/' . $data['photo'];
                            $data['photo_method'] = 'r';
                        }
                        */
                        ?>

                        <div class="photo">
                            <div class="photo-cont">
                                <a href="/image/r/1920/1080/<?= $data['photo_big']; ?>"  title="<?= esc($data['name']); ?>" rel="lightbox">
                                    <picture>
                                        <source srcset="/image/<?= $data['photo_method']; ?>/800/680/<?= $data['photo']; ?>" media="(max-width: 800px)">
                                        <img src="/image/<?= $data['photo_method']; ?>/900/570/<?= $data['photo']; ?>" alt="<?= esc($data['name']); ?>" />
                                    </picture>
                                </a>
                                <?php if (!empty($data['type_name'])): ?>
                                    <div class="type">
                                        <a href="/rozrywka/kalendarium/g/t/<?= $data['slug']; ?>" title="<?= $data['type_name']; ?>"><?php if (!empty($data['svg'])): ?><?= $data['svg']; ?><?php endif; ?><span><?= $data['type_name']; ?></span></a>
                                    </div>
                                <?php endif; ?>
                                <div class="caption">
                                    <?php if (!empty($data['calendar'][0]['date_start'])): ?>
                                        <div class="date">
                                            <?php if (empty($data['calendar'][0]['date_end'])): ?>
                                                <strong><?= date('d', strtotime($data['calendar'][0]['date_start'])); ?></strong>
                                                <span><?= lang('Admin.months_short_names.' . date('F', strtotime($data['calendar'][0]['date_start']))); ?></span>
                                            <?php elseif (time() >= strtotime($data['calendar'][0]['date_start']) && time() < strtotime($data['calendar'][0]['date_end'] . ' + 1 day')): ?>
                                                <strong><?= date('d'); ?></strong>
                                                <span><?= lang('Admin.months_short_names.' . date('F')); ?></span>
                                            <?php else: ?>
                                                <strong><?= date('d', strtotime($data['calendar'][0]['date_start'])); ?></strong>
                                                <span><?= lang('Admin.months_short_names.' . date('F', strtotime($data['calendar'][0]['date_start']))); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <h1 class="name"><?= $data['name']; ?></h1>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($data['photos'])): ?>
                        <div class="thumbnails-box">
                            <?php foreach($data['photos'] as $p=>$photo): ?>
                                <?php if($p < 3): ?>
                                    <div class="thumbnail">
                                        <a href="/image/r/1920/1080/<?=$photo['path']; ?>"  data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['name']); ?>" rel="lightbox">
                                            <picture>
                                                <img src="/image/c/460/300/<?=$photo['path']; ?>" alt="<?=esc($photo['caption']); ?>" />
                                            </picture>
                                            <?php if($p == 2 && count($data['photos']) > 3): ?>
                                                <span class="plus"><strong>+<?=count($data['photos']) - 3; ?></strong></span>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <a href="/image/r/1920/1080/<?=$photo['path']; ?>"  data-thumb="/image/c/120/70/<?=$photo['path'];?>" title="<?=esc($photo['caption'] ? $photo['caption']: $data['name']); ?>" rel="lightbox"></a>
                                <?php endif;  ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="event_column">
                    <div class="info">
                        <div class="item">
                            <label><?= lang('Event.user.Place'); ?></label>
                            <div>
                                <?php $where = array();
                                foreach ($data['calendar'] as $k => $calendar): ?>
                                    <?php
                                    $place = '';
                                    $place_link = '';
                                    if (!empty($calendar['place']) && !empty($calendar['place']['name'])) {
                                        $place = $calendar['place']['name'];
                                        if (!empty($calendar['place']['link'])) {
                                            $place_link = $calendar['place']['link'];
                                        }
                                    } elseif (!empty($calendar['custom_place'])) {
                                        $place = $calendar['custom_place'];
                                    }
                                    ?>
                                    <?php if (!empty($place) && !in_array($place, $where)): ?>
                                        <?php if (!empty($place_link)): ?><a href="/<?= $place_link; ?>"><?php endif; ?><?php if (!empty($where)): ?><br /><?php endif; ?><?= $place; ?><?php if (!empty($place_link)): ?></a><?php endif; ?>
                                        <?php $where[] = $place;
                                    endif; ?>
                        <?php endforeach; ?>
                            </div>
                        </div>
<?php if (!empty($data['calendar']) and count($data['calendar']) == 1): ?>
                            <div class="item date">
                                <label><?= lang('Event.user.Date'); ?></label>
                                <div>
                                    <?php if (empty($data['calendar'][0]['date_end'])): ?><?= lang('Event.days_names_no.' . date('N', strtotime($data['calendar'][0]['date_start']))); ?>, <?= date('d.m.Y', strtotime($data['calendar'][0]['date_start'])); ?>
                                    <?php elseif (!empty($data['calendar'][0]['date_end']) and!empty($data['calendar'][0]['date_start'])): ?>
                                        <?= lang('Event.days_names_no.' . date('N', strtotime($data['calendar'][0]['date_start']))); ?>, <?= date('d.m.Y', strtotime($data['calendar'][0]['date_start'])); ?>
                                        - <?= lang('Event.days_names_no.' . date('N', strtotime($data['calendar'][0]['date_end']))); ?>, <?= date('d.m.Y', strtotime($data['calendar'][0]['date_end'])); ?>
                            <?php endif; ?>
                                </div>
                            </div>	
    <?php if (!empty($data['calendar'][0]['hours'])): ?>					  
                                <div class="item">
                                    <label><?= lang('Event.user.Hour'); ?></label>
                                    <div>
                                        <?php foreach ($data['calendar'][0]['hours'] as $k => $hour): ?>
            <?php if ($k > 0): ?>, <?php endif; ?><?= $hour; ?>
                                <?php endforeach; ?>
                                    </div>
                                </div>
    <?php endif; ?>
<?php elseif (!empty($data['calendar']) && count($data['calendar']) > 1): ?>
                            <div class="item date">
                                <label><?= lang('Event.user.Date'); ?></label>
                                <div>
                                    <?php foreach ($data['calendar'] as $k => $cal): ?>
                                        <?php if ($k > 0): ?><br /><?php endif; ?>
                                        <?php if (empty($cal['date_end'])): ?><?= lang('Event.days_names_no.' . date('N', strtotime($cal['date_start']))); ?>, <?= $cal['date']; ?>
                                        <?php elseif (!empty($cal['date_end']) and!empty($cal['date_start'])): ?>
                                            <?= lang('Event.days_names_no.' . date('N', strtotime($cal['date_start']))); ?>, <?= date('d.m.Y', strtotime($cal['date_start'])); ?>
                                            - <?= lang('Event.days_names_no.' . date('N', strtotime($cal['date_end']))); ?>, <?= date('d.m.Y', strtotime($cal['date_end'])); ?>
        <?php endif; ?>
                            <?php endforeach; ?>
                                </div>
                            </div> 
<?php endif; ?>					  
<?php if (!empty($data['price'])): ?>
                            <div class="item">
                                <label><?= lang('Event.Price'); ?></label>
                                <div>
                            <?= $data['price']; ?>
                                </div>
                            </div>
<?php endif; ?>
<?php if (!empty($data['tickets'])): ?>
                            <div class="item">
                                <label><?= lang('Event.Tickets'); ?></label>
                                <div>
                                <?php if(!empty($data['source']) && $data['source'] == 'kupbilecik'): ?>
                                    <?= preg_replace('"\b(https?://\S+)"', '<a class="external-btn" href="$1" title="' . lang('Event.BuyTicket') . '" target="_blank" rel="nofollow">' . lang('Event.BuyTicket') . '</a>', $data['tickets']); ?>
                                <?php else: ?>
                                    <?= preg_replace('"\b(https?://\S+)"', '<a href="$1" title="' . lang('Event.Tickets') . '" target="_blank" rel="nofollow">$1</a>', $data['tickets']); ?>
                                <?php endif; ?>
                                </div>
                            </div>
<?php endif; ?>
<?php if (!empty($data['comments'])): ?>
                            <div class="item">
                                <label><?= lang('Event.user.Comments'); ?></label>
                                <div>
                            <?= $data['comments']; ?>
                                </div>
                            </div>	   
                    <?php endif; ?>
                    </div>
                    <?php if (!empty($data['content'])): ?>
                        <div class="content"><?= $data['content']; ?></div>
                <?php endif; ?>
                </div>
                <?php if (!empty($data['comment'])): ?>
                    <?= view_cell('\Modules\Comments\Libraries\Comments::showCommentsForm', ['id_lang' => $id_lang, 'locale' => $locale, 'id_link' => $data['id_link']]); ?>
<?php endif; ?>
<?php if (!empty($data['tags'])): ?>
                    <div class="tags">
                        <div class="title">
                            <h3><?= lang('User.other.Tags'); ?></h3>
                        </div>
                        <?php foreach ($data['tags'] as $tag): ?>
                            <div class="tag"><a href="/<?= !empty($global_links['search_tags']) ? $global_links['search_tags'] : ''; ?>/g/t/<?= $tag['tag']; ?>" title="<?= esc($tag['tag']); ?>"><?= $tag['tag']; ?></a></div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                if (!empty($data['place_events'])):
                    $today = date('Y-m-d');
                    $today_time = strtotime(date('Y-m-d'));
                    if (!empty($calendar['place']) && !empty($calendar['place']['name'])) {
                        $place = $calendar['place']['name'];
                    } elseif (!empty($calendar['custom_place'])) {
                        $place = $calendar['custom_place'];
                    }
                    ?>
                    <div class="closest-events-list">
                        <div class="title medium-resinet-title">
                            <h2 class="h-2"><?= lang('User.entertainment.OtherEventsIn'); ?> <?= !empty($place) ? $place : ''; ?></h2>
                        </div>
                        <div class="list-2">
                            <?php foreach ($data['place_events'] as $date_key => $day): ?>
        <?php if (!empty($day)): ?>
            <?php foreach ($day as $event): ?>
                                        <div class="event-item event-item-<?= $event['id']; ?>">
                                            <div class="event-item-cont">
                                                <div class="photo">
                                                    <div class="photo-cont">
                                                        <?php if (!empty($event['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                            <?php
                                                            if (!empty($event['photo']['path']) && !empty($event['photo']['crop_dimension'])) {
                                                                $event['photo']['crop_dimension'] = json_decode($event['photo']['crop_dimension'], true);
                                                                $event['photo']['path'] = $event['photo']['crop_dimension']['width'] . '/' . $event['photo']['crop_dimension']['height'] . '/' . $event['photo']['crop_dimension']['x'] . '/' . $event['photo']['crop_dimension']['y'] . '/' . $event['photo']['path'];
                                                            }
                                                            ?>
                                                            <a href="/<?= $event['link']; ?>" title="<?= esc($event['name']); ?>">
                                                                <picture>
                                                                    <img src="/image/c/600/400/<?= !empty($event['photo']['path']) ? $event['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?= esc($event['name']); ?>" class="trans400" />
                                                                </picture>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($event['patronage']): ?>
                                                            <div class="patronage"><?= lang('Users.user.Patronage'); ?></div>
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
                                                                <?php if (!empty($event['date']) and empty($mobile)): ?>
                                                            <div class="date">
                                                                <div class="date-cont">
                                                                    <?php if (empty($event['date_end'])): ?>
                                                                        <strong><?= date('d', strtotime($event['date_start'])); ?></strong>
                                                                        <span><?= lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                                    <?php elseif (time() >= strtotime($event['date_start']) && time() < strtotime($event['date_end'] . ' + 1 day')): ?>
                                                                        <strong><?= date('d'); ?></strong>
                                                                        <span><?= lang('Admin.months_short_names.' . date('F')); ?></span>
                                                                    <?php else: ?>
                                                                        <strong><?= date('d', strtotime($event['date_start'])); ?></strong>
                                                                        <span><?= lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                            <?php endif; ?>
                                                                </div>
                                                            </div>	
                                                            <?php endif; ?>
                                                        <div class="place">
                                                                <?php if (!empty($event['place']) || !empty($event['custom_place'])): ?>
                                                                <p class="label"><?= lang('Users.user.Place'); ?></p>
                                                                <p class="value">
                                                                        <?php if (!empty($event['place']) && !empty($event['place_link'])): ?><a href="/<?= $event['place_link']; ?>" title="<?= esc($event['place']); ?>"><?php endif; ?>
                                                                    <?= !empty($event['place']) ? $event['place'] : $event['custom_place']; ?>
                                                                    <?php if (!empty($event['place_link'])): ?></a><?php endif; ?>
                                                                    </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="hours">
                                                            <?php if (!empty($event['hours'])): ?>
                                                                <p class="label"><?= lang('Users.user.Hour'); ?></p>
                                                                <p class="value"><?php foreach ($event['hours'] as $k => $hour): ?><?php if ($k): ?>, <?php endif; ?><span><?= $hour; ?></span><?php endforeach; ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="tickets">
                                                            <?php if (!empty($event['price'])): ?>
                                                                <p class="label"><?= lang('Users.user.Tickets'); ?></p>
                                                                <p class="value"><?= $event['price']; ?></p>
                                                    <?php endif; ?>
                                                        </div>  
                                                    </div>
                <?php if (!empty($event['for_kids'])): ?>
                                                        <div class="for-kids">
                                                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg> <span><?= lang('Users.user.ForKids'); ?></span>
                                                        </div>
                <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
        <?php endif; ?>
                    <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php
                if (!empty($data['type_events'])):
                    $today = date('Y-m-d');
                    $today_time = strtotime(date('Y-m-d'));
                    ?>
                    <div class="closest-events-list">
                        <h2 class="title">Pozostałe wydarzenia z kategorii: <?= $data['type_name']; ?></h2>
                        <div class="list-2">
                            <?php foreach ($data['type_events'] as $date_key => $day): ?>
        <?php if (!empty($day)): ?>

            <?php foreach ($day as $event): ?>
                                        <div class="event-item event-item-<?= $event['id']; ?>">
                                            <div class="event-item-cont">
                                                <div class="photo">
                                                    <div class="photo-cont">
                                                        <?php if (!empty($event['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                            <?php
                                                            if (!empty($event['photo']['path']) && !empty($event['photo']['crop_dimension'])) {
                                                                $event['photo']['crop_dimension'] = json_decode($event['photo']['crop_dimension'], true);
                                                                $event['photo']['path'] = $event['photo']['crop_dimension']['width'] . '/' . $event['photo']['crop_dimension']['height'] . '/' . $event['photo']['crop_dimension']['x'] . '/' . $event['photo']['crop_dimension']['y'] . '/' . $event['photo']['path'];
                                                            }
                                                            ?>
                                                            <a href="/<?= $event['link']; ?>" title="<?= esc($event['name']); ?>">
                                                                <picture>
                                                                    <img src="/image/c/600/400/<?= !empty($event['photo']['path']) ? $event['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?= esc($event['name']); ?>" class="trans400" />
                                                                </picture>
                                                            </a>
                                                        <?php endif; ?>
                <?php if ($event['patronage']): ?>
                                                            <div class="patronage"><?= lang('Users.user.Patronage'); ?></div>
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
                                                                <?php if (!empty($event['date']) and empty($mobile)): ?>
                                                            <div class="date">
                                                                <div class="date-cont">
                                                                    <?php if (empty($event['date_end'])): ?>
                                                                        <strong><?= date('d', strtotime($event['date_start'])); ?></strong>
                                                                        <span><?= lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                                    <?php elseif (time() >= strtotime($event['date_start']) && time() < strtotime($event['date_end'] . ' + 1 day')): ?>
                                                                        <strong><?= date('d'); ?></strong>
                                                                        <span><?= lang('Admin.months_short_names.' . date('F')); ?></span>
                                                                    <?php else: ?>
                                                                        <strong><?= date('d', strtotime($event['date_start'])); ?></strong>
                                                                        <span><?= lang('Admin.months_short_names.' . date('F', strtotime($event['date_start']))); ?></span>
                                                            <?php endif; ?>
                                                                </div>
                                                            </div>	
                <?php endif; ?>
                                                        <div class="place">
                                                                <?php if (!empty($event['place']) || !empty($event['custom_place'])): ?>
                                                                <p class="label"><?= lang('Users.user.Place'); ?></p>
                                                                <p class="value">
                                                                    <?php if (!empty($event['place']) && !empty($event['place_link'])): ?><a href="/<?= $event['place_link']; ?>" title="<?= esc($event['place']); ?>"><?php endif; ?>
                                                                    <?= !empty($event['place']) ? $event['place'] : $event['custom_place']; ?>
                    <?php if (!empty($event['place_link'])): ?></a><?php endif; ?>
                                                                    </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="hours">
                                                            <?php if (!empty($event['hours'])): ?>
                                                                <p class="label"><?= lang('Users.user.Hour'); ?></p>
                                                                <p class="value"><?php foreach ($event['hours'] as $k => $hour): ?><?php if ($k): ?>, <?php endif; ?><span><?= $hour; ?></span><?php endforeach; ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="tickets">
                <?php if (!empty($event['price'])): ?>

                                                                <p class="label"><?= lang('Users.user.Tickets'); ?></p>
                                                                <p class="value"><?= $event['price']; ?></p>

                                                    <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php if (!empty($event['for_kids'])): ?>
                                                        <div class="for-kids">
                                                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg> <span><?= lang('Users.user.ForKids'); ?></span>
                                                        </div>
                                        <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
                        </div>



                    </div>




<?php endif; ?>




            </div>
            <div class="col col-sidebar">
<?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => 11, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
    </div>
</section>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>