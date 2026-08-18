<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($event) &&!empty($event['id'])): ?>
                <?= $event['name']; ?>
            <span>
            <?= lang('Event.NewEventRepertoireAdd'); ?>
            </span>
            <?php else: ?>
                <?= lang('Event.NewEventRepertoireAdd'); ?>
            <?php endif; ?>
        </div>
        <?=view('Modules\Event\Views\admin\event_tabs'); ?>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <?= view('Modules\Event\Views\admin\event_repertoire_statistics', array('statistics' => !empty($flashdata) && !empty($flashdata['statistics']) ? $flashdata['statistics'] : array())); ?>
        <form class="form event-repertoire-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/<?php echo $action; ?><?= !empty($event['id']) ? '/' . $event['id'] : ''; ?>" method="post">
            <div class="flex-row">
                <div class="left25">
                    <div class="form-row nag">
                        <h3><?= lang('Event.EventDate'); ?></h3>
                    </div>
                    <?php
                        $date = date('d.m.Y');
                        $no = 120;
                    ?>
                    <div class="">
                        <div class="available-dates-list">
                            <?php for($i=0; $i<$no; $i++): $time = strtotime($date); ?>
                                <?php if($i==0 || date('j', $time) == 1): ?>
                                    <div class="date-head">
                                        <h4><?=lang('Admin.months_names.' . date('F', $time)); ?> <?=date('Y', $time); ?></h4>
                                    </div>
                                <?php endif; ?>
                                <div class="date">
                                    <input type="checkbox" name="date[]" value="<?=$date; ?>" id="date-<?=$date; ?>">
                                    <label for="date-<?=$date; ?>"><?php if(in_array(date('N', $time), array(6,7))): ?><strong><?php endif; ?><?=date('d.m', $time); ?> <?=lang('Admin.days_names.' . date('l', $time)); ?><?php if(in_array(date('N', $time), array(6,7))): ?></strong><?php endif; ?></label>
                                </div>
                            <?php $date = date('Y-m-d', strtotime($date . ' +1 day')); endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="right75">
                    <div class="form-row nag">
                        <h3><?= lang('Event.BasicInformation'); ?></h3>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Event.EventPlace'); ?></label>
                        </div>
                        <div class="form-field">
                            <div class="form-cols">
                                <?php if(!empty($places)): ?>
                                    <?php foreach($places as $place): ?>
                                        <div class="form-col col-5">
                                            <input type="checkbox" name="place[]" value="<?= $place['id']; ?>" id="place-<?= $place['id']; ?>" <?= !empty($repertoire) && !empty($repertoire['id_place']) && $repertoire['id_place'] == $place['id'] ? ' checked="checked"' : ''; ?> />
                                            <label for="place-<?= $place['id']; ?>"><?= $place['name']; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Event.EventCustomPlace'); ?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="custom_place" value="<?=!empty($repertoire) && !empty($repertoire['custom_place']) ? $repertoire['custom_place'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Event.Hour'); ?></label>
                        </div>
                        <div class="form-field">
                            <div class="events-hours">
                                <?php if(!empty($repertoire['hour'])): ?>
                                    <?php foreach($repertoire['hour'] as $k=>$hour): ?>
                                        <?= view('Modules\Event\Views\admin\event_repertoire_hour', array('remove' => $k ? 1 : 0, 'hour' => $hour)); ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?= view('Modules\Event\Views\admin\event_repertoire_hour'); ?>
                                <?php endif; ?>
                                <a class="btn add-event-hour" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/add-event-hour" title="<?=lang('Event.AddEventHour'); ?>"><?=lang('Event.AddEventHour'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Event.Save'); ?></button>
            </div>
        </form>
    </div>
</div>
