<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Cinema.GenreList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add-genre" title=""><?=lang('Cinema.AddGenre');?></a></p>
        <?= view('Modules\Cinema\Views\admin\genre_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Cinema.Name');?>
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
            <?php if(!empty($genres)): ?>
                <?php foreach($genres as $genre): ?>
                    <div class="list-row list-row-<?=$genre['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/edit-genre/<?=$genre['id']; ?>" title="<?=$genre['name']; ?>"><?=$genre['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/edit-genre/<?=$genre['id']; ?>" title="<?=lang('Cinema.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/publish-genre/<?=$genre['id']; ?>" title="<?=lang('Cinema.Publish');?>"><?php if(!empty($genre['publish']) && $genre['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/delete-genre/<?=$genre['id']; ?>" data-title="<?=lang('Cinema.DeleteGenre');?>" data-message="<?=lang('Cinema.GenreDeleteConfirm') . ': <b>' . $genre['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Cinema.Remove');?>" data-btn-cancel="<?=lang('Cinema.Cancel');?>" title="<?=lang('Cinema.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
