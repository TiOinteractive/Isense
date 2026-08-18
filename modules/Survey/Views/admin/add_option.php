<div class="form-group order-item" data-no="<?php echo isset($no) ? $no : 0; ?>">
    <div class="form-group-head">
        <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
        <div class="no">#<?=$no; ?></div>
        <div class="delete">
            <a href="#" class="delete-option" title="<?=lang('Survey.option.Delete');?>" data-title="<?=lang('Survey.option.DeleteOption');?>" data-message="<?=lang('Survey.option.ConfirmInfo');?>" data-btn-ok="<?=lang('Survey.option.Delete');?>" data-btn-cancel="<?=lang('Survey.option.Cancel');?>"><i class="fa-regular fa-trash-can"></i></a>
        </div>
    </div>
    <input type="hidden" name="options[<?php echo isset($no) ? $no : 0; ?>][name]" value="<?=!empty($option) && !empty($option['name']) ? $option['name'] : ''; ?>" />
    <input type="hidden" name="options[<?php echo isset($no) ? $no : 0; ?>][id]" value="<?=!empty($option) ? $option['id'] : ''; ?>" />
    <input class="order-field" type="hidden" name="options[<?php echo isset($no) ? $no : 0; ?>][order]" value="<?=!empty($option) ? $option['order'] : ''; ?>" />
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
                            <label><?=lang('Survey.option.Option');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="options[<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][option]" value="<?=!empty($option['lang']) ? esc($option['lang'][$lang['id']]['option']) : ''; ?>" />
                        </div>
                    </div>
                </div>
            <?php ++$l; endforeach; ?>
        <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Survey.Publish');?></label>
        </div>
        <div class="form-field">
            <input type="checkbox" name="options[<?php echo isset($no) ? $no : 0; ?>][publish]" value="1"<?php if(!empty($option['publish']) && $option['publish']) echo 'checked="checked"'; ?> />
        </div>
    </div>
</div>