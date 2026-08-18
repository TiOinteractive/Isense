<form class="filters" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/add-news" method="post">
    <div class="filter">
        <label><?= lang('News.Title'); ?></label>
        <input type="text" name="name" value="<?= !empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
    <div class="filter">
        <button type="submit"><?= lang('News.Search'); ?></button>
    </div>
</form>
<form class="filters-results" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/related-news-save" method="post">
    <div class="list">
        <?php if (!empty($newslist)): ?>
            <div class="list-row list-head">
                <div class="list-col center w40"></div>
                <div class="list-col w50">Id</div>
                <div class="list-col w50"></div>
                <div class="list-col">
                    <?= lang('News.Title'); ?>
                </div>
                <div class="list-col w200"> <?= lang('News.Date'); ?></div>
            </div>
            <?php foreach ($newslist as $k => $news): ?>

                <div class="list-row list-row-<?= $news['id']; ?>">
                    <div class="list-col center w40">
                        <input type="checkbox" name="news[]" value="<?= $news['id']; ?>" />
                    </div>
                    <div class="list-col w50">
                        <?= $news['id']; ?>
                    </div>
                    <div class="list-col w50 no-padding">
                        <?php if (!empty($news['photo']['path'])): ?>
                            <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/edit/<?= $news['id_page_cont']; ?>/<?= $news['id']; ?>" title="<?= esc($news['title']); ?>" target="_blank">
                                <img src="/image/c/50/50/<?= $news['photo']['path']; ?>" alt="<?= esc($news['title']); ?>" />
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="list-col">
                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/news/edit/<?= $news['id_page_cont']; ?>/<?= $news['id']; ?>" title="<?= esc($news['title']); ?>" target="_blank"><?= $news['title']; ?></a>
                    </div>
                    <div class="list-col w200">
                        <?= $news['created_at']; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-row no-list-result"><?= lang('News.NoListResult'); ?></div>
        <?php endif; ?>
    </div>
</form>