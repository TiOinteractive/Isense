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
                            <label><?=lang('Slider.slide.Title');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][title]" value="<?=!empty($slide['lang']) ? esc($slide['lang'][$lang['id']]['title']) : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Caption');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][caption]" value="<?=!empty($slide['lang']) ? esc($slide['lang'][$lang['id']]['caption']) : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Description');?></label>
                        </div>
                        <div class="form-field">
                            <textarea name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][description]"><?=!empty($slide['lang']) ? esc($slide['lang'][$lang['id']]['description']) : ''; ?></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Url');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][url]" value="<?=!empty($slide['lang']) ? esc($slide['lang'][$lang['id']]['url']) : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.Photo');?></label>
                        </div>
                        <div class="form-field">
                            <div class="files-list">
                                <?php if(!empty($slide['lang']) && !empty($slide['lang'][$lang['id']]) && !empty($slide['lang'][$lang['id']]['photo'])): ?>
                                    <?=view('admin/filemenager/files_list', array('files'=>array($slide['lang'][$lang['id']]['photo']), 'name'=>'slide['.(isset($no) ? $no : 0).'][lang][' . $lang['id'] . ']','key_name'=>'photo')); ?>
                                <?php endif; ?>
                            </div>
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Slider.slide.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="photo" data-type="image" data-field-name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?= $lang['id']; ?>]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.PhotoMobile');?></label>
                        </div>
                        <div class="form-field">
                            <div class="files-list">
                                <?php if(!empty($slide['lang']) && !empty($slide['lang'][$lang['id']]) && !empty($slide['lang'][$lang['id']]['m_photo'])): ?>
                                    <?=view('admin/filemenager/files_list', array('files'=>array($slide['lang'][$lang['id']]['m_photo']), 'name'=>'slide['.(isset($no) ? $no : 0).'][lang][' . $lang['id'] . ']','key_name'=>'m_photo')); ?>
                                <?php endif; ?>
                            </div>
                            <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Slider.slide.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="m_photo" data-type="image" data-field-name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?= $lang['id']; ?>]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                        </div>
                    </div>
                    <div class="form-row radio-select-box">
                        <div class="form-label radio-select-options">
                            <div class="option<?php if(empty($slide['lang'][$lang['id']]['video_radio']) || $slide['lang'][$lang['id']]['video_radio'] == 'id-video'): ?> active<?php endif; ?>">
                                <input type="radio" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][video_radio]" value="id-video" id="id-video-<?php echo isset($no) ? $no : 0; ?>-<?=$lang['id']; ?>"<?php if(empty($slide['lang'][$lang['id']]['video_radio']) || $slide['lang'][$lang['id']]['video_radio'] == 'id-video'): ?> checked="checked"<?php endif; ?> />
                                <label for="id-video-<?php echo isset($no) ? $no : 0; ?>-<?=$lang['id']; ?>"><?=lang('Slider.slide.Video');?></label>
                            </div>
                            <div class="option<?php if(!empty($slide['lang'][$lang['id']]['video_radio']) && $slide['lang'][$lang['id']]['video_radio'] == 'video-url'): ?> active<?php endif; ?>">
                                <input type="radio" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][video_radio]" value="video-url" id="video-url-<?php echo isset($no) ? $no : 0; ?>-<?=$lang['id']; ?>"<?php if(!empty($slide['lang'][$lang['id']]['video_radio']) && $slide['lang'][$lang['id']]['video_radio'] == 'video-url'): ?> checked="checked"<?php endif; ?> />
                                <label for="video-url-<?php echo isset($no) ? $no : 0; ?>-<?=$lang['id']; ?>"><?=lang('Slider.slide.VideoUrl');?></label>
                            </div>
                        </div>
                        <div class="form-field radio-select-items">
                            <div class="radio-select-item id-video<?php if(empty($slide['lang'][$lang['id']]['video_radio']) || $slide['lang'][$lang['id']]['video_radio'] == 'id-video'): ?> active<?php endif; ?>">
                                <div class="files-list">
                                    <?php if(!empty($slide['lang']) && !empty($slide['lang'][$lang['id']]) && !empty($slide['lang'][$lang['id']]['video'])): ?>
                                        <?=view('admin/filemenager/files_list', array('files'=>array($slide['lang'][$lang['id']]['video']), 'name'=>'slide['.(isset($no) ? $no : 0).'][lang][' . $lang['id'] . ']','key_name'=>'video')); ?>
                                    <?php endif; ?>
                                </div>
                                <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Slider.slide.AddChangeFile');?>" class="btn file-menager" data-multi="false" data-key="video" data-type="video" data-field-name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?= $lang['id']; ?>]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangeFile');?></a>
                            </div>
                            <div class="radio-select-item video-url<?php if(!empty($slide['lang'][$lang['id']]['video_radio']) && $slide['lang'][$lang['id']]['video_radio'] == 'video-url'): ?> active<?php endif; ?>">
                                <input type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][video_url]" value="<?=!empty($slide['lang']) ? esc($slide['lang'][$lang['id']]['video_url']) : ''; ?>" />
                                <span class="s">(<?=lang('Slider.slide.VideoUrlInfo');?>)</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Slider.slide.DisplayTimeLimitation');?></label>
                        </div>
                        <div class="form-field">
                            <input class="datepicker-range time" type="text" name="slide[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][time]" value="<?=!empty($slide['lang']) && !empty($slide['lang'][$lang['id']]['time']) ? esc($slide['lang'][$lang['id']]['time']) : ''; ?>" />
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
        <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Slider.slide.Archive');?></label>
        </div>
        <div class="form-field">
            <input type="checkbox" name="slide[<?php echo isset($no) ? $no : 0; ?>][archive]" value="1"<?php if(!empty($slide['archive']) && $slide['archive']) echo 'checked="checked"'; ?> />
        </div>
    </div>
</div>