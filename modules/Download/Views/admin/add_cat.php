<div class="form-row nag">
    <h3><?= lang('Download.Categories'); ?></h3>
</div>
<p><a title="<?= lang('Download.AddCategory'); ?>" class="btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/add-cat/<?= $id_content; ?>"><?= lang('Download.AddCategory'); ?></a></p>
<div class="list">
    <div class="list-row list-head">
        <div class="list-col center w40">
            <?= lang('Download.Lp'); ?>
        </div>
        <div class="list-col">
            <?= lang('Download.Name'); ?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?= lang('Download.Count'); ?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?= lang('News.Edit'); ?>
        </div>
        <div class="list-col center w100 hide-500">
            <?= lang('News.Publish'); ?>
        </div>
        <div class="list-col center w100">
            <?= lang('News.Delete'); ?>
        </div>
    </div>	

    <?php if (!empty($form_data)): ?>
        <div class="list-order-box" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/order">
            <?php foreach ($form_data as $k => $cat): ?>
                <div class="list-row list-row-<?= $cat['id']; ?>">
                    <div class="list-col center w40 order">
                        <?= $cat['order']; ?>
                    </div>
                    <div class="list-col">
                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/edit-cat/<?= $id_content; ?>/<?= $cat['id']; ?>" title="<?= $cat['name']; ?>"><strong><?= $cat['name']; ?></strong></a>
                    </div>

                    <div class="list-col center w100 hide-1200">
                        <?= $cat['count']; ?>
                    </div>
                    <div class="list-col center w100 hide-1200">
                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/edit-cat/<?= $id_content; ?>/<?= $cat['id']; ?>" title="<?= lang('Download.Edit'); ?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                    </div>
                    <div class="list-col center w100 hide-500">
                        <a class="list-publish-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/publish-cat/<?= $cat['id']; ?>" title="<?= lang('Download.Publish'); ?>"><?php if (!empty($cat['publish']) && $cat['publish']): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                    </div>
                    <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))) {?>  <a class="list-remove-btn" href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/delete-cat/<?= $cat['id']; ?>" data-title="<?= lang('Download.Delete'); ?>" data-message="<?= lang('Download.DeleteConfirm') . ': <b>' . $cat['name'] . '</b>'; ?>" data-btn-ok="<?= lang('Download.Remove'); ?>" data-btn-cancel="<?= lang('Download.Cancel'); ?>" title="<?= lang('Download.Delete'); ?>"><i class="fa-solid fa-trash-can fa-2x"></i></a> <?php } ?>
                    </div>
                </div>
            <?php endforeach; ?>	

        </div>
    <?php else: ?>
        <div class="list-row no-list-result"><?= lang('Download.NoListResult'); ?></div>
    <?php endif; ?>
</div> 
