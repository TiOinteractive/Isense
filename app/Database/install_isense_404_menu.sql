-- ---------------------------------------------------------------------------
-- Menu „404 — Popularne działy" (id 10) — lista ratunkowa na stronie błędu 404.
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- CO ROBI: zaklada menu o JAWNYM id = 10 z pieciopozycyjna lista, ktora
-- app/Views/user/errors/404.php renderuje przez
-- view_cell('\App\Libraries\IsenseMenu::inlineLinks').
--
-- PO CO: wczesniej te piec linkow bylo zaszyte w szablonie 404 i trzy z nich
-- (naprawy/iphone, naprawy/ipad, naprawy/macbook) wskazywaly adresy, ktore nigdy
-- nie powstaly — jedyna sciezka ratunkowa z bledu 404 prowadzila w kolejne 404.
-- Pozycje sa typu 'page' (id_target), wiec adres bierze sie z tabeli `links` przy
-- kazdym renderze: zmiana adresu strony w panelu automatycznie przechodzi na 404.
-- Strona niepublikowana daje url '#', a cell takie pozycje pomija.
--
-- DLACZEGO JAWNE id: 404.php odwoluje sie do menu po numerze ($popularMenu = 10),
-- tak samo jak header.php do menu 1 i 9. Jesli id 10 jest juz zajete, warunek
-- NOT EXISTS po cichu pominie wstawienie — kontrolny SELECT na koncu to pokaze,
-- wtedy trzeba wybrac wolne id i poprawic $popularMenu w 404.php.
--
-- ZAKRES: cele wyszukiwane po adresie strony (`links`.`link`), nie po id — dzieki
-- temu skrypt dziala na innej instalacji, a pozycja, dla ktorej strony nie ma,
-- jest po prostu pomijana.
--
-- Skrypt jest RE-RUNNABLE.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < app/Database/install_isense_404_menu.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

SET @id_menu := 10;
SET @id_lang := 1;   -- tio_language: jedyny jezyk, PL

-- --- Menu -------------------------------------------------------------------
INSERT INTO `tio_menu` (`id`, `publish`, `created_at`, `edited_at`)
SELECT @id_menu, 1, NOW(), NOW() FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu`) m WHERE m.`id` = @id_menu);

INSERT INTO `tio_menu_lang` (`id_menu`, `id_lang`, `name`)
SELECT @id_menu, @id_lang, '404 — Popularne działy' FROM DUAL
 WHERE NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_lang`) ml
                    WHERE ml.`id_menu` = @id_menu AND ml.`id_lang` = @id_lang);

-- --- Pozycje ----------------------------------------------------------------
-- Kazdy blok: znajdz strone po adresie → wstaw pozycje, jesli jeszcze jej nie ma.
-- @id_page IS NULL (brak takiej strony) lub istniejaca pozycja = blok nic nie robi.

-- 1. Serwis iPhone
SET @link := 'serwis/serwis-iphone';
SET @label := 'Serwis iPhone';
SET @order := 0;
SET @id_page := (SELECT pl.`id_page` FROM `tio_links` l
                   JOIN `tio_page_lang` pl ON pl.`id_link` = l.`id` AND pl.`id_lang` = @id_lang
                  WHERE l.`link` = @link COLLATE utf8mb3_polish_ci LIMIT 1);
SET @id_item := (SELECT mi.`id` FROM `tio_menu_item` mi
                  WHERE mi.`id_menu` = @id_menu AND mi.`id_target` = @id_page LIMIT 1);
INSERT INTO `tio_menu_item` (`id_menu`,`id_parent`,`id_target`,`id_photo`,`target`,`order`,`type`,`class`,`svg`,`id_parent_active`)
SELECT @id_menu, 0, @id_page, 0, '', @order, 'page', '', '', 0 FROM DUAL
 WHERE @id_item IS NULL AND @id_page IS NOT NULL;
