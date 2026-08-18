<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/survey" method="get">
    <div class="filter">
        <label><?=lang('Survey.Question'); ?></label>
        <input type="text" name="question" value="<?=!empty($filters['question']) ? $filters['question'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Survey.Date'); ?></label>
        <input class="datepicker-range" type="text" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" autocomplete="off" />
    </div>
    <div class="filter">
        <label><?=lang('Survey.Publish'); ?></label>
        <select name="publish">
            <option value=""><?=lang('Survey.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Survey.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Survey.OnlyUnpublished'); ?></option>
        </select>
    </div>
    <div class="filter">
        <button type="submit"><?=lang('Survey.Search'); ?></button>
    </div>
</form>