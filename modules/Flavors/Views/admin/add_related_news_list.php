<?php if (!empty($related_news)): ?>
    <?php foreach ($related_news as $k => $news): ?>

        <div class="list-row list-row-<?= $news['id']; ?>">
            <div class="list-col w50">
                <input type="hidden" name="news[]" value="<?= $news['id']; ?>" />
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
            <div class="list-col w100 center">
                <a class="list-remove-btn-news" href="javascript:removeRelatedNews(<?= $news['id']; ?>);" data-title="Usuwanie powiązanego news" data-message="Czy na pewno chcesz usunąć news powiązany: <b><?= esc($news['title']); ?></b>" data-btn-ok="Usuń" data-btn-cancel="Anuluj" title="Usuwanie"><i class="fa-regular fa-trash-can fa-xl"></i></a>
            </div>
        </div>

    <?php endforeach; ?>
<?php else: ?>
    <div class="list-row no-list-result"><?= lang('News.NoListResult'); ?></div>
<?php endif; ?>