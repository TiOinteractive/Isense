<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/comments" method="get">
    <div class="filter">
        <label><?=lang('Comments.User'); ?></label>
        <input type="text" name="search" value="<?=!empty($filters['title']) ? $filters['title'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Comments.DateRange'); ?></label>
        <input class="datepicker-range" type="text" name="date" value="<?=!empty($filters['date']) ? $filters['date'] : ''; ?>" autocomplete="off" />
    </div>
    <div class="filter">
        <label><?=lang('Comments.Status'); ?></label>
        <select name="status">
            <option value=""></option>
            <option value="new"<?php if(!empty($filters['status']) && $filters['status'] == 'new'): ?> selected="selected"<?php endif; ?>><?=lang('Comments.status.new'); ?></option>
            <option value="viewed"<?php if(!empty($filters['status']) && $filters['status'] == 'viewed'): ?> selected="selected"<?php endif; ?>><?=lang('Comments.status.viewed'); ?></option>
            <option value="removed"<?php if(!empty($filters['status']) && $filters['status'] == 'removed'): ?> selected="selected"<?php endif; ?>><?=lang('Comments.status.removed'); ?></option>
        </select>
    </div>
    <?php if(!empty($modules)): ?>
    <div class="filter">
            <label><?=lang('Comments.Section'); ?></label>
            <select name="section">
                <option value=""></option>
                <?php foreach($pages as $p): ?>
                    <option value="<?=$p['id']; ?>"<?php if(!empty($filters['section']) && $p['id'] == $filters['section']): ?> selected="selected"<?php endif; ?>><?=$p['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
    <?php if(!empty($modules)): ?>
    <div class="filter">
            <label><?=lang('Comments.Module'); ?></label>
            <select name="module">
                <option value=""></option>
                <?php foreach($modules as $m): ?>
                    <option value="<?=$m['id']; ?>"<?php if(!empty($filters['module']) && $m['id'] == $filters['module']): ?> selected="selected"<?php endif; ?>><?=$m['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Comments.Search'); ?></button>
    </div>
</form>