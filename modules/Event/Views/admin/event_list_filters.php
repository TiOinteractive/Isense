<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=!empty($id_page) ? $id_page : ''; ?>/<?=$id_content; ?>" method="get">
    <div class="filter">
        <label><?=lang('Event.Name'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Event.Type'); ?></label>
        <select name="type" class="link-page-id">
            <option value=""></option>
            <?php if(!empty($types)): ?>
                <?php foreach($types as $type): ?>
                    <option value="<?= $type['id']; ?>"<?= !empty($filters['type']) && $filters['type'] == $type['id'] ? ' selected="selected"' : ''; ?>><?= $type['name']; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Event.Publish'); ?></label>
        <select name="publish">
            <option value=""><?=lang('Event.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Event.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Event.OnlyUnpublished'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Event.Patronage'); ?></label>
        <select name="patronage">
            <option value=""><?=lang('Event.All'); ?></option>
            <option value="1"<?=isset($filters['patronage']) && $filters['patronage'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Event.Yes'); ?></option>
            <option value="0"<?=isset($filters['patronage']) && $filters['patronage'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Event.No'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Event.Home'); ?></label>
        <select name="home">
            <option value=""><?=lang('Event.All'); ?></option>
            <option value="1"<?=isset($filters['home']) && $filters['home'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Event.Yes'); ?></option>
            <option value="0"<?=isset($filters['home']) && $filters['home'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Event.No'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Event.ForKids'); ?></label>
        <select name="for_kids">
            <option value=""><?=lang('Event.All'); ?></option>
            <option value="1"<?=isset($filters['for_kids']) && $filters['for_kids'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Event.Yes'); ?></option>
            <option value="0"<?=isset($filters['for_kids']) && $filters['for_kids'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Event.No'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Event.Recommended'); ?></label>
        <select name="recommended">
            <option value=""><?=lang('Event.All'); ?></option>
            <option value="1"<?=isset($filters['recommended']) && $filters['recommended'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Event.Yes'); ?></option>
            <option value="0"<?=isset($filters['recommended']) && $filters['recommended'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Event.No'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Event.ImportSource'); ?></label>
        <select name="source">
            <option value=""><?=lang('Event.All'); ?></option>
            <option value="kupbilecik"<?=isset($filters['source']) && $filters['source'] == 'kupbilecik' ? ' selected="selected"' : ''; ?>><?=lang('Event.import_source.kupbilecik'); ?></option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Event.Search'); ?></button>
    </div>
</form>