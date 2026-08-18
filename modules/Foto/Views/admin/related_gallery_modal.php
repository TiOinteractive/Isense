<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/add-related-gallery/<?=$id_product; ?>" method="post">
    <div class="filter">
        <label><?=lang('Foto.Name'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Foto.Publish'); ?></label>
        <select name="publish">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Foto.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Foto.OnlyUnpublished'); ?></option>
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
        <label><?=lang('News.Investments'); ?></label>
        <select name="investments">
            <option value=""><?=lang('News.All'); ?></option>
            <option value="1"<?=isset($filters['investments']) && $filters['investments'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyHome'); ?></option>
            <option value="0"<?=isset($filters['investments']) && $filters['investments'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('News.OnlyNotHome'); ?></option>
        </select>
    </div>
    <div class="filter">
        <button type="submit"><?=lang('News.Search'); ?></button>
    </div>
</form>
<form class="filters-results" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/foto/related-gallery-save/<?=$id_product; ?>" method="post">
    
</form>