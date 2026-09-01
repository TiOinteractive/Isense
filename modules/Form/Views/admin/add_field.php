<?php
/**
 * Wiersz pojedynczego pola formularza w panelu.
 *
 * Zmienne: $no (indeks w POST), $field (dane pola lub brak), $languages,
 *          $key (klucz lokalny nowego wiersza z FormAdmin::addField()),
 *          $max_upload_kb (efektywny limit rozmiaru pliku, do podpowiedzi).
 *
 * KLUCZ LOKALNY: warunki („pokaz gdy") wskazuja pola i opcje po kluczu, a nie
 * po ID z bazy — pole dodane AJAX-em nie ma jeszcze ID, a mimo to musi dac sie
 * od razu wybrac jako rodzic. Przeklad klucz -> ID robi FormModel przy zapisie.
 *   'f<ID>'  — pole istniejace,  'o<ID>'  — opcja istniejaca
 *   'n<hex>' — pole nowe,        'no<i>'  — opcja nowa
 */
$no = isset($no) ? (int) $no : 0;
$n = 'form_data[field][' . $no . ']';
$row_key = !empty($field['id']) ? 'f' . (int) $field['id'] : (!empty($key) ? $key : 'n' . $no);
$max_upload_kb = isset($max_upload_kb) ? (int) $max_upload_kb : 0;
$languages = !empty($languages) ? $languages : array();

/**
 * Renderer wiersza opcji — uzywany i dla opcji istniejacych, i do wypelnienia
 * <template> (wtedy $oi = '__i__', a JS podmienia placeholder na unikalny indeks).
 */
