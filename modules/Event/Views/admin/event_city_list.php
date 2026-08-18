<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Event.EventCityList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/add-city" title=""><?=lang('Event.AddEventCity');?></a></p>
        <?= view('Modules\Event\Views\admin\event_city_list_filters', array()); ?>
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
                    <?=lang('Event.Publish');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Event.Delete');?>
                </div>
            </div>
            <?php if(!empty($cities)): ?>
                <?php foreach($cities as $city): ?>
                    <div class="list-row list-row-<?=$city['id']; ?>">
                        <div class="list-col">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-city/<?=$city['id']; ?>" title="<?=esc($city['name']); ?>"><?=$city['name']; ?></a>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-city/<?=$city['id']; ?>" title="<?=lang('Event.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>
                        </div>
                        <div class="list-col center w100 hide-500">
                            <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/publish-city/<?=$city['id']; ?>" title="<?=lang('Event.Publish');?>"><?php if(!empty($city['publish']) && $city['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/delete-city/<?=$city['id']; ?>" data-title="<?=lang('Event.DeleteEventCity');?>" data-message="<?=lang('Event.CityDeleteConfirm') . ': <b>' . esc($city['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Event.Remove');?>" data-btn-cancel="<?=lang('Event.Cancel');?>" title="<?=lang('Event.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
