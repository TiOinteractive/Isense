<?php if (!empty($box_list[1])): ?>
    <section class="home_news_box">
        <div class="inside">
            <div class="big_news">
                <div class="photo">
                    <a href="/<?= $box_list[1]['link']; ?>" title="<?= esc($box_list[1]['title']); ?>">
                        <?php if (!empty($box_list[1]['photo']['path']) || (!empty($settings['no_photo_flavor']) && !empty($settings['no_photo_flavor']['path']))): ?>
                            <picture>
                                <source srcset="/image/c/500/250/<?= $box_list[1]['photo']['path']; ?>" media="(max-width: 800px)">
                                <img src="/image/c/720/470/<?= !empty($box_list[1]['photo']['path']) ? $box_list[1]['photo']['path'] : $settings['no_photo_flavor']['path']; ?>" alt="<?php if (!empty($box_list[1]['photo']['caption'])): ?><?= esc($box_list[1]['photo']['caption']); ?><?php else: ?><?= esc($box_list[1]['title']); ?><?php endif; ?>" class="trans400">
                            <?php endif; ?>	
                            <span>NEWS</span>
                        </picture>
                    </a>
                    <header>
                        <h2><a href="/<?= $box_list[1]['link']; ?>" title="<?= esc($box_list[1]['title']); ?>"><?= $box_list[1]['title']; ?></a></h2>
                    </header>
                </div>
            </div>
            <?php if (!empty($box_list[2]) || !empty($box_list[3])): ?>
                <div class="right">
                    <?php if (!empty($box_list[2])): ?>
                        <div class="small_news">
                            <div class="photo">
                                <a href="/<?= $box_list[2]['link']; ?>" title="<?= esc($box_list[2]['title']); ?>">
                                    <?php if (!empty($box_list[2]['photo']['path']) || (!empty($settings['no_photo_flavor']) && !empty($settings['no_photo_flavor']['path']))): ?>
                                        <picture>   
                                            <source srcset="/image/c/300/200/<?= !empty($box_list[2]['photo']['path']) ? $box_list[2]['photo']['path'] : $settings['no_photo_flavor']['path']; ?>" media="(max-width: 800px)">
                                            <img src="/image/c/350/220/<?= !empty($box_list[2]['photo']['path']) ? $box_list[2]['photo']['path'] : $settings['no_photo_flavor']['path']; ?>" alt="<?php if (!empty($box_list[2]['photo']['caption'])): ?><?= esc($box_list[2]['photo']['caption']); ?><?php else: ?><?= esc($box_list[2]['title']); ?><?php endif; ?>" class="trans400">
                                        </picture>
                                    <?php endif; ?>
                                </a>
                                <header>
                                    <h2><a href="/<?= $box_list[2]['link']; ?>" title="<?= esc($box_list[2]['title']); ?>"><?= $box_list[2]['title']; ?></a></h2>
                                </header>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($box_list[3])): ?>
                        <div class="small_news">
                            <div class="photo">
                                <a href="/<?= $box_list[3]['link']; ?>" title="<?= esc($box_list[3]['title']); ?>">
                                    <?php if (!empty($box_list[3]['photo']['path']) || (!empty($settings['no_photo_flavor']) && !empty($settings['no_photo_flavor']['path']))): ?>  
                                        <picture>
                                            <source srcset="/image/c/300/200/<?= !empty($box_list[3]['photo']['path']) ? $box_list[3]['photo']['path'] : $settings['no_photo_flavor']['path']; ?>" media="(max-width: 800px)">
                                            <img src="/image/c/350/220/<?= !empty($box_list[3]['photo']['path']) ? $box_list[3]['photo']['path'] : $settings['no_photo_flavor']['path']; ?>" alt="<?php if (!empty($box_list[3]['photo']['caption'])): ?><?= esc($box_list[3]['photo']['caption']); ?><?php else: ?><?= esc($box_list[3]['title']); ?><?php endif; ?>" class="trans400">
                                        </picture>
                                    <?php endif; ?>
                                </a>
                                <header>
                                    <h2><a href="/<?= $box_list[3]['link']; ?>" title="<?= esc($box_list[3]['title']); ?>"><?= $box_list[3]['title']; ?></a></h2>
                                </header>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>