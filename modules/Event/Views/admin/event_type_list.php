<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Event.EventTypeList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/add-type" title=""><?=lang('Event.AddEventType');?></a></p>
        <?= view('Modules\Event\Views\admin\event_type_list_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
        <div class="list">
            <div class="list-row list-head">
                <div class="list-col">
                    <?=lang('Event.Name');?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=lang('Event.Edit');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Event.ShowInSearch');?>
                </div>
                <div class="list-col center w100 hide-500">
                    <?=lang('Event.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Event.Delete');?>
                </div>
            </div>
            <?php if(!empty($types)): ?>
                <?php foreach($types as $type): ?>
                    <div class="list-row list-row-<?=$type['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-type/<?=$type['id']; ?>" title="<?=$type['name']; ?>"><?=$type['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-type/<?=$type['id']; ?>" title="<?=lang('Event.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/search-type/<?=$type['id']; ?>" title="<?=lang('Event.ShowInSearch');?>"><?php if(!empty($type['search']) && $type['search']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-xl fa-toggle-off"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/publish-type/<?=$type['id']; ?>" title="<?=lang('Event.Publish');?>"><?php if(!empty($type['publish']) && $type['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/delete-type/<?=$type['id']; ?>" data-title="<?=lang('Event.DeleteEventType');?>" data-message="<?=lang('Event.TypeDeleteConfirm') . ': <b>' . $type['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Event.Remove');?>" data-btn-cancel="<?=lang('Event.Cancel');?>" title="<?=lang('Event.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
