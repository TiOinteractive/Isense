<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Newsletter.GroupList');?></div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-add" title=""><?=lang('Newsletter.AddGroup');?></a></p>
        <?= view('Modules\Newsletter\Views\admin\group_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager' => NULL, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Newsletter.Name');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Export');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.Edit');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Newsletter.Delete');?>
                </div>
            </div>
            <?php if(!empty($groups)): ?>
                <?php foreach($groups as $group): ?>
                    <div class="list-row list-row-<?=$group['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-edit/<?=$group['id']; ?>" title="<?=$group['name']; ?>"><?=$group['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-export-csv/<?=$group['id']; ?>" title="<?=lang('Newsletter.Export');?> CSV"><i class="fa-solid fa-file-csv fa-2x"></i></a>
                            &nbsp;<a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-export-xlsx/<?=$group['id']; ?>" title="<?=lang('Newsletter.Export');?> XLSX"><i class="fa-solid fa-file-excel fa-2x"></i></a>
                            &nbsp;<a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-export-txt/<?=$group['id']; ?>" title="<?=lang('Newsletter.Export');?> TXT"><i class="fa-solid fa-file-lines fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-edit/<?=$group['id']; ?>" title="<?=lang('Newsletter.Edit');?>"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-publish/<?=$group['id']; ?>" title="<?=lang('Newsletter.Publish');?>"><?php if(!empty($group['publish']) && $group['publish']): ?><i class="fa-solid fa-square-check fa-2x"></i><?php else: ?><i class="fa-regular fa-square fa-2x"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-delete/<?=$group['id']; ?>" data-title="<?=lang('Newsletter.DeleteGroup');?>" data-message="<?=lang('Newsletter.DeleteGroupConfirm') . ': <b>' . $group['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Remove');?>" data-btn-cancel="<?=lang('Newsletter.Cancel');?>" title="<?=lang('Newsletter.Delete');?>"><i class="fa-solid fa-trash-can fa-2x"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager' => NULL, 'order_list'=>$order_list)); ?>
    </div>
</div>
