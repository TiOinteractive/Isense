-- ---------------------------------------------------------------------------
-- Modul Form — znaczniki `validation` dla autouzupelniania (name / address).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- PO CO: Helpers/form_field_helper.php zamienia kolumne `validation` na atrybuty
-- przegladarki. 'address' daje autocomplete="street-address", 'name' daje
-- autocomplete="name" — dzieki temu przegladarka podpowiada zapisane dane
-- kontaktowe (WCAG 1.3.5 Identify Input Purpose).
--
-- Zaden z tych dwoch znacznikow NIE dodaje reguly walidacyjnej po stronie
-- serwera — switch w Libraries/Form::ajax() nie ma przypadku 'name' ani
-- 'address' ani `default`. Jedyny dodatkowy efekt 'name' to imie i nazwisko
-- doklejane na koncu tematu maila (to samo, co robil juz
-- install_isense_name_validation.sql).
--
-- Pola e-mail i telefonu maja `validation` ustawiona od poczatku
-- (install_isense_*.sql), wiec type="email"/"tel", inputmode i autocomplete
-- pojawiaja sie tam bez zadnej migracji.
--
-- ZAKRES: wszystkie formularze na szablonie iSense — celowo po `f.template`,
-- a nie po liscie `id_page_cont` jak w install_isense_name_validation.sql.
-- Tamten skrypt trafial tylko w cztery zapisane na sztywno bloki i na czesci
-- instalacji nie oznaczyl nic (np. pole „Imie i nazwisko" na /trade-in).
--
-- Skrypt jest RE-RUNNABLE i celowo nie rusza pol, ktore maja juz ustawiona
-- jakakolwiek walidacje (`validation` = '' w warunku).
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_isense_address_validation.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

-- Adres (np. „Adres odbioru paczki").
UPDATE `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id`
  JOIN `tio_form` f ON f.`id` = ff.`id_form`
   SET ff.`validation` = 'address'
 WHERE f.`template` LIKE 'form\_isense%'
   AND (ff.`validation` IS NULL OR ff.`validation` = '')
   AND ffl.`name` LIKE 'Adres%';

-- Imie i nazwisko.
UPDATE `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id`
  JOIN `tio_form` f ON f.`id` = ff.`id_form`
   SET ff.`validation` = 'name'
 WHERE f.`template` LIKE 'form\_isense%'
   AND ff.`type` = 'text'
   AND (ff.`validation` IS NULL OR ff.`validation` = '')
   AND ffl.`name` = 'Imię i nazwisko';

-- Kontrola: po jednym wierszu na formularz dla kazdego ze znacznikow.
SELECT f.`id_page_cont` AS blok, ff.`id` AS id_pola, ffl.`name`, ff.`validation`
  FROM `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id`
  JOIN `tio_form` f ON f.`id` = ff.`id_form`
 WHERE ff.`validation` IN ('name', 'address')
 ORDER BY f.`id_page_cont`, ff.`order`;
