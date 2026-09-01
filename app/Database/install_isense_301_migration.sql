-- ---------------------------------------------------------------------------
-- Przekierowania 301 po migracji ze starego serwisu isense.pl (WordPress).
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- CO ROBI: wstawia 21 regul do tabeli `redirects` (modul Redirects). Tabela byla
-- pusta — przy migracji nie przeniesiono ani jednej reguly, przez co cala stara
-- struktura adresow (/serwis/<urzadzenie>/, /uslugi/<usluga>/, /o-nas/,
-- /naprawa-wysylkowa/) oraz historyczne adresy /naprawy/* zwracaly 404.
--
-- PO CO: te adresy nadal dzialaja na isense.pl (kod 200), sa zaindeksowane i
-- linkowane z zewnatrz. Adresy /naprawy/* to jeszcze starsza warstwa — stary
-- WordPress trzyma dla nich wlasne 301 do /serwis/*, wiec mialy ruch. Bez tych
-- regul kazde wejscie z Google lub z cudzego linku konczy sie bledem 404.
--
-- JAK TO DZIALA: `Home::index` (app/Controllers/Home.php) przy kazdym zadaniu
-- pyta modul o regule dla '/' . uri_string() — czyli o adres Z wiodacym
-- ukosnikiem i BEZ koncowego. Stare adresy mialy koncowy ukosnik; obcina go
-- regula z public/.htaccess (301), wiec /serwis/iphone/ przechodzi dwoma skokami:
--   /serwis/iphone/ -> 301 /serwis/iphone -> 301 /serwis/serwis-iphone
-- Google to akceptuje. Jesli kiedys bedzie potrzebny jeden skok, te same reguly
-- trzeba postawic w .htaccess PRZED regula obcinajaca ukosnik.
--
-- CZEGO TU NIE MA: adresow trzysegmentowych /naprawy/<kategoria>/<model> — te
-- trafiaja do trasy `Front::modelPricing` (app/Config/Routes.php), ktora rzuca
-- 404 bez pytania modulu Redirects. Gdyby okazalo sie, ze byly indeksowane,
-- trzeba dla nich dodac regule w .htaccess, nie tutaj.
--
-- `group` celowo puste: formularz edycji w panelu ma zamknieta liste grup
-- (resinet/entertainment/flavor/foto) i wlasna wartosc nie wybralaby sie w tym
-- selekcie, a przy zapisie z panelu zostalaby nadpisana.
--
-- ZAKRES: lista starych adresow pochodzi z nawigacji isense.pl (stary serwis nie
-- udostepnia sitemapy). Moze byc niepelna — wpisy blogowe i landingi kampanijne
-- moga istniec poza menu. Pelne pokrycie da dopiero eksport adresow z Google
-- Search Console starej domeny.
--
-- Skrypt jest RE-RUNNABLE (dopisuje tylko brakujace `from`); nie modyfikuje
-- regul juz istniejacych, wiec reczne zmiany w panelu przetrwaja ponowny bieg.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < app/Database/install_isense_301_migration.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

INSERT INTO `tio_redirects` (`from`, `to`, `type`, `publish`, `short`, `group`, `created_at`, `edited_at`)
SELECT src.f, src.t, '301', 1, 0, '', NOW(), NOW()
  FROM (
    -- Kategorie serwisowe (stara struktura /serwis/<urzadzenie>/).
    SELECT '/serwis/iphone'                    AS f, '/serwis/serwis-iphone'                          AS t
    UNION ALL SELECT '/serwis/ipad',                 '/serwis/serwis-ipad'
    -- iMac i MacBook scalone w nowym serwisie w jedna strone.
    UNION ALL SELECT '/serwis/macbook',              '/serwis/serwis-macbook-imac'
    UNION ALL SELECT '/serwis/imac',                 '/serwis/serwis-macbook-imac'
    UNION ALL SELECT '/serwis/apple-watch',          '/serwis/serwis-apple-watch'
    -- Brak strony MagSafe w nowym serwisie, usluga nadal w ofercie -> strona nadrzedna.
    UNION ALL SELECT '/serwis/zasilacze-magsafe',    '/serwis'
    -- iPod: usluga najpewniej wycofana. 301 na strone nadrzedna jako wariant
    -- bezpieczny; jesli klient potwierdzi trwale wycofanie, zamienic na 410
    -- (RewriteRule ^serwis/ipod/? - [G] w public/.htaccess) i usunac ten wiersz.
    UNION ALL SELECT '/serwis/ipod',                 '/serwis'

    -- Historyczne adresy /naprawy/* (na isense.pl obsluzone wlasnym 301).
    UNION ALL SELECT '/naprawy/iphone',              '/serwis/serwis-iphone'
    UNION ALL SELECT '/naprawy/ipad',                '/serwis/serwis-ipad'
    UNION ALL SELECT '/naprawy/macbook',             '/serwis/serwis-macbook-imac'
    UNION ALL SELECT '/naprawy/imac',                '/serwis/serwis-macbook-imac'
    UNION ALL SELECT '/naprawy/zasilacze',           '/serwis'

    -- Uslugi dodatkowe (stara sekcja /uslugi/).
    UNION ALL SELECT '/uslugi',                              '/uslugi-dodatkowe'
    UNION ALL SELECT '/uslugi/odzyskiwanie-danych',          '/uslugi-dodatkowe/odzyskiwanie-danych'
    UNION ALL SELECT '/uslugi/ekspertyzy-dla-ubezpieczycieli','/uslugi-dodatkowe/ekspertyzy-dla-ubezpieczycieli'
    -- Te dwie strony wyszly w nowej strukturze o poziom wyzej.
    UNION ALL SELECT '/uslugi/wymiany-sprzetu-na-nowy',      '/wymiany-sprzetu'
    UNION ALL SELECT '/uslugi/naprawy-gwarancyjne',          '/naprawy-gwarancyjne'
    -- Simlock: brak nastepcy. Jak przy iPodzie — kandydat do 410.
    UNION ALL SELECT '/uslugi/zdejmowanie-blokady-simlock',  '/uslugi-dodatkowe'

    -- Pozostale strony ze zmienionym slugiem.
    UNION ALL SELECT '/o-nas',                       '/o-serwisie'
    UNION ALL SELECT '/naprawa-wysylkowa',           '/naprawa-z-odbiorem'

    -- Adres z public/llms.txt, ktory nigdy nie istnial w nowej strukturze —
    -- do czasu poprawienia pliku niech prowadzi do wlasciwej strony.
    UNION ALL SELECT '/odzyskiwanie-danych',         '/uslugi-dodatkowe/odzyskiwanie-danych'
  ) AS src
  LEFT JOIN (SELECT `from` AS ex FROM `tio_redirects`) AS cur ON cur.ex = src.f
 WHERE cur.ex IS NULL;

-- --- Kontrola ---------------------------------------------------------------
-- Oczekiwane: 21 aktywnych regul typu 301, kazda z celem wskazujacym na
-- opublikowana strone (kolumna `cel_istnieje` = 1; wartosc 0 oznacza, ze strona
-- docelowa zostala usunieta lub odpublikowana i regula prowadzi w 404).
SELECT r.`from`, r.`to`, r.`type`, r.`publish`,
       (SELECT COUNT(*)
          FROM `tio_links` l
          JOIN `tio_page_lang` pl ON pl.`id_link` = l.`id` AND pl.`id_lang` = 1
          JOIN `tio_page` p ON p.`id` = pl.`id_page` AND p.`publish` = 1
         WHERE l.`link` = TRIM(LEADING '/' FROM r.`to`)) AS cel_istnieje
  FROM `tio_redirects` r
 ORDER BY r.`from`;
