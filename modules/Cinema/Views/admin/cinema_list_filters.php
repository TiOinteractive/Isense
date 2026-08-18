<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/cinema" method="get">
    <div class="filter">
        <label><?=lang('Cinema.Title'); ?></label>
        <input type="text" name="title" value="<?=!empty($filters['title']) ? $filters['title'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Cinema.CinemaDate'); ?></label>
        <input class="datepicker-range" type="text" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" autocomplete="off" />
    </div>
    <div class="filter">
        <label><?=lang('Cinema.Type'); ?></label>
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
        <label><?=lang('Cinema.CinemaPlace'); ?></label>
        <select name="place" class="link-page-id">
            <option value="0"></option>
            <?php if(!empty($places)): ?>
                <?php foreach($places as $place): ?>
                    <option value="<?= $place['id']; ?>"<?= !empty($filters['place']) && $filters['place'] == $place['id'] ? ' selected="selected"' : ''; ?>><?= $place['name']; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Cinema.Search'); ?></button>
    </div>
</form>