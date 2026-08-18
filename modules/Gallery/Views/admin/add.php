<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?php if(!empty($gallery) && !empty($gallery['id'])): ?>
                <?=$gallery['name']; ?>
                <span><?=lang('Gallery.GalleryEdit'); ?></span>
            <?php else: ?>
                <?=lang('Gallery.NewGalleryAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form news-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/gallery/<?php echo $action; ?>/<?=$id_content; ?><?=!empty($gallery['id']) ? '/' . $gallery['id'] : '' ; ?>" method="post">
            <div class="form-row nag">
                <h3><?=lang('Gallery.BasicInformation'); ?></h3>
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
                                    <label><?=lang('Gallery.Name');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-name" type="text" name="lang[<?=$lang['id']; ?>][name]" value="<?=!empty($gallery['lang']) ? esc($gallery['lang'][$lang['id']]['name']) : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Gallery.DirectLink');?></label>
                                </div>
                                <div class="form-field">
                                    <input class="link-page-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_page]" value="<?=!empty($page) ? $page['id_page'] : ''; ?>" />
                                    <input class="link-id" type="hidden" name="lang[<?=$lang['id']; ?>][id_link]" value="<?=!empty($gallery['lang']) ? $gallery['lang'][$lang['id']]['id_link'] : ''; ?>" />
                                    <input class="link-id-lang" type="hidden" value="<?=$lang['id']; ?>" />
                                    <input class="link-field" type="text" name="lang[<?=$lang['id']; ?>][link]" value="<?=!empty($gallery['lang']) ? $gallery['lang'][$lang['id']]['link'] : ''; ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Gallery.Introduction');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="lang[<?=$lang['id']; ?>][introduction]"><?=!empty($gallery['lang']) ? esc($gallery['lang'][$lang['id']]['introduction']) : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Gallery.Content');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea class="wyswig-textarea" name="lang[<?=$lang['id']; ?>][content]"><?=!empty($gallery['lang']) ? $gallery['lang'][$lang['id']]['content'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Gallery.MetaTags'); ?></h3>
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
                                    <label><?=lang('Gallery.MetaTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="meta[lang][<?=$lang['id']; ?>][title]" value="<?=!empty($gallery['meta']['lang']) ? $gallery['meta']['lang'][$lang['id']]['title'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Gallery.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][description]"><?=!empty($gallery['meta']['lang']) ? $gallery['meta']['lang'][$lang['id']]['description'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Gallery.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta[lang][<?=$lang['id']; ?>][keywords]"><?=!empty($gallery['meta']['lang']) ? $gallery['meta']['lang'][$lang['id']]['keywords'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Gallery.GalleryMedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.PrimaryPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($gallery['photo'])): ?>
                            <?=view('admin/filemenager/upload_file', array('field' => 'photo', 'file' => $gallery['photo'], 'multi' => false)); ?>
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
                    <label><?=lang('Gallery.Photos');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($gallery['photos'])): ?>
                            <?php foreach($gallery['photos'] as $k=>$photo): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'photos', 'file' => $photo, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="image" data-field="photos" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.Audio');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($gallery['audio'])): ?>
                            <?php foreach($gallery['audio'] as $k=>$audio): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'audio', 'file' => $audio, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="audio" data-field="audio" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.Video');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($gallery['video'])): ?>
                            <?php foreach($gallery['video'] as $k=>$video): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'video', 'file' => $video, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="video" data-field="video" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Gallery.GallerySettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.Template');?></label>
                </div>
                <div class="form-field">
                    <select name="template">
                        <?php if(!empty($templates)): ?>
                            <?php foreach($templates as $template): ?>
                                <option value="<?=$template['file']; ?>"<?=!empty($gallery) && $gallery['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?=$template['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
			 <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.SelectAsHome');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="home" <?php if(!empty($gallery['home']) && $gallery['home']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.Publish');?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" <?php if(!empty($gallery['publish']) && $gallery['publish']):?>checked="checked"<?php endif; ?>  value="1" >
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit" class=""><?=lang('Gallery.Save'); ?></button>
            </div>
        </form>
    </div>
</div>