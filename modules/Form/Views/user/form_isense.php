<?php
/*
Formularz iSense (dane kontaktowe + formularz)
*/

/**
 * Uklad 1:1 z modules/Isense/Views/user/contact/tpl.php — lewa kolumna
 * (dane kontaktowe, mapa) jest powielona z tamtej sekcji, prawa zawiera
 * pola konfigurowane w module Form.
 *
 * WAZNE: klasy Tailwinda pochodza z builda theme-build/src.css, ktory skanuje
 * zrodla przy kompilacji. Uzywamy DOKLADNIE tych samych ciagow klas co
 * contact/tpl.php — dzieki temu wszystkie sa juz w assets/isense/css/isense.css
 * i nie trzeba przebudowywac CSS. Nowe klasy wymagaja rebuildu (patrz src.css).
 *
 * Klasy .field-box, data-field, data-parent* oraz .field-box.submit sa wymagane
 * przez assets/js/form.js (warunki, komunikaty, bledy) — nie usuwac.
 */
helper(['url', 'isense', 'form_field']);
$c = !empty($data['contact']) ? $data['contact'] : array();

$cls_label = 'block text-sm font-medium text-[#1D1D1F] mb-2';
$cls_input = 'w-full px-4 py-3 rounded-lg border border-[#D2D2D7] bg-white focus:border-[#3b81f7] focus:ring-2 focus:ring-[#3b81f7]/20';
?>
<section class="bg-white py-16 lg:py-24 section-<?=$id_cont; ?>">
    <div class="max-w-[1300px] mx-auto px-4 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12">

            <!-- Dane kontaktowe -->
            <div>
                <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8"><?= esc(!empty($c['left_heading']) ? $c['left_heading'] : lang('User.contact.ContactData')) ?></h2>
                <div class="space-y-6 mb-12">
                    <?php if (!empty($c['address'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('map-pin', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1"><?= lang('User.contact.Address') ?></h3><p class="text-[#6E6E73] whitespace-pre-line"><?= esc($c['address']) ?></p></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($c['phone'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('phone', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1"><?= lang('User.contact.Phone') ?></h3><a href="tel:<?= esc(preg_replace('/\s+/', '', $c['phone']), 'attr') ?>" class="text-[#6E6E73] hover:text-[#3b81f7] transition-colors"><?= esc($c['phone']) ?></a></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($c['email'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('mail', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1"><?= lang('User.contact.Email') ?></h3><a href="mailto:<?= esc($c['email'], 'attr') ?>" class="text-[#6E6E73] hover:text-[#3b81f7] transition-colors"><?= esc($c['email']) ?></a></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($c['hours'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('clock', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div><h3 class="font-semibold text-[#1D1D1F] mb-1"><?= lang('User.contact.OpeningHours') ?></h3><div class="text-[#6E6E73] whitespace-pre-line"><?= esc($c['hours']) ?></div></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($c['nip']) || !empty($c['regon'])): ?>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0"><?= isense_icon('clipboard-list', 'w-6 h-6 text-[#3b81f7]') ?></div>
                        <div>
                            <h3 class="font-semibold text-[#1D1D1F] mb-1"><?= lang('User.contact.CompanyData') ?></h3>
                            <div class="text-[#6E6E73]">
                                <?php if (!empty($c['nip'])): ?><p><?= lang('User.contact.Nip') ?>: <?= esc($c['nip']) ?></p><?php endif; ?>
                                <?php if (!empty($c['regon'])): ?><p><?= lang('User.contact.Regon') ?>: <?= esc($c['regon']) ?></p><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($c['map'])): ?>
                <div class="rounded-2xl overflow-hidden border border-[#D2D2D7] h-80">
                    <iframe title="<?= esc(lang('User.contact.MapTitle'), 'attr') ?>" class="w-full h-full" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= esc($c['map'], 'attr') ?>"></iframe>
                </div>
                <?php endif; ?>
                <?php if (!empty($c['map_link'])): ?>
                <a href="<?= esc($c['map_link'], 'attr') ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 mt-3 text-sm text-[#3b81f7] hover:underline"><?= isense_icon('map-pin', 'w-4 h-4') ?><?= lang('User.contact.OpenInGoogleMaps') ?></a>
                <?php endif; ?>
            </div>

            <!-- Formularz -->
            <div class="form form-<?= (int) $data['id'] ?> isense-form">
                <h2 class="text-3xl font-bold text-[#1D1D1F] mb-8"><?= esc(!empty($title) ? $title : (!empty($data['name']) ? $data['name'] : lang('User.contact.WriteToUs'))) ?></h2>
                <?php if (!empty($data['description'])): ?>
                    <div class="text-[#6E6E73] mb-8"><?= $data['description'] ?></div>
                <?php endif; ?>
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
