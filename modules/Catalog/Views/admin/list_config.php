<div class="form-row">
    <div class="form-label">
        <label><?=lang('Catalog.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="order"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='order'):?> selected="selected"<?php endif; ?>><?=lang('Catalog.Order'); ?></option>
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Catalog.OrderLatest'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Catalog.ItemsPerPage');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Catalog.SearchEngine');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[search_engine]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['search_engine']) && $page_content['config']['search_engine'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Catalog.Pagination');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[pagination]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['pagination']) && $page_content['config']['pagination'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>