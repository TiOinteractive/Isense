<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Option');?></label>
    </div>
    <div class="form-field">
        <select name="config[option]">
            <option value="0"><?=lang('News.All'); ?></option>
            <option value="home"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='home'):?> selected="selected"<?php endif; ?>><?=lang('News.SelectedAsHome'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.ItemsPerPage');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">	
	<div class="form-label">
        <label><?=lang('News.MostReadDays');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[mostreaddays]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['mostreaddays']) ? $page_content['config']['mostreaddays'] : ''; ?>" />
    </div>
</div>
