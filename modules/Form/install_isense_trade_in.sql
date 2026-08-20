-- ---------------------------------------------------------------------------
-- Modul Form — formularz Trade-in na podstronie /trade-in (page 66).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`. Jezeli uruchamiasz to
--        na innej instalacji, podmien prefiks WSZEDZIE.
--
-- WYMAGANIA: najpierw uruchom `install_upgrade_conditional_fields.sql`
--            (kolumny parent_field / parent_values / max_files w form_field).
--
-- CO ROBI: blok page_content 263 mial juz zalozony rekord tio_form (id=2), ale
-- pusty — bez pol, bez odbiorcy, z domyslnym szablonem form1.php. Skrypt go
-- konfiguruje i wypelnia polami.
--
-- Pola to gałąź "Trade in" z formularza kontaktowego (form 1), ale BEZ selecta
-- "Temat" — na tej podstronie nie ma czego przelaczac, wiec wszystkie pola sa
-- widoczne od razu. Jedyny warunek jaki zostaje: "Jakie urzadzenie chcesz
-- kupic?" pokazuje sie po wybraniu "Zakup kolejnego urzadzenia".
--
-- Skrypt jest RE-RUNNABLE: kasuje wszystkie dotychczasowe pola formularza 2
-- i tworzy je od nowa. Konfiguracja formularza jest aktualizowana.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_isense_trade_in.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

SET @id_lang := 1;                    -- tio_language: jedyny jezyk, PL
SET @block   := 263;                  -- tio_page_content.id (strona /trade-in, page.id=66)
SET @mailto  := 'serwis@isense.pl';   -- <<< PODMIEN na docelowy adres

-- --- Formularz --------------------------------------------------------------
SET @id_form := (SELECT `id` FROM `tio_form` WHERE `id_page_cont` = @block LIMIT 1);

INSERT INTO `tio_form` (`id_page_cont`,`template`,`addressee`,`addressee_cc`,`addressee_bcc`,`captcha`)
SELECT @block, 'form_isense_2col.php', @mailto, '', '', 0 FROM DUAL WHERE @id_form IS NULL;
SET @id_form := IFNULL(@id_form, LAST_INSERT_ID());

-- Bez `addressee` Libraries/Form::ajax() cicho pomija cala wysylke.
-- Szablon musi miec swoj odpowiednik w Views/user/mails/ — inaczej, tak samo
-- cicho, nie pojdzie zaden mail (patrz Form.php, warunek file_exists()).
-- captcha=0, bo tio_settings.recaptchav3_* sa puste.
UPDATE `tio_form`
   SET `template` = 'form_isense_2col.php', `addressee` = @mailto, `captcha` = 0
 WHERE `id` = @id_form;

INSERT INTO `tio_form_lang` (`id_form`,`id_lang`,`name`,`description`,`success_msg`,`error_msg`)
SELECT @id_form, @id_lang, 'Formularz Trade-in', '',
       'Dziękujemy — zgłoszenie zostało wysłane. Odezwiemy się z wyceną najszybciej jak to możliwe.',
       'Nie udało się wysłać zgłoszenia. Spróbuj ponownie lub napisz na nasz adres e-mail.'
  FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM `tio_form_lang` WHERE `id_form` = @id_form AND `id_lang` = @id_lang);

-- `description` NIE jest tu nadpisywany — to tresc lewej kolumny szablonu,
-- redagowana w panelu (edytor WYSIWYG). Ponowne uruchomienie skryptu jej nie zniszczy.
UPDATE `tio_form_lang`
   SET `name`        = 'Formularz Trade-in',
       `success_msg` = 'Dziękujemy — zgłoszenie zostało wysłane. Odezwiemy się z wyceną najszybciej jak to możliwe.',
       `error_msg`   = 'Nie udało się wysłać zgłoszenia. Spróbuj ponownie lub napisz na nasz adres e-mail.'
 WHERE `id_form` = @id_form AND `id_lang` = @id_lang;

-- --- Tresc lewej kolumny ----------------------------------------------------
-- Przeniesiona z sekcji Isense `trade-in` (blok 152): plakietka, naglowek, lead
-- i trzy korzysci. Ikony musza byc wklejonym SVG — w tresci WYSIWYG nie zadziala
-- helper isense_icon().
--
-- WAZNE: uzyte klasy Tailwinda pochodza z modules/Isense/Views/user/trade-in/tpl.php
-- i form_isense.php, wiec sa juz w public/assets/isense/css/isense.css. Tresc z bazy
-- NIE jest skanowana przez theme-build/src.css — nowa klasa nie zadziala.
--
-- Ustawiane TYLKO gdy pole jest puste, zeby nie nadpisac zmian z panelu.
SET @desc := CONCAT(
'<div class="inline-flex items-center gap-2 bg-white text-[#3b81f7] px-4 py-2 rounded-full mb-6 border border-[#D2D2D7]">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>',
'<span class="text-sm font-medium">Trade-In</span></div>',
'<h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6">Oddaj stary sprzęt — odbierz rabat lub gotówkę</h2>',
'<p class="text-lg text-[#6E6E73] mb-8">Wycenimy Twoje urządzenie i zaproponujemy najlepszą ofertę odkupu lub rabatu na naprawę.</p>',
'<div class="space-y-6">',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><path d="M16 7h6v6"/><path d="m22 7-8.5 8.5-5-5L2 17"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Najlepsza wycena na rynku</h3><p class="text-sm text-[#6E6E73]">Oferujemy konkurencyjne ceny za używany sprzęt Apple</p></div></div>',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Rabat przy naprawie</h3><p class="text-sm text-[#6E6E73]">Wykorzystaj wartość starego sprzętu jako rabat na naprawę</p></div></div>',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Gotówka lub przelew</h3><p class="text-sm text-[#6E6E73]">Wypłata od ręki lub szybki przelew bankowy</p></div></div>',
'</div>');

UPDATE `tio_form_lang`
   SET `description` = @desc
 WHERE `id_form` = @id_form AND `id_lang` = @id_lang
   AND (`description` IS NULL OR `description` = '');

-- --- Czyszczenie poprzednich pol --------------------------------------------
DELETE ffol FROM `tio_form_field_option_lang` ffol
  JOIN `tio_form_field_option` ffo ON ffo.`id` = ffol.`id_option`
  JOIN `tio_form_field` ff ON ff.`id` = ffo.`id_field`
 WHERE ff.`id_form` = @id_form;
DELETE ffo FROM `tio_form_field_option` ffo
  JOIN `tio_form_field` ff ON ff.`id` = ffo.`id_field`
 WHERE ff.`id_form` = @id_form;
DELETE ffl FROM `tio_form_field_lang` ffl
  JOIN `tio_form_field` ff ON ff.`id` = ffl.`id_field`
 WHERE ff.`id_form` = @id_form;
DELETE FROM `tio_form_field` WHERE `id_form` = @id_form;

-- ===========================================================================
-- 1-3. Dane kontaktowe zglaszajacego
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`)
VALUES (@id_form,'text','',1,1,1);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Imię i nazwisko','');

INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`)
VALUES (@id_form,'text','email',2,1,1);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Email','');

INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`)
VALUES (@id_form,'text','phone',3,1,1);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Nr telefonu','');

-- ===========================================================================
-- 4-11. Pola Trade-in — widoczne zawsze (brak selecta "Temat").
-- ===========================================================================

-- 4. Model urzadzenia
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',4,1,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Wpisz model urządzenia','np. iPhone 14 Pro 128GB Gold');

-- 5. Wylogowanie z „Znajdz" + MDM (Tak/Nie)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',5,1,1,0,'');
SET @f_mdm := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_mdm,@id_lang,'Czy urządzenie będzie wylogowane z Usługi znajdź i pozbawione profili Mobile Device Management (MDM)?','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_mdm,'mdm-tak',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Tak');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_mdm,'mdm-nie',1,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Nie');

-- 6. Zakup w sieci komorkowej (Tak/Nie)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',6,1,1,0,'');
SET @f_siec := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_siec,@id_lang,'Czy urządzenie zostało zakupione w sieci komórkowej? (np. Orange, Play)','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_siec,'siec-tak',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Tak');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_siec,'siec-nie',1,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Nie');

-- 7. Stan urzadzenia
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',7,1,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Stan urządzenia','Opis stanu — ew. wgniecenia, obicia, stan baterii, problemy z funkcjami');

-- 8. Zdjecia (nie wymagane)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`,`max_files`,`max_file_size`)
VALUES (@id_form,'file',8,0,1,0,'',4,0);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Zdjęcia urządzenia','Załącz kilka zdjęć urządzenia (nie wymagane) — maks. 4 pliki JPG, PNG, WEBP');

-- 9. Sposob rozliczenia
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',9,1,1,0,'');
SET @f_rozl := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_rozl,@id_lang,'Jak chcesz rozliczyć odkup?','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_rozl,'gotowka',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Gotówka');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_rozl,'zakup-kolejnego',1,1);
SET @o_zakup := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o_zakup,@id_lang,'Zakup kolejnego urządzenia');

-- 10. ZAGNIEZDZENIE: widoczne tylko przy „Zakup kolejnego urzadzenia".
--     parent_values wskazuje na opcje utworzona wyzej w TYM skrypcie (@o_zakup),
--     a nie na id z formularza kontaktowego.
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',10,1,1,@f_rozl,@o_zakup);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Jakie urządzenie chcesz kupić?','np. iPhone 16 Pro 512GB');

-- 11. Propozycja cenowa
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',11,1,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Twoja propozycja cenowa','np. 2500 zł');

-- ===========================================================================
-- 12. ZGODA RODO — wymagana, widoczna zawsze, na samym koncu.
--     Nazwa pola jest jednoczesnie trescia klauzuli na froncie; szablon
--     renderuje `checkbox` jako szara ramke zgody.
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'checkbox',12,1,1,0,'');
SET @f_zgoda := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_zgoda,@id_lang,'Wyrażam zgodę na przetwarzanie moich danych osobowych, podanych w powyższym formularzu, wyłącznie w celu udzielenia odpowiedzi na wysłaną przeze mnie wiadomość. Przesłane w formularzu dane osobowe będą przetwarzane przez administratora zgodnie przepisami ustawy z dnia 29 sierpnia 1997 roku o ochronie danych osobowych (t.j. Dz.U. z 2002 roku Nr 101, poz. 926 z późn. zm.). Podanie danych jest dobrowolne. Administrator umożliwia wgląd do własnych danych osobowych i zapewnia prawo ich poprawiania, jak i usunięcia.','');

-- --- Kontrola ---------------------------------------------------------------
SELECT ff.`id`, ff.`order`, ff.`type`, ff.`required`, ff.`parent_field`, ff.`parent_values`, ffl.`name`
  FROM `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id` AND ffl.`id_lang` = @id_lang
 WHERE ff.`id_form` = @id_form
 ORDER BY ff.`order`;
