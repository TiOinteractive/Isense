<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/flavors/restaurants" method="get">
    <div class="filter">
        <label><?=lang('Flavors.RestaurantName'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
	  <div class="filter">
        <label><?=lang('Flavors.RestaurantCategory'); ?></label>
			<select name="id_category" class="link-category-id">
								<option value="0">(<?=lang('Foto.NoSelectedCategory'); ?>)</option>
								<?php if(!empty($pages)): ?>
									<?php foreach($pages as $k=>$p): ?>
										<?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($filters['id_category']) ? $filters['id_category'] : 0, 'count'=>count($pages), 'item_no'=>$k+1)); ?>
									<?php endforeach; ?>
								<?php endif; ?>
			</select>
	    </div>
    <div class="filter">
        <label><?=lang('News.Publish'); ?></label>
        <select name="publish">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyUnpublished'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Flavors.RestaurantAwarded'); ?></label>
        <select name="awarded">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['awarded']) && $filters['awarded'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyHome'); ?></option>
            <option value="0"<?=isset($filters['awarded']) && $filters['awarded'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyNotHome'); ?></option>
        </select>
    </div>
	<div class="filter">
        <label><?=lang('Flavors.RestaurantRecommended'); ?></label>
        <select name="recommended">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['recommended']) && $filters['recommended'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyHome'); ?></option>
            <option value="0"<?=isset($filters['recommended']) && $filters['recommended'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyNotHome'); ?></option>
        </select>
    </div>
		<div class="filter">
        <label><?=lang('Flavors.RestaurantArchive'); ?></label>
        <select name="archive">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['archive']) && $filters['archive'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyHome'); ?></option>
            <option value="0"<?=isset($filters['archive']) && $filters['archive'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyNotHome'); ?></option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('News.Search'); ?></button>
    </div>
</form>