
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Shopping.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Shopping.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('Shopping.OrderRandom'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Shopping.ShopsId');?></label>
    </div>
    <div class="form-field">
        <input type="text" name="config[shops]" value="<?=!empty($page_content['config']) && isset($page_content['config']['shops']) ? $page_content['config']['shops'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Shopping.ItemsPerPage');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Shopping.Pagination');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[pagination]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['pagination']) && $page_content['config']['pagination'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>