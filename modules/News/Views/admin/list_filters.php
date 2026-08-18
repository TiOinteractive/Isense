<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$id_page; ?>/<?=$id_content; ?>" method="get">
    <div class="filter">
        <label><?=lang('News.Title'); ?></label>
        <input type="text" name="title" value="<?=!empty($filters['title']) ? $filters['title'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('News.Date'); ?></label>
        <input class="datepicker-range" type="text" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" autocomplete="off" />
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
	<div class="filter">
        <label><?=lang('News.ChooseBox');?></label>
        <select name="box">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['box']) && $filters['box'] == 1 ? ' selected="selected"' : ''; ?>>Big Box 1</option>
            <option value="2"<?=isset($filters['box']) && $filters['box'] == 2 ? ' selected="selected"' : ''; ?>>Box 2</option>
			<option value="3"<?=isset($filters['box']) && $filters['box'] == 3 ? ' selected="selected"' : ''; ?>>Box 3</option>
			<option value="4"<?=isset($filters['box']) && $filters['box'] == 4 ? ' selected="selected"' : ''; ?>>Box 4</option>
			<option value="5"<?=isset($filters['box']) && $filters['box'] == 5 ? ' selected="selected"' : ''; ?>>Box 5</option>
			<option value="6"<?=isset($filters['box']) && $filters['box'] == 6 ? ' selected="selected"' : ''; ?>>Box 6</option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <input type="hidden" name="on_page" value="<?=!empty($filters['on_page']) ? $filters['on_page'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('News.Search'); ?></button>
    </div>
</form>