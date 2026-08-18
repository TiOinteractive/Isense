<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?= lang('Event.EventList'); ?></h3>
</div>
<p>
    <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/add/<?= $id_content; ?>" title=""><?=lang('Event.AddEvent');?></a>
    <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/add-events/<?= $id_content; ?>" title=""><?=lang('Event.AddMassEvents');?></a>
</p>
<?= view('Modules\Event\Views\admin\event_list_filters', array()); ?>
<?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
<?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
<div class="list">
    <div class="list-row list-head">
        <div class="list-col w50 no-padding"></div>
        <div class="list-col">
            <?=lang('Event.Name');?>
        </div>
        <div class="list-col center w40 hide-1200 no-padding"></div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Type');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.EventPlace');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Repertoire');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Views');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Patronage');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Recommended');?>
        </div>
        <div class="list-col center w100 hide-1200">
            <?=lang('Event.Home');?>
        </div>
        <div class="list-col center w100">
        </div>
    </div>
    <?php if(!empty($events)): ?>
        <?php foreach($events as $event): ?>
            <div class="list-row list-row-<?=$event['id']; ?>">
                <div class="list-col w50 no-padding">
                    <?php if(!empty($event['path'])): ?>
                        <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit/<?= $id_content; ?>/<?=$event['id']; ?>" title="<?=esc($event['name']); ?>">
                            <img src="/image/c/50/50/<?=$event['path']; ?>" alt="<?=esc($event['name']); ?>" />
                        </a>
                    <?php endif; ?>
                </div>
                <div class="list-col">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit/<?= $id_content; ?>/<?=$event['id']; ?>" title="<?=esc($event['name']); ?>"><?=$event['name']; ?></a>
                </div>
                <div class="list-col center w40 hide-1200 no-padding">
                    <?php if(!empty($event['source']) && $event['source'] == 'kupbilecik'): ?><img class="source-ico" src="/adm/img/kupbilecik-ico.png" alt="<?=lang('Event.import_source.' . $event['source']); ?>" /><?php endif; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=!empty($event['type']) ? $event['type'] : ''; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?php if(!empty($event['places'])): ?>
                        <?php foreach($event['places'] as $p=>$place): ?><?php if($p): ?>, <?php endif; ?><a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit-place/<?=$place['id_page_cont']; ?>/<?=$place['id']; ?>" title="<?=esc($place['name']); ?>"><?=$place['name']; ?></a><?php endforeach; ?>
                    <?php endif; ?>
                    <?php if(!empty($event['custom_places'])): ?>
                        <?php foreach($event['custom_places'] as $p=>$place): ?><?php if($p): ?>, <?php endif; ?><?=$place['custom_place']; ?><?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/repertoire/<?=$event['id']; ?>" title="<?=lang('Event.AddEventRepertoire');?>"><i class="fa-regular fa-calendar-plus fa-xl"></i></a>&nbsp;
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/calendar?event=<?=$event['id']; ?>" title="<?=lang('Event.CheckEventRepertoire');?>"><i class="fa-regular fa-calendar-days fa-xl"></i></a>
                </div>
                <div class="list-col center w100 hide-1200">
                    <?=!empty($event['views']) ? $event['views'] : '0'; ?>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/patronage/<?=$event['id']; ?>" title="<?=lang('Event.Patronage');?>"><?php if(!empty($event['patronage']) && $event['patronage']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/recommended/<?=$event['id']; ?>" title="<?=lang('Event.Recommended');?>"><?php if(!empty($event['recommended']) && $event['recommended']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                </div>
                <div class="list-col center w100 hide-1200">
                    <a class="list-home-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/home/<?=$event['id']; ?>" title="<?=lang('Event.Home');?>"><?php if(!empty($event['home']) && $event['home']): ?><i class="fa-solid fa-toggle-on fa-xl"></i><?php else: ?><i class="fa-solid fa-toggle-off fa-xl"></i><?php endif; ?></a>
                </div>
                <div class="list-col center w100">
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/copy/<?= $id_content; ?>/<?=$event['id']; ?>" title="<?=lang('Event.Copy');?>"><i class="fa-regular fa-copy fa-xl"></i></a>&nbsp;
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/edit/<?= $id_content; ?>/<?=$event['id']; ?>" title="<?=lang('Event.Edit');?>"><i class="fa-solid fa-pencil fa-xl"></i></a>&nbsp;
                    <a class="list-publish-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/publish/<?=$event['id']; ?>" title="<?=lang('Event.Publish');?>"><?php if(!empty($event['publish']) && $event['publish']): ?><i class="fa-solid fa-square-check fa-xl"></i><?php else: ?><i class="fa-regular fa-square fa-xl"></i><?php endif; ?></a>&nbsp;
                    <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/delete/<?=$event['id']; ?>" data-title="<?=lang('Event.DeleteEvent');?>" data-message="<?=lang('Event.DeleteConfirm') . ': <b>' . esc($event['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Event.Remove');?>" data-btn-cancel="<?=lang('Event.Cancel');?>" title="<?=lang('Event.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>