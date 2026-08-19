<?php $admin = ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG'); ?>
<div class="main-cont">
    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head">
            <?php if (! empty($model['id'])): ?>
                <?= esc(! empty($model['name']) ? $model['name'] : ''); ?>
                <span><?= lang('Pricing.ModelEdit'); ?></span>
            <?php else: ?>
                <?= lang('Pricing.ModelAdd'); ?>
                <span><?= esc($service['name']); ?></span>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', ['flashdata' => ! empty($flashdata) ? $flashdata : []]); ?>
        <form class="form" action="<?= $admin; ?>/pricing/<?= $action; ?>/<?= $service['id']; ?><?= ! empty($model['id']) ? '/' . $model['id'] : ''; ?>" method="post">
            <div class="form-row nag">
                <h3><?= lang('Pricing.BasicInformation'); ?></h3>
            </div>
            <div class="tabs">
                <?php if (! empty($languages) && count($languages) > 1): ?>
                    <div class="tabs-head">
                        <?php $l = 0; foreach ($languages as $lang): ?>
                            <div class="tab<?= $l === 0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                        <?php ++$l; endforeach; ?>
                    </div>
                    <div class="tabs-content">
                <?php endif; ?>
                <?php $l = 0; foreach ($languages as $lang): $lid = $lang['id']; ?>
                    <div class="tab-item<?= $l === 0 ? ' active' : ''; ?>">
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Pricing.ModelName'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lid; ?>][name]" value="<?= ! empty($model['lang'][$lid]['name']) ? esc($model['lang'][$lid]['name']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Pricing.Time'); ?></label>
                                <div class="desc"><?= lang('Pricing.TimeHint'); ?></div>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lid; ?>][time]" value="<?= ! empty($model['lang'][$lid]['time']) ? esc($model['lang'][$lid]['time']) : ''; ?>" />
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if (! empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <!-- Cena — liczba wspólna dla wszystkich wersji językowych (poza zakładkami języków) -->
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Pricing.Price'); ?></label>
                    <div class="desc"><?= lang('Pricing.PriceHint'); ?></div>
                </div>
                <div class="form-field">
                    <input type="number" step="0.01" min="0" name="price" value="<?= isset($model['price']) && $model['price'] !== null && $model['price'] !== '' ? esc(number_format((float) $model['price'], 2, '.', '')) : ''; ?>" />
                </div>
            </div>
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?= lang('Pricing.Settings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?= lang('Pricing.Publish'); ?></label>
                </div>
                <div class="form-field">
                    <input type="checkbox" name="publish" value="1"<?= ! isset($model['id']) || ! empty($model['publish']) ? ' checked="checked"' : ''; ?> />
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit"><?= lang('Pricing.Save'); ?></button>
            </div>
        </form>
    </div>
</div>