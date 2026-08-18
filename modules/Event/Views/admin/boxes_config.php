<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.Options');?></label>
    </div>
    <div class="form-field">
        <select name="config[options][]" multiple="multiple">
            <option value="0"><?=lang('News.All'); ?></option>
            <option value="home"<?php if(!empty($page_content['config']) && !empty($page_content['config']['options']) && in_array('home', $page_content['config']['options'])):?> selected="selected"<?php endif; ?>><?=lang('Event.Home'); ?></option>
            <option value="patronage"<?php if(!empty($page_content['config']) && !empty($page_content['config']['options']) && in_array('patronage', $page_content['config']['options'])):?> selected="selected"<?php endif; ?>><?=lang('Event.Patronage'); ?></option>
            <option value="recommended"<?php if(!empty($page_content['config']) && !empty($page_content['config']['options']) && in_array('recommended', $page_content['config']['options'])):?> selected="selected"<?php endif; ?>><?=lang('Event.Recommended'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.NewsLists');?></label>
    </div>
    <div class="form-field">
        <div class="form-cols">
            <?php if(!empty($news)): ?>
                <?php foreach($news as $list): ?>
                    <div class="form-col col-10">
                        <input type="checkbox" name="config[lists][]" value="<?=$list['id_content']; ?>" id="config-lists-<?=$list['id_content']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['lists']) && in_array($list['id_content'], $page_content['config']['lists'])):?> checked="checked"<?php endif; ?> />
                        <label for="config-lists-<?=$list['id_content']; ?>"><?=(!empty($list['tree_name']) ? $list['tree_name'] : '') . $list['name'] . ' [' . (!empty($list['content_name']) ? $list['content_name'] . ' - ' : '') . '' . lang('Admin.page_content.Tab') . ' ' . ($list['order'] + 1) . ']'; ?></label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Event.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('Event.OrderRandom'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.ItemsPerPage');?></label>
    </div>
    <div class="form-field">
        <input type="number" name="config[no]" min="0" max="100" value="<?=!empty($page_content['config']) && isset($page_content['config']['no']) ? $page_content['config']['no'] : ''; ?>" />
    </div>
</div>