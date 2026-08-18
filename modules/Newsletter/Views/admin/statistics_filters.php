<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/statistics/<?=$newsletter['id']; ?>" method="get">
    <div class="filter">
        <label><?=lang('Newsletter.Email'); ?></label>
        <input type="text" name="email" value="<?=!empty($filters['email']) ? $filters['email'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Newsletter.Status'); ?></label>
        <select name="status">
            <option value=""><?=lang('Newsletter.All'); ?></option>
            <option value="sent"<?=isset($filters['status']) && $filters['status'] == 'sent' ? ' selected="selected"' : ''; ?>><?=lang('Newsletter.status.sent'); ?></option>
            <option value="readed"<?=isset($filters['status']) && $filters['status'] == 'readed' ? ' selected="selected"' : ''; ?>><?=lang('Newsletter.status.readed'); ?></option>
            <option value="error"<?=isset($filters['status']) && $filters['status'] == 'error' ? ' selected="selected"' : ''; ?>><?=lang('Newsletter.status.error'); ?></option>
        </select>
    </div>
    <div class="filter">
        <button type="submit"><?=lang('Newsletter.Search'); ?></button>
    </div>
</form>