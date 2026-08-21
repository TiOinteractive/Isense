-- ---------------------------------------------------------------------------
-- Menu „Nagłówek — przyciski" (id 9) — przyciski CTA w nagłówku strony.
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- CO ROBI: zaklada menu o JAWNYM id = 9 z jedna pozycja „Naprawa wysylkowa"
-- prowadzaca do #naprawa-wysylkowa — czyli dokladnie dzisiejszy, zaszyty
-- w kodzie przycisk. Od tej pory etykieta, adres i kolejnosc sa edytowalne
-- w panelu → Menu, a kazda kolejna pozycja pojawi sie jako osobny przycisk
-- w trzech miejscach naraz: pasek desktopowy, menu mobilne i przyklejony
-- pasek CTA na dole ekranu telefonu.
--
-- DLACZEGO JAWNE id: app/Views/isense/partials/header.php odwoluje sie do menu
-- po numerze ($buttonsMenu = 9), tak samo jak do menu 1–8. Menu 1–8 powstaly
-- recznie i nie sa odtwarzalne z repo — ten skrypt tego bledu nie powtarza.
--
-- SPRAWDZ WYNIK: jesli id 9 jest juz zajete przez inne menu, warunek
-- NOT EXISTS po cichu pominie wstawienie. Kontrolny SELECT na koncu to pokaze
-- — wtedy trzeba wybrac wolne id i poprawic $buttonsMenu w header.php.
--
-- Skrypt jest RE-RUNNABLE.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < app/Database/install_isense_header_buttons_menu.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

SET @id_menu := 9;
SET @id_lang := 1;   -- tio_language: jedyny jezyk, PL

-- --- Menu -------------------------------------------------------------------
INSERT INTO `tio_menu` (`id`, `publish`, `created_at`, `edited_at`)
SELECT @id_menu, 1, NOW(), NOW() FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu`) m WHERE m.`id` = @id_menu);

INSERT INTO `tio_menu_lang` (`id_menu`, `id_lang`, `name`)
SELECT @id_menu, @id_lang, 'Nagłówek — przyciski' FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_lang`) ml
                    WHERE ml.`id_menu` = @id_menu AND ml.`id_lang` = @id_lang);

-- --- Pozycja „Naprawa wysyłkowa" --------------------------------------------
-- type = 'own', bo tylko dla tego typu MenuModel zapisuje wlasny adres (url).
SET @id_item := (
    SELECT mi.`id` FROM `tio_menu_item` mi
      JOIN `tio_menu_item_lang` mil ON mil.`id_menu_item` = mi.`id`
     WHERE mi.`id_menu` = @id_menu AND mil.`url` = '#naprawa-wysylkowa'
     LIMIT 1
);

INSERT INTO `tio_menu_item` (`id_menu`,`id_parent`,`id_target`,`id_photo`,`target`,`order`,`type`,`class`,`svg`,`id_parent_active`)
SELECT @id_menu, 0, 0, 0, '', 0, 'own', '', '', 0 FROM DUAL WHERE @id_item IS NULL;
SET @id_item := IFNULL(@id_item, LAST_INSERT_ID());

INSERT INTO `tio_menu_item_lang` (`id_menu_item`,`id_lang`,`name`,`url`,`title`)
SELECT @id_item, @id_lang, 'Naprawa wysyłkowa', '#naprawa-wysylkowa', '' FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_item_lang`) l
                    WHERE l.`id_menu_item` = @id_item AND l.`id_lang` = @id_lang);

-- --- Kontrola ---------------------------------------------------------------
-- Oczekiwane: menu id 9 „Nagłówek — przyciski" i jedna pozycja
-- „Naprawa wysyłkowa" → #naprawa-wysylkowa.
SELECT m.`id` AS id_menu, ml.`name` AS menu, m.`publish`,
       mil.`name` AS pozycja, mil.`url`, mi.`type`, mi.`order`
  FROM `tio_menu` m
  LEFT JOIN `tio_menu_lang` ml ON ml.`id_menu` = m.`id` AND ml.`id_lang` = @id_lang
  LEFT JOIN `tio_menu_item` mi ON mi.`id_menu` = m.`id`
  LEFT JOIN `tio_menu_item_lang` mil ON mil.`id_menu_item` = mi.`id` AND mil.`id_lang` = @id_lang
 WHERE m.`id` = @id_menu
 ORDER BY mi.`order`;
