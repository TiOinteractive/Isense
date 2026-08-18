<?php $fd = $form_data['lang'] ?? []; ?>
<div class="form-row nag">
    <h3><?= lang('Isense.SectionHero'); ?></h3>
</div>
<div class="tabs">
    <?php if (!empty($languages) && count($languages) > 1): ?>
        <div class="tabs-head">
            <?php $l = 0; foreach ($languages as $lang): ?>
                <div class="tab<?= $l == 0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
            <?php ++$l; endforeach; ?>
        </div>
        <div class="tabs-content">
    <?php endif; ?>
        <?php $l = 0; foreach ($languages as $lang): $f = $fd[$lang['id']] ?? []; ?>
            <div class="tab-item<?= $l == 0 ? ' active' : ''; ?>">
                <div class="form-row">
                    <div class="form-label"><label><?= lang('Isense.Eyebrow'); ?></label></div>
                    <div class="form-field">
                        <input type="text" name="form_data[lang][<?= $lang['id']; ?>][eyebrow]" value="<?= esc($f['eyebrow'] ?? '', 'attr'); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><label><?= lang('Isense.Heading'); ?></label></div>
                    <div class="form-field">
                        <input type="text" name="form_data[lang][<?= $lang['id']; ?>][heading]" value="<?= esc($f['heading'] ?? '', 'attr'); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><label><?= lang('Isense.CtaLabel'); ?></label></div>
                    <div class="form-field">
                        <input type="text" name="form_data[lang][<?= $lang['id']; ?>][cta_label]" value="<?= esc($f['cta_label'] ?? '', 'attr'); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><label><?= lang('Isense.CtaUrl'); ?></label></div>
                    <div class="form-field">
                        <input type="text" name="form_data[lang][<?= $lang['id']; ?>][cta_url]" value="<?= esc($f['cta_url'] ?? '', 'attr'); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label"><label><?= lang('Isense.BgImage'); ?></label></div>
                    <div class="form-field">
                        <input type="text" name="form_data[lang][<?= $lang['id']; ?>][bg]" value="<?= esc($f['bg'] ?? '', 'attr'); ?>" placeholder="/assets/isense/img/hero.png">
                    </div>
                </div>
            </div>
        <?php ++$l; endforeach; ?>
    <?php if (!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
</div>
