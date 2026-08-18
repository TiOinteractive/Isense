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
                    <?php
                        $today = date('Y-m-d');
                        $today_time = strtotime(date('Y-m-d'));
                    ?>
                    <?= view('\Modules\Event\Views\user/list\m_events_list_search.php', array('today_time' => $today_time)) ?>
                    
                    <?php if (!empty($data['list'])): ?>
                        <div class="list-2-cols">
                            <?php foreach ($data['list'] as $e): ?>
                                <div class="event-item">
                                    <div class="event-item-cont">
                                        <div class="photo">
                                            <?php if(!empty($e['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                                <?php
                                                    if(!empty($e['photo']['path']) && !empty($e['photo']['crop_dimension'])) {
                                                        $e['photo']['crop_dimension']=json_decode($e['photo']['crop_dimension'], true); 
                                                        $e['photo']['path'] = $e['photo']['crop_dimension']['width'] . '/' . $e['photo']['crop_dimension']['height'] . '/' . $e['photo']['crop_dimension']['x'] . '/' . $e['photo']['crop_dimension']['y'] . '/' . $e['photo']['path'];
                                                    }
                                                ?>
                                                <a href="/<?=$e['link']; ?>" title="<?=esc($e['name']); ?>">
                                                    <img src="/image/c/600/400/<?=!empty($e['photo']['path']) ? $e['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($e['name']); ?>" />
                                                </a>
                                            <?php endif; ?>
                                            <?php if(!empty($e['type_name'])): ?>
                                                <div class="type">
                                                    <a href="/<?=(!empty($global_links['calendar']) ? $global_links['calendar'] : '/') . '/g/t/' . $e['type_slug']; ?>" title="<?=esc($e['type_name']); ?>"><?=$e['type_name']; ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($e['patronage']): ?>
                                                <div class="patronage"><?=lang('Users.user.Patronage'); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="info">
                                            <div class="date">
                                                <?php if(empty($e['date_end'])): ?>
                                                    <strong><?=date('d', strtotime($e['date_start'])); ?></strong>
                                                    <span><?=lang('Admin.months_short_names.' . date('F', strtotime($e['date_start']))); ?></span>
                                                <?php elseif(time()>=strtotime($e['date_start']) && time()<strtotime($e['date_end'] . ' + 1 day')): ?>
                                                    <strong><?=date('d'); ?></strong>
                                                    <span><?=lang('Admin.months_short_names.' . date('F')); ?></span>
                                                <?php else: ?>
                                                    <strong><?=date('d', strtotime($e['date_start'])); ?></strong>
                                                    <span><?=lang('Admin.months_short_names.' . date('F', strtotime($e['date_start']))); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <h3><a href="/<?=$e['link']; ?>" title="<?=esc($e['name']); ?>"><?=$e['name']; ?></a></h3>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($data['pager'])): ?>
                            <?= $data['pager']->links('event-' . $id_cont, 'front_entertainment'); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    
 



            
        </div> 	
    </section>
<?php endif; ?>