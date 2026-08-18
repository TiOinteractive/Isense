<?php
/* 
Premiery i zapowiedzi 
 */
?>
<?php if(!empty($data) && !empty($data['list'])): ?>
    <section class="section-<?=$id_cont; ?> premiere-announcement-list">
        <?php if(!empty($title)): ?> 
            <div class="title resinet-title">
               <h1 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h1>
                <?php if(!empty($subtitle)): ?>
                    <h3 class="h-2"><?=$subtitle; ?></h3>
                <?php endif; ?>
           </div>	
        <?php endif; ?>     
        <?php if(!empty($data['list']['premiere'])): ?>
            <div class="list">
                <div class="data-nag">
                    <div class="data-nag-cont">
                        <?=lang('User.entertainment.Premieres'); ?>
                    </div>
                </div>
                <?php foreach($data['list']['premiere'] as $movie): ?>
                    <div class="movie-item">
                        <div class="movie-item-cont">
                            <div class='poster'>
                                <div class='poster-cont'>
                                    <?php if(!empty($movie['path'])): ?>
                                        <a href="/<?=$movie['link']; ?>" title="<?=esc($movie['title']); ?>">
                                            <img src='/image/r/400/578/<?=$movie['path']; ?>' alt='<?=esc($movie['title']); ?>' />
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info">
                                <h2><a href="/<?=$movie['link']; ?>" title="<?=esc($movie['title']); ?>"><?=$movie['title']; ?></a></h2>
                                <p class='details'><?php if(!empty($movie['genres'])): ?><strong><?php foreach($movie['genres'] as $g=>$genre): ?><?php if($g): ?>, <?php endif; ?><?=$genre['name']; ?><?php endforeach; ?></strong><?php endif; ?><?php if(!empty($movie['country'])): ?><?php if(!empty($movie['genres'])): ?><span>|</span><?php endif; ?><strong><?=$movie['country']; ?></strong><?php endif; ?><?php if(!empty($movie['duration'])): ?><?php if(!empty($movie['genres']) || !empty($movie['country'])): ?><span>|</span><?php endif; ?><strong><?=$movie['duration']; ?> <?=lang('Cinema.user.min'); ?></strong><?php endif; ?></p>
                                <table class="more-details">
                                    <?php if(!empty($movie['date'])): ?>
                                        <tr class="premiere">
                                            <td class="label"><?=lang('Cinema.user.Premiere'); ?>:</td>
                                            <td class="data"><?=date('d.m.Y', strtotime($movie['date'])); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['director'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Director'); ?>:</td>
                                            <td class="data"><?=$movie['director']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['scenario'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Scenario'); ?>:</td>
                                            <td class="data"><?=$movie['scenario']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['actors'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Actors'); ?>:</td>
                                            <td class="data"><?=$movie['actors']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['country'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Country'); ?>:</td>
                                            <td class="data"><?=$movie['country']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['distributor'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Distributor'); ?>:</td>
                                            <td class="data"><?=$movie['distributor']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>   
        <?php if(!empty($data['list']['announcement'])): ?>
            <div class="list">
                <div class="data-nag">
                    <div class="data-nag-cont">
                        <?=lang('User.entertainment.Announcements'); ?>
                    </div>
                </div>
                <?php foreach($data['list']['announcement'] as $movie): ?>
                    <div class="movie-item">
                        <div class="movie-item-cont">
                            <div class='poster'>
                                <div class='poster-cont'>
                                    <?php if(!empty($movie['path'])): ?>
                                        <a href="/<?=$movie['link']; ?>" title="<?=esc($movie['title']); ?>">
                                            <img src='/image/r/400/578/<?=$movie['path']; ?>' alt='<?=esc($movie['title']); ?>' />
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="info">
                                <h2><a href="/<?=$movie['link']; ?>" title="<?=esc($movie['title']); ?>"><?=$movie['title']; ?></a></h2>
                                <p class='details'><?php if(!empty($movie['genres'])): ?><strong><?php foreach($movie['genres'] as $g=>$genre): ?><?php if($g): ?>, <?php endif; ?><?=$genre['name']; ?><?php endforeach; ?></strong><?php endif; ?><?php if(!empty($movie['country'])): ?><?php if(!empty($movie['genres'])): ?><span>|</span><?php endif; ?><strong><?=$movie['country']; ?></strong><?php endif; ?><?php if(!empty($movie['duration'])): ?><?php if(!empty($movie['genres']) || !empty($movie['country'])): ?><span>|</span><?php endif; ?><strong><?=$movie['duration']; ?> <?=lang('Cinema.user.min'); ?></strong><?php endif; ?></p>
                                <table class="more-details">
                                    <?php if(!empty($movie['date'])): ?>
                                        <tr class="premiere">
                                            <td class="label"><?=lang('Cinema.user.Premiere'); ?>:</td>
                                            <td class="data"><?=date('d.m.Y', strtotime($movie['date'])); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['director'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Director'); ?>:</td>
                                            <td class="data"><?=$movie['director']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['scenario'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Scenario'); ?>:</td>
                                            <td class="data"><?=$movie['scenario']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['actors'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Actors'); ?>:</td>
                                            <td class="data"><?=$movie['actors']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['country'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Country'); ?>:</td>
                                            <td class="data"><?=$movie['country']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if(!empty($movie['distributor'])): ?>
                                        <tr>
                                            <td class="label"><?=lang('Cinema.user.Distributor'); ?>:</td>
                                            <td class="data"><?=$movie['distributor']; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
