<?php $admin = ($locale ? '/' . $locale : '') . '/' . env('ADMIN_PANEL_SLUG'); ?>
<div class="main-cont">
    <?php if (isset($breadcrumbs)) { echo $breadcrumbs; } ?>
    <div class="c">
        <div class="head">
            <?php if (! empty($service['id'])): ?>
                <?= esc(! empty($service['name']) ? $service['name'] : ''); ?>
                <span><?= lang('Pricing.ServiceEdit'); ?></span>
            <?php else: ?>
                <?= lang('Pricing.ServiceAdd'); ?>
                <span><?= esc($category['name']); ?></span>
            <?php endif; ?>
        </div>
        <?= view('admin/alert_box', ['flashdata' => ! empty($flashdata) ? $flashdata : []]); ?>
        <form class="form" action="<?= $admin; ?>/pricing/<?= $action; ?>/<?= $category['id']; ?><?= ! empty($service['id']) ? '/' . $service['id'] : ''; ?>" method="post">
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
                                <label><?= lang('Pricing.ServiceName'); ?></label>
                            </div>
                            <div class="form-field">
                                <input type="text" name="lang[<?= $lid; ?>][name]" value="<?= ! empty($service['lang'][$lid]['name']) ? esc($service['lang'][$lid]['name']) : ''; ?>" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-label">
                                <label><?= lang('Pricing.Description'); ?></label>
                                <div class="desc"><?= lang('Pricing.DescriptionHint'); ?></div>
                            </div>
                            <div class="form-field">
                                <textarea name="lang[<?= $lid; ?>][description]" rows="4"><?= ! empty($service['lang'][$lid]['description']) ? esc($service['lang'][$lid]['description']) : ''; ?></textarea>
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
                    <input type="checkbox" name="publish" value="1"<?= ! isset($service['id']) || ! empty($service['publish']) ? ' checked="checked"' : ''; ?> />
                </div>
            </div>
            <div class="form-row submit">
                <button type="submit"><?= lang('Pricing.Save'); ?></button>
            </div>
        </form>
    </div>
</div>
