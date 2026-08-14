<?php
/*
 * Wspólne filtry list cennika (kategorie / usługi / modele).
 * Oczekuje: $filters_action (adres listy), $filters.
 */
?>
<form class="filters" action="<?= $filters_action; ?>" method="get">
    <div class="filter">
        <label><?= lang('Pricing.Name'); ?></label>
        <input type="text" name="name" value="<?= ! empty($filters['name']) ? esc($filters['name']) : ''; ?>" />
    </div>
    <div class="filter">
        <label><?= lang('Pricing.Publish'); ?></label>
        <select name="publish">
            <option value=""><?= lang('Pricing.All'); ?></option>
            <option value="1"<?= isset($filters['publish']) && $filters['publish'] === '1' ? ' selected="selected"' : ''; ?>><?= lang('Pricing.OnlyPublished'); ?></option>
            <option value="0"<?= isset($filters['publish']) && $filters['publish'] === '0' ? ' selected="selected"' : ''; ?>><?= lang('Pricing.OnlyUnpublished'); ?></option>
        </select>
    </div>
    <input type="hidden" name="order" value="<?= ! empty($filters['order']) ? esc($filters['order']) : ''; ?>" />
    <input type="hidden" name="on_page" value="<?= ! empty($filters['on_page']) ? (int) $filters['on_page'] : ''; ?>" />
    <div class="filter">
        <button type="submit"><?= lang('Pricing.Search'); ?></button>
    </div>
</form>
