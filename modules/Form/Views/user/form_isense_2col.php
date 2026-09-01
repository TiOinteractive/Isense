<?php
/*
Formularz iSense (opis + formularz)
*/

/**
 * Wariant form_isense.php dla podstron innych niz kontakt (np. /trade-in).
 * Rozni sie tylko lewa kolumna: zamiast danych teleadresowych i mapy renderuje
 * `description` formularza — pole WYSIWYG z panelu (Views/admin/form.php), wiec
 * opis da sie zmienic bez dotykania kodu. Puste pole = pusta kolumna.
 *
 * WAZNE: klasy Tailwinda pochodza z builda theme-build/src.css, ktory NIE skanuje
 * modules/Form/Views. Wszystkie uzyte tu ciagi klas sa identyczne z tymi w
 * form_isense.php / modules/Isense/Views/user/contact/tpl.php, dzieki czemu juz
 * siedza w assets/isense/css/isense.css. Nowa klasa sie nie pojawi — wymagalaby
 * dopisania @source w src.css i przebudowy CSS.
 *
 * Klasy .field-box, data-field, data-parent* oraz .field-box.submit sa wymagane
 * przez assets/js/form.js (warunki, komunikaty, bledy) — nie usuwac.
 *
 * UWAGA: Libraries/Form::ajax() wysyla mail tylko gdy istnieje plik
 * Views/user/mails/<template>. Ten szablon ma swoj odpowiednik
 * mails/form_isense_2col.php — bez niego wysylka bylaby po cichu pomijana.
 */
helper(['url', 'isense', 'form_field']);

