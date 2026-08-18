<div class="form-row">
    <div class="form-label">
        <label><?=lang('Gallery.Option');?></label>
    </div>
    <div class="form-field">
        <select name="config[option]">
            <option value="0"><?=lang('Gallery.All'); ?></option>
            <option value="home"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='home'):?> selected="selected"<?php endif; ?>><?=lang('Gallery.SelectedAsHome'); ?></option>
			<option value="subpage"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='subpage'):?> selected="selected"<?php endif; ?>><?=lang('Gallery.Subpage'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Gallery.Lists');?></label>
    </div>
    <div class="form-field">
        <div class="form-cols">
            <?php if(!empty($lists)): ?>
                <?php foreach($lists as $list): ?>
                    <div class="form-col col-10">
                        <input type="checkbox" name="config[lists][]" value="<?=$list['id_content']; ?>" id="config-lists-<?=$list['id_content']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['lists']) && in_array($list['id_content'], $page_content['config']['lists'])):?> checked="checked"<?php endif; ?> />
                        <label for="config-lists-<?=$list['id_content']; ?>"><?=$list['name'] . ' [' . $list['id_content'] . ']'; ?></label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Gallery.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Gallery.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('Gallery.OrderRandom'); ?></option>
			<option value="name"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='name'):?> selected="selected"<?php endif; ?>><?=lang('Gallery.OrderName'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Gallery.ItemsPerPage');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Gallery.Pagination');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[pagination]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['pagination']) && $page_content['config']['pagination'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>