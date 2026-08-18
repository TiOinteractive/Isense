
<?php if(!empty($lists)): ?>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Event.Lists');?></label>
        </div>
        <div class="form-field">
            <div class="form-cols">
                <?php foreach($lists as $list): ?>
                    <div class="form-col col-10">
                        <input type="checkbox" name="config[lists][]" value="<?=$list['id_content']; ?>" id="config-lists-<?=$list['id_content']; ?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['lists']) && in_array($list['id_content'], $page_content['config']['lists'])):?> checked="checked"<?php endif; ?> />
                        <label for="config-lists-<?=$list['id_content']; ?>"><?=(!empty($list['tree_name']) ? $list['tree_name'] : '') . $list['name'] . ' [' . (!empty($list['content_name']) ? $list['content_name'] . ' - ' : '') . '' . lang('Admin.page_content.Tab') . ' ' . ($list['order'] + 1) . ']'; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
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
        <label><?=lang('Event.Tags');?></label>
    </div>
    <div class="form-field">
        <input type="text" name="config[tags]" class="tags_input" data-url="/<?=env('ADMIN_PANEL_SLUG'); ?>/tags/searchtags/" value="<?=!empty($page_content['config']) && !empty($page_content['config']['tags']) ? esc($page_content['config']['tags']) : ''; ?>" />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.ShowAllAlsoPast');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[show_all]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['show_all']) && $page_content['config']['show_all'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.Patronage');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[patronage]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['patronage']) && $page_content['config']['patronage'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.Recommended');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[recommended]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['recommended']) && $page_content['config']['recommended'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="closest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='closest'):?> selected="selected"<?php endif; ?>><?=lang('Event.OrderClosest'); ?></option>
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('Event.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('Event.OrderRandom'); ?></option>
            <option value="order"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='order'):?> selected="selected"<?php endif; ?>><?=lang('Event.Order'); ?></option>
			<option value="most_popular"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='most_popular'):?> selected="selected"<?php endif; ?>><?=lang('Event.User.MostPopular'); ?></option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.GroupByDay');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[group_day]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['group_day']) && $page_content['config']['group_day'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.SearchEngine');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[search_engine]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['search_engine']) && $page_content['config']['search_engine'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.PhotoFromGallery');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[gallery_photo]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['gallery_photo']) && $page_content['config']['gallery_photo'] ? ' checked="checked"' : ''; ?> />
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
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Event.Pagination');?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="config[pagination]" value="1" <?=!empty($page_content['config']) && !empty($page_content['config']['pagination']) && $page_content['config']['pagination'] ? ' checked="checked"' : ''; ?> />
    </div>
</div>