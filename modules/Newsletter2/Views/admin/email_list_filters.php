<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/newsletter/mails" method="get">
    <div class="filter">
        <label><?=lang('Newsletter.Search'); ?></label>
        <input type="text" name="name" value="<?=!empty($filters['name']) ? $filters['name'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Newsletter.Agreement'); ?></label>
        <select name="agreement">
            <option value=""><?=lang('Newsletter.All'); ?></option>
            <option value="1"<?=isset($filters['agreement']) && $filters['agreement'] == 1 ? ' selected="selected"' : ''; ?>><?=lang('Newsletter.TurnedOn2'); ?></option>
            <option value="0"<?=isset($filters['agreement']) && $filters['agreement'] == 0 ? ' selected="selected"' : ''; ?>><?=lang('Newsletter.TurnedOff2'); ?></option>
        </select>
    </div>
    <?php if(!empty($groups)): ?>
        <div class="filter">
            <label><?=lang('Newsletter.Group'); ?></label>
            <select name="group">
                <option value=""><?=lang('Newsletter.All'); ?></option>
                <?php foreach($groups as $group): ?>
                    <option value="<?=$group['id']; ?>"<?php if(!empty($filters['group']) && $group['id'] == $filters['group']): ?> selected="selected"<?php endif; ?>><?=$group['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Newsletter.Search'); ?></button>
    </div>
    <div class="filter">
        <button class="ajax ico" name="export" value="csv" type="submit" title="<?=lang('Newsletter.Export'); ?> CSV"><i class="fa-solid fa-file-csv fa-2x"></i></button>
        <button class="ajax ico" name="export" value="xlsx" type="submit" title="<?=lang('Newsletter.Export'); ?> XLSX"><i class="fa-solid fa-file-excel fa-2x"></i></button>
        <button class="ajax ico" name="export" value="txt" type="submit" title="<?=lang('Newsletter.Export'); ?> TXT"><i class="fa-solid fa-file-lines fa-2x"></i></button>
    </div>
</form>