<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$id_page; ?>/<?=$id_content; ?>" method="get">
    <div class="filter">
        <label><?=lang('Foto.Foto.Name'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
	<div class="filter">
        <label><?=lang('News.Date'); ?></label>
        <input class="datepicker-range" type="text" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" autocomplete="off" />
    </div>
	<div class="filter">
	      <label><?=lang('Foto.GalleryCategory'); ?></label>
	 <select name="id_category">
			<option value="0">(<?=lang('Admin.page.ThereIsNoParent'); ?>)</option>
				<?php if(!empty($categorylists)): ?>
					<?php foreach($categorylists as $k=>$p): ?>
						<?= view('admin/page/select_parents', array('page'=>$p, 're_id'=>!empty($filters['id_category']) ?$filters['id_category'] : 0, 'count'=>count($categorylists), 'item_no'=>$k+1)); ?>
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
        <label><?=lang('News.Home'); ?></label>
        <select name="home">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['home']) && $filters['home'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyHome'); ?></option>
            <option value="0"<?=isset($filters['home']) && $filters['home'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyNotHome'); ?></option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('News.Search'); ?></button>
    </div>
</form>	