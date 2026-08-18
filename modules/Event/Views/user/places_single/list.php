<?php
/*
  Event place type tpl
 */
?>

<?= view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 11, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<div class="container">    
    <div class="entertainment-row">
        <div class="col col-content">
            <?php if (!empty($data)): ?>
                <section class="section places-list-with-main-type">
                    <div class="container">
                        <div class="title resinet-title">
                            <h2 class="h-1">Miejsca, instytucje</h2>
                        </div>	  
                        <div class="search-engine-2">
                            <form action="/rozrywka/miejsca" method="get" class="events-form">
                                <div class="search-top">
                                    <input class="field" type="hidden" name="t" value="">
                                    <?php if (!empty($data['cat_list'])): ?>
                                        <select name="type" onchange="$('.events-form').submit();">
                                            <option value="0">wszystkie</option>
                                            <?php foreach ($data['cat_list'] as $type): ?>
                                                <option value="<?= $type['id']; ?>" <?php if (!empty($data['id']) and $data['id'] == $type['id']): ?> selected="selected"<?php endif; ?>><?= $type['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                    <div class="filter-box filter-search">
                                        <input class="field" type="text" name="s" value="<?php if (!empty($data['filters']['s'])): ?><?= $data['filters']['s']; ?><?php endif; ?>" placeholder="Znajdź miejsce..." autocomplete="off">
                                        <button><svg viewBox="0 0 512 512"><path d="M456.69,421.39,362.6,327.3a173.81,173.81,0,0,0,34.84-104.58C397.44,126.38,319.06,48,222.72,48S48,126.38,48,222.72s78.38,174.72,174.72,174.72A173.81,173.81,0,0,0,327.3,362.6l94.09,94.09a25,25,0,0,0,35.3-35.3ZM97.92,222.72a124.8,124.8,0,1,1,124.8,124.8A124.95,124.95,0,0,1,97.92,222.72Z"></path></svg></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="list list-1">
                            <div class="place-group place-group-<?= $data['id']; ?>">
                                <div class="place-group-cont">
                                    <div class="group-header">
                                        <h2><a href="/<?= $data['link']; ?>" title="<?= esc($data['name']); ?>"><?= $data['name']; ?></a></h2>
                                    </div>
                                    <div class="places-list">
                                        <?php foreach ($data['places'] as $place): ?>
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
                                                        <?php if (!empty($data['name'])): ?>
                                                            <p class="type"><a href="/<?= $data['link']; ?>" title="<?= esc($data['name']); ?>"><?= $data['name']; ?></a></p>
                                                        <?php endif; ?>
                                                        <h3><a href="/<?= $place['link']; ?>" title="<?= esc($place['name']); ?>"><?= $place['name']; ?></a></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>			
        </div>
        <div class="col col-sidebar">
            <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => 5, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
        </div>
    </div>
</div>
<?= view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>