$cls_label = 'block text-sm font-medium text-[#1D1D1F] mb-2';
$cls_input = 'w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:outline-none focus:ring-2 focus:ring-[#3b81f7]/20';
?>
<section class="bg-[#F5F5F7] py-16 lg:py-24 section-<?=$id_cont; ?>">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12">

            <!-- Opis (pole `description` formularza, edytowalne w panelu) -->
            <div>
                <?php if (!empty($data['description'])): ?>
                    <div class="text-[#6E6E73] mb-8"><?= $data['description'] ?></div>
                <?php endif; ?>
            </div>

            <!-- Formularz -->
            <div class="form form-<?= (int) $data['id'] ?> isense-form">
                <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8"><?= esc(!empty($title) ? $title : (!empty($data['name']) ? $data['name'] : lang('User.contact.WriteToUs'))) ?></h2>
                <?php if (!empty($data['fields'])): ?>
                <form id="form-<?= (int) $data['id'] ?>" class="form form-<?= (int) $data['id'] ?> ajax space-y-6" data-msg-required="<?= esc(lang('Form.field.Required'), 'attr') ?>" data-msg-invalid="<?= esc(lang('Form.field.Invalid'), 'attr') ?>" data-msg-checkbox="<?= esc(lang('Form.field.CheckboxRequired'), 'attr') ?>" data-msg-files="<?= esc(lang('Form.file.TooMany', array('{0}')), 'attr') ?>" method="post" enctype="multipart/form-data" action="<?= uri_string() ?>">
                    <input type="hidden" name="content" value="<?= (int) $id_cont ?>" />
                    <?php /* Honeypot — ukryty inline, bo modul nie ma wlasnego arkusza stylow. */ ?>
                    <div class="field-box h" style="display:none" aria-hidden="true">
                        <input type="text" name="field_h" value="" tabindex="-1" autocomplete="off" />
                    </div>

                    <?php foreach ($data['fields'] as $field): ?>
                        <?php
                            $id = (int) $field['id'];
                            $req = !empty($field['required']);
                            $label = esc($field['name']) . ($req ? ' *' : '');
                            $ph = !empty($field['description']) ? ' placeholder="' . esc($field['description'], 'attr') . '"' : '';

                            $attr = ' data-field="field_' . $id . '"';
                            $cls = 'field-box' . ($req ? ' required' : '');
                            if (!empty($field['parent_field'])) {
                                $attr .= ' data-parent="field_' . (int) $field['parent_field'] . '"'
                                       . ' data-parent-options="' . esc($field['parent_values'], 'attr') . '"'
                                       . ' style="display:none"';
                                $cls .= ' conditional h-cond';
                            }
                        ?>
                        <div class="<?= $cls ?>"<?= $attr ?>>
                            <?php if ($field['type'] === 'checkbox'): ?>
                                <?php /* Uklad zgody na przetwarzanie danych z sekcji Isense.
                                         Szara ramka jest samym <label>, a nie osobnym <div> —
                                         assets/js/form.js oznacza blad przez addClass('error')
                                         na RODZICU inputa, wiec ramka musi nim byc, zeby dalo
                                         sie ja podswietlic (patrz assets/isense/css/form.css). */ ?>
                                <label for="field-<?= $id ?>" class="bg-[#F5F5F7] rounded border border-[#E5E5EA] p-4 flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="field_<?= $id ?>" value="1" id="field-<?= $id ?>"<?= form_field_attrs($field, 'checkbox') ?> class="mt-0.5 w-4 h-4 flex-shrink-0 accent-[#3b81f7]">
                                    <span class="text-xs text-[#6E6E73] leading-relaxed"><?= $label ?></span>
                                </label>
                            <?php else: ?>
                                <label for="field-<?= $id ?>" class="<?= $cls_label ?>"><?= $label ?></label>
                                <?php if ($field['type'] === 'textarea'): ?>
                                    <textarea name="field_<?= $id ?>" id="field-<?= $id ?>" rows="6"<?= $ph ?><?= form_field_attrs($field, 'textarea') ?> class="<?= $cls_input ?> resize-none"></textarea>
                                <?php elseif ($field['type'] === 'select'): ?>
                                    <select name="field_<?= $id ?>" id="field-<?= $id ?>"<?= form_field_attrs($field, 'select') ?> class="<?= $cls_input ?>">
                                        <option value=""><?= esc(!empty($field['description']) ? $field['description'] : lang('Form.field.Choose')) ?></option>
                                        <?php foreach ($field['options'] as $option): ?>
                                            <option value="<?= (int) $option['id'] ?>"><?= esc($option['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php elseif ($field['type'] === 'file'): ?>
                                    <?php
                                        $max_files = max(1, (int) $field['max_files']);
                                        // Ta sama lista, ktora waliduje Libraries/Form::ajax()
                                        // (regula mime_in) — inaczej okienko wyboru pliku
                                        // przepuszczaloby typy odrzucane potem przez backend.
                                        // Jawna lista zamiast image/*: przy image/* iOS Safari
                                        // wyslalby HEIC, ktorego images.imageMimeIn nie akceptuje.
                                        $accept = config('Images')->imageMimeIn;
                                    ?>
                                    <input type="file" name="field_<?= $id ?>[]" id="field-<?= $id ?>"<?= $max_files > 1 ? ' multiple="multiple"' : '' ?><?= $accept !== '' ? ' accept="' . esc($accept, 'attr') . '"' : '' ?> data-max-files="<?= $max_files ?>"<?= form_field_attrs($field, 'file') ?> class="<?= $cls_input ?>" />
                                <?php elseif ($field['type'] === 'number'): ?>
                                    <input type="number" name="field_<?= $id ?>" id="field-<?= $id ?>" value=""<?= $ph ?><?= form_field_attrs($field, 'number') ?> class="<?= $cls_input ?>" />
                                <?php else: ?>
                                    <?php /* type/inputmode/autocomplete wynikaja z kolumny `validation`
                                             ustawianej w panelu — patrz Helpers/form_field_helper.php. */ ?>
                                    <input type="<?= form_field_input_type($field) ?>" name="field_<?= $id ?>" id="field-<?= $id ?>" value=""<?= $ph ?><?= form_field_attrs($field, 'input') ?> class="<?= $cls_input ?>" />
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="field-box submit">
                        <?php
                            /* Przycisk g-recaptcha tylko gdy klucz witryny jest ustawiony.
                               Bez tego warunku wlaczenie captchy przy pustym
                               settings.recaptchav3_site_key dawalo przycisk z pustym
                               data-sitekey — reCAPTCHA nie odpalala callbacku i formularza
                               nie dalo sie wyslac. Teraz degradujemy do zwyklego submitu. */
                            $sitekey = !empty($settings['recaptchav3_site_key']) ? $settings['recaptchav3_site_key'] : '';
                        ?>
                        <?php if (!empty($data['captcha']) && $sitekey !== ''): ?>
                            <button class="g-recaptcha w-full bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium flex items-center justify-center gap-2" data-sitekey="<?= esc($sitekey, 'attr') ?>" data-callback="reCaptchaForm<?= (int) $data['id'] ?>Submit" data-action="submit"><?= isense_icon('send', 'w-5 h-5') ?><?= lang('Form.field.Send') ?></button>
                        <?php else: ?>
                            <button type="submit" name="submit" class="w-full bg-[#3b81f7] text-white px-8 py-4 rounded-lg hover:bg-[#2563eb] transition-colors font-medium flex items-center justify-center gap-2"><?= isense_icon('send', 'w-5 h-5') ?><?= lang('Form.field.Send') ?></button>
                        <?php endif; ?>
                        <p class="text-xs text-[#6E6E73] mt-3"><?= lang('User.contact.RequiredFields') ?></p>
                    </div>
                </form>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
