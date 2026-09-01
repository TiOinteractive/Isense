-- ---------------------------------------------------------------------------
-- Modul Form — seed formularza kontaktowego iSense (3 warianty).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`. Jezeli uruchamiasz to
--        na innej instalacji, podmien prefiks WSZEDZIE.
--
-- WYMAGANIA: najpierw uruchom `install_upgrade_conditional_fields.sql`.
--
-- Skrypt jest RE-RUNNABLE: kasuje wszystkie dotychczasowe pola formularza
-- na bloku 262 (w tym smieciowe pole testowe) i tworzy je od nowa.
-- Konfiguracja samego formularza (odbiorca, szablon) jest aktualizowana.
--
-- Struktura warunkow:
--   Temat --+- Trade in           (pola 5-12, z zagniezdzeniem w polu 11)
--           +- Wycena naprawy     (pola 13-15)
--           +- Naprawa wysylkowa  (pola 16-23)
--                                  +- „Jak dostarczyc?" - kurierem serwisu -> pola 20-22
--                                     (to jest ZAGNIEZDZENIE 2. POZIOMU)
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_isense_kontakt.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

SET @id_lang := 1;                    -- tio_language: jedyny jezyk, PL
SET @block   := 262;                  -- tio_page_content.id (strona /kontakt, page.id=64)
SET @mailto  := 'serwis@isense.pl';   -- <<< PODMIEN na docelowy adres

-- --- Formularz --------------------------------------------------------------
SET @id_form := (SELECT `id` FROM `tio_form` WHERE `id_page_cont` = @block LIMIT 1);

INSERT INTO `tio_form` (`id_page_cont`,`template`,`addressee`,`addressee_cc`,`addressee_bcc`,`captcha`)
SELECT @block, 'form1.php', @mailto, '', '', 0 FROM DUAL WHERE @id_form IS NULL;
SET @id_form := IFNULL(@id_form, LAST_INSERT_ID());

-- Bez `addressee` Libraries/Form::ajax() cicho pomija cala wysylke.
-- captcha=0, bo tio_settings.recaptchav3_* sa puste — inaczej kazda wysylka padnie.
UPDATE `tio_form`
   SET `template` = 'form1.php', `addressee` = @mailto, `captcha` = 0
 WHERE `id` = @id_form;

INSERT INTO `tio_form_lang` (`id_form`,`id_lang`,`name`,`description`,`success_msg`,`error_msg`)
SELECT @id_form, @id_lang, 'Formularz kontaktowy iSense', '',
       'Dziękujemy — wiadomość została wysłana. Odezwiemy się najszybciej jak to możliwe.',
       'Nie udało się wysłać wiadomości. Spróbuj ponownie lub napisz na nasz adres e-mail.'
  FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM `tio_form_lang` WHERE `id_form` = @id_form AND `id_lang` = @id_lang);

UPDATE `tio_form_lang`
   SET `name`        = 'Formularz kontaktowy iSense',
       `success_msg` = 'Dziękujemy — wiadomość została wysłana. Odezwiemy się najszybciej jak to możliwe.',
       `error_msg`   = 'Nie udało się wysłać wiadomości. Spróbuj ponownie lub napisz na nasz adres e-mail.'
 WHERE `id_form` = @id_form AND `id_lang` = @id_lang;

-- --- Czyszczenie poprzednich pol (w tym testowego) --------------------------
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
-- 1-3. Pola wspolne
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`)
VALUES (@id_form,'text','name',1,1,1);
SET @f_name := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_name,@id_lang,'Imię i nazwisko','');

INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`)
VALUES (@id_form,'text','email',2,1,1);
SET @f_mail := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_mail,@id_lang,'Email','');

INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`)
VALUES (@id_form,'text','phone',3,1,1);
SET @f_phone := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_phone,@id_lang,'Nr telefonu','');

-- ===========================================================================
-- 4. TEMAT — select sterujacy trzema wariantami
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`)
VALUES (@id_form,'select','',4,1,1);
SET @f_temat := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_temat,@id_lang,'Temat','-- wybierz temat --');

INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_temat,'trade-in',0,1);
SET @o_trade := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o_trade,@id_lang,'Trade in');

INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_temat,'wycena-naprawy',1,1);
SET @o_wycena := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o_wycena,@id_lang,'Wycena naprawy');

INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_temat,'naprawa-wysylkowa',2,1);
SET @o_wysylka := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o_wysylka,@id_lang,'Naprawa wysyłkowa');

-- ===========================================================================
-- 5-12. WARIANT: TRADE IN
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',5,1,1,@f_temat,@o_trade);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Wpisz model urządzenia','np. iPhone 14 Pro 128GB Gold');

-- 6. Wylogowanie z „Znajdz" + MDM (Tak/Nie)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',6,1,1,@f_temat,@o_trade);
SET @f_mdm := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_mdm,@id_lang,'Czy urządzenie będzie wylogowane z Usługi znajdź i pozbawione profili Mobile Device Management (MDM)?','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_mdm,'mdm-tak',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Tak');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_mdm,'mdm-nie',1,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Nie');

-- 7. Zakup w sieci komorkowej (Tak/Nie)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',7,1,1,@f_temat,@o_trade);
SET @f_siec := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_siec,@id_lang,'Czy urządzenie zostało zakupione w sieci komórkowej? (np. Orange, Play)','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_siec,'siec-tak',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Tak');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_siec,'siec-nie',1,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Nie');

-- 8. Stan urzadzenia
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',8,1,1,@f_temat,@o_trade);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Stan urządzenia','Opis stanu — ew. wgniecenia, obicia, stan baterii, problemy z funkcjami');

-- 9. Zdjecia (nie wymagane)
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`,`max_files`,`max_file_size`)
VALUES (@id_form,'file',9,0,1,@f_temat,@o_trade,4,0);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Zdjęcia urządzenia','Załącz kilka zdjęć urządzenia (nie wymagane) — maks. 4 pliki JPG, PNG, WEBP');

