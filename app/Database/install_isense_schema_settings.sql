-- ---------------------------------------------------------------------------
-- Dane strukturalne (schema.org) — wartosci poczatkowe dla iSense.
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`. Na innej instalacji podmien
--        prefiks WSZEDZIE.
--
-- PO CO TO: blok JSON-LD w <head> byl zahardkodowany w Page::getDefaultMetatags()
-- i opisywal zupelnie inny serwis — portal informacyjny RESinet.pl z Rzeszowa
-- (typ NewsMediaOrganization, adres przy Twardowskiego 4B, areaServed
-- = Podkarpackie, foundingDate 2001). Teraz graf buduje App\Libraries\SchemaOrg
-- z ustawien panelu (Ustawienia -> "Dane strukturalne (schema.org)"), a ten
-- skrypt wpisuje do bazy prawdziwe dane iSense.
--
-- BEZ URUCHOMIENIA TEGO SKRYPTU JSON-LD wyjdzie bez adresu, godzin i geo —
-- SchemaOrg pomija puste ustawienia — dopoki ktos nie uzupelni pol w panelu.
--
-- KIEDY: po wdrozeniu kodu z nowa sekcja w app/Views/admin/settings/form.php.
-- Kolejnosc ma znaczenie: SettingsModel::saveSettings() kasuje kazdy rekord
-- `settings`, ktorego nie bylo w POST, wiec klucz bez pola w formularzu znika
-- przy pierwszym zapisie panelu.
--
-- Skrypt jest RE-RUNNABLE: istniejacych wartosci nie nadpisuje.
-- `tio_settings.name` nie ma unikalnego indeksu, stad wzorzec
-- INSERT ... SELECT ... WHERE NOT EXISTS zamiast ON DUPLICATE KEY UPDATE.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < app/Database/install_isense_schema_settings.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

-- --- 1. Ustawienia globalne (jedna wartosc, bez wersji jezykowych) -----------

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'address_street', 'ul. Dobra 56/66'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'address_street');

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'address_postal_code', '00-312'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'address_postal_code');

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'address_city', 'Warszawa'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'address_city');

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'address_region', 'mazowieckie'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'address_region');

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'address_country', 'PL'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'address_country');

-- Wspolrzedne przybliżone (Dobra 56/66, Warszawa). Do doprecyzowania pinezka
-- w Google Maps, gdy bedzie znana dokladna lokalizacja lokalu.
INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'geo_lat', '52.241900'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'geo_lat');

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'geo_lng', '21.024600'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'geo_lng');

-- Maszynowy odpowiednik wyswietlanego `opening_hours`
-- ("Poniedziałek – Piątek: 9:00 – 19:00", weekend nieczynne).
INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'opening_hours_spec', 'Mo-Fr 09:00-19:00'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'opening_hours_spec');

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'area_served', 'Warszawa, Polska'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'area_served');

-- Zgodnie z trescia strony ("Działamy od 2008"), nie z poprzednim literalem 2001.
INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'founding_date', '2008'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'founding_date');

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'price_range', '$$'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'price_range');

-- --- 2. Slogan — ustawienie per-jezyk ---------------------------------------
-- Rekord glowny z `value` = NULL (tak jak company_name), tresc w settings_lang.

INSERT INTO `tio_settings` (`name`, `value`)
SELECT 'slogan', NULL
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `tio_settings` WHERE `name` = 'slogan');

-- Polski slogan dla kazdego jezyka, ktory nie ma jeszcze wlasnego wpisu.
-- Wersje obcojezyczne uzupelniamy w panelu (Ustawienia -> zakladka jezyka).
INSERT INTO `tio_settings_lang` (`id_settings`, `id_lang`, `value`)
SELECT s.`id`, l.`id`, 'Serwis i naprawa sprzętu Apple w Warszawie'
  FROM `tio_settings` s
  JOIN `tio_language` l
 WHERE s.`name` = 'slogan'
   AND NOT EXISTS (
       SELECT 1 FROM (SELECT * FROM `tio_settings_lang`) sl
        WHERE sl.`id_settings` = s.`id` AND sl.`id_lang` = l.`id`
   );

-- --- Kontrola ---------------------------------------------------------------
SELECT `name`, COALESCE(`value`, '(per-jezyk)') AS wartosc
  FROM `tio_settings`
 WHERE `name` IN ('address_street','address_postal_code','address_city','address_region',
                  'address_country','geo_lat','geo_lng','opening_hours_spec','area_served',
                  'founding_date','price_range','slogan','company_name','phone','email')
 ORDER BY `name`;

SELECT sl.`id_lang`, sl.`value`
  FROM `tio_settings_lang` sl
  JOIN `tio_settings` s ON s.`id` = sl.`id_settings`
 WHERE s.`name` IN ('slogan', 'company_name')
 ORDER BY s.`name`, sl.`id_lang`;
