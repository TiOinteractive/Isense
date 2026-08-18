<div class="list-row list-row-<?=$type['id']; ?> level-<?=$type['level']; ?><?= $item_no<$count ? ' full' : ''; ?>">
	<div class="list-col">
	   	<input type="hidden" class="order-field" name="order[]" value="<?=$type['id'];?>" />
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/edit-place-type/<?=$type['id']; ?>" title="<?=$type['name']; ?>" class="name"><?=$type['name']; ?></a>
    </div>
    <div class="list-col center w100">
        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/edit-place-type/<?=$type['id']; ?>" title="<?=lang('Event.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
    </div>
    <div class="list-col center w100">
        <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/publish-place-type/<?=$type['id']; ?>" title="<?=lang('Event.Publish');?>"><?php if(!empty($type['publish']) && $type['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
    </div>
    <div class="list-col center w100">
        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/delete-place-type/<?=$type['id']; ?>" data-title="<?=lang('Event.DeletePlaceType');?>" data-message="<?=lang('Event.PlaceTypeDeleteConfirm') . ': <b>' . $type['name'] . '</b>'; ?>" data-btn-ok="<?=lang('Event.Remove');?>" data-btn-cancel="<?=lang('Event.Cancel');?>" title="<?=lang('Event.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
    </div>
</div>
<?php if(!empty($type['list'])): ?>
  <div class="list-sub-place">
    <?php foreach($type['list'] as $j=>$t): ?>
        <?= view('\Modules\Event\Views\admin/event_place_type_item', array('type' => $t, 'count'=>count($type['list']), 'item_no'=>$j+1)); ?>
    <?php endforeach; ?>
	</div>
<?php endif; ?>