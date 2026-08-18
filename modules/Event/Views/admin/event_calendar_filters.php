<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/calendar" method="get">
    <div class="filter">
        <label><?=lang('Event.Name'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Event.EventDate'); ?></label>
        <input class="datepicker-range" type="text" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" autocomplete="off" />
    </div>
    <div class="filter">
        <label><?=lang('Event.Type'); ?></label>
        <select name="type" class="link-page-id">
            <option value="0"></option>
            <?php if(!empty($types)): ?>
                <?php foreach($types as $type): ?>
                    <option value="<?= $type['id']; ?>"<?= !empty($filters['type']) && $filters['type'] == $type['id'] ? ' selected="selected"' : ''; ?>><?= $type['name']; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Event.EventPlace'); ?></label>
        <select name="place" class="link-page-id">
            <option value="0"></option>
            <?php if(!empty($places)): ?>
                <?php foreach($places as $place): ?>
                    <option value="<?= $place['id']; ?>"<?= !empty($filters['place']) && $filters['place'] == $place['id'] ? ' selected="selected"' : ''; ?>><?= $place['name']; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
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