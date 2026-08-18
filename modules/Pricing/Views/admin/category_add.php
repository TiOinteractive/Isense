<?php $admin = ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG'); ?>
<div class="main-cont">
    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head">
            <?php if (! empty($category['id'])): ?>
                <?= esc(! empty($category['name']) ? $category['name'] : ''); ?>
                <span><?= lang('Pricing.CategoryEdit'); ?></span>
            <?php else: ?>
                <?= lang('Pricing.CategoryAdd'); ?>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', ['flashdata' => ! empty($flashdata) ? $flashdata : []]); ?>
        <form class="form" action="<?= $admin; ?>/pricing/<?= $action; ?><?= ! empty($category['id']) ? '/' . $category['id'] : ''; ?>" method="post">
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
                                <label><?= lang('Pricing.CategoryName'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lid; ?>][name]" value="<?= ! empty($category['lang'][$lid]['name']) ? esc($category['lang'][$lid]['name']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Pricing.Slug'); ?></label>
                                <div class="desc"><?= lang('Pricing.SlugHint'); ?></div>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lid; ?>][slug]" value="<?= ! empty($category['lang'][$lid]['slug']) ? esc($category['lang'][$lid]['slug']) : ''; ?>" />
                            </div>
                        </div>
                    </div>
                <?php ++$l; endforeach; ?>
                <?php if (! empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
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
                    <input type="checkbox" name="publish" value="1"<?= ! isset($category['id']) || ! empty($category['publish']) ? ' checked="checked"' : ''; ?> />
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit"><?= lang('Pricing.Save'); ?></button>
            </div>
        </form>
    </div>
</div>
