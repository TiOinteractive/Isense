<?php
/* 
W kinach - strona główna
 */
?>
<?php if(!empty($data) && !empty($data['list'])): ?>
    <section class="section-<?=$id_cont; ?> home-list repertoire-list cinema-repertoire-list">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title resinet-title entertainment-title">
                   <h2 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h2>
                    <?php if(!empty($subtitle)): ?>
                        <h3 class="h-2"><?=$subtitle; ?></h3>
                    <?php endif; ?>
                    <?php if($url):?><a class="more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainment.CheckFullRepertoire')); ?>"><?=lang('User.entertainment.CheckFullRepertoire'); ?></a><?php endif; ?>
               </div>	
            <?php endif; ?>         
            <div class="list">
                <?php foreach($data['list'] as $movie): ?>
                    <div class="movie-item movie-item-<?=$movie['id']; ?>">
                        <div class="poster">
                            <?php if($movie['poster']): ?>
                                <a href="/<?=$movie['link']; ?>" title="<?=esc($movie['title']); ?>">
                                    <picture>
                                        <img src="/image/c/400/578/<?=$movie['poster']; ?>" alt="<?=esc($movie['title']); ?>" class="trans400">
                                    </picture>
                                <?php if(!empty($movie['premiere'])): ?>
                                    <span class="premiere"><?=lang('User.entertainment.Premiere'); ?></span>
                                <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="info">
                            <h3><a href="/<?=$movie['link']; ?>" title="<?=esc($movie['title']); ?>"><?=$movie['title']; ?></a></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> 	
    </section>
<?php endif; ?>