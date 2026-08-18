<div class="form-group order-item" data-no="<?php echo isset($no) ? $no : 0; ?>">
    <div class="form-group-head">
        <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
        <div class="no">#<?=$no; ?></div>
        <div class="delete">
            <a href="#" class="delete-field" title="<?=lang('Form.fields.Delete');?>" data-title="<?=lang('Form.fields.DeleteField');?>" data-message="<?=lang('Form.fields.ConfirmInfo');?>" data-btn-ok="<?=lang('Form.fields.Delete');?>" data-btn-cancel="<?=lang('Form.fields.Cancel');?>"><i class="fa-regular fa-trash-can"></i></a>
        </div>
    </div>
    <input type="hidden" name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][id]" value="<?=!empty($field) ? $field['id'] : ''; ?>" />
    <input class="order-field" type="hidden" name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][order]" value="<?=!empty($field) ? $field['order'] : ''; ?>" />
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
                            <label><?=lang('Form.fields.Name');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][name]" value="<?=!empty($field['lang']) ? esc($field['lang'][$lang['id']]['name']) : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Form.fields.Description');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][lang][<?=$lang['id']; ?>][description]" value="<?=!empty($field['lang']) ? esc($field['lang'][$lang['id']]['description']) : ''; ?>" />
                        </div>
                    </div>
                </div>
            <?php ++$l; endforeach; ?>
        <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.Validation');?></label>
        </div>
        <div class="form-field">
            <select name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][validation]">
                <option value=""></option>
                <option value="email"<?php if(!empty($field['validation']) && $field['validation'] == 'email'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Email'); ?></option>
                <option value="phone"<?php if(!empty($field['validation']) && $field['validation'] == 'phone'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Phone'); ?></option>
                <option value="zip_code"<?php if(!empty($field['validation']) && $field['validation'] == 'zip_code'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.ZipCode'); ?></option>
                <option value="nip"<?php if(!empty($field['validation']) && $field['validation'] == 'nip'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.NIP'); ?></option>
                <option value="regon"<?php if(!empty($field['validation']) && $field['validation'] == 'regon'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Regon'); ?></option>
                <option value="pesel"<?php if(!empty($field['validation']) && $field['validation'] == 'pesel'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Pesel'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.Type');?></label>
        </div>
        <div class="form-field">
            <select name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][type]">
                <option value="text"<?php if(!empty($field['type']) && $field['type'] == 'text'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.Text'); ?></option>
                <option value="textarea"<?php if(!empty($field['type']) && $field['type'] == 'textarea'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.TextArea'); ?></option>
                <option value="number"<?php if(!empty($field['type']) && $field['type'] == 'number'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.Number'); ?></option>
                <option value="checkbox"<?php if(!empty($field['type']) && $field['type'] == 'checkbox'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.Checkbox'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.Required');?></label>
        </div>
        <div class="form-field">
            <input type="checkbox" name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][required]" value="1"<?=!empty($field['required']) && $field['required'] ? 'checked="checked"' : ''; ?> />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.Publish');?></label>
        </div>
        <div class="form-field">
            <input type="checkbox" name="form_data[field][<?php echo isset($no) ? $no : 0; ?>][publish]" value="1"<?=!empty($field['publish']) && $field['publish'] ? 'checked="checked"' : ''; ?> />
        </div>
    </div>
</div>