<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/event/city" method="get">
    <div class="filter">
        <label><?=lang('Event.Name'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? esc($filters['name']) : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Event.Publish'); ?></label>
        <select name="publish">
            <option value=""><?=lang('Event.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Event.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Event.OnlyUnpublished'); ?></option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? esc($filters['order']) : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Event.Search'); ?></button>
    </div>
</form>
