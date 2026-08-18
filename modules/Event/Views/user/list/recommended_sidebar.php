<?php
/* 
Polecane - sidebar
*/
?>
<?php if(!empty($data) && !empty($data['list'])): ?>
    <section class="section-<?=$id_cont; ?> recommended-events-list events-list sidebar">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title resinet-title-small">
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
                                </div>
                            </div>
                            <div class="info">
                                <?php if(!empty($event['type_name'])): ?>
                                    <div class="type">
                                        <a href="<?=(!empty($global_links['calendar']) ? $global_links['calendar'] : '/') . '/g/t/' . $event['type_slug']; ?>" title="<?=esc($event['type_name']); ?>"><?=$event['type_name']; ?></a>
                                    </div>
                                <?php endif; ?>
                                <h3 class="name"><a href="/<?=$event['link']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a></h3>
                                <?php if(!empty($event['place']) || !empty($event['custom_place'])): ?>
                                    <h4 class="place">
                                        <?php if(!empty($event['place']) || !empty($event['custom_place'])): ?>
                                            <span class="place-name">
                                                <?php if(!empty($event['place']) && !empty($event['place_link'])): ?><a href="/<?=$event['place_link']; ?>" title="<?=esc($event['place']); ?>"><?php endif; ?>
                                                    <?=!empty($event['place']) ? $event['place'] : $event['custom_place']; ?>
                                                <?php if(!empty($event['place_link'])): ?></a><?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </h4>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>