SET @id_item := IFNULL(@id_item, LAST_INSERT_ID());
INSERT INTO `tio_menu_item_lang` (`id_menu_item`,`id_lang`,`name`,`url`,`title`)
SELECT @id_item, @id_lang, @label, '', '' FROM DUAL
 WHERE @id_page IS NOT NULL
   AND NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_item_lang`) l
                    WHERE l.`id_menu_item` = @id_item AND l.`id_lang` = @id_lang);

-- 2. Serwis iPad
SET @link := 'serwis/serwis-ipad';
SET @label := 'Serwis iPad';
SET @order := 1;
SET @id_page := (SELECT pl.`id_page` FROM `tio_links` l
                   JOIN `tio_page_lang` pl ON pl.`id_link` = l.`id` AND pl.`id_lang` = @id_lang
                  WHERE l.`link` = @link COLLATE utf8mb3_polish_ci LIMIT 1);
SET @id_item := (SELECT mi.`id` FROM `tio_menu_item` mi
                  WHERE mi.`id_menu` = @id_menu AND mi.`id_target` = @id_page LIMIT 1);
INSERT INTO `tio_menu_item` (`id_menu`,`id_parent`,`id_target`,`id_photo`,`target`,`order`,`type`,`class`,`svg`,`id_parent_active`)
SELECT @id_menu, 0, @id_page, 0, '', @order, 'page', '', '', 0 FROM DUAL
 WHERE @id_item IS NULL AND @id_page IS NOT NULL;
SET @id_item := IFNULL(@id_item, LAST_INSERT_ID());
INSERT INTO `tio_menu_item_lang` (`id_menu_item`,`id_lang`,`name`,`url`,`title`)
SELECT @id_item, @id_lang, @label, '', '' FROM DUAL
 WHERE @id_page IS NOT NULL
   AND NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_item_lang`) l
                    WHERE l.`id_menu_item` = @id_item AND l.`id_lang` = @id_lang);

-- 3. Serwis MacBook, iMac
SET @link := 'serwis/serwis-macbook-imac';
SET @label := 'Serwis MacBook, iMac';
SET @order := 2;
SET @id_page := (SELECT pl.`id_page` FROM `tio_links` l
                   JOIN `tio_page_lang` pl ON pl.`id_link` = l.`id` AND pl.`id_lang` = @id_lang
                  WHERE l.`link` = @link COLLATE utf8mb3_polish_ci LIMIT 1);
SET @id_item := (SELECT mi.`id` FROM `tio_menu_item` mi
                  WHERE mi.`id_menu` = @id_menu AND mi.`id_target` = @id_page LIMIT 1);
INSERT INTO `tio_menu_item` (`id_menu`,`id_parent`,`id_target`,`id_photo`,`target`,`order`,`type`,`class`,`svg`,`id_parent_active`)
SELECT @id_menu, 0, @id_page, 0, '', @order, 'page', '', '', 0 FROM DUAL
 WHERE @id_item IS NULL AND @id_page IS NOT NULL;
