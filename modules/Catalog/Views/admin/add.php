<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($catalog) && !empty($catalog['id'])): ?>
                <?=$catalog['name']; ?>
                <span><?=lang('Catalog.CatalogEdit'); ?></span>
            <?php else: ?>
                <?=lang('Catalog.NewCatalogAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form catalog-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/catalog/<?php echo $action; ?>/<?=$id_content; ?><?=!empty($catalog['id']) ? '/' . $catalog['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Catalog.PrimarySettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Catalog.CatalogType');?></label>
                </div>
                <div class="form-field">
                    <select name="type" style="width:auto;">
                        <option value="simple"<?php if(!empty($catalog['type']) && $catalog['type'] == 'simple'): ?> selected="selected"<?php endif; ?>><?=lang('Catalog.type.Simple'); ?></option>
                        <option value="nolink"<?php if(!empty($catalog['type']) && $catalog['type'] == 'nolink'): ?> selected="selected"<?php endif; ?>><?=lang('Catalog.type.NoLink'); ?></option>
                        <option value="slider"<?php if(!empty($catalog['type']) && $catalog['type'] == 'slider'): ?> selected="selected"<?php endif; ?>><?=lang('Catalog.type.Slider'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row nag">
                <h3><?=lang('Catalog.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                    <?php $l=0; foreach($languages as $lang): ?>
                        <div class="link-box lang-<?=$lang['id']; ?> tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.Name');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($catalog['lang']) ? esc($catalog['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row type-option simple slider<?php if(!empty($catalog['type']) && $catalog['type'] == 'nolink'): ?> hidden<?php endif; ?>">
                                <div class="form-label">
                                    <label><?=lang('Catalog.DirectLink');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-page-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_page]" value="<?=!empty($page) ? $page['id_page'] : ''; ?>" />
                                    <input class="link-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_link]" value="<?=!empty($catalog['lang']) ? $catalog['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?=$lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?=$lang['id']; ?>][link]" value="<?=!empty($catalog['lang']) ? esc($catalog['lang'][$lang['id']]['link']) : ''; ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.Content');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][content]"><?=!empty($catalog['lang']) ? $catalog['lang'][$lang['id']]['content'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.Address');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="address-field" name="lang[<?=$lang['id']; ?>][address]"><?=!empty($catalog['lang']) ? $catalog['lang'][$lang['id']]['address'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.OpenHours');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][open_hours]"><?=!empty($catalog['lang']) ? $catalog['lang'][$lang['id']]['open_hours'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.Tags');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="lang[<?=$lang['id']; ?>][tags]" class="tags_input" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/tags/searchtags/<?=$id_content; ?>" value="<?=!empty($catalog['lang']) ? esc($catalog['lang'][$lang['id']]['tags']) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Catalog.Website');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="website" value="<?=!empty($catalog['website']) ? $catalog['website'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Catalog.Email');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="email" value="<?=!empty($catalog['email']) ? $catalog['email'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Catalog.Phone');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="phone" value="<?=!empty($catalog['phone']) ? $catalog['phone'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Catalog.Metatags'); ?></h3>
            </div>
            <div class="tabs">
                <?php if(!empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                    <?php $l=0; foreach($languages as $lang): ?>
                        <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.MetaTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="meta[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($catalog['meta']['lang']) ? $catalog['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][description]"><?=!empty($catalog['meta']['lang']) ? $catalog['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Catalog.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][keywords]"><?=!empty($catalog['meta']['lang']) ? $catalog['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Catalog.CatalogMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('News.PrimaryPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($catalog['photo'])): ?>
                            <?=view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $catalog['photo'], 'multi' => false)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddChangeFile'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photo" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Catalog.Photos');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($catalog['photos'])): ?>
                            <?php foreach($catalog['photos'] as $k=>$photo): ?>
                                <?=view('admin/filemenager/upload_' . (!empty($catalog['type']) && $catalog['type'] == 'slider' ? 'slider' : 'file'), array('field' => 'photos', 'file' => $photo, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photos" data-option="<?=!empty($catalog['type']) ? $catalog['type'] : ''; ?>" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Catalog.CatalogSettings'); ?></h3>
            </div>
            <div class="form-row type-option simple slider<?php if(!empty($catalog['type']) && $catalog['type'] == 'nolink'): ?> hidden<?php endif; ?>">
                <div class="form-label">
                    <label><?=lang('Catalog.Template');?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?=$template['file']; ?>"<?=!empty($catalog) && $catalog['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?=$template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Catalog.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($catalog['publish']) && $catalog['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Catalog.MapCoordinates');?></label>
                </div>
                <div class="form-field">
                    <input class="cords-field" type="text" name="cords" value="<?=!empty($catalog['cords']) ? $catalog['cords'] : '0,0'; ?>" />
                    <div class="map-container" id="map"></div>
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Catalog.Save'); ?></button>
            </div>
        </form>
    </div>
</div>