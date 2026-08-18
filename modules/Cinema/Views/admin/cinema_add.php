<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($movie) &&!empty($movie['id'])): ?>
                <?= $movie['title']; ?>
            <span>
            <?= lang('Cinema.NewCalendarAdd'); ?>
            </span>
            <?php else: ?>
            <?= lang('Cinema.NewCalendarAdd'); ?>
        <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <?= view('Modules\Cinema\Views\admin\cinema_statistics', array('statistics' => !empty($flashdata) && !empty($flashdata['statistics']) ? $flashdata['statistics'] : array())); ?>
        <form class="form cinema-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/cinema/<?php echo $action; ?><?= !empty($movie['id']) ? '/' . $movie['id'] : ''; ?>" method="post">
            <div class="flex-row">
                <div class="left25">
                    <div class="form-row nag">
                        <h3><?= lang('Cinema.CinemaDate'); ?></h3>
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
                        <h3><?= lang('Cinema.BasicInformation'); ?></h3>
                    </div>
                    <?php if(!empty($movie) &&!empty($movie['id'])): ?>
                        <input type="hidden" name="id_movie" value="<?=$movie['id']; ?>" />
                    <?php else: ?>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Cinema.Movie'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="multi-select-search" type="text" name="szukaj" value="" placeholder="<?=lang('Cinema.FindMovie'); ?>" autocomplete="off" />
                                <div class="multi-select-box">
                                    <?= view('Modules\Cinema\Views\admin\announcement_movies'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Cinema.CinemaPlace'); ?></label>
                        </div>
                        <div class="form-field">
                            <select name="id_place">
                                <?php if(!empty($places)): ?>
                                    <?php foreach($places as $place): ?>
                                        <option value="<?= $place['id']; ?>"<?= !empty($calendar) && !empty($calendar['id_place']) && $calendar['id_place'] == $place['id'] ? ' selected="selected"' : ''; ?>><?= $place['name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Cinema.Type'); ?></label>
                        </div>
                        <div class="form-field">
                            <?php if(!empty($types)): ?>
                                <div class="form-cols">
                                    <?php foreach($types as $type): ?>
                                        <div class="form-col col-2">
                                            <input id="type-<?= $type['id']; ?>" type="checkbox" name="type[]" value="<?= $type['id']; ?>"<?= !empty($movie) && !empty($movie['types']) && in_array($type['id'], $movie['types']) ? ' checked="checked"' : ''; ?> />
                                            <label for="type-<?= $type['id']; ?>"><?= $type['name']; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Cinema.Hour'); ?></label>
                        </div>
                        <div class="form-field">
                            <div class="cinema-hours">
                                <?php if(!empty($calendar['hour'])): ?>
                                    <?php foreach($calendar['hour'] as $k=>$hour): ?>
                                        <?= view('Modules\Cinema\Views\admin\cinema_hour', array('remove' => $k ? 1 : 0, 'hour' => $hour, 'h' => $k)); ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?= view('Modules\Cinema\Views\admin\cinema_hour', array('h' => 0)); ?>
                                <?php endif; ?>
                                <a class="btn add-cinema-hour" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/cinema/add-cinema-hour" title="<?=lang('Event.AddEventHour'); ?>"><?=lang('Cinema.AddCinemaHour'); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Cinema.Premiere'); ?></label>
                        </div>
                        <div class="form-field">
                            <input type="checkbox" name="premiere" <?php if(!empty($calendar['premiere']) && $calendar['premiere']): ?>checked="checked"<?php endif; ?>  value="1" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?= lang('Cinema.PrePremiere'); ?></label>
                        </div>
                        <div class="form-field">
                            <input type="checkbox" name="pre-premiere" <?php if(!empty($calendar['pre-premiere']) && $calendar['pre-premiere']): ?>checked="checked"<?php endif; ?>  value="1" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Cinema.Save'); ?></button>
            </div>
        </form>
    </div>
</div>