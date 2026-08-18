<?php
/* 
Movie tpl
*/
?>

<?=view($mobile && file_exists(APPPATH . 'Views/user/m_header.php') ? 'user/m_header' : 'user/header'); ?>
<?= view_cell('\Modules\Advertisement\Libraries\Advertisement::showAdvertisement', ['id' => 11, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
<section class="movie-single movie-<?=$data['id'];?>">
    <div class="container">
        <div class="entertainment-row">
            <div class="col col-content">
                <div class="movie-header">
                    <?php if(!empty($data['poster'])): ?>
                        <div class="poster">
                            <div class="photo-cont">
                                <a href="/image/r/1920/1080/<?=$data['poster']; ?>"  title="<?=esc($data['title']); ?>" rel="lightbox">
                                    <picture>
                                        <img src="/image/r/711/1000/<?=$data['poster']; ?>" alt="<?=esc(!empty($data['poster_caption']) ? $data['poster_caption'] : $data['title']); ?>" />
                                    </picture>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="info">
                        <h1 class="name"><?=$data['title']; ?></h1>
                        <div class="more-info">
                            <?php if(!empty($data['duration'])): ?><strong><?=$data['duration']; ?> <?=lang('Cinema.user.min'); ?></strong><?php endif; ?>
                            <?php if(!empty($data['duration']) && !empty($data['production'])): ?><span>|</span><?php endif; ?>
                            <?php if(!empty($data['production'])): ?><strong><?=$data['production']; ?></strong><?php endif; ?>
                            <?php if((!empty($data['duration']) || !empty($data['production'])) && !empty($data['genres'])): ?><span>|</span><?php endif; ?>
                            <?php if(!empty($data['genres'])): ?>
                                <?php foreach($data['genres'] as $g=>$genre): ?><strong><?php if($g): ?>, <?php endif; ?><?=$genre['name']; ?></strong><?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <table class="details">
                            <?php if(!empty($data['premiere']) && !empty($data['premiere']['date'])): ?>
                                <tr class="premiere">
                                    <td class="label"><?=lang('Cinema.user.Premiere'); ?>:</td>
                                    <td class="data"><?=date('d.m.Y', strtotime($data['premiere']['date'])); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if(!empty($data['director'])): ?>
                                <tr>
                                    <td class="label"><?=lang('Cinema.user.Director'); ?>:</td>
                                    <td class="data"><?=$data['director']; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if(!empty($data['scenario'])): ?>
                                <tr>
                                    <td class="label"><?=lang('Cinema.user.Scenario'); ?>:</td>
                                    <td class="data"><?=$data['scenario']; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if(!empty($data['actors'])): ?>
                                <tr>
                                    <td class="label"><?=lang('Cinema.user.Actors'); ?>:</td>
                                    <td class="data"><?=$data['actors']; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if(!empty($data['country'])): ?>
                                <tr>
                                    <td class="label"><?=lang('Cinema.user.Country'); ?>:</td>
                                    <td class="data"><?=$data['country']; ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if(!empty($data['distributor'])): ?>
                                <tr>
                                    <td class="label"><?=lang('Cinema.user.Distributor'); ?>:</td>
                                    <td class="data"><?=$data['distributor']; ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                <?php if(!empty($data['content'])): ?>
                    <div class="content"><?=$data['content']; ?></div>
                <?php endif; ?>
                <?php if(!empty($data['video_url'])): ?>
                    <div class="movie-video<?=!empty($data['video_source']) ? ' external' : ''; ?>">
                        <?php if(!empty($data['video_source'])): ?>
                            <div id="<?=$data['video_source']; ?>-<?=$data['external_id']; ?>" class="video-external <?=$data['video_source']; ?> controls" data-id="<?=$data['external_id']; ?>" data-url="<?=$data['video_url']; ?>">
                            </div>
                        <?php else: ?>
                            <video autoplay muted loop>
                                <source src="<?=$data['video_url']; ?>" type="<?=$data['video_mime']; ?>">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="calendar-repertoire">
                    <?php if(!empty($data['dates'])): ?>
                        <div class="search-engine-2">
                            <div class="events-form">
                                    <div class="search-carousel">
                                      <div class="cinema-carousel-wrap">
                                            <?php foreach($data['dates'] as $k=>$date): ?>
                                                <?php
                                                    $today_time=strtotime('now');
                                                    $diff = (strtotime($date['date']) - $today_time) / (24 * 60 * 60);
                                                    $day_no = date('N', strtotime($date['date']));
                                                    $day_name = '';
                                                    switch ($diff) {
                                                        case 0: $day_name = lang('Event.Today');
                                                            break;
                                                        case 1: $day_name = lang('Event.Tomorrow');
                                                            break;
                                                        case 2: $day_name = lang('Event.AfterTomorrow');
                                                            break;
                                                        default: $day_name = lang('Event.days_names_no.' . $day_no);
                                                            break;
                                                    }
                                                ?>
                                                <div class="day day-no-<?=$day_no; ?><?php if($date['active']): ?> active<?php endif; ?>">
                                                    <div class="header" data-date="<?=date('d.m.Y', strtotime($date['date'])); ?>">
                                                        <?php if(in_array($day_no, array(5,6,7))): ?><span class="day-weekend"><?=lang('Event.user.Weekend'); ?></span><?php endif; ?>
                                                        <span class="day-name"><?=$day_name; ?></span>
                                                    </div>
                                                    <div class="day-date<?php if(empty($date['count'])): ?> no-movies<?php endif; ?>" data-date="<?=date('d.m.Y', strtotime($date['date'])); ?>">
                                                        <a<?php if($date['active']): ?> class='active'<?php endif; ?> href="<?=$date['link']; ?>" title="<?=lang('Cinema.days_names_no.' . $date['day_no']); ?> <?=$date['day']; ?>" data-data="<?=$date['date']; ?>"><strong><?=date('d', strtotime($date['date'])); ?></strong>
                                                        <span><?=lang('Event.user.months_names_no2.' . date('n', strtotime($date['date']))); ?></span></a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>		
                                </div>
                                  </div>
                      <?php endif; ?>
                    <?php if(!empty($data['calendar'])): ?>
                        <div class='cinemas'>
                            <?php foreach($data['calendar'] as $id_cinema=>$cinema): ?>
                                <div class='cinema'>
                                    <div class='logo'>
                                        <?php if(!empty($data['cinemas']) && !empty($data['cinemas'][$id_cinema]) && !empty($data['cinemas'][$id_cinema]['path'])): ?>
                                            <a href='/{$kina2[$kino_id].link}' title='{$kina2[$kino_id].nazwa|escape}'>
                                                <img src='/image/r/200/200/<?=$data['cinemas'][$id_cinema]['path']; ?>' alt='<?=esc($data['cinemas'][$id_cinema]['name']); ?>' />
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class='data'>    
                                        <h3><a href='/<?=$data['cinemas'][$id_cinema]['link']; ?>' title='<?=esc($data['cinemas'][$id_cinema]['name']); ?>'><?=$data['cinemas'][$id_cinema]['name']; ?></a></h3>
                                        <div class='types'>
                                            <?php foreach($cinema as $id_type=>$type): ?>
                                            <div class='type'>
                                                <?php if(!empty($data['types'])): ?>
                                                    <?php
                                                        $type_arr = explode('_', $id_type);
                                                    ?>
                                                <div class='name'><?php if(!empty($type_arr)): ?><?php foreach($type_arr as $t=>$id_type): ?><?php if(!empty($data['types'][$id_type])): ?><?php if($t): ?>, <?php endif; ?><?=$data['types'][$id_type]['name']; ?><?php endif; ?><?php endforeach; ?><?php endif; ?></div>
                                                <?php endif; ?>
                                                <div class='hours'>
                                                    <?php foreach($type as $hour): ?>
                                                        <span<?php if($hour['before']): ?> class="expired"<?php endif; ?>><?=$hour['name']; ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
            <div class="col col-sidebar">
                <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => 8, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
            </div>
        </div>
    </div>
</section>


<?=view($mobile && file_exists(APPPATH . 'Views/user/m_footer.php') ? 'user/m_footer' : 'user/footer'); ?>