-- 10. Sposob rozliczenia
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',10,1,1,@f_temat,@o_trade);
SET @f_rozl := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_rozl,@id_lang,'Jak chcesz rozliczyć odkup?','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_rozl,'gotowka',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Gotówka');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_rozl,'zakup-kolejnego',1,1);
SET @o_zakup := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o_zakup,@id_lang,'Zakup kolejnego urządzenia');

-- 11. ZAGNIEZDZENIE: widoczne tylko przy „Zakup kolejnego urzadzenia",
--     ktore samo jest widoczne tylko przy Temat = Trade in.
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',11,1,1,@f_rozl,@o_zakup);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Jakie urządzenie chcesz kupić?','np. iPhone 16 Pro 512GB');

-- 12. Propozycja cenowa
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',12,1,1,@f_temat,@o_trade);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Twoja propozycja cenowa','np. 2500 zł');

-- ===========================================================================
-- 13-15. WARIANT: WYCENA NAPRAWY
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',13,1,1,@f_temat,@o_wycena);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Wpisz model urządzenia','np. iPhone 14 Pro 128GB Gold');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',14,1,1,@f_temat,@o_wycena);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Opisz problemy','Co się dzieje z urządzeniem, od kiedy, czy było już naprawiane');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`,`max_files`,`max_file_size`)
VALUES (@id_form,'file',15,0,1,@f_temat,@o_wycena,4,0);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Zdjęcia urządzenia','Załącz kilka zdjęć sprzętu — maks. 4 pliki JPG, PNG, WEBP');

-- ===========================================================================
-- 16-23. WARIANT: NAPRAWA WYSYLKOWA
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',16,1,1,@f_temat,@o_wysylka);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Wpisz model urządzenia','np. iPhone 14 Pro 128GB Gold');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',17,0,1,@f_temat,@o_wysylka);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Numer seryjny / IMEI urządzenia','Jeśli to możliwe');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',18,1,1,@f_temat,@o_wysylka);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Opisz problem/y','Jeśli wysyłasz kilka sprzętów, opisz każdy w osobnym akapicie');

-- 19. Select WEWNATRZ grupy „Naprawa wysylkowa" — sam warunkuje pola 20-22
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'select',19,1,1,@f_temat,@o_wysylka);
SET @f_dost := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_dost,@id_lang,'Jak chcesz dostarczyć sprzęt?','-- wybierz --');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_dost,'swoim-kurierem',0,1);
SET @o := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o,@id_lang,'Swoim kurierem');
INSERT INTO `tio_form_field_option` (`id_field`,`slug`,`order`,`publish`) VALUES (@f_dost,'kurierem-serwisu',1,1);
SET @o_kurier := LAST_INSERT_ID();
INSERT INTO `tio_form_field_option_lang` (`id_option`,`id_lang`,`name`) VALUES (@o_kurier,@id_lang,'Kurierem serwisu');

-- 20-22. ZAGNIEZDZENIE 2. POZIOMU:
--        Temat = Naprawa wysylkowa -> Dostawa = Kurierem serwisu -> te pola.
-- `validation` = 'address' nie dodaje reguly serwerowej — wlacza
-- autocomplete="street-address" (Helpers/form_field_helper.php).
INSERT INTO `tio_form_field` (`id_form`,`type`,`validation`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea','address',20,1,1,@f_dost,@o_kurier);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Adres odbioru paczki','Ulica, nr, kod pocztowy, miejscowość');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',21,1,1,@f_dost,@o_kurier);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Preferowana data odbioru','np. 2026-09-01');

INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',22,0,1,@f_dost,@o_kurier);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Alternatywna data odbioru','');

-- 23. Uwagi
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',23,0,1,@f_temat,@o_wysylka);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Uwagi','Wpisz swoje uwagi, dane do faktury, kod do urządzenia');

-- ===========================================================================
-- 24. ZGODA RODO — wymagana, widoczna zawsze (bez warunku), na samym koncu.
--     Nazwa pola jest jednoczesnie trescia klauzuli na froncie; szablon
--     form_isense.php renderuje `checkbox` jako szara ramke zgody.
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'checkbox',24,1,1,0,'');
SET @f_zgoda := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_zgoda,@id_lang,'Wyrażam zgodę na przetwarzanie moich danych osobowych, podanych w powyższym formularzu, wyłącznie w celu udzielenia odpowiedzi na wysłaną przeze mnie wiadomość. Przesłane w formularzu dane osobowe będą przetwarzane przez administratora zgodnie przepisami ustawy z dnia 29 sierpnia 1997 roku o ochronie danych osobowych (t.j. Dz.U. z 2002 roku Nr 101, poz. 926 z późn. zm.). Podanie danych jest dobrowolne. Administrator umożliwia wgląd do własnych danych osobowych i zapewnia prawo ich poprawiania, jak i usunięcia.','');

-- --- Kontrola ---------------------------------------------------------------
SELECT ff.`id`, ff.`order`, ff.`type`, ff.`required`, ff.`parent_field`, ff.`parent_values`, ffl.`name`
  FROM `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id` AND ffl.`id_lang` = @id_lang
 WHERE ff.`id_form` = @id_form
 ORDER BY ff.`order`;
