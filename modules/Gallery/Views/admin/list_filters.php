<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$id_page; ?>/<?=$id_content; ?>" method="get">
    <div class="filter">
        <label><?=lang('Gallery.Name'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Gallery.Publish'); ?></label>
        <select name="publish">
            <option value=""><?=lang('Gallery.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Gallery.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Gallery.OnlyUnpublished'); ?></option>
        </select>
    </div>
	<div class="filter">
        <label><?=lang('Gallery.Home'); ?></label>
        <select name="home">
            <option value=""><?=lang('Gallery.All'); ?></option>
            <option value="1"<?=isset($filters['home']) && $filters['home'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Gallery.OnlyHome'); ?></option>
            <option value="0"<?=isset($filters['home']) && $filters['home'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Gallery.OnlyNotHome'); ?></option>
        </select>
    </div>
     <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Gallery.Search'); ?></button>
    </div>
</form>