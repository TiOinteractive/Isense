<div class="form-row nag">
    <h3><?= lang('Download.BasicInformation'); ?></h3>
</div>
<div class="tabs">
    <?php if(!empty($languages) && count($languages) > 1): ?>
    <div class="tabs-head">
        <?php $l = 0;
        foreach ($languages as $lang):
            ?>
            <div class="tab<?= $l == 0 ? ' active' : ''; ?>"><span class="name"><?= $lang['name']; ?></span><span class="short-name"><?= $lang['short_name']; ?></span></div>
            <?php ++$l;
        endforeach;
        ?>
    </div>
    <div class="tabs-content">
    <?php endif; ?>
<?php $l = 0;
foreach ($languages as $lang):
    ?>
            <div class="link-box lang-<?= $lang['id']; ?> tab-item<?= $l == 0 ? ' active' : ''; ?>">
                <div class="form-row">
                    <div class="form-field">
                        <div class="files-list order-box" style="margin-bottom:20px;">
    <?php if (!empty($form_data['files'][$lang['id']])): ?>
        <?= view('Modules\Download\Views\admin\filemenager\upload_file', array('files' => $form_data['files'][$lang['id']])); ?>
    <?php endif; ?>
                        </div>   
                        <a href="<?= $locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/download/open-menager" title="<?= lang('Download.AddFiles'); ?>" class="btn file-menager" data-multi="true" data-field-name="form_data[lang][<?= $lang['id']; ?>]" data-title="<?= lang('Admin.file-menager.FileMenager'); ?>" data-btn-ok="<?= lang('Admin.file-menager.Select'); ?>" data-btn-cancel="<?= lang('Admin.file-menager.Cancel'); ?>"><?= lang('Download.AddFiles'); ?></a>
                    </div>
                </div>
            </div>
    <?php ++$l;
endforeach;
?>
    <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
</div>
<div class="form-row-space"></div>
<div class="form-row nag">
    <h3><?= lang('Download.Settings'); ?></h3>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?= lang('Download.Template'); ?></label>
    </div>
    <div class="form-field">
        <select name="form_data[template]">
            <?php if (!empty($templates)): ?>
    <?php foreach ($templates as $template): ?>
                    <option value="<?= $template['file']; ?>"<?= !empty($form_data) && $form_data['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?= $template['name']; ?></option>
    <?php endforeach; ?>
<?php endif; ?>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label><?= lang('Download.Publish'); ?></label>
    </div>
    <div class="form-field">
        <input type="checkbox" name="form_data[publish]" <?php if (!empty($form_data['publish']) && $form_data['publish']): ?>checked="checked"<?php endif; ?>  value="1" >
    </div>
</div>