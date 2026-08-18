<?php
/* 
Lista filmów
 */
?>
<?php if(!empty($data) && !empty($data['movies'])): ?>
    <section class="section-<?=$id_cont; ?> cinema-movies">
        <div class="container">
            <?php if(!empty($title)): ?> 
                <div class="title resinet-title">
                   <h1 class="h-1"><?php if($url):?><a href="<?=$url; ?>" title="<?=esc($title); ?>"><?php endif; ?><?=$title; ?><?php if($url):?></a><?php endif; ?></h1>
                    <?php if(!empty($subtitle)): ?>
                        <h3 class="h-2"><?=$subtitle; ?></h3>
                    <?php endif; ?>
                    <?php if($url):?><a class="more" href="<?=$url; ?>" title="<?=esc(lang('User.entertainment.CheckFullRepertoire')); ?>"><?=lang('User.entertainment.CheckFullRepertoire'); ?></a><?php endif; ?>
               </div>	
            <?php endif; ?>     
            <div class="movies">
                <?php foreach($data['movies'] as $k=>$movie): ?>
                    <div class='movie'>
                        <div class='poster'>
                            <?php if(!empty($movie['path'])): ?>
                                <a href='/<?=$movie['link']; ?>' title="<?=esc($movie['title']); ?>">
                                    <img src='/image/c/400/578/<?=$movie['path']; ?>' alt='<?=esc($movie['title']); ?>' />
                                </a>
                            <?php endif; ?>
                        </div>
                        <h2><a href='/<?=$movie['link']; ?>' title="<?=esc($movie['title']); ?>"><?=$movie['title']; ?></a></h2>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if(!empty($data['pager'])): ?>
                <?=$data['pager']->links('movie-' . $id_cont, 'front_entertainment'); ?>
            <?php endif; ?>
    </section>
<?php endif; ?>
