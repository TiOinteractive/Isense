<?php if(!empty($categories)):?>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('Foto.ChooseCategory');?></label>
    </div>
    <div class="form-field">
       <select name="config[cat]">
	     <?php foreach($categories as $cat):?>
		   <option value="<?=$cat['id'];?>" <?php if(!empty($page_content['config']['cat']) && $page_content['config']['cat']==$cat['id']):?> selected="selected"<?php endif; ?>><?=$cat['name'];?></option>
		 <?php endforeach;?>
	   </select>
    </div>
</div>
<?php endif;?>
<div class="form-row">
    <div class="form-label">
        <label><?=lang('News.Order');?></label>
    </div>
    <div class="form-field">
        <select name="config[order]">
            <option value="latest"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='latest'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderLatest'); ?></option>
            <option value="random"<?php if(!empty($page_content['config']) && !empty($page_content['config']['order']) && $page_content['config']['order']=='random'):?> selected="selected"<?php endif; ?>><?=lang('News.OrderRandom'); ?></option>
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
