<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($event) && !empty($event['id'])): ?>
                <?= $event['name']; ?>
                <span>
                    <?= lang('Event.EventEdit'); ?>
                </span>
            <?php elseif($action == 'copy' && !empty($event) && !empty($event['name'])): ?>
                <?= $event['name']; ?>
                <span>
                    <?= lang('Event.EventCopy'); ?>
                </span>
            <?php else: ?>
                <?= lang('Event.NewEventAdd'); ?>
            <?php endif; ?>
        </div>
        <?=view('Modules\Event\Views\admin\event_tabs'); ?>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form event-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/<?php echo $action; ?>/<?=$id_content; ?><?= !empty($event['id']) ? '/' . $event['id'] : ''; ?>" method="post">
            <div class="form-row nag with-btn">
                <h3><?= lang('Event.BasicInformation'); ?></h3>
                <?php if(!empty($event) && !empty($event['id'])): ?>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/calendar?event=<?=$event['id']; ?>" class="btn add-slide" title=""><?= lang('Event.CheckEventCalendar'); ?></a>
                <?php endif; ?>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l = 0;
                        foreach($languages as $lang): ?>
                        <div class="tab<?= $l==0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                        <?php ++$l;
                        endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                <?php $l = 0; foreach($languages as $lang): ?>
                    <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l==0 ? ' active' : ''; ?>">
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Name'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($event['lang']) ? esc($event['lang'][$lang['id']]['name']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.SubName'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lang['id']; ?>][subname]" value="<?= !empty($event['lang']) ? esc($event['lang'][$lang['id']]['subname']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.DirectLink'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-page-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_page]" value="<?=!empty($page) ? $page['id_page'] : ''; ?>" />
                                <input class="link-id" type="hidden" name="lang[<?= $lang['id']; ?>][id_link]" value="<?= !empty($event['lang']) ? $event['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                <input class="link-id-lang" type="hidden" value="<?= $lang['id']; ?>" />
                                <input class="link-field" type="text" name="lang[<?= $lang['id']; ?>][link]" value="<?= !empty($event['lang']) ? esc($event['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Introduction'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lang['id']; ?>][introduction]"><?= !empty($event['lang']) ? esc($event['lang'][$lang['id']]['introduction']) : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Content'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea class="wyswig-textarea" name="lang[<?= $lang['id']; ?>][content]"><?= !empty($event['lang']) ? $event['lang'][$lang['id']]['content'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Comments'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lang['id']; ?>][comments]"><?= !empty($event['lang']) ? esc($event['lang'][$lang['id']]['comments']) : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.TicketPrice'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea class="sm-h" name="lang[<?= $lang['id']; ?>][price]"><?= !empty($event['lang']) ? esc($event['lang'][$lang['id']]['price']) : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Tickets'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lang['id']; ?>][tickets]"><?= !empty($event['lang']) ? esc($event['lang'][$lang['id']]['tickets']) : ''; ?></textarea>
                            </div>
                        </div>
			<div class="form-row">
                            <div class="form-label">
                                <label><?=lang('Event.Tags');?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?=$lang['id']; ?>][tags]" class="tags_input" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/tags/searchtags/<?=$id_content; ?>" value="<?=!empty($event['lang']) ? esc($event['lang'][$lang['id']]['tags']) : ''; ?>" />
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Type'); ?></label>
                </div>
                <div class="form-field">
                    <select name="id_type">
                        <?php if(!empty($types)): ?>
                            <?php foreach($types as $type): ?>
                                <option value="<?= $type['id']; ?>"<?= !empty($event) && $event['id_type'] == $type['id'] ? ' selected="selected"' : ''; ?>><?= $type['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <?php /*
            <div class="form-row-space"></div>
            <div class="form-row nag with-btn">
                <h3><?= lang('Event.EventDate'); ?></h3>
                <?php if(!empty($event) && !empty($event['id'])): ?>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/calendar?event=<?=$event['id']; ?>" class="btn add-slide" title=""><?= lang('Event.CheckEventCalendar'); ?></a>
                <?php endif; ?>
            </div>
            */ ?>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.EventPlace'); ?></label>
                </div>
                <div class="form-field">
                    <select class="short" name="place[]">
                        <option value=''></option>
                        <?php if(!empty($places)): ?>
                            <?php foreach($places as $place): ?>
                                <option value="<?= $place['id']; ?>"<?php if(!empty($event) && !empty($event['repertoire']) && !empty($event['repertoire']['places']) && in_array($place['id'], $event['repertoire']['places'])): ?> selected="selected"<?php endif; ?>><?= $place['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    &nbsp;
                    <input class="short" type="text" name="custom_place" value="<?=!empty($event) && !empty($event['repertoire']) && !empty($event['repertoire']['custom_place']) ? $event['repertoire']['custom_place'] : ''; ?>" placeholder="<?= lang('Event.EventCustomPlace'); ?>" />
                    <?php /*
                    <select name="place[]" multiple="multiple">
                        <option value=''></option>
                        <?php if(!empty($places)): ?>
                            <?php foreach($places as $place): ?>
                                <option value="<?= $place['id']; ?>"<?php if(!empty($event) && !empty($event['repertoire']) && !empty($event['repertoire']['places']) && in_array($place['id'], $event['repertoire']['places'])): ?> selected="selected"<?php endif; ?>><?= $place['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="form-cols">
                        <?php if(!empty($places)): ?>
                            <?php foreach($places as $place): ?>
                                <div class="form-col col-3">
                                    <input type="checkbox" name="place[]" value="<?= $place['id']; ?>" id="place-<?= $place['id']; ?>" <?= !empty($repertoire) && !empty($repertoire['id_place']) && $repertoire['id_place'] == $place['id'] ? ' checked="checked"' : ''; ?> />
                                    <label for="place-<?= $place['id']; ?>"><?= $place['name']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                     */ ?>
                </div>
            </div>
            <?php /*
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.EventCustomPlace'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="custom_place" value="<?=!empty($event) && !empty($event['repertoire']) && !empty($event['repertoire']['custom_place']) ? $event['repertoire']['custom_place'] : ''; ?>" />
                </div>
            </div>
            */ ?>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.EventDate'); ?></label>
                </div>
                <div class="form-field">
                    <?php /*<input class="datepicker-range" type="text" name="date" value="<?=!empty($repertoire) && !empty($repertoire['date']) ? $repertoire['date'] : ''; ?>" autocomplete="off" />*/ ?>
                    <input class="datepicker-date short" type="text" name="date_start" value="<?=!empty($event) && !empty($event['repertoire']) && !empty($event['repertoire']['date_start']) ? date('d.m.Y', strtotime($event['repertoire']['date_start'])) : ''; ?>" autocomplete="off" />
                    -
                    <input class="datepicker-date short" type="text" name="date_end" value="<?=!empty($event) && !empty($event['repertoire']) && !empty($event['repertoire']['date_end']) ? date('d.m.Y', strtotime($event['repertoire']['date_end'])) : ''; ?>" autocomplete="off" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Hour'); ?></label>
                </div>
                <div class="form-field">
                    <div class="events-hours">
                        <?php if(!empty($event) && !empty($event['repertoire']) && !empty($event['repertoire']['hours'])): ?>
                            <?php foreach($event['repertoire']['hours'] as $k=>$h): ?>
                                <?= view('Modules\Event\Views\admin\event_repertoire_hour', array('hour' => $h, 'remove' => $k > 0)); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?= view('Modules\Event\Views\admin\event_repertoire_hour'); ?>
                        <?php endif; ?>
                        <a class="btn add-event-hour" href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/add-event-hour" title="<?=lang('Event.AddEventHour'); ?>"><?=lang('Event.AddEventHour'); ?></a>
                    </div>
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Event.EventSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Template'); ?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?= $template['file']; ?>"<?= !empty($event) && $event['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?= $template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.SelectAsHome'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="home" <?php if(empty($event['id']) || (!empty($event['home']) && $event['home'])): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Patronage'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="patronage" <?php if(!empty($event['patronage']) && $event['patronage']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.ForKids'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="for_kids" <?php if(!empty($event['for_kids']) && $event['for_kids']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Recommended'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="recommended" <?php if(!empty($event['recommended']) && $event['recommended']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.UserComments'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="comment" <?php if(empty($event['id']) || (!empty($event['comment']) && $event['comment'])): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(empty($event['id']) || (!empty($event['publish']) && $event['publish'])): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Event.EventMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.PrimaryPhoto'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($event['photo'])): ?>
                            <?= view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $event['photo'], 'multi' => false, 'crop' => $action != 'copy' ? true : false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-crop="true" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Photos'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($event['photos'])): ?>
                            <?php foreach($event['photos'] as $k => $photo): ?>
                                <?= view('admin/filemenager/upload_file', array('field' => 'photos', 'file' => $photo, 'multi' => true, 'no' => $k,'crop'=>false)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photos" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Audio'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($event['audio'])): ?>
                            <?php foreach($event['audio'] as $k => $audio): ?>
                                <?= view('admin/filemenager/upload_file', array('field' => 'audio', 'file' => $audio, 'multi' => true, 'no' => $k,'crop'=>false)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="audio" data-field="audio" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Video'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                    <?php if(!empty($event['video'])): ?>
                        <?php foreach($event['video'] as $k => $video): ?>
                            <?= view('admin/filemenager/upload_file', array('field' => 'video', 'file' => $video, 'multi' => true, 'no' => $k,'crop'=>false)); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="video" data-field="video" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Event.Metatags'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l = 0; foreach($languages as $lang): ?>
                            <div class="tab<?= $l==0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                <?php $l = 0; foreach($languages as $lang): ?>
                    <div class="tab-item<?= $l==0 ? ' active' : ''; ?>">
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.MetaTitle'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="meta[lang][<?= $lang['id']; ?>][title]" value="<?= !empty($event['meta']['lang']) ? $event['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.MetaDescription'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="meta[lang][<?= $lang['id']; ?>][description]"><?= !empty($event['meta']['lang']) ? $event['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Event.Save'); ?></button>
            </div>
        </form>
    </div>
</div>
