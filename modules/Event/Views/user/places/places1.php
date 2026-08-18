<?php
/*
  Lista miejsc po kategoriach lvl 1 -  wersja 1
 */
?>
<?php if (!empty($data) && !empty($data['structure'])): ?>
    <section class="section-<?= $id_cont; ?> places-list-with-main-type">
        <div class="container">
            <?php if (!empty($title)): ?> 
                <div class="title resinet-title">
                    <h2 class="h-1"><?php if ($url): ?><a href="<?= $url; ?>" title="<?= esc($title); ?>"><?php endif; ?><?= $title; ?><?php if ($url): ?></a><?php endif; ?></h2>
                    <?php if (!empty($subtitle)): ?>
                        <h3 class="h-2"><?= $subtitle; ?></h3>
                    <?php endif; ?>
                    <?php if ($url): ?><a class="more" href="<?= $url; ?>" title="<?= esc(lang('User.entertainment.SeeAll')); ?>"><?= lang('User.entertainment.SeeAll'); ?></a><?php endif; ?>
                </div>	
            <?php endif; ?>    



            <div class="search-engine-2">
                <form action="/rozrywka/miejsca-instytucje" method="get" class="events-form">
                    <div class="search-top">
                        <input class="field" type="hidden" name="t" value="">
                        <?php if(!empty($data['cat_list'])):?>
                        <select name="type" onchange="$('.events-form').submit();">
                            <option value="0">wszystkie</option>
                            <?php foreach($data['cat_list'] as $type):?>
                            <option value="<?= $type['id']; ?>" <?php if(!empty($data['filters']['type']) and $data['filters']['type']==$type['id']):?> selected="selected"<?php endif;?>><?= $type['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif;?>
                        <div class="filter-box filter-search">
                            <input class="field" type="text" name="s" value="<?php if(!empty($data['filters']['s'])):?><?= $data['filters']['s']; ?><?php endif;?>" placeholder="Znajdź miejsce..." autocomplete="off">
                            <button><svg viewBox="0 0 512 512"><path d="M456.69,421.39,362.6,327.3a173.81,173.81,0,0,0,34.84-104.58C397.44,126.38,319.06,48,222.72,48S48,126.38,48,222.72s78.38,174.72,174.72,174.72A173.81,173.81,0,0,0,327.3,362.6l94.09,94.09a25,25,0,0,0,35.3-35.3ZM97.92,222.72a124.8,124.8,0,1,1,124.8,124.8A124.95,124.95,0,0,1,97.92,222.72Z"></path></svg></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="list list-1">
                <?php foreach ($data['structure'] as $type): ?>
                    <?php if (!empty($type['places'])): ?>
                        <div class="place-group place-group-<?= $type['id']; ?>">
                            <div class="place-group-cont">
                                <div class="group-header">
                                    <h2><a href="/<?= $type['link']; ?>" title="<?= esc($type['name']); ?>"><?= $type['name']; ?></a></h2>
                                </div>
                                <div class="places-list">
                                    <?php foreach ($type['places'] as $place): ?>
                                        <div class="place-item place-item-<?= $place['id']; ?>">
                                            <div class="place-item-cont">
                                                <div class="photo">
                                                    <?php if ($place['path']): ?>
                                                        <a href="/<?= $place['link']; ?>" title="<?= esc($place['name']); ?>">
                                                            <?php if (!empty($place['photo']['path'])): ?>
                                                                <picture>
                                                                    <img src="/image/c/300/200/<?= $place['photo']['path']; ?>" alt="<?= esc($place['photo']['caption']); ?>" class="trans400" />
                                                                </picture>
                                                                <div class="logo">
                                                                    <img src="/image/r/60/40/<?= $place['path']; ?>" alt="<?= esc($place['name']); ?>" class="trans400" />
                                                                </div>
                                                            <?php else: ?>
                                                                <picture>
                                                                    <img src="/image/c/300/200/<?= $place['path']; ?>" alt="<?= esc($place['name']); ?>" class="trans400" />
                                                                </picture>
                                                            <?php endif; ?>															 
                                                        </a>
                                                    <?php elseif (!empty($settings['no_photo']['path'])): ?>
                                                        <a href="/<?= $place['link']; ?>" title="<?= esc($place['name']); ?>">
                                                            <picture>
                                                                <img src="/image/c/300/200/<?= $settings['no_photo']['path']; ?>" alt="<?= esc($place['name']); ?>" class="trans400" />
                                                            </picture>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="info">
                                                    <?php if (!empty($place['place_type_name'])): ?>
                                                        <p class="type"><a href="/<?= $place['place_type_link']; ?>" title="<?= esc($place['place_type_name']); ?>"><?= $place['place_type_name']; ?></a></p>
                                                        <?php endif; ?>
                                                    <h3><a href="/<?= $place['link']; ?>" title="<?= esc($place['name']); ?>"><?= $place['name']; ?></a></h3>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>
