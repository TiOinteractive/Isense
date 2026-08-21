-- ---------------------------------------------------------------------------
-- Modul Form — formularz wyceny naprawy na /wycena-naprawy (page 88).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`. Jezeli uruchamiasz to
--        na innej instalacji, podmien prefiks WSZEDZIE.
--
-- WYMAGANIA: najpierw uruchom `install_upgrade_conditional_fields.sql`
--            (kolumny parent_field / parent_values / max_files w form_field).
--
-- CO ROBI: blok page_content 266 mial zalozony rekord tio_form (id=4), ale
-- pusty — bez pol, bez odbiorcy, z domyslnym szablonem form1.php. Skrypt go
-- konfiguruje i wypelnia polami.
--
-- Pola to gałąź "Wycena naprawy" z formularza kontaktowego (form 1), ale BEZ
-- selecta "Temat". To najprostszy z trzech formularzy iSense — nie ma tu
-- ZADNEGO pola warunkowego, wszystkie sa widoczne od razu.
--
-- SZABLON: form_isense_2col.php — ten sam co /trade-in i /naprawa-z-odbiorem.
-- Ma juz swoj odpowiednik w Views/user/mails/, bez ktorego Form::ajax()
-- po cichu pomijalby wysylke.
--
-- Skrypt jest RE-RUNNABLE: kasuje wszystkie dotychczasowe pola formularza
-- i tworzy je od nowa. Konfiguracja formularza jest aktualizowana.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_isense_wycena_naprawy.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

SET @id_lang := 1;                    -- tio_language: jedyny jezyk, PL
SET @block   := 266;                  -- tio_page_content.id (/wycena-naprawy, page.id=88)
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
SELECT @id_form, @id_lang, 'Formularz wyceny naprawy', '',
       'Dziękujemy — zgłoszenie zostało wysłane. Odezwiemy się z wyceną i oceną możliwości naprawy.',
       'Nie udało się wysłać zgłoszenia. Spróbuj ponownie lub napisz na nasz adres e-mail.'
  FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM `tio_form_lang` WHERE `id_form` = @id_form AND `id_lang` = @id_lang);

-- `description` NIE jest tu nadpisywany — ustawiamy go nizej tylko gdy jest pusty.
UPDATE `tio_form_lang`
   SET `name`        = 'Formularz wyceny naprawy',
       `success_msg` = 'Dziękujemy — zgłoszenie zostało wysłane. Odezwiemy się z wyceną i oceną możliwości naprawy.',
       `error_msg`   = 'Nie udało się wysłać zgłoszenia. Spróbuj ponownie lub napisz na nasz adres e-mail.'
 WHERE `id_form` = @id_form AND `id_lang` = @id_lang;

-- --- Tresc lewej kolumny ----------------------------------------------------
-- Na tej podstronie nie ma sekcji, z ktorej mozna by przeniesc tresc — ponizsze
-- jest napisane od zera, ale KAZDE zdanie ma pokrycie albo w polach formularza,
-- albo w deklaracjach, ktore serwis juz publikuje w kilkunastu miejscach
-- ("Diagnoza jest zawsze bezplatna i bez zobowiazan", "O kosztach naprawy
-- informujemy przed jej rozpoczeciem"). Celowo BEZ obietnic czasowych.
--
-- Naglowek celowo nie powtarza "Wycena naprawy" — to tytul bloku page-hero (265)
-- stojacego bezposrednio nad formularzem.
--
-- Ikony musza byc wklejonym SVG — w tresci WYSIWYG nie zadziala isense_icon().
--
-- WAZNE: klasy Tailwinda skopiowane z modules/Isense/Views/user/pickup/tpl.php,
-- wiec sa juz w public/assets/isense/css/isense.css. Tresc z bazy NIE jest
-- skanowana przez theme-build/src.css — nowa klasa nie zadziala.
--
-- Ustawiane TYLKO gdy pole jest puste, zeby nie nadpisac zmian z panelu.
SET @desc := CONCAT(
'<div class="inline-flex items-center gap-2 bg-[#3b81f7]/10 text-[#3b81f7] px-4 py-2 rounded-full mb-6">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>',
'<span class="text-sm font-medium">Bezpłatna diagnoza</span></div>',
'<h2 class="text-3xl lg:text-4xl font-bold text-[#1D1D1F] mb-6">Sprawdź, ile kosztuje naprawa</h2>',
'<p class="text-lg text-[#6E6E73] mb-8">Opisz usterkę i dołącz zdjęcia — odpowiemy z wyceną i oceną możliwości naprawy. Diagnoza jest zawsze bezpłatna i bez zobowiązań.</p>',
'<div class="space-y-6">',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Diagnoza bez opłat</h3><p class="text-sm text-[#6E6E73]">Wycenę i ocenę usterki wykonujemy bezpłatnie, bez zobowiązań</p></div></div>',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/><circle cx="18.5" cy="15.5" r="2.5"/><path d="M20.27 17.27 22 19"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Wycena na podstawie zdjęć</h3><p class="text-sm text-[#6E6E73]">Dołącz zdjęcia sprzętu, żeby wycena była dokładniejsza</p></div></div>',
'<div class="flex gap-4"><div class="w-12 h-12 bg-[#3b81f7]/10 rounded-xl flex items-center justify-center flex-shrink-0">',
'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-[#3b81f7]" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>',
'</div><div><h3 class="font-semibold text-[#1D1D1F] mb-1">Naprawa po Twojej akceptacji</h3><p class="text-sm text-[#6E6E73]">Koszt poznajesz przed rozpoczęciem prac</p></div></div>',
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
-- 4-6. Pola wyceny — wszystkie widoczne zawsze, zero warunkow.
-- ===========================================================================

-- 4. Model urzadzenia
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'text',4,1,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Wpisz model urządzenia','np. iPhone 14 Pro 128GB Gold');

-- 5. Opis problemow
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'textarea',5,1,1,0,'');
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Opisz problemy','Co się dzieje z urządzeniem, od kiedy, czy było już naprawiane');

-- 6. Zdjecia (nie wymagane) — to na nich opiera sie korzysc "Wycena na podstawie zdjec"
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`,`max_files`,`max_file_size`)
VALUES (@id_form,'file',6,0,1,0,'',4,0);
SET @f := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f,@id_lang,'Zdjęcia urządzenia','Załącz kilka zdjęć sprzętu — maks. 4 pliki JPG, PNG, WEBP');

