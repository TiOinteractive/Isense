<div class="form-group order-item" data-no="<?php echo isset($no) ? $no : 0; ?>">
    <div class="form-group-head">
        <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
        <div class="no">#<?=$no; ?></div>
        <div class="delete">
            <a href="#" class="delete-slide" title="<?=lang('Slider.slide.Delete');?>" data-title="<?=lang('Slider.slide.DeleteSlide');?>" data-message="<?=lang('Slider.slide.ConfirmInfo');?>" data-btn-ok="<?=lang('Slider.slide.Delete');?>" data-btn-cancel="<?=lang('Slider.slide.Cancel');?>"><i class="fa-regular fa-trash-can"></i></a>
        </div>
    </div>
    <input type="hidden" name="slide[<?php echo isset($no) ? $no : 0; ?>][id]" value="<?=!empty($slide) ? $slide['id'] : ''; ?>" />
    <input class="order-field" type="hidden" name="slide[<?php echo isset($no) ? $no : 0; ?>][order]" value="<?=!empty($slide) ? $slide['order'] : ''; ?>" />
    <div class="tabs">
        <div class="tabs-head">
            <?php $l=0; foreach($languages as $lang): ?>
            <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
            <?php ++$l; endforeach; ?>
        </div>
        <div class="tabs-content">
            <?php $l=0; foreach($languages as $lang): ?>
                <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Title');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][title]" value="<?=!empty($slide['lang']) ? $slide['lang'][$lang['id']]['title'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Caption');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][caption]" value="<?=!empty($slide['lang']) ? $slide['lang'][$lang['id']]['caption'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Description');?></label>
                        </div>
                        <div class="form-field">
                            <textarea name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][description]"><?=!empty($slide['lang']) ? $slide['lang'][$lang['id']]['description'] : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Url');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][url]" value="<?=!empty($slide['lang']) ? $slide['lang'][$lang['id']]['url'] : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Publish');?></label>
                        </div>
                        <div class="form-field">
                            <input type="checkbox" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][publish]" value="1"<?php if(!empty($slide['lang']) && !empty($slide['lang'][$lang['id']]['publish']) && $slide['lang'][$lang['id']]['publish']) echo 'checked="checked"'; ?> />
                        </div>
                    </div>
                </div>
            <?php ++$l; endforeach; ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Slider.slide.Photo');?></label>
        </div>
        <div class="form-field">
            <div class="files-list">
                <?php if(!empty($slide['file'])): ?>
                    <?=view('admin/filemenager/files_list', array('files'=>array($slide['file']), 'name'=>'slide['.(isset($no) ? $no : 0).']','key_name'=>'file')); ?>
                <?php endif; ?>
            </div>
            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Slider.slide.AddFile');?>" class="btn file-menager"  data-multi="false" data-type="image" data-field-name="slide[<?php echo isset($no) ? $no : 0; ?>]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddFile');?></a>
        </div>
    </div>
	<div class="form-row">
        <div class="form-label">
            <label><?=lang('Slider.slide.PhotoMobile');?></label>
        </div>
        <div class="form-field">
            <div class="files-list">
                <?php if(!empty($slide['file_mobile'])): ?>
                    <?=view('admin/filemenager/files_list', array('files'=>array($slide['file_mobile']), 'name'=>'slide['.(isset($no) ? $no : 0).']','key_name'=>'file_mobile')); ?>
                <?php endif; ?>
            </div>
            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Slider.slide.AddFile');?>" class="btn file-menager" data-multi="false" data-key="file_mobile" data-type="image" data-field-name="slide[<?php echo isset($no) ? $no : 0; ?>]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddFile');?></a>
        </div>
    </div>
</div>