<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Cinema.TypeList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/add-type" title=""><?=lang('Cinema.AddType');?></a></p>
        <?= view('Modules\Cinema\Views\admin\type_list_filters', array()); ?>
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
            <?php if(!empty($types)): ?>
                <?php foreach($types as $type): ?>
                    <div class="list-row list-row-<?=$type['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/edit-type/<?=$type['id']; ?>" title="<?=$type['name']; ?>"><?=$type['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/edit-type/<?=$type['id']; ?>" title="<?=lang('Cinema.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/publish-type/<?=$type['id']; ?>" title="<?=lang('Cinema.Publish');?>"><?php if(!empty($type['publish']) && $type['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema/delete-type/<?=$type['id']; ?>" data-title="<?=lang('Cinema.DeleteType');?>" data-message="<?=lang('Cinema.TypeDeleteConfirm') . ': <b>' . $type['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Cinema.Remove');?>" data-btn-cancel="<?=lang('Cinema.Cancel');?>" title="<?=lang('Cinema.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