-- ===========================================================================
-- 7. ZGODA RODO — wymagana, na samym koncu.
--    Nazwa pola jest jednoczesnie trescia klauzuli na froncie; szablon
--    renderuje `checkbox` jako szara ramke zgody.
-- ===========================================================================
INSERT INTO `tio_form_field` (`id_form`,`type`,`order`,`required`,`publish`,`parent_field`,`parent_values`)
VALUES (@id_form,'checkbox',7,1,1,0,'');
SET @f_zgoda := LAST_INSERT_ID();
INSERT INTO `tio_form_field_lang` (`id_field`,`id_lang`,`name`,`description`)
VALUES (@f_zgoda,@id_lang,'Wyrażam zgodę na przetwarzanie moich danych osobowych, podanych w powyższym formularzu, wyłącznie w celu udzielenia odpowiedzi na wysłaną przeze mnie wiadomość. Przesłane w formularzu dane osobowe będą przetwarzane przez administratora zgodnie przepisami ustawy z dnia 29 sierpnia 1997 roku o ochronie danych osobowych (t.j. Dz.U. z 2002 roku Nr 101, poz. 926 z późn. zm.). Podanie danych jest dobrowolne. Administrator umożliwia wgląd do własnych danych osobowych i zapewnia prawo ich poprawiania, jak i usunięcia.','');

-- --- Kontrola ---------------------------------------------------------------
SELECT ff.`id`, ff.`order`, ff.`type`, ff.`required`, ff.`parent_field`, ff.`parent_values`, ffl.`name`
  FROM `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id` AND ffl.`id_lang` = @id_lang
 WHERE ff.`id_form` = @id_form
 ORDER BY ff.`order`;
