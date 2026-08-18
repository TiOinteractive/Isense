<?php if(!empty($movies)): ?>
    <?php foreach($movies as $m=>$movie): ?>
    <div class='movie'>
        <div class='poster'>
            <?php if(!empty($movie['path'])): ?>
                <a href="/<?=$movie['link']; ?>" title="<?=esc($movie['title']); ?>">
                    <img src='/image/r/400/578/<?=$movie['path']; ?>' alt='<?=esc($movie['title']); ?>' />
                </a>
            <?php endif; ?>
        </div>
        <div class='info'>
            <?php if($movie['premiere']): ?><p class="premiere"><?=lang('Cinema.user.Premiere'); ?></p><?php endif; ?>
            <?php if($movie['prepremiere']): ?><p class="prepremiere"><?=lang('Cinema.user.Prepremiere'); ?></p><?php endif; ?>
            <h2><a href='/<?=$movie['link']; ?>' title="<?=esc($movie['title']); ?>"><?=$movie['title']; ?></a></h2>
            <p class='details'><?php if(!empty($movie['genres'])): ?><strong><?php foreach($movie['genres'] as $g=>$genre): ?><?php if($g): ?>, <?php endif; ?><?=$genre['name']; ?><?php endforeach; ?></strong><?php endif; ?><?php if(!empty($movie['country'])): ?><?php if(!empty($movie['genres'])): ?><span>|</span><?php endif; ?><strong><?=$movie['country']; ?></strong><?php endif; ?><?php if(!empty($movie['duration'])): ?><?php if(!empty($movie['genres']) || !empty($movie['country'])): ?><span>|</span><?php endif; ?><strong><?=$movie['duration']; ?> <?=lang('Cinema.user.min'); ?></strong><?php endif; ?></p>
            <?php if($movie['for_kids']): ?>
                <div class="for-kids">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none" stroke="none"/><circle cx="12" cy="12" r="9"/><line x1="9" x2="9.01" y1="10" y2="10"/><line x1="15" x2="15.01" y1="10" y2="10"/><path d="M9.5 15a3.5 3.5 0 0 0 5 0"/><path d="M12 3a2 2 0 0 0 0 4"/></svg>
                </div>
            <?php endif; ?>
            <div class='cinemas'>
                <?php foreach($movie['calendar'] as $id_cinema=>$cinema): ?>
                    <div class='cinema'>
                        <div class='data'>
                            <?php if(!empty($cinemas) && !empty($cinemas[$id_cinema])): ?>
                                <h3><a href='/<?=$cinemas[$id_cinema]['link']; ?>' title='<?=esc($cinemas[$id_cinema]['name']); ?>'><?=$cinemas[$id_cinema]['name']; ?></a></h3>
                            <?php endif; ?>
                            <div class='types'>
                                <?php foreach($cinema as $id_type=>$type): ?>
                                    <div class='type'>
                                        <?php if(!empty($types)): ?>
                                            <?php
                                                $type_arr = explode('_', $id_type);
                                            ?>
                                            <div class='name'><?php if(!empty($type_arr)): ?><?php foreach($type_arr as $t=>$id_type): ?><?php if(!empty($types[$id_type])): ?><?php if($t): ?>, <?php endif; ?><?=$types[$id_type]['name']; ?><?php endif; ?><?php endforeach; ?><?php endif; ?></div>
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
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="info-no-results"><?=lang('Cinema.user.NoFilms'); ?></p>
<?php endif; ?>