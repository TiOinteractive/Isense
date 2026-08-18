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
        <label><?=lang('Foto.GalleryCategory');?></label>
    </div>
    <div class="form-field">
        <select name="config[category]">
            <option value="0"><?=lang('News.All'); ?></option>
             <?php if(!empty($pages)): ?>
                            <?php foreach($pages as $k=>$p): ?>
                                <?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($page_content['config']['category']) ? $page_content['config']['category']: 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
                            <?php endforeach; ?>
             <?php endif; ?>
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
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderRandom'); ?></option>
            <option value="date"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='date'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderDate'); ?></option>
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