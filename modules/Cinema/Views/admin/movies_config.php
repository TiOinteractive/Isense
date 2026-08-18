
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Cinema.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Cinema.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('Cinema.OrderRandom'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Cinema.Option');?></label>
    </div>
    <div class="form-field">
        <select name="config[option]">
            <option value=""></option>
            <option value="premiere"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='premiere'):?> selected="selected"<?php endif; ?>><?=lang('Cinema.Premiere'); ?></option>
            <option value="prepremiere"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='prepremiere'):?> selected="selected"<?php endif; ?>><?=lang('Cinema.Prepremiere'); ?></option>
            <option value="both"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='both'):?> selected="selected"<?php endif; ?>><?=lang('Cinema.PremiereAndPrepremiere'); ?></option>
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-label">
        <label><?=lang('Cinema.SearchEngine');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[search_engine]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['search_engine']) && $page_content['config']['search_engine'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Cinema.ItemsPerPage');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Cinema.Pagination');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[pagination]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['pagination']) && $page_content['config']['pagination'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>