$renderOption = function ($oi, $opt) use ($n, $languages) {
    $on = $n . '[option][' . $oi . ']';
    $opt_key = !empty($opt['id']) ? 'o' . (int) $opt['id'] : 'no' . $oi;
    ?>
    <div class="opt-row" data-opt-row data-opt-key="<?=esc($opt_key, 'attr'); ?>">
        <input type="hidden" name="<?=$on; ?>[id]" value="<?=!empty($opt['id']) ? (int) $opt['id'] : ''; ?>" />
        <input type="hidden" name="<?=$on; ?>[key]" value="<?=esc($opt_key, 'attr'); ?>" />
        <input type="hidden" name="<?=$on; ?>[slug]" value="<?=!empty($opt['slug']) ? esc($opt['slug'], 'attr') : ''; ?>" />
        <input type="hidden" name="<?=$on; ?>[order]" value="<?=isset($opt['order']) ? (int) $opt['order'] : 0; ?>" />
        <?php foreach($languages as $lg): ?>
            <input type="text" class="opt-name" data-lang="<?=(int) $lg['id']; ?>"
                   placeholder="<?=lang('Form.fields.OptionName'); ?><?=count($languages) > 1 ? ' (' . esc($lg['short_name']) . ')' : ''; ?>"
                   name="<?=$on; ?>[lang][<?=(int) $lg['id']; ?>][name]"
                   value="<?=!empty($opt['lang'][$lg['id']]['name']) ? esc($opt['lang'][$lg['id']]['name']) : ''; ?>" />
        <?php endforeach; ?>
        <button type="button" class="btn opt-remove" data-opt-remove title="<?=lang('Form.fields.DeleteOption'); ?>">&times;</button>
    </div>
    <?php
};
?>
<div class="form-group order-item" data-no="<?=$no; ?>" data-key="<?=esc($row_key, 'attr'); ?>">
    <div class="form-group-head">
        <div class="expand"><i class="fa-solid fa-chevron-up"></i></div>
        <div class="no">#<?=$no; ?></div>
        <div class="delete">
            <a href="#" class="delete-field" title="<?=lang('Form.fields.Delete');?>" data-title="<?=lang('Form.fields.DeleteField');?>" data-message="<?=lang('Form.fields.ConfirmInfo');?>" data-btn-ok="<?=lang('Form.fields.Delete');?>" data-btn-cancel="<?=lang('Form.fields.Cancel');?>"><i class="fa-regular fa-trash-can"></i></a>
        </div>
    </div>
    <input type="hidden" name="<?=$n; ?>[id]" value="<?=!empty($field) ? (int) $field['id'] : ''; ?>" />
    <input type="hidden" name="<?=$n; ?>[key]" value="<?=esc($row_key, 'attr'); ?>" />
    <input class="order-field" type="hidden" name="<?=$n; ?>[order]" value="<?=!empty($field) ? (int) $field['order'] : ''; ?>" />
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
                            <input type="text" class="field-name" name="<?=$n; ?>[lang][<?=$lang['id']; ?>][name]" value="<?=!empty($field['lang']) ? esc($field['lang'][$lang['id']]['name']) : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><?=lang('Form.fields.Description');?></label>
                        </div>
                        <div class="form-field">
                            <input type="text" name="<?=$n; ?>[lang][<?=$lang['id']; ?>][description]" value="<?=!empty($field['lang']) ? esc($field['lang'][$lang['id']]['description']) : ''; ?>" />
                        </div>
                    </div>
                </div>
            <?php ++$l; endforeach; ?>
        <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.Type');?></label>
        </div>
        <div class="form-field">
            <select class="field-type" name="<?=$n; ?>[type]">
                <option value="text"<?php if(!empty($field['type']) && $field['type'] == 'text'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.Text'); ?></option>
                <option value="textarea"<?php if(!empty($field['type']) && $field['type'] == 'textarea'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.TextArea'); ?></option>
                <option value="number"<?php if(!empty($field['type']) && $field['type'] == 'number'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.Number'); ?></option>
                <option value="checkbox"<?php if(!empty($field['type']) && $field['type'] == 'checkbox'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.Checkbox'); ?></option>
                <option value="select"<?php if(!empty($field['type']) && $field['type'] == 'select'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.Select'); ?></option>
                <option value="file"<?php if(!empty($field['type']) && $field['type'] == 'file'):?> selected="selected"<?php endif; ?>><?=lang('Form.type.File'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-row field-type-cfg" data-for="text,textarea,number">
        <div class="form-label">
            <label><?=lang('Form.fields.Validation');?></label>
        </div>
        <div class="form-field">
            <select name="<?=$n; ?>[validation]">
                <option value=""></option>
                <?php /* 'name' i 'address' nie dodaja zadnej reguly walidacji — to
                         znaczniki: 'name' wskazuje pole z imieniem i nazwiskiem
                         (doklejane na koncu tematu maila), 'address' wlacza
                         autocomplete="street-address" (WCAG 1.3.5). */ ?>
                <option value="name"<?php if(!empty($field['validation']) && $field['validation'] == 'name'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Name'); ?></option>
                <option value="address"<?php if(!empty($field['validation']) && $field['validation'] == 'address'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Address'); ?></option>
                <option value="email"<?php if(!empty($field['validation']) && $field['validation'] == 'email'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Email'); ?></option>
                <option value="phone"<?php if(!empty($field['validation']) && $field['validation'] == 'phone'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Phone'); ?></option>
                <option value="zip_code"<?php if(!empty($field['validation']) && $field['validation'] == 'zip_code'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.ZipCode'); ?></option>
                <option value="nip"<?php if(!empty($field['validation']) && $field['validation'] == 'nip'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.NIP'); ?></option>
                <option value="regon"<?php if(!empty($field['validation']) && $field['validation'] == 'regon'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Regon'); ?></option>
                <option value="pesel"<?php if(!empty($field['validation']) && $field['validation'] == 'pesel'):?> selected="selected"<?php endif; ?>><?=lang('Form.validation.Pesel'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-row field-type-cfg" data-for="select">
        <div class="form-label">
            <label><?=lang('Form.fields.Options');?></label>
        </div>
        <div class="form-field" data-opt-repeater>
            <div data-opt-items>
                <?php foreach((!empty($field['options']) ? $field['options'] : array()) as $oi=>$opt) { $renderOption($oi, $opt); } ?>
            </div>
            <template data-opt-template><?php $renderOption('__i__', array()); ?></template>
            <button type="button" class="btn" data-opt-add><?=lang('Form.fields.AddOption'); ?></button>
        </div>
    </div>
    <div class="form-row field-type-cfg" data-for="file">
        <div class="form-label">
            <label><?=lang('Form.fields.MaxFiles');?></label>
        </div>
        <div class="form-field">
            <input type="number" min="1" max="10" name="<?=$n; ?>[max_files]" value="<?=!empty($field['max_files']) ? (int) $field['max_files'] : 4; ?>" />
        </div>
    </div>
    <div class="form-row field-type-cfg" data-for="file">
        <div class="form-label">
            <label><?=lang('Form.fields.MaxFileSize');?></label>
        </div>
        <div class="form-field">
            <input type="number" min="0" name="<?=$n; ?>[max_file_size]" value="<?=isset($field['max_file_size']) ? (int) $field['max_file_size'] : 0; ?>" />
            <?php if($max_upload_kb): ?>
                <span class="s"><?=lang('Form.fields.MaxFileSizeHint', array($max_upload_kb)); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.ShowWhenField');?></label>
        </div>
        <div class="form-field">
            <?php /* Lista rodzicow budowana w JS z aktualnego DOM — patrz /adm/js/form.js */ ?>
            <select class="parent-select" name="<?=$n; ?>[parent_key]" data-selected="<?=!empty($field['parent_field']) ? 'f' . (int) $field['parent_field'] : ''; ?>"></select>
        </div>
    </div>
    <div class="form-row parent-values-row">
        <div class="form-label">
            <label><?=lang('Form.fields.ShowWhenValues');?></label>
        </div>
        <div class="form-field">
            <?php
                // Stan zapisany takze w kluczach ('o44,o45'), zeby JS mial jeden jezyk.
                $selected_options = '';
                if(!empty($field['parent_values'])) {
                    $selected_options = implode(',', array_map(
                        function ($v) { return 'o' . (int) $v; },
                        array_filter(explode(',', $field['parent_values']), 'strlen')
                    ));
                }
            ?>
            <select class="parent-values" multiple="multiple" size="4" name="<?=$n; ?>[parent_values][]" data-selected="<?=esc($selected_options, 'attr'); ?>"></select>
            <span class="s"><?=lang('Form.fields.ShowWhenHint'); ?></span>
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.Required');?></label>
        </div>
        <div class="form-field">
            <input type="checkbox" name="<?=$n; ?>[required]" value="1"<?=!empty($field['required']) && $field['required'] ? 'checked="checked"' : ''; ?> />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">
            <label><?=lang('Form.fields.Publish');?></label>
        </div>
        <div class="form-field">
            <input type="checkbox" name="<?=$n; ?>[publish]" value="1"<?=!empty($field['publish']) && $field['publish'] ? 'checked="checked"' : ''; ?> />
        </div>
    </div>
</div>
