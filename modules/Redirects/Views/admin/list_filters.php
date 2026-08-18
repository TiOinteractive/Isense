<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/redirects" method="get">
    <div class="filter">
        <label><?=lang('Redirects.RedirectFrom'); ?></label>
        <input type="text" name="from" value="<?=!empty($filters['from']) ? $filters['from'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Redirects.RedirectTo'); ?></label>
        <input class="datepicker-range" type="text" name="to" value="<?=!empty($filters['to']) ? $filters['to'] : ''; ?>" autocomplete="off" />
    </div>
    <div class="filter">
        <label><?=lang('Redirects.Type'); ?></label>
        <select name="type">
            <option value=""><?=lang('Redirects.All'); ?></option>
            <option value="1"<?=isset($filters['type']) && $filters['type'] == '302' ? ' selected="selected"' : ''; ?>><?=lang('Redirects.redirect.302'); ?></option>
            <option value="0"<?=isset($filters['type']) && $filters['type'] == '301' ? ' selected="selected"' : ''; ?>><?=lang('Redirects.redirect.301'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Redirects.Group'); ?></label>
        <select name="group">
            <option value=""><?=lang('Redirects.group.All'); ?></option>
            <option value="resinet"<?=isset($filters['group']) && $filters['group'] == 'resinet' ? ' selected="selected"' : ''; ?>><?=lang('Redirects.group.resinet'); ?></option>
            <option value="entertainment"<?=isset($filters['group']) && $filters['group'] == 'entertainment' ? ' selected="selected"' : ''; ?>><?=lang('Redirects.group.entertainment'); ?></option>
            <option value="flavor"<?=isset($filters['group']) && $filters['group'] == 'flavor' ? ' selected="selected"' : ''; ?>><?=lang('Redirects.group.flavor'); ?></option>
            <option value="foto"<?=isset($filters['group']) && $filters['group'] == 'foto' ? ' selected="selected"' : ''; ?>><?=lang('Redirects.group.foto'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Redirects.Publish'); ?></label>
        <select name="publish">
            <option value=""><?=lang('Redirects.All'); ?></option>
            <option value="1"<?=isset($filters['publish']) && $filters['publish'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Redirects.OnlyPublished'); ?></option>
            <option value="0"<?=isset($filters['publish']) && $filters['publish'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Redirects.OnlyUnpublished'); ?></option>
        </select>
    </div>
    <div class="filter">
        <label><?=lang('Redirects.ShortLink'); ?></label>
        <select name="short">
            <option value=""><?=lang('Redirects.All'); ?></option>
            <option value="1"<?=isset($filters['short']) && $filters['short'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Redirects.OnlyShortLink'); ?></option>
            <option value="0"<?=isset($filters['short']) && $filters['short'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Redirects.OnlyNotShortLink'); ?></option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Redirects.Search'); ?></button>
    </div>
</form>