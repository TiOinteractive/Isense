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
                        <input type="text" name="form_data[lang][<?=$lang['id']; ?>][name]" value="<?=!empty($form_data['lang']) ? esc($form_data['lang'][$lang['id']]['name']) : ''; ?>" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Gallery.Introduction');?></label>
                    </div>
                    <div class="form-field">
                        <textarea name="form_data[lang][<?=$lang['id']; ?>][introduction]"><?=!empty($form_data['lang']) ? esc($form_data['lang'][$lang['id']]['introduction']) : ''; ?></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Gallery.Content');?></label>
                    </div>
                    <div class="form-field">
                        <textarea class="wyswig-textarea" name="form_data[lang][<?=$lang['id']; ?>][content]"><?=!empty($form_data['lang']) ? $form_data['lang'][$lang['id']]['content'] : ''; ?></textarea>
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
            <?php if(!empty($form_data['photo'])): ?>
                <?=view('admin/filemenager/upload_file', array('field' => 'form_data[photo]', 'file' => $form_data['photo'], 'multi' => false)); ?>
            <?php endif; ?>
        </div>
        <span class="btn fileinput-button">
            <span><?=lang('Admin.file-menager.AddChangeFile'); ?></span>
            <input class="fileupload" type="file" name="file" data-type="image" data-field="form_data[photo]" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" >
        </span>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Gallery.Photos');?></label>
    </div>
    <div class="form-field">
        <div class="files-list order-box">
            <?php if(!empty($form_data['photos'])): ?>
                <?php foreach($form_data['photos'] as $k=>$photo): ?>
                    <?=view('admin/filemenager/upload_file', array('field' => 'form_data[photos]', 'file' => $photo, 'multi' => true, 'no' => $k)); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <span class="btn fileinput-button">
            <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
            <input class="fileupload" type="file" name="file" data-type="image" data-field="form_data[photos]" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
        </span>
    </div>
</div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.Audio');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($form_data['audio'])): ?>
                            <?php foreach($form_data['audio'] as $k=>$audio): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'orm_data[audio]', 'file' => $audio, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="audio" data-field="form_data[audio]" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
                    </span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Gallery.Video');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list order-box">
                        <?php if(!empty($form_data['video'])): ?>
                            <?php foreach($form_data['video'] as $k=>$video): ?>
                                <?=view('admin/filemenager/upload_file', array('field' => 'orm_data[video]', 'file' => $video, 'multi' => true, 'no' => $k)); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <span class="btn fileinput-button">
                        <span><?=lang('Admin.file-menager.AddFiles'); ?></span>
                        <input class="fileupload" type="file" name="file" data-type="video" data-field="form_data[video]" data-url="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/upload/tmp" multiple>
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
        <select name="form_data[template]">
            <?php if(!empty($templates)): ?>
                <?php foreach($templates as $template): ?>
                    <option value="<?=$template['file']; ?>"<?=!empty($form_data) && $form_data['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?=$template['name']; ?></option>
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
        <input type="checkbox" name="form_data[home]" <?php if(!empty($form_data['home']) && $form_data['home']):?>checked="checked"<?php endif; ?>  value="1" >
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Gallery.Publish');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="form_data[publish]" <?php if(!empty($form_data['publish']) && $form_data['publish']):?>checked="checked"<?php endif; ?>  value="1" >
    </div>
</div>