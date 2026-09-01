<div class="main-cont">
    <?php if(isset($breadcrumbs)) {echo $breadcrumbs;} ?>
    <div class="c">
        <div class="head">
            <?=lang('Admin.settings.Settings'); ?>
        </div>
        <?= view('admin/alert_box', array('flashdata'=>!empty($flashdata) ? $flashdata : array())); ?>
        <form class="form settings-form" action="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/settings" method="post">
            <div class="form-row nag">
                <h3><?=lang('Admin.settings.GlobalSettings'); ?></h3>
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
                                    <label><?=lang('Admin.settings.CompanyName');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="company_name[<?=$lang['id']; ?>]" value="<?=!empty($settings['company_name']) && !empty($settings['company_name'][$lang['id']]) ? $settings['company_name'][$lang['id']] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.settings.ShortCompanyName');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="company_short_name[<?=$lang['id']; ?>]" value="<?=!empty($settings['company_short_name']) && !empty($settings['company_short_name'][$lang['id']]) ? $settings['company_short_name'][$lang['id']] : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Email');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="email" value="<?= !empty($settings['email']) ? $settings['email'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Phone');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="phone" value="<?= !empty($settings['phone']) ? $settings['phone'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Address');?></label>
                </div>
                <div class="form-field">
                    <?php /* textarea, a nie input — adres bywa wieloliniowy (np. nazwa budynku
                             w drugiej linii). Odbiorcy musza go wypuszczac przez nl2br(). */ ?>
                    <textarea name="address"><?= !empty($settings['address']) ? $settings['address'] : ''; ?></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.NIP');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="nip" value="<?= !empty($settings['nip']) ? $settings['nip'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Regon');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="regon" value="<?= !empty($settings['regon']) ? $settings['regon'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.KRS');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="krs" value="<?= !empty($settings['krs']) ? $settings['krs'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.BankAccount');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="bank_account" value="<?= !empty($settings['bank_account']) ? $settings['bank_account'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.BankName');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="bank_name" value="<?= !empty($settings['bank_name']) ? $settings['bank_name'] : ''; ?>" >
                </div>
            </div>

            <!-- Dane kontaktowe na stronie /kontakt (lewa kolumna formularza) -->
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3>Strona kontaktu — lokalizacja i godziny</h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Lokalizacja na mapie</label>
                </div>
                <div class="form-field">
                    <input type="text" name="map_location" value="<?= !empty($settings['map_location']) ? esc($settings['map_location'], 'attr') : ''; ?>" >
                    <div class="shortcodes">Adres wpisywany do Google Maps, np. <em>Biblioteka Uniwersytecka w Warszawie, Dobra 56/66, Warszawa</em>. Z tego pola powstaje mapa i link „Otwórz w Google Maps”. Puste pole = mapa się nie wyświetla.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Godziny otwarcia</label>
                </div>
                <div class="form-field">
                    <textarea name="opening_hours" rows="3"><?= !empty($settings['opening_hours']) ? esc($settings['opening_hours']) : ''; ?></textarea>
                    <div class="shortcodes">Każda linia wyświetli się osobno.</div>
                </div>
            </div>

            <!-- Dane strukturalne (schema.org) — trafiają do JSON-LD w <head> każdej strony -->
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3>Dane strukturalne (schema.org)</h3>
            </div>
            <div class="form-row">
                <div class="form-field">
                    <div class="shortcodes">Te pola budują blok JSON-LD w nagłówku strony — to z nich Google czyta adres, godziny i zakres działania firmy. Pole „Adres” wyżej jest tekstem dla człowieka; tutaj ten sam adres podajemy rozbity na części.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Ulica i numer</label>
                </div>
                <div class="form-field">
                    <input type="text" name="address_street" value="<?= !empty($settings['address_street']) ? esc($settings['address_street'], 'attr') : ''; ?>" >
                    <div class="shortcodes">np. <em>ul. Dobra 56/66</em></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Kod pocztowy</label>
                </div>
                <div class="form-field">
                    <input type="text" name="address_postal_code" value="<?= !empty($settings['address_postal_code']) ? esc($settings['address_postal_code'], 'attr') : ''; ?>" >
                    <div class="shortcodes">np. <em>00-312</em></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Miasto</label>
                </div>
                <div class="form-field">
                    <input type="text" name="address_city" value="<?= !empty($settings['address_city']) ? esc($settings['address_city'], 'attr') : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Województwo</label>
                </div>
                <div class="form-field">
                    <input type="text" name="address_region" value="<?= !empty($settings['address_region']) ? esc($settings['address_region'], 'attr') : ''; ?>" >
                    <div class="shortcodes">np. <em>mazowieckie</em></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Kraj</label>
                </div>
                <div class="form-field">
                    <input type="text" name="address_country" value="<?= !empty($settings['address_country']) ? esc($settings['address_country'], 'attr') : ''; ?>" >
                    <div class="shortcodes">Dwuliterowy kod ISO, dla Polski: <em>PL</em></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Szerokość geograficzna</label>
                </div>
                <div class="form-field">
                    <input type="text" name="geo_lat" value="<?= !empty($settings['geo_lat']) ? esc($settings['geo_lat'], 'attr') : ''; ?>" >
                    <div class="shortcodes">Z Google Maps: prawy przycisk myszy na pinezce → pierwsza liczba. np. <em>52.241900</em>. Puste = współrzędne nie trafią do JSON-LD.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Długość geograficzna</label>
                </div>
                <div class="form-field">
                    <input type="text" name="geo_lng" value="<?= !empty($settings['geo_lng']) ? esc($settings['geo_lng'], 'attr') : ''; ?>" >
                    <div class="shortcodes">Druga liczba z Google Maps, np. <em>21.024600</em></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Godziny otwarcia (format dla Google)</label>
                </div>
                <div class="form-field">
                    <textarea name="opening_hours_spec" rows="3"><?= !empty($settings['opening_hours_spec']) ? esc($settings['opening_hours_spec']) : ''; ?></textarea>
                    <?php /* Osobne pole od "Godziny otwarcia" wyzej: tamto jest tekstem po polsku
                             dla odwiedzajacego, tego wymaga schema.org i musi byc maszynowe. */ ?>
                    <div class="shortcodes">Jedna reguła w linii, format <em>Dni GG:MM-GG:MM</em>. Dni: <em>Mo Tu We Th Fr Sa Su</em>, można zakresem (<em>Mo-Fr</em>) lub listą (<em>Mo,We,Fr</em>). Przykład:<br><em>Mo-Fr 09:00-19:00</em><br><em>Sa 10:00-14:00</em><br>Dni nieczynne po prostu pomijamy. Linie w złym formacie są ignorowane.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Obszar działania</label>
                </div>
                <div class="form-field">
                    <input type="text" name="area_served" value="<?= !empty($settings['area_served']) ? esc($settings['area_served'], 'attr') : ''; ?>" >
                    <div class="shortcodes">Lista po przecinku, np. <em>Warszawa, Polska</em></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Rok założenia</label>
                </div>
                <div class="form-field">
                    <input type="text" name="founding_date" value="<?= !empty($settings['founding_date']) ? esc($settings['founding_date'], 'attr') : ''; ?>" >
                    <div class="shortcodes">Rok (<em>2008</em>) albo pełna data (<em>2008-03-01</em>). Musi się zgadzać z tym, co piszemy w treści strony.</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Przedział cenowy</label>
                </div>
                <div class="form-field">
                    <input type="text" name="price_range" value="<?= !empty($settings['price_range']) ? esc($settings['price_range'], 'attr') : ''; ?>" >
                    <div class="shortcodes">Umowna skala, np. <em>$$</em> lub <em>100-2000 PLN</em></div>
                </div>
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
                                    <label>Slogan firmy</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="slogan[<?=$lang['id']; ?>]" value="<?= esc(!empty($settings['slogan']) && !empty($settings['slogan'][$lang['id']]) ? $settings['slogan'][$lang['id']] : '', 'attr'); ?>" />
                                    <div class="shortcodes">Jedno zdanie opisujące firmę, np. <em>Serwis i naprawa sprzętu Apple w Warszawie</em></div>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>

            <!-- Pasek ogłoszeniowy (góra strony) — pod ustawieniami globalnymi -->
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3>Pasek ogłoszeniowy (góra strony)</h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label>Pokaż pasek ogłoszeniowy</label>
                </div>
                <div class="form-field">
                    <select name="announcement_enabled">
                        <option value="1"<?= (!empty($settings['announcement_enabled']) && $settings['announcement_enabled'] != '0') ? ' selected' : ''; ?>>Tak — pokaż</option>
                        <option value="0"<?= (empty($settings['announcement_enabled']) || $settings['announcement_enabled'] == '0') ? ' selected' : ''; ?>>Nie — ukryj</option>
                    </select>
                </div>
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
                                    <label>Treść ogłoszenia (można używać HTML)</label>
                                </div>
                                <div class="form-field">
                                    <textarea name="announcement_text[<?=$lang['id']; ?>]" rows="2" placeholder='np. &lt;strong&gt;Uwaga:&lt;/strong&gt; w sobotę serwis nieczynny — &lt;a href="/kontakt"&gt;napisz do nas&lt;/a&gt;'><?= esc(!empty($settings['announcement_text']) && !empty($settings['announcement_text'][$lang['id']]) ? $settings['announcement_text'][$lang['id']] : '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row-space"></div>
			 <div class="form-row nag">
                <h3><?=lang('Admin.settings.LogoSVG'); ?></h3>
            </div>
			<div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.LogoSvgCode');?></label>
                </div>
                <div class="form-field">
                    <textarea name="logo_svg"><?=!empty($settings['logo_svg']) && !empty($settings['logo_svg']) ? $settings['logo_svg'] : ''; ?></textarea>
                </div>
            </div>
			
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.settings.Multimedia'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Logo');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($settings['logo'])): ?>
                            <?=view('admin/filemenager/files_list', array('files'=>array($settings['logo']), 'name'=>'logo','key_name'=>'')); ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="" data-field-name="logo" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                    <input type="hidden" name="id_logo" value="<?=!empty($settings['logo']) ? $settings['logo']['id'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.LogoDark');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($settings['logo_dark'])): ?>
                            <?=view('admin/filemenager/files_list', array('files'=>array($settings['logo_dark']), 'name'=>'logo_dark','key_name'=>'')); ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="" data-field-name="logo_dark" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                    <input type="hidden" name="id_logo_dark" value="<?=!empty($settings['logo_dark']) ? $settings['logo_dark']['id'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Favicon');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($settings['favicon'])): ?>
                            <?=view('admin/filemenager/files_list', array('files'=>array($settings['favicon']), 'name'=>'favicon','key_name'=>'')); ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="" data-field-name="favicon" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                    <input type="hidden" name="id_favicon" value="<?=!empty($settings['favicon']) ? $settings['favicon']['id'] : ''; ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.NoPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($settings['no_photo'])): ?>
                            <?=view('admin/filemenager/files_list', array('files'=>array($settings['no_photo']), 'name'=>'no_photo','key_name'=>'')); ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="" data-field-name="no_photo" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                    <input type="hidden" name="id_no_photo" value="<?=!empty($settings['no_photo']) ? $settings['no_photo']['id'] : ''; ?>" />
                </div>
            </div>
             <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.settings.DefaultMetatags'); ?></h3>
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
                                    <label><?=lang('Admin.settings.MetaTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="meta_title[<?=$lang['id']; ?>]" value="<?=!empty($settings['meta_title']) && !empty($settings['meta_title'][$lang['id']]) ? $settings['meta_title'][$lang['id']] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.settings.MetaDescription');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta_description[<?=$lang['id']; ?>]"><?=!empty($settings['meta_description']) && !empty($settings['meta_description'][$lang['id']]) ? $settings['meta_description'][$lang['id']] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-label">
                                    <label><?=lang('Admin.settings.MetaKeywords');?></label>
                                </div>
                                <div class="form-field">
                                    <textarea name="meta_keywords[<?=$lang['id']; ?>]"><?=!empty($settings['meta_keywords']) && !empty($settings['meta_keywords'][$lang['id']]) ? $settings['meta_keywords'][$lang['id']] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.MetaPhoto');?></label>
                </div>
                <div class="form-field">
                    <div class="files-list">
                        <?php if(!empty($settings['meta_photo'])): ?>
                            <?=view('admin/filemenager/files_list', array('files'=>array($settings['meta_photo']), 'name'=>'meta_photo','key_name'=>'')); ?>
                        <?php endif; ?>
                    </div>
                    <a href="<?=$locale ? '/' . $locale : ''; ?>/<?= env('ADMIN_PANEL_SLUG'); ?>/file-menager/open" title="<?=lang('Admin.file-menager.AddChangePhoto');?>" class="btn file-menager" data-multi="false" data-key="" data-field-name="meta_photo" data-title="<?=lang('Admin.file-menager.FileMenager');?>" data-btn-ok="<?=lang('Admin.file-menager.Select');?>" data-btn-cancel="<?=lang('Admin.file-menager.Cancel');?>"><?=lang('Slider.slide.AddChangePhoto');?></a>
                    <input type="hidden" name="id_meta_photo" value="<?=!empty($settings['meta_photo']) ? $settings['meta_photo']['id'] : ''; ?>" />
                </div>
            </div>
            
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.settings.FormsSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.FormSender');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="form_sender" value="<?= !empty($settings['form_sender']) ? $settings['form_sender'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.FormSenderEmail');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="form_sender_email" value="<?= !empty($settings['form_sender_email']) ? $settings['form_sender_email'] : ''; ?>" >
                </div>
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
                                    <label><?=lang('Admin.settings.NewsletterDefaultTitle');?></label>
                                </div>
                                <div class="form-field">
                                    <input type="text" name="neewsletter_title_default[<?=$lang['id']; ?>]" value="<?=!empty($settings['neewsletter_title_default']) && !empty($settings['neewsletter_title_default'][$lang['id']]) ? $settings['neewsletter_title_default'][$lang['id']] : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    <?php ++$l; endforeach; ?>
                <?php if(!empty($languages) && count($languages) > 1): ?></div><?php endif; ?>
            </div>
            
            
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.settings.SocialSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Facebook');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="facebook" value="<?= !empty($settings['facebook']) ? $settings['facebook'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.YouTube');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="youtube" value="<?= !empty($settings['youtube']) ? $settings['youtube'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Instagram');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="instagram" value="<?= !empty($settings['instagram']) ? $settings['instagram'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.Twitter');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="twitter" value="<?= !empty($settings['twitter']) ? $settings['twitter'] : ''; ?>" >
                </div>
            </div>
             <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.TikTok');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="tiktok" value="<?= !empty($settings['tiktok']) ? $settings['tiktok'] : ''; ?>" >
                </div>
            </div>
		   <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.settings.WidgetSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.GoogleSiteVerificationHtmlTag');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="widget_gsv" value="<?= !empty($settings['widget_gsv']) ? $settings['widget_gsv'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.GoogleAnalytics');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="widget_ga" value="<?= !empty($settings['widget_ga']) ? $settings['widget_ga'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.GoogleTagManager');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="widget_gtm" value="<?= !empty($settings['widget_gtm']) ? $settings['widget_gtm'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.FacebookPixel');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="widget_fp" value="<?= !empty($settings['widget_fp']) ? $settings['widget_fp'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.reCAPTCHAv3_site_key');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="recaptchav3_site_key" value="<?= !empty($settings['recaptchav3_site_key']) ? $settings['recaptchav3_site_key'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.reCAPTCHAv3_secret_key');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="recaptchav3_secret_key" value="<?= !empty($settings['recaptchav3_secret_key']) ? $settings['recaptchav3_secret_key'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.GoogleMapsJavaScriptAPIKey');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="widget_gmjs" value="<?= !empty($settings['widget_gmjs']) ? $settings['widget_gmjs'] : ''; ?>" >
                </div>
            </div>
            
            <div class="form-row-space"></div>
            <div class="form-row nag">
                <h3><?=lang('Admin.settings.OtherSettings'); ?></h3>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.WorkingHours');?></label>
                </div>
                <div class="form-field">
                    <input type="text" name="working_hours" value="<?= !empty($settings['working_hours']) ? $settings['working_hours'] : ''; ?>" >
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.IndexWebsite');?></label>
                </div>
                <div class="form-field">
                    <select name="index_website">
                        <option value="1"<?php if(isset($settings['index_website']) && $settings['index_website'] == 1):?> selected="selected"<?php endif; ?>><?=lang('Admin.settings.Index'); ?></option>
                        <option value="0"<?php if(isset($settings['index_website']) && $settings['index_website'] == 0):?> selected="selected"<?php endif; ?>><?=lang('Admin.settings.DoNotIndex'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label">
                    <label><?=lang('Admin.settings.TurnOnTechnicalBreak');?></label>
                </div>
                <div class="form-field">
                    <select name="technical_break">
                        <option value="1"<?php if(isset($settings['technical_break']) && $settings['technical_break'] == 1):?> selected="selected"<?php endif; ?>><?=lang('Admin.settings.TurnOn'); ?></option>
                        <option value="0"<?php if(!isset($settings['technical_break']) || $settings['technical_break'] == 0):?> selected="selected"<?php endif; ?>><?=lang('Admin.settings.TurnOff'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-row submit">
                 <button type="submit" class=""><?=lang('Admin.settings.Save'); ?></button>
            </div>     
        </form>
    </div>
</div>
