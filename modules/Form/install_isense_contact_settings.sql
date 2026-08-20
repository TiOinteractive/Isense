-- ---------------------------------------------------------------------------
-- Ustawienia globalne lewej kolumny strony /kontakt (szablon form_isense.php).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`. Jezeli uruchamiasz to
--        na innej instalacji, podmien prefiks WSZEDZIE.
--
-- PO CO TO: do tej pory adres, godziny i mapa lewej kolumny byly zaszyte
-- w Modules\Form\Libraries\Form::loadIsenseContact(). Teraz pochodza z tabeli
-- `tio_settings`, edytowalnej w panelu (Ustawienia -> "Strona kontaktu —
-- lokalizacja i godziny"). Ten skrypt przenosi dotychczasowe wartosci do bazy,
-- zeby po wdrozeniu strona wygladala DOKLADNIE tak samo jak wczesniej.
--
-- BEZ URUCHOMIENIA TEGO SKRYPTU mapa i godziny znikna ze strony /kontakt,
-- dopoki ktos nie wejdzie w Ustawienia i nie kliknie zapisz.
--
-- Skrypt jest RE-RUNNABLE: istniejacych wartosci nie nadpisuje, wiec nie
-- skasuje tego, co administrator zdazyl juz zmienic w panelu.
-- `tio_settings.name` nie ma unikalnego indeksu, stad wzorzec
-- INSERT ... SELECT ... WHERE NOT EXISTS zamiast ON DUPLICATE KEY UPDATE.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_isense_contact_settings.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

-- Adres wpisywany do Google Maps. Z niego powstaje URL osadzenia
-- (?q=...&output=embed) oraz link "Otworz w Google Maps".
INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'map_location', 'Biblioteka Uniwersytecka w Warszawie, Dobra 56/66, Warszawa'
  FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'map_location');

-- Godziny otwarcia. Kazda linia renderuje sie osobno (whitespace-pre-line).
INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'opening_hours', CONCAT('Poniedziałek – Piątek: 9:00 – 19:00', CHAR(10), 'Sobota – Niedziela: Nieczynne')
  FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'opening_hours');

-- Kontrola: obie pozycje powinny byc na liscie.
SELECT `name`, `value` FROM `tio_settings`
 WHERE `name` IN ('map_location', 'opening_hours', 'address', 'phone', 'email')
 ORDER BY `name`;
