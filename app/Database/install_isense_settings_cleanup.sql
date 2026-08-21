-- ---------------------------------------------------------------------------
-- Ustawienia — sprzatanie po poprzednim projekcie (RESinet / RzeszowskieSmaki).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- CO ROBI:
--   1. Zmienia nazwe ustawienia `logo_resinet_svg` na `logo_svg`.
--   2. Kasuje cztery ustawienia, ktorych pola zniknely z panelu:
--      advert_email, advert_phone, flavor_email, flavor_email2.
--
-- KIEDY URUCHOMIC: PRZED pierwszym zapisem konfiguracji w panelu.
-- SettingsModel::saveSettings() kasuje kazdy rekord `settings`, ktorego nie
-- bylo w POST (poza url_% i special_%). Skoro pole `logo_resinet_svg` juz
-- w formularzu nie istnieje, pierwszy zapis skasowalby ten rekord razem
-- z jego wartoscia — czyli z kodem SVG logotypu. Po zmianie nazwy rekord
-- odpowiada polu `logo_svg` i zapis go nie rusza.
--
-- Skrypt jest RE-RUNNABLE.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < app/Database/install_isense_settings_cleanup.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

-- --- 1. logo_resinet_svg -> logo_svg ----------------------------------------
-- Gdyby `logo_svg` juz istnialo (skrypt puszczony drugi raz albo pole zapisane
-- recznie), zostawiamy je w spokoju i kasujemy osierocony stary rekord.
UPDATE `tio_settings`
   SET `name` = 'logo_svg'
 WHERE `name` = 'logo_resinet_svg'
   AND NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_settings`) s WHERE s.`name` = 'logo_svg');

DELETE FROM `tio_settings` WHERE `name` = 'logo_resinet_svg';

-- --- 2. Ustawienia bez pola w panelu ----------------------------------------
-- Najpierw wersje jezykowe, potem rekordy glowne (brak FK z ON DELETE CASCADE).
DELETE sl FROM `tio_settings_lang` sl
  JOIN `tio_settings` s ON s.`id` = sl.`id_settings`
 WHERE s.`name` IN ('advert_email', 'advert_phone', 'flavor_email', 'flavor_email2');

DELETE FROM `tio_settings`
 WHERE `name` IN ('advert_email', 'advert_phone', 'flavor_email', 'flavor_email2');

-- --- Kontrola ---------------------------------------------------------------
-- Oczekiwane: jeden wiersz `logo_svg`, zero pozostalych.
SELECT `name`, LEFT(COALESCE(`value`, ''), 40) AS wartosc
  FROM `tio_settings`
 WHERE `name` IN ('logo_svg', 'logo_resinet_svg', 'advert_email', 'advert_phone', 'flavor_email', 'flavor_email2')
 ORDER BY `name`;
