<?php if(!empty($news)): ?>
    <div class="resinet-news-other">      
        <div class="title">
            <h3><?=lang('User.news.OtherNews'); ?> <?=lang('User.news.from.' . $info_page['id']); ?></h3>
        </div>
        <div class="list">
            <?php foreach($news as $n): ?>
                <div class="news-item news-item-<?=$n['id']; ?>">
                    <div class="news-item-cont">
                        <div class="photo">
                            <div class="photo-cont">
                                <?php if(!empty($n['photo']['path']) || (!empty($settings['no_photo']) && !empty($settings['no_photo']['path']))): ?>
                                    <?php
                                        if(!empty($n['photo']['path']) && !empty($n['crop_dimension'])) {
                                            $n['crop_dimension']=json_decode($n['crop_dimension'], true); 
                                            $n['photo']['path'] = $n['crop_dimension']['width'] . '/' . $n['crop_dimension']['height'] . '/' . $n['crop_dimension']['x'] . '/' . $n['crop_dimension']['y'] . '/' . $n['photo']['path'];
                                        }
                                    ?>
                                    <a href="<?php if(!$n['link_redirect']): ?>/<?php endif; ?><?=$n['link']; ?>" title="<?=esc($n['title']); ?>"<?php if(!empty($n['link_external'])): ?> target="_blank"<?php endif; ?>>
                                        <picture>
                                            <source srcset="/image/c/460/300/<?=!empty($n['photo']['path']) ? $n['photo']['path'] : $settings['no_photo']['path']; ?>" media="(max-width: 800px)">
                                            <img src="/image/c/800/520/<?=!empty($n['photo']['path']) ? $n['photo']['path'] : $settings['no_photo']['path']; ?>" alt="<?=esc($n['title']); ?>" class="trans400" />
                                        </picture>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="info">
                            <?php if(!empty($n['header'])):?>
                                <h4 class="header"><?=$n['header']; ?></h4>
                            <?php elseif(!empty($n['info_page']['header']) && !empty($n['info_page']['link'])):?> 
                                <h4 class="header"><a href="/<?=$n['info_page']['link'];?>" title="<?=esc($n['info_page']['header']);?>"><?=$n['info_page']['header'];?></a></h4>	
                            <?php elseif(!empty($n['info_page']['name']) && !empty($n['info_page']['link'])):?> 
                                <h4 class="header"><a href="/<?=$n['info_page']['link'];?>" title="<?=esc($n['info_page']['name']);?>"><?=$n['info_page']['name'];?></a></h4>
                            <?php endif; ?>
                            <h3 class="name"><a href="<?php if(!$n['link_redirect']): ?>/<?php endif; ?><?=$n['link']; ?>" title="<?=esc($n['title']); ?>"<?php if(!empty($n['link_external'])): ?> target="_blank"<?php endif; ?>><?=$n['title']; ?></a></h3>
                            <?php if(!empty($n['introduction'])):?>
                                <p class="introduction"><?=word_limiter($n['introduction'], 32, '...'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if(!empty($pager)): ?>
            <?=$pager->links('news-' . $id_page_cont, 'front_entertainment'); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>