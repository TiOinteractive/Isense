<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Event.EventPlaceTypeList');?></div>
        <p><a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/add-place-type" title=""><?=lang('Event.AddEventPlaceType');?></a></p>
        <?= view('Modules\Event\Views\admin\event_place_type_filters', array()); ?>
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
            <?php if(!empty($types)): ?>
			  <form method="post" id="order-place-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/orderplace">	
                <?php foreach($types as $k=>$type): ?>
				    <div class="list-place">
                    <?= view('\Modules\Event\Views\admin/event_place_type_item', array('type'=>$type, 'count'=>count($types), 'item_no'=>$k+1)); ?>
					</div>
                <?php endforeach; ?>
			  </form>	
            <?php endif; ?>
        </div>
    </div>
</div>
