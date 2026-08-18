<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head"><?=lang('Event.EventCalendar');?></div>
        <p>
            <a class="btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/import" title=""><?=lang('Event.ImportKupBilecik');?></a>
        </p>
        <?= view('Modules\Event\Views\admin\event_calendar_filters', array()); ?>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
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
                <div class="list-col center w200 hide-1200">
                    <?=lang('Event.EventPlace');?>
                </div>
                <div class="list-col center w200 hide-500">
                    <?=lang('Event.EventDate');?>
                </div>
                <div class="list-col center w100">
                    <?=lang('Event.Delete');?>
                </div>
            </div>
            <?php if(!empty($calendar)): ?>
                <?php foreach($calendar as $c): ?>
                    <div class="list-row list-row-<?=$c['id']; ?>">
                        <div class="list-col w50 no-padding">
                            <?php if(!empty($c['path'])): ?>
                                <img src="/image/c/50/50/<?=$c['path']; ?>" alt="<?=esc($c['name']); ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="list-col">
                            <?=$c['name']; ?>
                        </div>
                        <div class="list-col center w40 hide-1200 no-padding">
                            <?php if(!empty($c['source']) && $c['source'] == 'kupbilecik'): ?><img class="source-ico" src="/adm/img/kupbilecik-ico.png" alt="<?=lang('Event.import_source.' . $c['source']); ?>" /><?php endif; ?>
                        </div>
                        <div class="list-col center w100 hide-1200">
                            <?php if(!empty($c['type'])): ?><?=$c['type']; ?><?php endif; ?>
                        </div>
                        <div class="list-col center w200 hide-1200">
                            <?php if(!empty($c['place'])): ?><?=$c['place']; ?><?php elseif(!empty($c['custom_place'])): ?><?=$c['custom_place']; ?><?php endif; ?>
                        </div>
                        <div class="list-col center w200 hide-500">
                            <?=date('d.m.Y', strtotime($c['date_start'])); ?><?php if(!empty($c['date_end']) && $c['date_start'] != $c['date_end']): ?> - <?=date('d.m.Y', strtotime($c['date_end'])); ?><?php endif; ?><?php if(!empty($c['hours'])): ?><br /><?=implode(', ', $c['hours']); ?><?php endif; ?>
                        </div>
                        <div class="list-col center w100">
                        <?php if(isset($_SESSION['role']) and !in_array($_SESSION['role'],array('editor','contributor'))): ?><a class="list-remove-btn" href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/delete-calendar/<?=$c['id']; ?>" data-title="<?=lang('Event.DeleteEventCalendar');?>" data-message="<?=lang('Event.CalendarDeleteConfirm') . ': <b>' . esc($c['name']) . '</b>'; ?>" data-btn-ok="<?=lang('Event.Remove');?>" data-btn-cancel="<?=lang('Event.Cancel');?>" title="<?=lang('Event.Delete');?>"><i class="fa-regular fa-trash-can fa-xl"></i></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?= view('admin/order_and_pagination', array('pager'=>$pager, 'order_list'=>$order_list)); ?>
    </div>
</div>