SET @id_item := IFNULL(@id_item, LAST_INSERT_ID());
INSERT INTO `tio_menu_item_lang` (`id_menu_item`,`id_lang`,`name`,`url`,`title`)
SELECT @id_item, @id_lang, @label, '', '' FROM DUAL
 WHERE @id_page IS NOT NULL
   AND NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_item_lang`) l
                    WHERE l.`id_menu_item` = @id_item AND l.`id_lang` = @id_lang);

-- 4. Trade In
SET @link := 'trade-in';
SET @label := 'Trade In';
SET @order := 3;
SET @id_page := (SELECT pl.`id_page` FROM `tio_links` l
                   JOIN `tio_page_lang` pl ON pl.`id_link` = l.`id` AND pl.`id_lang` = @id_lang
                  WHERE l.`link` = @link COLLATE utf8mb3_polish_ci LIMIT 1);
SET @id_item := (SELECT mi.`id` FROM `tio_menu_item` mi
                  WHERE mi.`id_menu` = @id_menu AND mi.`id_target` = @id_page LIMIT 1);
INSERT INTO `tio_menu_item` (`id_menu`,`id_parent`,`id_target`,`id_photo`,`target`,`order`,`type`,`class`,`svg`,`id_parent_active`)
SELECT @id_menu, 0, @id_page, 0, '', @order, 'page', '', '', 0 FROM DUAL
 WHERE @id_item IS NULL AND @id_page IS NOT NULL;
SET @id_item := IFNULL(@id_item, LAST_INSERT_ID());
INSERT INTO `tio_menu_item_lang` (`id_menu_item`,`id_lang`,`name`,`url`,`title`)
SELECT @id_item, @id_lang, @label, '', '' FROM DUAL
 WHERE @id_page IS NOT NULL
   AND NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_item_lang`) l
                    WHERE l.`id_menu_item` = @id_item AND l.`id_lang` = @id_lang);

-- 5. Kontakt
SET @link := 'kontakt';
SET @label := 'Kontakt';
SET @order := 4;
SET @id_page := (SELECT pl.`id_page` FROM `tio_links` l
                   JOIN `tio_page_lang` pl ON pl.`id_link` = l.`id` AND pl.`id_lang` = @id_lang
                  WHERE l.`link` = @link COLLATE utf8mb3_polish_ci LIMIT 1);
SET @id_item := (SELECT mi.`id` FROM `tio_menu_item` mi
                  WHERE mi.`id_menu` = @id_menu AND mi.`id_target` = @id_page LIMIT 1);
INSERT INTO `tio_menu_item` (`id_menu`,`id_parent`,`id_target`,`id_photo`,`target`,`order`,`type`,`class`,`svg`,`id_parent_active`)
SELECT @id_menu, 0, @id_page, 0, '', @order, 'page', '', '', 0 FROM DUAL
 WHERE @id_item IS NULL AND @id_page IS NOT NULL;
SET @id_item := IFNULL(@id_item, LAST_INSERT_ID());
INSERT INTO `tio_menu_item_lang` (`id_menu_item`,`id_lang`,`name`,`url`,`title`)
SELECT @id_item, @id_lang, @label, '', '' FROM DUAL
 WHERE @id_page IS NOT NULL
   AND NOT EXISTS (SELECT 1 FROM (SELECT * FROM `tio_menu_item_lang`) l
                    WHERE l.`id_menu_item` = @id_item AND l.`id_lang` = @id_lang);

-- --- Kontrola ---------------------------------------------------------------
-- Oczekiwane: menu id 10 „404 — Popularne działy" i piec pozycji z adresami
-- serwis/serwis-iphone, serwis/serwis-ipad, serwis/serwis-macbook-imac,
-- trade-in, kontakt. Pusty `adres` = strona niepublikowana lub usunieta.
SELECT m.`id` AS id_menu, ml.`name` AS menu, m.`publish`,
       mi.`order`, mil.`name` AS pozycja, l.`link` AS adres, p.`publish` AS strona_publish
  FROM `tio_menu` m
  LEFT JOIN `tio_menu_lang` ml ON ml.`id_menu` = m.`id` AND ml.`id_lang` = @id_lang
  LEFT JOIN `tio_menu_item` mi ON mi.`id_menu` = m.`id`
  LEFT JOIN `tio_menu_item_lang` mil ON mil.`id_menu_item` = mi.`id` AND mil.`id_lang` = @id_lang
  LEFT JOIN `tio_page` p ON p.`id` = mi.`id_target`
  LEFT JOIN `tio_page_lang` pl ON pl.`id_page` = p.`id` AND pl.`id_lang` = @id_lang
  LEFT JOIN `tio_links` l ON l.`id` = pl.`id_link`
 WHERE m.`id` = @id_menu
 ORDER BY mi.`order`;
