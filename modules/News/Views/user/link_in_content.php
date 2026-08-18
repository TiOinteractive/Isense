<?php if(!empty($news)): ?>
    <div class="news-short">
        <p class="news-short-header"><?=lang('News.ReadAlso'); ?></p>
        <?php foreach($news as $n): ?>
            <div class="news-short-item">
                <div class="news-short-photo">
                    <?php if(!empty($n['path'])): ?>
                        <a href="/<?=$n['link']; ?>" titel="<?=esc($n['title']); ?>">
                            <img src="/image/c/460/300/<?=$n['path']; ?>" alt="<?=esc($n['title']); ?>" />
                        </a>
                    <?php endif; ?>
                </div>
                <div class="news-short-info">
                    <h4><a href="/<?=$n['link']; ?>" titel="<?=esc($n['title']); ?>"><?=$n['title']; ?></a></h4>
                    <?php if(!empty($n['introduction'])): ?>
                        <p><?=$n['introduction']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>