<form class="form parameter-form" id="modal-value" action="<?= $locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/flavors/save_parameter_value/<?= $id_parameter; ?><?php if(!empty($value['id'])) { echo '?id_value='.$value['id']; }?>" method="post">
    <div class="tabs">
        <?php if(!empty($languages) && count($languages) > 1): ?>
            <div class="tabs-head">
                <?php $l = 0; foreach ($languages as $lang): ?>
                    <div class="tab<?= $l == 0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
                <?php ++$l; endforeach; ?>
            </div>
            <div class="tabs-content">
        <?php endif; ?>
            <?php $l = 0; foreach ($languages as $lang): ?>
                <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l == 0 ? ' active' : ''; ?>">
                    <div class="form-row" style="margin:0px;">
                        <div class="form-label">
                            <label><?= lang('Flavors.ParametersValue'); ?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="lang[<?= $lang['id']; ?>][value]" value="<?= !empty($value['lang']) ? esc($value['lang'][$lang['id']]['value']) : ''; ?>" />
                        </div>
                    </div>
                </div>
            <?php ++$l; endforeach; ?>		
        <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
    </div>	
</form>		