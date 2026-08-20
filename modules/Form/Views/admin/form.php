<div class="form-row nag">
    <h3><?=lang('Form.SectionContent'); ?></h3>
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
                        <label><?=lang('Form.Name');?></label>
                    </div>
                    <div class="form-field">
                        <input type="text" name="form_data[lang][<?=$lang['id']; ?>][name]" value="<?=!empty($form_data['lang'][$lang['id']]['name']) ? $form_data['lang'][$lang['id']]['name'] : ''; ?>" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Form.Content');?></label>
                    </div>
                    <div class="form-field">
                        <textarea class="wyswig-textarea" name="form_data[lang][<?=$lang['id']; ?>][description]"><?=!empty($form_data['lang'][$lang['id']]['description']) ? $form_data['lang'][$lang['id']]['description'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Form.SuccessMessage');?></label>
                    </div>
                    <div class="form-field">
                        <textarea name="form_data[lang][<?=$lang['id']; ?>][success_msg]"><?=!empty($form_data['lang'][$lang['id']]['success_msg']) ? $form_data['lang'][$lang['id']]['success_msg'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-label">
                        <label><?=lang('Form.ErrorMessage');?></label>
                    </div>
                    <div class="form-field">
                        <textarea name="form_data[lang][<?=$lang['id']; ?>][error_msg]"><?=!empty($form_data['lang'][$lang['id']]['error_msg']) ? $form_data['lang'][$lang['id']]['error_msg'] : ''; ?></textarea>
                    </div>
                </div>
            </div>
        <?php ++$l; endforeach; ?>
    <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.Addressees');?> <span class="s">(<?=lang('Form.CommaSeparated'); ?>)</span></label>
        </div>
        <div class="form-field">
            <input type="text" name="form_data[addressee]" value="<?=!empty($form_data['addressee']) ? $form_data['addressee'] : ''; ?>" />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.AddresseesCC');?> <span class="s">(<?=lang('Form.CommaSeparated'); ?>)</span></label>
        </div>
        <div class="form-field">
            <input type="text" name="form_data[addressee_cc]" value="<?=!empty($form_data['addressee_cc']) ? $form_data['addressee_cc'] : ''; ?>" />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.AddresseesBCC');?> <span class="s">(<?=lang('Form.CommaSeparated'); ?>)</span></label>
        </div>
        <div class="form-field">
            <input type="text" name="form_data[addressee_bcc]" value="<?=!empty($form_data['addressee_bcc']) ? $form_data['addressee_bcc'] : ''; ?>" />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.TurnOnCaptcha');?></label>
        </div>
        <div class="form-field">
            <input type="checkbox" name="form_data[captcha]" value="1"<?php if(!empty($form_data['captcha']) && $form_data['captcha']): ?> checked="checked"<?php endif; ?> />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.Template');?></label>
        </div>
        <div class="form-field">
            <select name="form_data[template]">
                <?php if(!empty($templates)): ?>
                    <?php foreach($templates as $template): ?>
                        <option value="<?=$template['file']; ?>"<?=!empty($form_data['template']) && $form_data['template'] == $template['file'] ? ' selected="selected"' : ''; ?>><?=$template['name']; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>
<div class="form-row-space"></div>
<div class="form-row nag with-btn">
    <h3><?=lang('Form.FormFields'); ?></h3>
    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?=env('ADMIN_PANEL_SLUG'); ?>/form/field-add" class="btn add-field" title=""><?=lang('Form.fields.AddField');?></a>
</div>
<div class="order-box form-fields-box" data-lang-none="<?=lang('Form.fields.ShowWhenNone'); ?>">
    <?php /* Sentinel: bez niego POST bez sekcji pol (blad JS, zwinieta sekcja)
             skasowalby cala konfiguracje pol — patrz FormModel::saveFormFields(). */ ?>
    <input type="hidden" name="form_data[fields_present]" value="1" />
    <?php if(!empty($form_data['fields'])): ?>
        <?php foreach($form_data['fields'] as $no=>$field): ?>
            <?=view('Modules\Form\Views\admin\add_field', array(
                'field' => $field,
                'no' => $no,
                'languages' => $languages,
                'max_upload_kb' => !empty($max_upload_kb) ? $max_upload_kb : 0,
            )); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>