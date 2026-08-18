<div class="form-row nag">
    <h3><?=lang('Wyswig.SectionContent'); ?></h3>
</div>
<div class="tabs">
    <?php if(!empty($languages) && count($languages) > 1): ?>
        <div class="tabs-head">
            <?php $l=0; foreach($languages as $lang): ?>
            <div class="tab<?=$l==0 ? ' active' : ''; ?>"><span class="name"><?=$lang['name']; ?></span><span class="short-name"><?=$lang['short_name']; ?></span></div>
            <?php ++$l; endforeach; ?>
        </div>
        <div class="tabs-content">
    <?php endif; ?>
        <?php $l=0; foreach($languages as $lang): ?>
            <div class="tab-item<?=$l==0 ? ' active' : ''; ?>">
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Wyswig.Content');?></label>
                    </div>
                    <div class="form-field">
                        <textarea class="wyswig-textarea" name="form_data[lang][<?=$lang['id']; ?>][content]"><?=!empty($form_data['lang'][$lang['id']]['content']) ? $form_data['lang'][$lang['id']]['content'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
        <?php ++$l; endforeach; ?>
    <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
</div>
