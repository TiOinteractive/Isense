-- ---------------------------------------------------------------------------
-- Moduł Cennik (Pricing) — instalacja.
--
-- Prefiks tabel: `tio_` (zgodnie z DBPrefix w .env).
--
-- Struktura: pricing_category (iPhone, iPad...) → pricing_service (usługi)
--            → pricing_model (modele z ceną, czasem realizacji i gwarancją).
-- Kolumny tekstowe per język leżą w tabelach *_lang.
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `tio_pricing_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL DEFAULT 0,
  `publish` tinyint(1) NOT NULL DEFAULT 1,
  `edited_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tio_pricing_category_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_category_id_lang` (`id_category`,`id_lang`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tio_pricing_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_category` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `publish` tinyint(1) NOT NULL DEFAULT 1,
  `edited_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_category_order` (`id_category`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tio_pricing_service_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_service_id_lang` (`id_service`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `tio_pricing_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_service` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `publish` tinyint(1) NOT NULL DEFAULT 1,
  `edited_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_service_order` (`id_service`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Cena / czas / gwarancja są tekstem: w cenniku występuje „bezpłatnie", „od 1200 zł", „2–3 dni".
CREATE TABLE IF NOT EXISTS `tio_pricing_model_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_model` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `price` varchar(100) NOT NULL DEFAULT '',
  `time` varchar(100) NOT NULL DEFAULT '',
  `warranty` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id_model_id_lang` (`id_model`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------------
-- Rejestracja modułu w panelu (kafelek w górnym menu: main=1).
-- Tabela `tio_module` w tej instalacji nie ma kolumny `order` — nie wstawiamy jej.
-- ---------------------------------------------------------------------------

INSERT INTO `tio_module` (`slug`, `main`, `publish`, `separate`, `created_at`, `ico`) VALUES
('Pricing', 1, 1, 1, NOW(), '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.41 11.58l-9-9A2 2 0 0 0 11 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 .59 1.42l9 9a2 2 0 0 0 2.82 0l7-7a2 2 0 0 0 0-2.84zM12.41 20L4 11.59V4h7.59L20 12.41zM7.5 5A2.5 2.5 0 1 0 10 7.5 2.5 2.5 0 0 0 7.5 5zm0 3.5a1 1 0 1 1 1-1 1 1 0 0 1-1 1z"/></svg>');

INSERT INTO `tio_module_lang` (`id_module`, `id_lang`, `name`, `created_at`)
SELECT m.id, l.id, IF(l.lang_code LIKE 'pl%', 'Cennik', 'Price list'), NOW()
FROM `tio_module` m
CROSS JOIN `tio_language` l
WHERE m.slug = 'Pricing';
