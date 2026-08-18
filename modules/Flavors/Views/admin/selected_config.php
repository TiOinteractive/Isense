<div class="form-row">
    <div class="form-label">
        <label><?=lang('Flavors.Option');?></label>
    </div>
    <div class="form-field">
        <select name="config[option]">
            <option value="0"><?=lang('News.All'); ?></option>
			<option value="recommended"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='recommended'):?> selected="selected"<?php endif; ?>><?=lang('Flavors.RestaurantRecommended'); ?></option>
			<option value="awarded"<?php if(!empty($page_content['config']) && !empty($page_content['config']['option']) && $page_content['config']['option']=='awarded'):?> selected="selected"<?php endif; ?>><?=lang('Flavors.RestaurantAwarded'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderLatest'); ?></option>
			<option value="latestrandom"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latestrandom'):?> selected="selected"<?php endif; ?>>Najnowsze losowo</option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderRandom'); ?></option>
            <option value="date"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='date'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderDate'); ?></option>
            <option value="order"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='order'):?> selected="selected"<?php endif; ?>><?=lang('News.Order'); ?></option>
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
        <label><?=lang('News.Pagination');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[pagination]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['pagination']) && $page_content['config']['pagination'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>