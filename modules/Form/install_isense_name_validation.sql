-- ---------------------------------------------------------------------------
-- Modul Form — oznaczenie pola „Imie i nazwisko" walidacja `name`.
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- PO CO: Libraries/Form.php doklej wartosc tego pola na koncu tematu maila
-- (np. „Formularz Trade-in — Jan Kowalski"). Znacznikiem jest kolumna
-- `validation` = 'name'. Nie dodaje ona zadnej reguly walidacyjnej — switch
-- w bloku regul nie ma przypadku 'name' ani `default`, wiec dla istniejacych
-- formularzy nic sie nie zmienia poza tematem maila.
--
-- Skrypty install_isense_*.sql zakladaja juz ten znacznik przy tworzeniu pola.
-- Ten plik jest dla instalacji, na ktorych formularze juz istnieja.
--
-- Skrypt jest RE-RUNNABLE i celowo nie rusza pol, ktore maja juz ustawiona
-- jakakolwiek walidacje (`validation` = '' w warunku).
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_isense_name_validation.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

-- Bloki page_content czterech formularzy iSense:
--   262 /kontakt, 263 /trade-in, 264 /naprawa-z-odbiorem, 266 /wycena-naprawy
UPDATE `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id`
  JOIN `tio_form` f ON f.`id` = ff.`id_form`
   SET ff.`validation` = 'name'
 WHERE f.`id_page_cont` IN (262, 263, 264, 266)
   AND ff.`type` = 'text'
   AND (ff.`validation` IS NULL OR ff.`validation` = '')
   AND ffl.`name` = 'Imię i nazwisko';

-- Kontrola: powinno wyjsc po jednym wierszu na formularz.
SELECT f.`id_page_cont` AS blok, ff.`id` AS id_pola, ffl.`name`, ff.`validation`
  FROM `tio_form_field` ff
  JOIN `tio_form_field_lang` ffl ON ffl.`id_field` = ff.`id`
  JOIN `tio_form` f ON f.`id` = ff.`id_form`
 WHERE ff.`validation` = 'name'
 ORDER BY f.`id_page_cont`;
