<form class="filters" action="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/page/content/<?=$id_page; ?>/<?=$id_content; ?>" method="get">
    <div class="filter">
        <label><?=lang('Cinema.Title'); ?></label>
        <input type="text" name="title" value="<?=!empty($filters['title']) ? $filters['title'] : ''; ?>" />
    </div>
    <div class="filter">
        <label><?=lang('Cinema.Genre'); ?></label>
        <select name="genre" class="link-page-id">
            <option value="0"></option>
            <?php if(!empty($genres)): ?>
                <?php foreach($genres as $genre): ?>
                    <option value="<?= $genre['id']; ?>"<?= !empty($filters['genre']) && $filters['genre'] == $genre['id'] ? ' selected="selected"' : ''; ?>><?= $genre['name']; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
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
    <input type="hidden" name="order" value="<?=!empty($filters['order']) ? $filters['order'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?=lang('Cinema.Search'); ?></button>
    </div>
</form>