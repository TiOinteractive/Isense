<div class="form-group" data-no="<?php echo isset($no) ? $no : 0; ?>">
    <div class="form-group-head">
        <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
        <div class="no">#<?=$no; ?></div>
        <div class="delete">
            <a href="#" class="delete-marker" title="<?=lang('Maps.marker.Delete');?>" data-title="<?=lang('Maps.marker.DeleteMarker');?>" data-message="<?=lang('Maps.marker.ConfirmInfo');?>" data-btn-ok="<?=lang('Maps.marker.Delete');?>" data-btn-cancel="<?=lang('Maps.marker.Cancel');?>"><i class="fa-regular fa-trash-can"></i></a>
        </div>
    </div>
    <input type="hidden" name="marker[<?php echo isset($no) ? $no : 0; ?>][id]" value="<?=!empty($marker) ? $marker['id'] : ''; ?>" />
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
                            <label><?=lang('Maps.marker.Title');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="marker[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][title]" value="<?=!empty($marker['lang']) ? esc($marker['lang'][$lang['id']]['title']) : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Maps.marker.Caption');?></label>
                        </div>
                        <div class="form-field">
                            <textarea name="marker[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][caption]"><?=!empty($marker['lang']) ? esc($marker['lang'][$lang['id']]['caption']) : ''; ?></textarea>
                        </div>
                    </div>
                </div>
            <?php ++$l; endforeach; ?>
        <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
        <div class="form-row">
            <div class="form-label">
                <label><?=lang('Maps.marker.Coordinates');?></label>
            </div>
            <div class="form-field">
                <input type="text" name="marker[<?php echo isset($no) ? $no : 0; ?>][cords]" value="<?=!empty($marker['cords']) ? esc($marker['cords']) : ''; ?>" />
            </div>
        </div>
        <div class="form-row radio-select-box">
            <div class="form-label radio-select-options">
                <div class="option<?php if(empty($marker['file_radio']) || $marker['file_radio'] == 'id-file'): ?> active<?php endif; ?>">
                    <input type="radio" name="marker[<?php echo isset($no) ? $no : 0; ?>][file_radio]" value="id-file" id="id-file-<?php echo isset($no) ? $no : 0; ?>"<?php if(empty($marker['file_radio']) || $marker['file_radio'] == 'id-file'): ?> checked="checked"<?php endif; ?> />
                    <label for="id-file-<?php echo isset($no) ? $no : 0; ?>"><?=lang('Maps.marker.File');?></label>
                </div>
                <div class="option<?php if(!empty($marker['file_radio']) && $marker['file_radio'] == 'svg'): ?> active<?php endif; ?>">
                    <input type="radio" name="marker[<?php echo isset($no) ? $no : 0; ?>][file_radio]" value="svg" id="svg-<?php echo isset($no) ? $no : 0; ?>"<?php if(!empty($marker['file_radio']) && $marker['file_radio'] == 'svg'): ?> checked="checked"<?php endif; ?> />
                    <label for="svg-<?php echo isset($no) ? $no : 0; ?>"><?=lang('Maps.marker.SvgIcon');?></label>
                </div>
            </div>
            <div class="form-field radio-select-items">
                <div class="radio-select-item id-file<?php if(empty($marker['file_radio']) || $marker['file_radio'] == 'id-file'): ?> active<?php endif; ?>">
                    <div class="files-list">
                        <?php if(!empty($marker) && !empty($marker['file'])): ?>
                            <?=view('admin/filemenager/files_list', array('files'=>array($marker['file']), 'name'=>'marker['.(isset($no) ? $no : 0).']','key_name'=>'file')); ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Maps.marker.AddChangeFile');?>" class="btn file-menager" data-multi="false" data-key="file" data-type="image" data-field-name="marker[<?php echo isset($no) ? $no : 0; ?>]" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Maps.marker.AddChangeFile');?></a>
                </div>
                <div class="radio-select-item svg<?php if(!empty($marker['file_radio']) && $marker['file_radio'] == 'svg'): ?> active<?php endif; ?>">
                    <textarea name="marker[<?php echo isset($no) ? $no : 0; ?>][svg]"><?=!empty($marker) ? esc($marker['svg']) : ''; ?></textarea>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">
                <label><?=lang('Maps.marker.Publish');?></label>
            </div>
            <div class="form-field">
                <input type="checkbox" name="marker[<?php echo isset($no) ? $no : 0; ?>][publish]" value="1"<?php if(!empty($marker) && !empty($marker['publish']) && $marker['publish']) echo 'checked="checked"'; ?> />
            </div>
        </div>
    </div>
</div>