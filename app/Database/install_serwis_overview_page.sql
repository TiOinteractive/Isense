-- ---------------------------------------------------------------------------
-- Strona /serwis — strona przegladowa kategorii uslug serwisowych.
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- KONTEKST (audyt SEO z 31.08.2026):
--   Adres /serwis byl zgloszony w sitemap-main.xml, a zwracal blad 500; po
--   ustaniu bledu zostal jako strona calkowicie pusta — bez H1 i bez tresci.
--   Powod: oba bloki tresci strony nie mialy wierszy w `page_content_lang`,
--   a front laczy blok z ta tabela i filtruje po `id_lang`, wiec blok bez
--   wiersza jezykowego jest niewidoczny mimo `publish = 1`.
--
-- CO ROBI:
--   1. Uzupelnia brakujace wiersze `page_content_lang` dla WSZYSTKICH blokow
--      (naprawa danych; kod modelu robi to juz przy zapisie bloku).
--   2. Kasuje ze strony /serwis puste bloki Wyswig (bez wiersza w `wyswig`) —
--      i tak nigdy sie nie renderowaly.
--   3. Zaklada na stronie /serwis blok `page-hero` (naglowek H1) i blok
--      `link-cards` z piecioma kartami prowadzacymi do istniejacych podstron.
--   4. Uzupelnia metatagi strony (`page_meta_lang`) — tylko gdy sa puste.
--
-- CZEGO NIE ROBI: nie dodaje przekierowania i nie usuwa adresu z mapy strony —
--   /serwis ma zostac w sitemap jako strona nadrzedna dla podstron serwis/*.
--
-- WYMAGA: wdrozonego kodu z tej samej zmiany (guardy w `Home::index()` oraz
--   `PageModel::ensurePageContentLang()`), inaczej bloki dalej sie nie pokaza.
--
-- Skrypt jest RE-RUNNABLE i pracuje na ID wyszukanych po slugach, wiec nie
-- zaklada zgodnosci numeracji miedzy srodowiskami. Gdy nie znajdzie strony
-- /serwis albo elementow modulu Isense, pierwszy wiersz wyniku
-- (`kontrola_wejscia`) zaczyna sie od slowa PRZERWANO i zadna zmiana na
-- stronie nie zostaje wykonana.
--
-- Uruchomienie:
--   mysql -h<host> -P<port> -u<user> -p --default-character-set=utf8 <baza> \
--         < app/Database/install_serwis_overview_page.sql
--
-- PO URUCHOMIENIU: wyczyscic cache aplikacji (`php spark cache:clear`).
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

START TRANSACTION;

-- --- 0. Ustalenie ID po slugach ---------------------------------------------
SET @id_lang  := (SELECT `id` FROM `tio_language` WHERE `default` = 1 LIMIT 1);
SET @id_link  := (SELECT `id` FROM `tio_links` WHERE `link` = 'serwis' AND `id_lang` = @id_lang LIMIT 1);
SET @id_page  := (SELECT `id_page` FROM `tio_page_lang` WHERE `id_link` = @id_link AND `id_lang` = @id_lang LIMIT 1);
SET @me_hero  := (SELECT me.`id` FROM `tio_module_element` me JOIN `tio_module` m ON m.`id` = me.`id_module` WHERE m.`slug` = 'Isense' AND me.`slug` = 'page-hero' LIMIT 1);
SET @me_cards := (SELECT me.`id` FROM `tio_module_element` me JOIN `tio_module` m ON m.`id` = me.`id_module` WHERE m.`slug` = 'Isense' AND me.`slug` = 'link-cards' LIMIT 1);

-- Kontrola wejscia — PRZECZYTAC PRZED DALSZA CZESCIA WYNIKU.
-- Gdy ktoregos ID nie ma, kroki 2-5 nie ruszaja zadnego wiersza (kazdy z nich
-- jest warunkowany tymi zmiennymi), wiec skrypt konczy sie bez zmian na stronie.
SELECT IF(
    @id_lang IS NULL OR @id_page IS NULL OR @me_hero IS NULL OR @me_cards IS NULL,
    CONCAT('PRZERWANO — brak: ',
           IF(@id_lang  IS NULL, 'jezyka domyslnego ', ''),
           IF(@id_page  IS NULL, 'strony o adresie serwis ', ''),
           IF(@me_hero  IS NULL, 'elementu Isense/page-hero ', ''),
           IF(@me_cards IS NULL, 'elementu Isense/link-cards ', '')),
    CONCAT('OK — strona /serwis: id_page=', @id_page, ', id_lang=', @id_lang)
) AS `kontrola_wejscia`;

-- --- 1. Brakujace wiersze jezykowe blokow tresci -----------------------------
-- Blok bez wiersza w `page_content_lang` jest dla frontu niewidoczny.
INSERT INTO `tio_page_content_lang` (`id_page_cont`, `id_lang`, `title`, `subtitle`, `url`)
SELECT c.`id`, l.`id`, '', '', ''
FROM `tio_page_content` c
CROSS JOIN `tio_language` l
LEFT JOIN `tio_page_content_lang` cl ON cl.`id_page_cont` = c.`id` AND cl.`id_lang` = l.`id`
WHERE cl.`id` IS NULL;

-- --- 2. Puste bloki Wyswig na stronie /serwis --------------------------------
-- Blok Wyswig bez wiersza w `wyswig` nie ma tresci i nigdy sie nie renderowal.
-- Kasujemy blok razem z jego wierszami jezykowymi (DELETE wielotabelowy).
DELETE c, cl
FROM `tio_page_content` c
JOIN `tio_module_element` me ON me.`id` = c.`id_module_element`
JOIN `tio_module` m ON m.`id` = me.`id_module`
LEFT JOIN `tio_wyswig` w ON w.`id_page_cont` = c.`id`
LEFT JOIN `tio_page_content_lang` cl ON cl.`id_page_cont` = c.`id`
WHERE c.`id_page` = @id_page AND m.`slug` = 'Wyswig' AND w.`id` IS NULL;

-- --- 3a. Blok page-hero (naglowek H1) ---------------------------------------
SET @cont_hero := (SELECT `id` FROM `tio_page_content` WHERE `id_page` = @id_page AND `id_module_element` = @me_hero ORDER BY `id` LIMIT 1);

INSERT INTO `tio_page_content` (`id_page`, `order`, `publish`, `id_module_element`, `id_element`, `template`, `id_sidebar`, `created_at`)
SELECT @id_page, 0, 1, @me_hero, 0, '', 0, NOW() FROM DUAL
WHERE @cont_hero IS NULL AND @id_page IS NOT NULL AND @me_hero IS NOT NULL;

SET @cont_hero := (SELECT `id` FROM `tio_page_content` WHERE `id_page` = @id_page AND `id_module_element` = @me_hero ORDER BY `id` LIMIT 1);
UPDATE `tio_page_content` SET `order` = 0, `publish` = 1 WHERE `id` = @cont_hero;

-- --- 3b. Blok link-cards (lista uslug serwisowych) ---------------------------
SET @cont_cards := (SELECT `id` FROM `tio_page_content` WHERE `id_page` = @id_page AND `id_module_element` = @me_cards ORDER BY `id` LIMIT 1);

INSERT INTO `tio_page_content` (`id_page`, `order`, `publish`, `id_module_element`, `id_element`, `template`, `id_sidebar`, `created_at`)
SELECT @id_page, 1, 1, @me_cards, 0, '', 0, NOW() FROM DUAL
WHERE @cont_cards IS NULL AND @id_page IS NOT NULL AND @me_cards IS NOT NULL;

SET @cont_cards := (SELECT `id` FROM `tio_page_content` WHERE `id_page` = @id_page AND `id_module_element` = @me_cards ORDER BY `id` LIMIT 1);
UPDATE `tio_page_content` SET `order` = 1, `publish` = 1 WHERE `id` = @cont_cards;

-- --- 3c. Wiersze jezykowe obu blokow -----------------------------------------
INSERT INTO `tio_page_content_lang` (`id_page_cont`, `id_lang`, `title`, `subtitle`, `url`)
SELECT c.`id`, l.`id`, '', '', ''
FROM `tio_page_content` c
CROSS JOIN `tio_language` l
LEFT JOIN `tio_page_content_lang` cl ON cl.`id_page_cont` = c.`id` AND cl.`id_lang` = l.`id`
WHERE c.`id` IN (@cont_hero, @cont_cards) AND cl.`id` IS NULL;

-- --- 4. Dane sekcji modulu Isense (JSON w isense_section_lang) ---------------
INSERT INTO `tio_isense_section` (`id_page_cont`, `created_at`)
SELECT @cont_hero, NOW() FROM DUAL
WHERE @cont_hero IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM `tio_isense_section` WHERE `id_page_cont` = @cont_hero);

INSERT INTO `tio_isense_section` (`id_page_cont`, `created_at`)
SELECT @cont_cards, NOW() FROM DUAL
WHERE @cont_cards IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM `tio_isense_section` WHERE `id_page_cont` = @cont_cards);

SET @sec_hero  := (SELECT `id` FROM `tio_isense_section` WHERE `id_page_cont` = @cont_hero LIMIT 1);
SET @sec_cards := (SELECT `id` FROM `tio_isense_section` WHERE `id_page_cont` = @cont_cards LIMIT 1);

SET @hero_data := '{"variant":"light","bg":"","eyebrow":"","title":"Serwis Apple","subtitle":"Naprawiamy iPhone, iPada, MacBooka, iMaca, Apple Watch oraz słuchawki AirPods i Beats. Bezpłatna diagnoza, gwarancja na naprawę i wysyłka door-to-door z całej Polski."}';

SET @cards_data := '{"heading":"Co naprawiamy","lead":"Wybierz urządzenie, żeby poznać zakres napraw i orientacyjny cennik.","columns":"3","cards":[{"icon":"smartphone","title":"Serwis iPhone","desc":"Wyświetlacze, baterie, aparaty, gniazda ładowania i naprawy płyt głównych.","url":"serwis/serwis-iphone","link_label":"Zobacz szczegóły"},{"icon":"tablet","title":"Serwis iPad","desc":"Wymiana szyby i wyświetlacza, baterie, gniazda ładowania, naprawy po zalaniu.","url":"serwis/serwis-ipad","link_label":"Zobacz szczegóły"},{"icon":"laptop","title":"Serwis MacBook i iMac","desc":"Matryce, klawiatury, baterie, dyski, naprawy elektroniki i po zalaniu.","url":"serwis/serwis-macbook-imac","link_label":"Zobacz szczegóły"},{"icon":"watch","title":"Serwis Apple Watch","desc":"Wymiana szkła i wyświetlacza, baterie, naprawy po zalaniu.","url":"serwis/serwis-apple-watch","link_label":"Zobacz szczegóły"},{"icon":"headphones","title":"Słuchawki AirPods i Beats","desc":"Diagnostyka, wymiana baterii i naprawy słuchawek Apple oraz Beats.","url":"serwis/sluchawki-airpods-i-beats","link_label":"Zobacz szczegóły"}]}';

INSERT INTO `tio_isense_section_lang` (`id_isense_section`, `id_lang`, `data`)
SELECT @sec_hero, @id_lang, @hero_data FROM DUAL
WHERE @sec_hero IS NOT NULL AND @id_lang IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM `tio_isense_section_lang` WHERE `id_isense_section` = @sec_hero AND `id_lang` = @id_lang);
UPDATE `tio_isense_section_lang` SET `data` = @hero_data
WHERE `id_isense_section` = @sec_hero AND `id_lang` = @id_lang;

INSERT INTO `tio_isense_section_lang` (`id_isense_section`, `id_lang`, `data`)
SELECT @sec_cards, @id_lang, @cards_data FROM DUAL
WHERE @sec_cards IS NOT NULL AND @id_lang IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM `tio_isense_section_lang` WHERE `id_isense_section` = @sec_cards AND `id_lang` = @id_lang);
UPDATE `tio_isense_section_lang` SET `data` = @cards_data
WHERE `id_isense_section` = @sec_cards AND `id_lang` = @id_lang;

-- --- 5. Metatagi strony (tylko gdy puste — nie nadpisujemy pracy redaktora) --
INSERT INTO `tio_page_meta_lang` (`id_page`, `id_lang`, `title`, `description`, `keywords`)
SELECT @id_page, @id_lang, '', '', '' FROM DUAL
WHERE @id_page IS NOT NULL AND @id_lang IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM `tio_page_meta_lang` WHERE `id_page` = @id_page AND `id_lang` = @id_lang);

UPDATE `tio_page_meta_lang`
SET `title` = 'Serwis Apple — naprawa iPhone, iPad, MacBook i Apple Watch | iSense'
WHERE `id_page` = @id_page AND `id_lang` = @id_lang AND (`title` IS NULL OR `title` = '');

UPDATE `tio_page_meta_lang`
SET `description` = 'Niezależny serwis Apple w Warszawie. Naprawa iPhone, iPad, MacBook, iMac, Apple Watch i słuchawek AirPods. Bezpłatna diagnoza, gwarancja, wysyłka z całej Polski.'
WHERE `id_page` = @id_page AND `id_lang` = @id_lang AND (`description` IS NULL OR `description` = '');

COMMIT;

-- --- 6. Kontrola po wykonaniu ------------------------------------------------
-- Oczekiwane: dwa bloki (page-hero, link-cards), oba publish = 1, kazdy
-- z wierszem jezykowym i z danymi JSON.
SELECT c.`id`, c.`order`, c.`publish`, me.`slug` AS `sekcja`,
       (SELECT COUNT(*) FROM `tio_page_content_lang` cl WHERE cl.`id_page_cont` = c.`id`) AS `wiersze_lang`,
       (SELECT COUNT(*) FROM `tio_isense_section` s JOIN `tio_isense_section_lang` sl ON sl.`id_isense_section` = s.`id` WHERE s.`id_page_cont` = c.`id`) AS `wiersze_json`
FROM `tio_page_content` c
JOIN `tio_module_element` me ON me.`id` = c.`id_module_element`
WHERE c.`id_page` = @id_page
ORDER BY c.`order`;

-- Oczekiwane: pusty wynik (zaden blok w calym serwisie nie jest juz osierocony).
SELECT c.`id` AS `blok_bez_wiersza_jezykowego`
FROM `tio_page_content` c
LEFT JOIN `tio_page_content_lang` cl ON cl.`id_page_cont` = c.`id`
WHERE cl.`id` IS NULL;
