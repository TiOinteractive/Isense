-- ---------------------------------------------------------------------------
-- Modul Form — rozszerzenie schematu:
--   (A) typ pola `select` z definiowalnymi, tlumaczonymi opcjami,
--   (B) warunkowe pokazywanie pol sterowane wartoscia selecta (z zagniezdzeniem),
--   (C) typ pola `file` (upload zdjec wysylanych jako zalacznik maila).
--
-- UWAGA: jezeli baza uzywa prefiksu tabel (DBPrefix w .env), prefiks MUSI
--        wystapic w KAZDEJ nazwie tabeli ponizej. W tym projekcie DBPrefix
--        = `tio_` i taki prefiks jest juz wpisany.
--
-- Serwer docelowy: MariaDB 11.4 (WAMP, 127.0.0.1:3307, baza `isense`).
-- MariaDB obsluguje `IF NOT EXISTS` w ALTER TABLE, wiec skrypt mozna
-- uruchomic wielokrotnie. Na czystym MySQL 8.x nalezy usunac wszystkie
-- `IF NOT EXISTS` z instrukcji ALTER i uruchomic skrypt dokladnie raz.
--
-- Uruchomienie:
--   mysql -h127.0.0.1 -P3307 -uroot -p --default-character-set=utf8 isense \
--         < modules/Form/install_upgrade_conditional_fields.sql
-- ---------------------------------------------------------------------------

-- --- 1. Nowe kolumny w form_field ------------------------------------------
ALTER TABLE `tio_form_field`
  -- (B) sterowanie warunkowe: pole widoczne tylko gdy select `parent_field`
  --     ma wybrana jedna z opcji wymienionych w `parent_values`.
  --     parent_field  = tio_form_field.id (pole typu `select`, o mniejszym `order`)
  --     parent_values = CSV z tio_form_field_option.id, np. "12,13"
  --     parent_field = 0  -> pole widoczne zawsze.
  --     Zagniezdzenie wynika z lancucha: dziecko jest widoczne tylko gdy
  --     widoczny jest takze jego rodzic (rekurencyjnie).
  ADD COLUMN IF NOT EXISTS `parent_field`  int(11)      NOT NULL DEFAULT 0  AFTER `type`,
  ADD COLUMN IF NOT EXISTS `parent_values` varchar(255) NOT NULL DEFAULT '' AFTER `parent_field`,

  -- (C) konfiguracja uploadu (uzywane tylko dla type='file')
  --     max_files     = maksymalna liczba plikow w tym polu (1 = pojedynczy)
  --     max_file_size = limit w KB; 0 = wez z Config\Images::$maxFileSize.
  --     Wartosc NIGDY nie podnosi limitu globalnego — serwer bierze min().
  ADD COLUMN IF NOT EXISTS `max_files`     int(11)      NOT NULL DEFAULT 1  AFTER `parent_values`,
  ADD COLUMN IF NOT EXISTS `max_file_size` int(11)      NOT NULL DEFAULT 0  AFTER `max_files`,

  -- Zarezerwowane pod PRZYSZLA iteracje: powtarzalna grupa pol
  -- („dodaj kolejne urzadzenie"). W tej iteracji kolumny sa zapisywane
  -- przez model, ale ignorowane przez admina i front.
  --   repeatable = 1  -> pole typu `group` jest powtarzalne
  --   repeat_max      -> limit powtorzen (0 = bez limitu)
  --   id_group        -> tio_form_field.id kontenera, do ktorego pole nalezy
  ADD COLUMN IF NOT EXISTS `repeatable`    tinyint(1)   NOT NULL DEFAULT 0  AFTER `max_file_size`,
  ADD COLUMN IF NOT EXISTS `repeat_max`    int(11)      NOT NULL DEFAULT 0  AFTER `repeatable`,
  ADD COLUMN IF NOT EXISTS `id_group`      int(11)      NOT NULL DEFAULT 0  AFTER `repeat_max`;

-- --- 2. Indeksy -------------------------------------------------------------
-- form_field nie mial ZADNEGO indeksu poza PK, a front filtruje
-- WHERE id_form=? AND publish=1 ORDER BY `order`.
ALTER TABLE `tio_form_field`
  ADD INDEX IF NOT EXISTS `id_form_order` (`id_form`, `order`),
  ADD INDEX IF NOT EXISTS `parent_field`  (`parent_field`),
  ADD INDEX IF NOT EXISTS `id_group`      (`id_group`);

ALTER TABLE `tio_form_field_lang`
  ADD INDEX IF NOT EXISTS `id_field_id_lang` (`id_field`, `id_lang`);

ALTER TABLE `tio_form_lang`
  ADD INDEX IF NOT EXISTS `id_form_id_lang` (`id_form`, `id_lang`);

ALTER TABLE `tio_form`
  ADD INDEX IF NOT EXISTS `id_page_cont` (`id_page_cont`);

-- --- 3. Opcje selecta -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tio_form_field_option` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `id_field`   int(11)      NOT NULL,
  -- slug: wylacznie informacyjny / pod czytelnosc skryptow seed.
  -- Warunki (form_field.parent_values) wskazuja ZAWSZE `id`, nigdy slug.
  -- Slug jest generowany raz, przy tworzeniu opcji, i nigdy nie regenerowany.
  `slug`       varchar(100) NOT NULL DEFAULT '',
  `order`      int(11)      NOT NULL DEFAULT 0,
  `publish`    tinyint(1)   NOT NULL DEFAULT 1,
  `edited_at`  timestamp    NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp    NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_field_order` (`id_field`, `order`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;

CREATE TABLE IF NOT EXISTS `tio_form_field_option_lang` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `id_option`  int(11)      NOT NULL,
  `id_lang`    int(11)      NOT NULL,
  `name`       varchar(250) NOT NULL DEFAULT '',
  `edited_at`  timestamp    NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp    NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_option_id_lang` (`id_option`, `id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;
