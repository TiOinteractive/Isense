-- ---------------------------------------------------------------------------
-- Modul Form — formularz naprawy wysylkowej na /naprawa-z-odbiorem (page 67).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`. Jezeli uruchamiasz to
--        na innej instalacji, podmien prefiks WSZEDZIE.
--
-- WYMAGANIA: najpierw uruchom `install_upgrade_conditional_fields.sql`
--            (kolumny parent_field / parent_values / max_files w form_field).
--
-- CO ROBI: blok page_content 264 mial zalozony rekord tio_form (id=3), ale
-- pusty — bez pol, bez odbiorcy, z domyslnym szablonem form1.php. Skrypt go
-- konfiguruje i wypelnia polami.
--
-- Pola to gałąź "Naprawa wysylkowa" z formularza kontaktowego (form 1), ale
-- BEZ selecta "Temat" — na tej podstronie nie ma czego przelaczac. Zostaje
-- jedno przelaczanie: adres odbioru i daty pojawiaja sie po wybraniu
-- "Kurierem serwisu". W formularzu kontaktowym bylo to zagniezdzenie 2. poziomu
-- (Temat -> sposob dostawy -> adres); tutaj splaszcza sie do jednego poziomu.
--
-- SZABLON: form_isense_2col.php — ten sam co /trade-in. Ma juz swoj odpowiednik
-- w Views/user/mails/, bez ktorego Form::ajax() po cichu pomijalby wysylke.
--
-- Skrypt jest RE-RUNNABLE: kasuje wszystkie dotychczasowe pola formularza 3
-- i tworzy je od nowa. Konfiguracja formularza jest aktualizowana.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_isense_naprawa_wysylkowa.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

SET @id_lang := 1;                    -- tio_language: jedyny jezyk, PL
SET @block   := 264;                  -- tio_page_content.id (/naprawa-z-odbiorem, page.id=67)
SET @mailto  := 'serwis@isense.pl';   -- <<< PODMIEN na docelowy adres

-- --- Formularz --------------------------------------------------------------
SET @id_form := (SELECT `id` FROM `tio_form` WHERE `id_page_cont` = @block LIMIT 1);

INSERT INTO `tio_form` (`id_page_cont`,`template`,`addressee`,`addressee_cc`,`addressee_bcc`,`captcha`)
SELECT @block, 'form_isense_2col.php', @mailto, '', '', 0 FROM DUAL WHERE @id_form IS NULL;
SET @id_form := IFNULL(@id_form, LAST_INSERT_ID());

-- Bez `addressee` Libraries/Form::ajax() cicho pomija cala wysylke.
-- captcha=0, bo tio_settings.recaptchav3_* sa puste.
UPDATE `tio_form`
   SET `template` = 'form_isense_2col.php', `addressee` = @mailto, `captcha` = 0
 WHERE `id` = @id_form;

INSERT INTO `tio_form_lang` (`id_form`,`id_lang`,`name`,`description`,`success_msg`,`error_msg`)
SELECT @id_form, @id_lang, 'Formularz naprawy wysyłkowej', '',
       'Dziękujemy — zgłoszenie zostało wysłane. Skontaktujemy się w sprawie odbioru sprzętu.',
       'Nie udało się wysłać zgłoszenia. Spróbuj ponownie lub napisz na nasz adres e-mail.'
  FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM `tio_form_lang` WHERE `id_form` = @id_form AND `id_lang` = @id_lang);

-- `description` NIE jest tu nadpisywany — ustawiamy go nizej tylko gdy jest pusty.
UPDATE `tio_form_lang`
   SET `name`        = 'Formularz naprawy wysyłkowej',
       `success_msg` = 'Dziękujemy — zgłoszenie zostało wysłane. Skontaktujemy się w sprawie odbioru sprzętu.',
       `error_msg`   = 'Nie udało się wysłać zgłoszenia. Spróbuj ponownie lub napisz na nasz adres e-mail.'
 WHERE `id_form` = @id_form AND `id_lang` = @id_lang;

-- --- Tresc lewej kolumny ----------------------------------------------------
-- Przeniesiona z sekcji Isense `pickup` (blok 149): plakietka, naglowek, lead
-- i trzy korzysci. Ikony musza byc wklejonym SVG — w tresci WYSIWYG nie zadziala
-- helper isense_icon().
--
-- ZMIANA WZGLEDEM SEKCJI 149: trzecia korzysc "Weryfikacja SMS" zostala
-- zastapiona. Weryfikacji SMS NIE MA w kodzie — przycisk "Wyslij kod"
-- w pickup/tpl.php to type="button" bez handlera w JS i bez endpointu.
-- W zamian korzysc pokryta przez realne pola formularza (preferowana
-- i alternatywna data odbioru).
--
-- WAZNE: klasy Tailwinda skopiowane z modules/Isense/Views/user/pickup/tpl.php,
-- wiec sa juz w public/assets/isense/css/isense.css. Tresc z bazy NIE jest
-- skanowana przez theme-build/src.css — nowa klasa nie zadziala.
--
-- Ustawiane TYLKO gdy pole jest puste, zeby nie nadpisac zmian z panelu.
SET @desc := CONCAT(
'<div class="inline-flex items-center gap-2 bg-[#3b81f7]/10 text-[#3b81f7] px-4 py-2 rounded-full mb-6">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>',
'<span class="text-sm font-medium">Door-to-Door</span></div>',
'<h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6">Naprawa wysyłkowa</h2>',
'<p class="text-lg text-[#6E6E73] mb-8">Nie masz czasu przyjechać do serwisu? Skorzystaj z naprawy wysyłkowej — odbieramy kurierem z całej Polski, naprawiamy i odsyłamy z powrotem.</p>',
'<div class="space-y-6">',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><polyline points="3.29 7 12 12 20.71 7"/><path d="m7.5 4.27 9 5.15"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Bezpłatny odbiór i dostawa</h3><p class="text-sm text-[#6E6E73]">Kurier odbierze sprzęt i dostarczy po naprawie bez żadnych opłat</p></div></div>',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Bezpieczne opakowanie</h3><p class="text-sm text-[#6E6E73]">Zapewniamy profesjonalne zabezpieczenie sprzętu podczas transportu</p></div></div>',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Wygodny termin odbioru</h3><p class="text-sm text-[#6E6E73]">Wskazujesz preferowaną i alternatywną datę odbioru kuriera</p></div></div>',
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
VALUES (@id_form,'text','name',1,1,1);
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
-- 4-7, 11. Pola naprawy wysylkowej — widoczne zawsze (brak selecta "Temat").
-- ===========================================================================

-- 4. Model urzadzenia
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',4,1,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Wpisz model urządzenia','np. iPhone 14 Pro 128GB Gold');

-- 5. Numer seryjny / IMEI (nie wymagane)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',5,0,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Numer seryjny / IMEI urządzenia','Jeśli to możliwe');

-- 6. Opis problemow
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',6,1,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Opisz problem/y','Jeśli wysyłasz kilka sprzętów, opisz każdy w osobnym akapicie');

-- 7. Sposob dostarczenia sprzetu
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',7,1,1,0,'');
SET @f_dost := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_dost,@id_lang,'Jak chcesz dostarczyć sprzęt?','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_dost,'swoim-kurierem',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Swoim kurierem');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_dost,'kurierem-serwisu',1,1);
SET @o_kurier := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o_kurier,@id_lang,'Kurierem serwisu');

-- ===========================================================================
-- 8-10. ZAGNIEZDZENIE: widoczne tylko przy "Kurierem serwisu".
--       parent_values wskazuje na opcje utworzona wyzej w TYM skrypcie
--       (@o_kurier), a nie na id z formularza kontaktowego.
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',8,1,1,@f_dost,@o_kurier);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Adres odbioru paczki','Ulica, nr, kod pocztowy, miejscowość');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',9,1,1,@f_dost,@o_kurier);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Preferowana data odbioru','np. 2026-09-01');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',10,0,1,@f_dost,@o_kurier);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Alternatywna data odbioru','');

-- 11. Uwagi (nie wymagane, widoczne zawsze)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',11,0,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Uwagi','Wpisz swoje uwagi, dane do faktury, kod do urządzenia');

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
