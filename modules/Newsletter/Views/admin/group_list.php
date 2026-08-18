<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Newsletter.GroupList');?></div>
        <?=view('Modules\Newsletter\Views\admin\tabs'); ?>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-add" title="<?=lang('Newsletter.AddGroup');?>"><i class="fa-solid fa-plus"></i> <?=lang('Newsletter.AddGroup');?></a></p>
        <?= view('Modules\Newsletter\Views\admin\group_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager' => NULL)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col <?php if(!empty($filters['order_array']) && !empty($filters['order_array']['name'])): ?> <?=$filters['order_array']['name']; ?><?php endif; ?>" data-order="name">
                    <?=lang('Newsletter.Name');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.EmailsCount');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Emails');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Newsletter.Export');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Newsletter.Edit');?>
                </div>
                <div class="list-col center w100 hide-500<?php if(!empty($filters['order_array']) && !empty($filters['order_array']['publish'])): ?> <?=$filters['order_array']['publish']; ?><?php endif; ?>" data-order="publish">
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
                            <?=$group['emails_tosend']; ?> / <?=$group['emails_all']; ?>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mails?group=<?=$group['id']; ?>" title="<?=lang('Newsletter.Emails');?>"><i class="fa-solid fa-magnifying-glass fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-export-csv/<?=$group['id']; ?>" title="<?=lang('Newsletter.Export');?> CSV"><i class="fa-solid fa-file-csv fa-xl"></i></a>
                            &nbsp;<a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-export-xlsx/<?=$group['id']; ?>" title="<?=lang('Newsletter.Export');?> XLSX"><i class="fa-solid fa-file-excel fa-xl"></i></a>
                            &nbsp;<a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-export-txt/<?=$group['id']; ?>" title="<?=lang('Newsletter.Export');?> TXT"><i class="fa-solid fa-file-lines fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-edit/<?=$group['id']; ?>" title="<?=lang('Newsletter.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-publish/<?=$group['id']; ?>" title="<?=lang('Newsletter.Publish');?>"><?php if(!empty($group['publish']) && $group['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/group-delete/<?=$group['id']; ?>" data-title="<?=lang('Newsletter.DeleteGroup');?>" data-message="<?=lang('Newsletter.DeleteGroupConfirm') . ': <b>' . $group['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Newsletter.Remove');?>" data-btn-cancel="<?=lang('Newsletter.Cancel');?>" title="<?=lang('Newsletter.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager' => NULL)); ?>
    </div>
</div>
