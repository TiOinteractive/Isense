<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?= lang('Cinema.MovieList'); ?></h3>
</div>
<p>
    <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add-movie/<?= $id_content; ?>" title=""><?=lang('Cinema.AddMovie');?></a>
    <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add-movies/<?= $id_content; ?>" title=""><?=lang('Cinema.AddMassMovies');?></a>
</p>

<?= view('Modules\Cinema\Views\admin\movie_list_filters', array()); ?>
<?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
<div class="list">
    <div class="list-row list-head">
        <div class="list-col w50 no-padding"></div>
        <div class="list-col">
            <?=lang('Cinema.Title');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Cinema.Genre');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Cinema.Type');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Cinema.Repertoire');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Cinema.Announcements');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Cinema.Views');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Cinema.Edit');?>
        </div>
        <div class="list-col center w100 hide-500">
            <?=lang('Cinema.Publish');?>
        </div>
        <div class="list-col center w100">
            <?=lang('Cinema.Delete');?>
        </div>
    </div>
    <?php if(!empty($movies)): ?>
        <?php foreach($movies as $movie): ?>
            <div class="list-row list-row-<?=$movie['id']; ?>">
                <div class="list-col w50 no-padding">
                    <?php if(!empty($movie['path'])): ?>
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/edit-movie/<?= $id_content; ?>/<?=$movie['id']; ?>" title="<?=esc($movie['title']); ?>">
                            <img src="/image/c/50/50/<?=$movie['path']; ?>" alt="<?=esc($movie['title']); ?>" />
                        </a>
                    <?php endif; ?>
                </div>
                <div class="list-col">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/edit-movie/<?= $id_content; ?>/<?=$movie['id']; ?>" title="<?=esc($movie['title']); ?>"><?=$movie['title']; ?></a>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?php if(!empty($movie['genres'])): ?>
                        <?php foreach($movie['genres'] as $k=>$genre): ?><?php if($k): ?>, <?php endif; ?><?=$genre['name']; ?><?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?php if(!empty($movie['types'])): ?>
                        <?php foreach($movie['types'] as $k=>$type): ?><?php if($k): ?>, <?php endif; ?><?=$type['name']; ?><?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add/<?=$movie['id']; ?>" title="<?=lang('Cinema.AddRepertoire');?>"><i class="fa-regular fa-calendar-plus fa-xl"></i></a>&nbsp;
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema?movie=<?=$movie['id']; ?>" title="<?=lang('Cinema.CheckRepertoire');?>"><i class="fa-regular fa-calendar-days fa-xl"></i></a>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add-announcement/<?=$movie['id']; ?>" title="<?=lang('Cinema.AddAnnouncement');?>"><i class="fa-solid fa-microphone fa-xl"></i></a>&nbsp;
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/announcement?movie=<?=$movie['id']; ?>" title="<?=lang('Cinema.CheckAnnouncement');?>"><i class="fa-solid fa-bullhorn fa-xl"></i></a>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=!empty($movie['views']) ? $movie['views'] : '0'; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/edit-movie/<?= $id_content; ?>/<?=$movie['id']; ?>" title="<?=lang('Cinema.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                </div>
                <div class="list-col center w100 hide-500">
                    <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/publish-movie/<?=$movie['id']; ?>" title="<?=lang('Cinema.Publish');?>"><?php if(!empty($movie['publish']) && $movie['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                </div>
                <div class="list-col center w100">
                <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/delete-movie/<?=$movie['id']; ?>" data-title="<?=lang('Cinema.DeleteMovie');?>" data-message="<?=lang('Cinema.MovieDeleteConfirm') . ': <b>' . esc($movie['title']) . '</b>'; ?>" data-btn-ok="<?=lang('Cinema.Remove');?>" data-btn-cancel="<?=lang('Cinema.Cancel');?>" title="<?=lang('Cinema.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>

