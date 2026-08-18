<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/banner/zone/<?=$zone['id']; ?>" method="get">
    <div class="filter">
        <label><?=lang('Banner.Name'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Banner.Date');?></label>
        <input class="datepicker-range" type="text" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" autocomplete="off" />
    </div>
    <div class="filter">
        <label><?=lang('Banner.Publish'); ?></label>
        <select name="publish">
            <option value="all" <?=isset($filters['publish']) && $filters['publish'] == 'all' ? ' selected="selected"' : ''; ?>><?=lang('Banner.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Banner.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Banner.OnlyUnpublished'); ?></option>
			<option value="active"<?=isset($filters['publish']) && $filters['publish'] == 'active' ? ' selected="selected"' : ''; ?>><?=lang('Banner.OnlyActive'); ?></option>
            <option value="notactive"<?=isset($filters['publish']) && $filters['publish'] == 'notactive' ? ' selected="selected"' : ''; ?>><?=lang('Banner.OnlyUnactive'); ?></option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Banner.Search'); ?></button>
    </div>
</form>