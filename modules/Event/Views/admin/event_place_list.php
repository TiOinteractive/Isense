<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?= lang('Event.EventPlaceList'); ?></h3>
</div>
<p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/add-place/<?=$id_content; ?>" title=""><?=lang('Event.AddEventPlace');?></a></p>
<?= view('Modules\Event\Views\admin\event_place_list_filters', array()); ?>
<?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
<div class="list">
    <div class="list-row list-head">
        <div class="list-col w50 no-padding"></div>
        <div class="list-col">
            <?=lang('Event.Name');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Type');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Views');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Edit');?>
        </div>
        <div class="list-col center w100 hide-500">
            <?=lang('Event.Publish');?>
        </div>
        <div class="list-col center w100">
            <?=lang('Event.Delete');?>
        </div>
    </div>
    <?php if(!empty($places)): ?>
        <?php foreach($places as $place): ?>
            <div class="list-row list-row-<?=$place['id']; ?>">
                <div class="list-col w50 no-padding">
                    <?php if(!empty($place['path'])): ?>
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-place/<?=$id_content; ?>/<?=$place['id']; ?>" title="<?=esc($place['name']); ?>">
                            <img src="/image/c/50/50/<?=$place['path']; ?>" alt="<?=esc($place['name']); ?>" />
                        </a>
                    <?php endif; ?>
                </div>
                <div class="list-col">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-place/<?=$id_content; ?>/<?=$place['id']; ?>" title="<?=esc($place['name']); ?>"><?=$place['name']; ?></a>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=$place['type']; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=!empty($place['views']) ? $place['views'] : '0'; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-place/<?=$id_content; ?>/<?=$place['id']; ?>" title="<?=lang('Event.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                </div>
                <div class="list-col center w100 hide-500">
                    <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/publish-place/<?=$place['id']; ?>" title="<?=lang('Event.Publish');?>"><?php if(!empty($place['publish']) && $place['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                </div>
                <div class="list-col center w100">
                <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/delete-place/<?=$place['id']; ?>" data-title="<?=lang('Event.DeleteEventPlace');?>" data-message="<?=lang('Event.PlaceDeleteConfirm') . ': <b>' . esc($place['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Event.Remove');?>" data-btn-cancel="<?=lang('Event.Cancel');?>" title="<?=lang('Event.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
