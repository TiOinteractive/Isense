<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;
    } ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($place) &&!empty($place['id'])): ?>
                <?= $place['name']; ?>
            <span>
            <?= lang('Event.EventPlaceEdit'); ?>
            </span>
            <?php else: ?>
            <?= lang('Event.NewEventPlaceAdd'); ?>
        <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata' => !empty($flashdata) ? $flashdata : array())); ?>
        <form class="form event-place-form" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/event/<?php echo $action; ?>/<?php echo $id_content; ?><?= !empty($place['id']) ? '/' . $place['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Event.BasicInformation'); ?></h3>
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
                                <input class="link-name" type="text" name="lang[<?= $lang['id']; ?>][name]" value="<?= !empty($place['lang']) ? esc($place['lang'][$lang['id']]['name']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.DirectLink'); ?></label>
                            </div>
                            <div class="form-field">
                                <input class="link-page-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_page]" value="<?=!empty($page) ? $page['id_page'] : ''; ?>" />
                                <input class="link-id" type="hidden" name="lang[<?= $lang['id']; ?>][id_link]" value="<?= !empty($place['lang']) ? $place['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                <input class="link-id-lang" type="hidden" value="<?= $lang['id']; ?>" />
                                <input class="link-field" type="text" name="lang[<?= $lang['id']; ?>][link]" value="<?= !empty($place['lang']) ? esc($place['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Address'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lang['id']; ?>][address]"><?= !empty($place['lang']) ? $place['lang'][$lang['id']]['address'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.WorkingHours'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lang['id']; ?>][working_hours]"><?= !empty($place['lang']) ? $place['lang'][$lang['id']]['working_hours'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Content'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea class="wyswig-textarea" name="lang[<?= $lang['id']; ?>][content]"><?= !empty($place['lang']) ? $place['lang'][$lang['id']]['content'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.Repertoire'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea class="wyswig-textarea" name="lang[<?= $lang['id']; ?>][repertoire]"><?= !empty($place['lang']) ? $place['lang'][$lang['id']]['repertoire'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
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
                                <input type="text" name="meta[lang][<?= $lang['id']; ?>][title]" value="<?= !empty($place['meta']['lang']) ? $place['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.MetaDescription'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="meta[lang][<?= $lang['id']; ?>][description]"><?= !empty($place['meta']['lang']) ? $place['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Event.MetaKeywords'); ?></label>
                            </div>
                            <div class="form-field">
                                <textarea name="meta[lang][<?= $lang['id']; ?>][keywords]"><?= !empty($place['meta']['lang']) ? $place['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Event.EventPlaceMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.PrimaryPhoto'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($place['photo'])): ?>
                            <?= view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $place['photo'], 'multi' => false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?= lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-url="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Photos'); ?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($place['photos'])): ?>
                            <?php foreach($place['photos'] as $k => $photo): ?>
                                <?= view('admin/filemenager/upload_file', array('field' => 'photos', 'file' => $photo, 'multi' => true, 'no' => $k)); ?>
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
                        <?php if(!empty($place['audio'])): ?>
                            <?php foreach($place['audio'] as $k => $audio): ?>
                                <?= view('admin/filemenager/upload_file', array('field' => 'audio', 'file' => $audio, 'multi' => true, 'no' => $k)); ?>
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
                    <?php if(!empty($place['video'])): ?>
                        <?php foreach($place['video'] as $k => $video): ?>
                            <?= view('admin/filemenager/upload_file', array('field' => 'video', 'file' => $video, 'multi' => true, 'no' => $k)); ?>
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
                <h3><?= lang('Event.EventPlaceSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Type'); ?></label>
                </div>
                <div class="form-field">
                    <input class="link-module" type="hidden" value="event_place" />
                    <select name="id_type" class="link-page-id">
                       <?php if(!empty($types)): ?>
                            <?php foreach($types as $k=>$t): ?>
                                <?= view('\Modules\Event\Views\admin/event_place_type_select_parents', array('type'=>$t, 'id_parent'=>!empty($place['id_type']) ? $place['id_type'] : 0, 'count'=>count($types), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.City'); ?></label>
                </div>
                <div class="form-field">
                    <select name="id_city">
                        <option value="0"><?= lang('Event.ThereIsNoCity'); ?></option>
                        <?php if(!empty($cities)): ?>
                            <?php foreach($cities as $c): ?>
                                <option value="<?= $c['id']; ?>"<?= !empty($place['id_city']) && $place['id_city'] == $c['id'] ? ' selected="selected"' : ''; ?>><?= esc($c['name']); ?><?= empty($c['publish']) ? ' (' . lang('Event.OnlyUnpublished') . ')' : ''; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Street'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="street" value="<?= !empty($place['street']) ? esc($place['street']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.BuildingNo'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="building_no" value="<?= !empty($place['building_no']) ? esc($place['building_no']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Postcode'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="postcode" value="<?= !empty($place['postcode']) ? esc($place['postcode']) : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Template'); ?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?= $template['file']; ?>"<?= !empty($place) && $place['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?= $template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.WWW'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="www" value="<?=!empty($place['www']) ? $place['www'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Email'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="email" value="<?=!empty($place['email']) ? $place['email'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Phone'); ?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="phone" value="<?=!empty($place['phone']) ? $place['phone'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.SelectAsHome'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="home" <?php if(!empty($place['home']) && $place['home']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.UserComments'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="comment" <?php if(!empty($place['comment']) && $place['comment']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Event.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($place['publish']) && $place['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?= lang('Event.Save'); ?></button>
            </div>
        </form>
    </div>
</div>
