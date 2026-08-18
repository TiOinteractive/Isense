<?php
/* 
Wydarzenia i kino
 */

?>
<?php if(!empty($data)): ?>
    <section class="section-<?=$id_cont; ?> event-cinema-section">
        <div class="container">
            <div class="column-row">
                <div class="col col-content">
                    <?php if(!empty($data['events'])): ?>
                        <?php if(!empty($title)): ?> 
                            <div class="title resinet-title-small title-with-menu">
                               <h2><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
							   <ul>
									<li><a href="/rozrywka/kalendarium/g/t/impreza">Imprezy</a></li>
									<li><a href="/rozrywka/kalendarium/g/t/koncert">Koncerty</a></li>
									<li><a href="/rozrywka/kalendarium/g/t/spektakl">Spektakle</a></li>
									<li><a href="/rozrywka/kalendarium/g/t/wystawa">Wystawy</a></li>
							   </ul>
                           </div>	
                        <?php endif; ?> 
                        <div class="events-list-main" >
                            <?php foreach($data['events'] as $e): ?>
                                <div class="event-item">
                                    <div class="event-item-cont">
                                        <div class="photo">
											<?php
											if(!empty($e['crop_dimension'])) {
												$e['crop_dimension']=json_decode($e['crop_dimension']); 
												$e['photo']=$e['crop_dimension']->width.'/'.$e['crop_dimension']->height.'/'.$e['crop_dimension']->x.'/'.$e['crop_dimension']->y.'/'.$e['photo'];
											}	
										  ?>
                                            <a href="/<?=$e['link']; ?>" title="<?=esc($e['name']); ?>">
                                                <img src="/image/c/600/400/<?=$e['photo']; ?>" alt="<?=esc($e['name']); ?>">
                                            </a>
                                            <?php if(!empty($e['type_name'])): ?>
                                                <div class="type">
                                                    <a href="/<?= $global_links['calendar'] . '/g/t/' . $e['type_slug']; ?>" title="<?=esc($e['type_name']); ?>"><?=$e['type_name']; ?></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="info">
                                            <div class="date">
                                                <?php if(empty($e['date_end'])): ?>
                                                    <strong><?=date('d', strtotime($e['date_start'])); ?></strong>
                                                    <span><?=lang('Admin.months_short_names.' . date('F', strtotime($e['date_start']))); ?></span>
                                                <?php else: ?>
                                                    <strong><?=date('d'); ?></strong>
                                                    <span><?=lang('Admin.months_short_names.' . date('F')); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <h3><a href="/<?=$e['link']; ?>" title="<?=esc($e['name']); ?>"><?=$e['name']; ?></a></h3>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($data['movies'])): ?>
                        <?php if(!empty($data['title2'])): ?> 
                            <div class="title resinet-title-small">
                               <h2><?php if($data['url2']):?><a href="<?=$data['url2']; ?>" title="<?=esc($data['title2']); ?>"><?php endif; ?><?=$data['title2']; ?><?php if($data['url2']):?></a><?php endif; ?></h2>
                           </div>	
                        <?php endif; ?> 
                        <div class="movies-list-main">
                            <?php foreach($data['movies'] as $m): ?>
                                <div class="movie-item">
                                    <div class="movie-item-cont">
                                        <div class="poster">
                                            <?php if($m['poster']): ?>
                                                <a href="/<?=$m['link']; ?>" title="<?=esc($m['title']); ?>">
                                                    <picture>
                                                        <img src="/image/c/400/578/<?=$m['poster']; ?>" alt="<?=esc($m['title']); ?>" class="trans400">
                                                    </picture>
                                                <?php if(!empty($m['premiere'])): ?>
                                                    <span class="premiere"><?=lang('User.entertainment.Premiere'); ?></span>
                                                <?php endif; ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="info">
                                            <h3><a href="/<?=$m['link']; ?>" title="<?=esc($m['title']); ?>"><?=$m['title']; ?></a></h3>
                                            <?php if(!empty($m['genres'])): ?>
                                                <div class="genres">
                                                    <?php foreach($m['genres'] as $k=>$g): ?><?php if($k): ?>, <?php endif; ?><span class="genre"><?=$g['name']; ?></span><?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col col-sidebar">
                    <?php if(!empty($id_sidebar)): ?>
                        <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>