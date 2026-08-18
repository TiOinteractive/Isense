<?php
/* 
RzeszowskieSmaki - lista aktualności
*/
if(!empty($data) && !empty($data['list'])): ?>
<?php if(!empty($id_sidebar)): ?>
    <div class="sidebar-column">
<?php endif; ?>
<section class="section-<?=$id_cont; ?> news-list">
    	<?php if(!empty($title)): ?> 
	 <div class="title">
            <h2><?=$title; ?></h2>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
	</div>	
    <?php endif; ?>         
        <div class="list">
                <?php foreach($data['list'] as $news): ?>
                <div class="news-item news-item-<?=$news['id']; ?>">
                    <div class="photo">
                        <?php if(!empty($news['photo']['path']) || (!empty($settings['no_photo_flavor']) && !empty($settings['no_photo_flavor']['path']))): ?>
                            <?php
                                if(!empty($news['photo']['path']) && !empty($news['photo']['crop_dimension'])) {
                                    $news['photo']['crop_dimension']=json_decode($news['photo']['crop_dimension'], true); 
                                    $news['photo']['path'] = $news['photo']['crop_dimension']['width'] . '/' . $news['photo']['crop_dimension']['height'] . '/' . $news['photo']['crop_dimension']['x'] . '/' . $news['photo']['crop_dimension']['y'] . '/' . $news['photo']['path'];
                                }
                            ?>
                            <a href="/<?=$news['link']; ?>" title="<?=esc($news['title']); ?>">
                                <picture>
                                    <img src="/image/c/460/300/<?=!empty($news['photo']['path']) ? $news['photo']['path'] : $settings['no_photo_flavor']['path']; ?>" alt="<?=esc($news['title']); ?>" class="trans400">
                                </picture>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="info">
                        <h2><a href="/<?=$news['link']; ?>" title="<?=esc($news['title']); ?>"><?=$news['title']; ?></a></h2>
                        <?php if(!empty($news['introduction'])):?>
                            <div class="introduction"><?=$news['introduction'];?></div>
                        <?php endif;?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if(!empty($data['pager'])): ?>
                <?=$data['pager']->links('news-' . $id_cont, 'front_full'); ?>
            <?php endif; ?>

</section>
        <?php endif; ?>
<?php if(!empty($id_sidebar)): ?>
         <?= view_cell('\App\Libraries\Sidebar::showSidebar', ['id' => $id_sidebar, 'id_lang' => $id_lang, 'locale' => $locale]) ?>
	</div>
<?php endif; ?>		