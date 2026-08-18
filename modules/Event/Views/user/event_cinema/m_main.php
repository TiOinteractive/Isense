<?php
/*
  Wydarzenia i kino
 */
?>
<?php if (!empty($data)): ?>
    <section class="section-<?= $id_cont; ?> event-cinema-section">
        <div class="container">
            <div class="column-row">
                <div class="col col-sidebar">
                    <?php if (!empty($id_sidebar)): ?>
                        <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
                    <?php endif; ?>
                </div>
                <div class="col col-content">
                    <?php if (!empty($data['events'])): ?> 
                        <div class="recommended-events-list">
                            <div class="list">
                                <?php foreach ($data['events'] as $e): ?>
                                    <div class="event-item">
                                        <div class="event-item-cont">
                                            <div class="photo">
                                                <div class="photo-cont">
                                                    <a href="/<?= $e['link']; ?>" title="<?= esc($e['name']); ?>">
                                                        <img src="/image/c/600/400/<?= $e['photo']; ?>" alt="<?= esc($e['name']); ?>" />
                                                    </a>
                                                    <?php if ($e['patronage']): ?>
                                                        <div class="patronage"><?= lang('Users.user.Patronage'); ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($e['for_kids'])): ?>
                                                        <div class="for-kids">
                                                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>	
                                            </div>
                                            <div class="info">
                                                <?php if (!empty($e['type_name'])): ?>
                                                    <div class="type">
                                                        <a href="/<?= $global_links['calendar'] . '/g/t/' . $e['type_slug']; ?>" title="<?= esc($e['type_name']); ?>"><?= $e['type_name']; ?></a>
                                                    </div>
                                                <?php endif; ?>
                                                <h3 class="name"><a href="/<?= $e['link']; ?>" title="<?= esc($e['name']); ?>"><?= $e['name']; ?></a></h3>
                                                <div class="date">
                                                    <?php if (empty($e['date_end'])): ?>
                                                        <?php if($e['date_start']==date("Y-m-d")):?>
                                                            <strong>Dzisiaj</strong>
                                                        <?php elseif($e['date_start']==date("Y-m-d",strtotime('+1day'))): ?>
                                                            <strong>Jutro</strong>
                                                        <?php elseif($e['date_start']==date("Y-m-d",strtotime('+2day'))): ?>
                                                            <strong>Pojutrze</strong>
                                                        <?php else:?>
                                                            <span><?= date("d.m.Y", strtotime($e['date_start'])); ?></span>
                                                        <?php endif;?>	
                                                    <?php else: ?>
                                                        <strong>Dzisiaj</strong>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['movies'])): ?>
                        <?php if (!empty($data['title2'])): ?> 
                            <div class="title resinet-title">
                                <h2><?php if ($data['url2']): ?><a href="<?= $data['url2']; ?>" title="<?= esc($data['title2']); ?>"><?php endif; ?><?= $data['title2']; ?><?php if ($data['url2']): ?></a><?php endif; ?></h2>
                            </div>	
                        <?php endif; ?> 
                        <div class="movies-list-main">
                            <?php foreach ($data['movies'] as $m): ?>
                                <div class="movie-item">
                                    <div class="movie-item-cont">
                                        <div class="poster">
                                            <?php if ($m['poster']): ?>
                                                <a href="/<?= $m['link']; ?>" title="<?= esc($m['title']); ?>">
                                                    <picture>
                                                        <img src="/image/c/400/578/<?= $m['poster']; ?>" alt="<?= esc($m['title']); ?>" class="trans400" />
                                                    </picture>
                                                    <?php if (!empty($m['premiere'])): ?>
                                                        <span class="premiere"><?= lang('User.entertainment.Premiere'); ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="info">
                                            <h3><a href="/<?= $m['link']; ?>" title="<?= esc($m['title']); ?>"><?= $m['title']; ?></a></h3>
                                            <?php if (!empty($m['genres'])): ?>
                                                <div class="genres">
                                                    <?php foreach ($m['genres'] as $k => $g): ?><?php if ($k): ?>, <?php endif; ?><span class="genre"><?= $g['name']; ?></span><?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>