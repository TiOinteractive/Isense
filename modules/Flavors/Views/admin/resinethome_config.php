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
        <label><?=lang('Flavors.ChooseMenu');?></label>
    </div>
    <div class="form-field">
        <select name="config[menu]">
          <?php if(!empty($menus)):?>
		   <?php foreach($menus as $menu):?>
		     <option value="<?=$menu['id'];?>" <?php if(!empty($page_content['config']) && !empty($page_content['config']['menu']) && $page_content['config']['menu']==$menu['id']):?>selected="selected"<?php endif; ?>><?=$menu['name'];?></option>
		   <?php endforeach;?>
		  <?php endif;?>
        </select>
    </div>
</div>