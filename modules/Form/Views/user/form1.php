<?php
/*
Formularz 1
*/
/* form_field: atrybuty required / type / autocomplete / inputmode wyliczane
   z kolumny `validation` — patrz Helpers/form_field_helper.php. */
helper('form_field');
?>
<section class="section section-<?=$id_cont; ?>">
    <div class="container">
        <?php if(!empty($title)): ?>
            <h2><?=$title; ?></h2>
        <?php endif; ?>
        <?php if(!empty($subtitle)): ?>
            <h3><?=$subtitle; ?></h3>
        <?php endif; ?>
        <?php if(!empty($data)): ?>
            <div class="form form-<?=$data['id']; ?>">
                <?php if(!empty($data['description'])): ?>
                    <div class="description"><?=$data['description']; ?></div>
                <?php endif; ?>
				<?php if(!empty($data['name'])): ?>
                    <h2 class="form-name"><?=$data['name']; ?></h2>
                <?php endif; ?>
                <?php if(!empty($data['fields'])): ?>
                    <form id="form-<?=$data['id']; ?>" class="form form-<?=$data['id']; ?> ajax" data-msg-required="<?=esc(lang('Form.field.Required'), 'attr'); ?>" data-msg-invalid="<?=esc(lang('Form.field.Invalid'), 'attr'); ?>" data-msg-checkbox="<?=esc(lang('Form.field.CheckboxRequired'), 'attr'); ?>" data-msg-files="<?=esc(lang('Form.file.TooMany', array('{0}')), 'attr'); ?>" method="post" enctype="multipart/form-data" action="<?=uri_string(); ?>">
                        <input type="hidden" name="content" value="<?=$id_cont; ?>" />
                        <?php /* Honeypot — ukryty inline, bo dla klasy .field-box.h nie ma
                                 zadnej reguly CSS i pole bylo widoczne dla uzytkownikow,
                                 a jego wypelnienie odrzuca cala wysylke. */ ?>
                        <div class="field-box h" style="display:none" aria-hidden="true">
                            <input type="text" name="field_h" value="" tabindex="-1" autocomplete="off" />
                        </div>
                        <?php foreach($data['fields'] as $field): ?>
                            <?php
                                // Pole warunkowe: /assets/js/form.js pokazuje je dopiero, gdy select
                                // wskazany przez data-parent ma wybrana jedna z data-parent-options.
                                // Ukrycie inline, zeby pole nie mignelo przed zaladowaniem JS.
                                $cond_attr = '';
                                $cond_cls = '';
                                if(!empty($field['parent_field'])) {
                                    $cond_attr = ' data-parent="field_' . (int) $field['parent_field'] . '"'
                                               . ' data-parent-options="' . esc($field['parent_values'], 'attr') . '"'
                                               . ' style="display:none"';
                                    $cond_cls = ' conditional h-cond';
                                }
                            ?>
                            <div class="field-box<?=$field['required'] ? ' required' : ''; ?><?=$cond_cls; ?>" data-field="field_<?=(int) $field['id']; ?>"<?=$cond_attr; ?>>
                                <?php
									if(!empty($field['description'])) {$placeholder=' placeholder="'.$field['description'].'" ';} else {$placeholder='';}
                                    switch($field['type']) {
                                        case 'textarea':
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . $field['name'] . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            echo '<div><textarea '.$placeholder.' name="field_' . $field['id'] . '" id="field-' . $field['id'] . '"' . form_field_attrs($field, 'textarea') . '></textarea></div>';
                                            break;
                                        case 'number':
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . $field['name'] . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            echo '<div><input '.$placeholder.' type="number" name="field_' . $field['id'] . '" value="" id="field-' . $field['id'] . '"' . form_field_attrs($field, 'number') . ' /></div>';
                                            break;
                                        case 'checkbox':
                                            echo '<div class="label"><input type="checkbox" name="field_' . $field['id'] . '" value="1" id="field-' . $field['id'] . '"' . form_field_attrs($field, 'checkbox') . ' /></div>';
                                            echo '<div><label for="field-' . $field['id'] . '">' . ($field['required'] ? '<span class="req">*</span> ' : '') . $field['name'] . '</label></div>';
                                            break;
                                        case 'select':
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . esc($field['name']) . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            echo '<div><select name="field_' . $field['id'] . '" id="field-' . $field['id'] . '"' . form_field_attrs($field, 'select') . '>';
                                            echo '<option value="">' . esc(!empty($field['description']) ? $field['description'] : lang('Form.field.Choose')) . '</option>';
                                            // value = ID opcji: stabilne, niezalezne od etykiety i jezyka.
                                            foreach($field['options'] as $option) {
                                                echo '<option value="' . (int) $option['id'] . '">' . esc($option['name']) . '</option>';
                                            }
                                            echo '</select></div>';
                                            break;
                                        case 'file':
                                            $max_files = max(1, (int) $field['max_files']);
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . esc($field['name']) . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            // Nazwa zawsze z [] — serwer ma wtedy jedna sciezke kodu
                                            // (getFileMultiple() zwraca tablice takze dla 1 pliku).
                                            // Jawna lista MIME zamiast image/*: przy image/* iOS Safari
                                            // wyslalby HEIC, ktorego images.imageMimeIn nie akceptuje.
                                            // Lista brana z tego samego zrodla co regula mime_in
                                            // w Libraries/Form::ajax(), zeby sie nie rozjechaly.
                                            $accept = config('Images')->imageMimeIn;
                                            echo '<div><input type="file" name="field_' . $field['id'] . '[]" id="field-' . $field['id'] . '"'
                                               . ($max_files > 1 ? ' multiple="multiple"' : '')
                                               . ($accept !== '' ? ' accept="' . esc($accept, 'attr') . '"' : '')
                                               . ' data-max-files="' . $max_files . '"'
                                               . form_field_attrs($field, 'file') . ' /></div>';
                                            if(!empty($field['description'])) {
                                                echo '<div class="hint">' . esc($field['description']) . '</div>';
                                            }
                                            break;
                                        default:
                                            echo '<div class="label"><label for="field-' . $field['id'] . '">' . $field['name'] . ($field['required'] ? '<span class="req">*</span>' : '') . '</label></div>';
                                            echo '<div><input type="' . form_field_input_type($field) . '" '.$placeholder.' name="field_' . $field['id'] . '" value="" id="field-' . $field['id'] . '"' . form_field_attrs($field, 'input') . ' /></div>';
                                            break;
                                    }
                                ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="field-box submit">
                           <div class="label">&nbsp;</div><div>
                                <?php
                                    /* Przycisk g-recaptcha tylko gdy klucz witryny jest ustawiony —
                                       przy pustym settings.recaptchav3_site_key reCAPTCHA nie
                                       odpalala callbacku i formularza nie dalo sie wyslac. */
                                    $sitekey = !empty($settings['recaptchav3_site_key']) ? $settings['recaptchav3_site_key'] : '';
                                ?>
                                <?php if(!empty($data['captcha']) && $sitekey !== ''): ?>
                                    <button class="g-recaptcha trans400" data-sitekey="<?=esc($sitekey, 'attr'); ?>" data-callback='reCaptchaForm<?=(int) $data['id']; ?>Submit' data-action='submit'><?=lang('Form.field.Send'); ?></button>
                                <?php else: ?>
                                    <input type="submit" name="submit" value="<?=lang('Form.field.Send'); ?>" class="trans400">
                                <?php endif; ?>
                           </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
</section>
