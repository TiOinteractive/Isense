<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.Type');?></label>
    </div>
    <div class="form-field">
        <div class="form-cols">
            <?php if(!empty($types)): ?>
                <?php foreach($types as $type): ?>
                    <div class="form-col col-2">
                        <input type="checkbox" name="config[types][]" value="<?= $type['id']; ?>" id="config-types-<?=$type['id']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['types']) && in_array($type['id'], $page_content['config']['types'])):?> checked="checked"<?php endif; ?> />
                        <label for="config-types-<?=$type['id']; ?>"><?= $type['name']; ?></label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.OrderEvent');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="date"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='date'):?> selected="selected"<?php endif; ?>><?=lang('Event.config.OrderDate'); ?></option>
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Event.config.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('Event.config.OrderRandom'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.ItemsPerPageEvent');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.HomeEvent');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[home]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['home']) && $page_content['config']['home'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>


<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.OrderCinema');?></label>
    </div>
    <div class="form-field">
        <select name="config[order2]">
            <option value="date"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order2']) && $page_content['config']['order2']=='date'):?> selected="selected"<?php endif; ?>><?=lang('Event.config.OrderDate'); ?></option>
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order2']) && $page_content['config']['order2']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Event.config.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order2']) && $page_content['config']['order2']=='random'):?> selected="selected"<?php endif; ?>><?=lang('Event.config.OrderRandom'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.config.ItemsPerPageCinema');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no2]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no2']) ? $page_content['config']['no2'] : ''; ?>" />
    </div>
</div>