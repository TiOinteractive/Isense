<div class="form-row">
    <div class="form-label">
        <label><?=lang('Pricing.PageShowCategories');?></label>
        <div class="desc"><?=lang('Pricing.PageShowCategoriesHint');?></div>
    </div>
    <div class="form-field">
        <?php if(!empty($categories)): ?>
            <!-- Pole kontrolne: bez niego odznaczenie wszystkich kategorii nie wyczyściłoby zapisanego wyboru
                 (PageContentModel zapisuje config tylko gdy POST zawiera niepustą tablicę config). -->
            <input type="hidden" name="config[categories_set]" value="1" />
            <div class="form-cols">
                <?php foreach($categories as $category): ?>
                    <div class="form-col col-2">
                        <input type="checkbox" name="config[categories][]" value="<?=$category['id']; ?>" id="config-categories-<?=$category['id']; ?>"<?php if(!empty($page_content['config']) && !empty($page_content['config']['categories']) && in_array($category['id'], $page_content['config']['categories'])): ?> checked="checked"<?php endif; ?> />
                        <label for="config-categories-<?=$category['id']; ?>"><?=esc($category['name']); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="s"><?=lang('Pricing.PageNoCategories');?> <a href="<?=($locale ? '/' . $locale : ''); ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/pricing"><?=lang('Pricing.Pricing');?></a></p>
        <?php endif; ?>
    </div>
</div>
