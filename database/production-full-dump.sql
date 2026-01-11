-- PC-Wittfoot UG - Vollständiger Datenbank-Export
-- Datum: 2026-01-11 02:15:56
-- Datenbank: pc_wittfoot

SET FOREIGN_KEY_CHECKS=0;

-- Tabelle: api_cache
DROP TABLE IF EXISTS `api_cache`;
CREATE TABLE `api_cache` (
  `cache_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cache_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cache_key`),
  KEY `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle: audit_log
DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_action` (`user_id`,`action`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('1', NULL, 'password_reset_completed', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 17:44:52');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('2', '1', 'admin_login', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 17:45:08');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('3', NULL, 'password_reset_requested', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 17:48:14');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('4', '1', 'admin_login', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 17:52:15');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('5', '1', 'admin_login', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 17:56:05');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('6', NULL, 'admin_login_password', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 19:45:31');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('7', NULL, 'admin_login_password', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-G973U) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/14.2 Chrome/87.0.4280.141 Mobile Safari/537.36', '2026-01-01 19:47:24');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('8', '1', 'admin_login_2fa', 'user', '1', '{\"trusted_device\":false}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 19:56:39');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('9', NULL, 'admin_login_password', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 19:57:10');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('10', '1', 'admin_login_2fa', 'user', '1', '{\"trusted_device\":true}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 19:57:24');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('11', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 22:21:45');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('12', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-02 00:29:22');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('13', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-02 23:05:30');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('14', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-03 18:59:21');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('15', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 00:36:30');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('16', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 10:47:34');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('17', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 13:43:40');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('18', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 15:45:52');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('19', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 15:48:37');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('20', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 16:44:54');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('21', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 17:37:51');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('22', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-04 18:25:46');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('23', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-10 21:28:31');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('24', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-10 22:55:16');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('25', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-10 23:43:38');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('26', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-11 00:22:11');
INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES ('27', '1', 'admin_login_trusted_device', 'user', '1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-11 01:11:34');

-- Tabelle: blog_posts
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` int DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_published` (`published`),
  KEY `idx_slug` (`slug`),
  KEY `idx_author` (`author_id`),
  KEY `idx_published_at` (`published_at`),
  FULLTEXT KEY `idx_search` (`title`,`excerpt`,`content`),
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_posts` (`id`, `slug`, `title`, `excerpt`, `content`, `author_id`, `published`, `published_at`, `created_at`, `updated_at`) VALUES ('1', 'neue-exone-pcs-eingetroffen', 'Neue exone Business-PCs eingetroffen', 'Frische Lieferung von Extracomputer ist da!', '<p>Wir haben eine neue Lieferung der beliebten <strong>exone Business-PCs</strong> erhalten!</p><p>Die Serie Business 3000 überzeugt mit aktueller Intel-Technologie, schnellen NVMe-SSDs und wird komplett in Deutschland gefertigt.</p><p>Perfekt für Büro, Home-Office und kleine Unternehmen. Jetzt im Shop verfügbar!</p>', '1', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `blog_posts` (`id`, `slug`, `title`, `excerpt`, `content`, `author_id`, `published`, `published_at`, `created_at`, `updated_at`) VALUES ('2', 'refurbished-laptops-warum', 'Warum refurbished Laptops?', 'Hochwertig, günstig, nachhaltig', '<p><strong>Refurbished Hardware</strong> ist generalüberholte, professionell aufbereitete Technik.</p><p>Unsere Vorteile:</p><ul><li>Bis zu 70% günstiger als Neuware</li><li>Professionell getestet und gereinigt</li><li>12 Monate Gewährleistung</li><li>Nachhaltig und umweltfreundlich</li></ul><p>Alle Geräte werden in unserer Werkstatt geprüft und mit frischer Windows-Installation ausgeliefert.</p>', '1', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `blog_posts` (`id`, `slug`, `title`, `excerpt`, `content`, `author_id`, `published`, `published_at`, `created_at`, `updated_at`) VALUES ('4', 'testblog', 'testblog', 'editortest', '<h2>Test mit Bild</h2>
<p>Hier ist ein Testbild:</p>
<img src=\"http://localhost:8000/uploads/blog/logo.jpg\" alt=\"Test Logo\" style=\"max-width: 100%; height: auto;\">
<p>Das Bild sollte jetzt angezeigt werden.</p>', '1', '1', '2026-01-01 17:08:00', '2026-01-01 17:08:49', '2026-01-01 19:09:20');
INSERT INTO `blog_posts` (`id`, `slug`, `title`, `excerpt`, `content`, `author_id`, `published`, `published_at`, `created_at`, `updated_at`) VALUES ('5', 'test-mit-bild', 'Test mit Bild', 'Ein Test mit Bild', '<h2>Textausrichtung - Demo</h2>

<h3>Linksbündig (Standard)</h3>
<p class=\"text-left\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dies ist ein linksbündiger Text, der standardmäßig verwendet wird.</p>

<h3>Zentriert</h3>
<p class=\"text-center\">Dieser Text ist zentriert ausgerichtet und eignet sich gut für Überschriften oder wichtige Aussagen.</p>

<h3>Rechtsbündig</h3>
<p class=\"text-right\">Dieser Text ist rechtsbündig ausgerichtet. Wird selten verwendet, aber manchmal für Zitate oder Signaturen.</p>

<h3>Blocksatz</h3>
<p class=\"text-justify\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Dies ist ein Blocksatz-Text, bei dem beide Ränder bündig ausgerichtet sind.</p>

<hr>

<p class=\"text-center\"><strong>Alle Ausrichtungen funktionieren!</strong></p>', '1', '1', '2026-01-01 18:57:00', '2026-01-01 19:03:04', '2026-01-01 19:21:37');

-- Tabelle: booking_settings
DROP TABLE IF EXISTS `booking_settings`;
CREATE TABLE `booking_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `booking_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('1', 'booking_start_time', '11:00', 'Erste verfügbare Buchungszeit (Format: HH:MM)', '2026-01-04 19:45:53');
INSERT INTO `booking_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('2', 'booking_end_time', '13:00', 'Letzte verfügbare Buchungszeit (Format: HH:MM)', '2026-01-04 19:45:53');
INSERT INTO `booking_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('3', 'booking_interval_minutes', '60', 'Zeitabstand zwischen Terminen in Minuten', '2026-01-04 19:45:53');
INSERT INTO `booking_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES ('4', 'max_bookings_per_slot', '1', 'Maximale Anzahl Buchungen pro Zeitslot', '2026-01-04 19:45:53');

-- Tabelle: bookings
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `service_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time DEFAULT NULL,
  `booking_end_time` time DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `customer_firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone_country` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '+49',
  `customer_phone_mobile` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone_landline` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_house_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manage_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hellocash_customer_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_manage_token` (`manage_token`),
  KEY `idx_booking_date` (`booking_date`),
  KEY `idx_booking_type` (`booking_type`),
  KEY `idx_status` (`status`),
  KEY `idx_hellocash_customer_id` (`hellocash_customer_id`),
  KEY `idx_booking_type_date` (`booking_type`,`booking_date`),
  KEY `idx_booking_times` (`booking_date`,`booking_time`,`booking_end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('1', 'fixed', 'software', '2026-01-02', '11:15:00', NULL, 'lieselotte@name.de', '', NULL, 'liese', 'lotte', '', '+49', '1786666666', NULL, '', '', '', '', '20ee3fc5c9abc425ccd4435c3d355308b37b64ad1a5a99dabcbc32c4cbf38770', '9', 'pending', '2026-01-01 03:08:49', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('2', 'fixed', 'software', '2026-01-02', '11:15:00', NULL, 'klausklause@klausi.de', '', NULL, 'Klaus', 'Klausen', '', '+49', '17855555555', NULL, '', '', '', '', 'ca426e1676da552d85b774ab22e619ebb66a756614c8774ab893e0ec64eb7ff3', '12', 'pending', '2026-01-01 03:29:06', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('3', 'fixed', 'software', '2026-01-02', '11:15:00', NULL, 'maxi@mueller.de', '', NULL, 'Maxi', 'Müller', '', '+49', '17877777777', NULL, '', '', '', '', '94f1a37387d623a5f5c656a16beed2f73c71df21ddbe42a52e78e26000fb47f4', '16', 'pending', '2026-01-01 03:53:51', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('4', 'walkin', 'sonstiges', '2026-01-03', '14:00:00', NULL, 'mueller@maxi.deue', '', NULL, 'Maxi', 'Müller', '', '+49', '1795555555', NULL, '', '', '', '', 'c64a431bafa3037b252e355f8b2936a60bc6c45f741d8d8143a1687707b3ce55', '17', 'pending', '2026-01-01 04:13:31', '2026-01-04 14:45:46');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('5', 'fixed', 'pc-reparatur', '2026-01-06', '08:00:00', '00:00:00', 'klausklause@klausi.de', 'Abholung Reparatur', 'HP Probook Notebook', 'Klaus', 'Klausen', NULL, '+49', '17855555555', NULL, '', '', '', '', '90bf555484c5896201960e9b34c9f739d1dae5181dd8913c0f2f3b2629b44db0', NULL, 'confirmed', '2026-01-01 05:19:00', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('6', 'fixed', 'pc-reparatur', '2026-01-14', '11:00:00', NULL, 'Anna1@pc-wittfoot.de', '', NULL, 'Anna1', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'd36159b13029a3bda9da01ca479c5ed2d2d2197f61474579f40e594d0317498c', '25', 'pending', '2026-01-03 19:05:34', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('7', 'fixed', 'notebook-reparatur', '2026-01-14', '11:00:00', NULL, 'anna2@pc-wittfoot.de', 'Anna ist nass', NULL, 'Ann2', 'Nas', '', '+49', '1234', NULL, 'Annasweg', '2', '26123', 'Oldenburg', '064180c3774e24738a1989d6be8a01b6c63b0edd036b3a53c713248c4aa0a15e', '25', 'pending', '2026-01-03 19:37:17', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('8', 'fixed', 'notebook-reparatur', '2026-01-14', '12:00:00', NULL, 'anna3@pc-wittfoot.de', '', NULL, 'Anna3', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '2e9ad32b11dbff08d77b4699ebfe759ffd764da00c81790aa877212ec1608860', '26', 'pending', '2026-01-03 20:05:04', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('9', 'fixed', 'beratung', '2026-01-15', '11:00:00', NULL, 'anna4@pc-wittfoot.de', 'Noch nen Gedicht', NULL, 'Anna4', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '56804754f465927b5b005f1b818d86bc182a7dbf7b1ceab42d61b1360e2f85b2', '27', 'pending', '2026-01-04 00:33:57', '2026-01-04 01:11:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('10', 'fixed', 'software', '2026-01-16', '11:00:00', NULL, 'Anna5@pc-wittfoot.de', 'jojojo', NULL, 'Anna5', 'Nas', '', '+49', '1234', NULL, 'Annasweg', '1', '26123', 'Oldenburg', 'fea8cd97c4f695b11e2b31ec6fa2542d8c16ed47226bea8c917b251376aff4c7', '28', 'cancelled', '2026-01-04 01:23:06', '2026-01-04 01:29:48');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('11', 'fixed', 'installation', '2026-01-16', '11:00:00', NULL, 'anna6@pc-wittfoot.de', '', NULL, 'Anna6', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '8192e1dcc34b2c1dc239ff43f1a97ee17aee3a5c8911a1dd6982c3cf7a498270', '29', 'cancelled', '2026-01-04 01:36:47', '2026-01-04 01:37:13');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('12', 'fixed', 'installation', '2026-01-15', '12:00:00', NULL, 'anna7@pc-wittfoot.de', 'gääääähhhhhhn', NULL, 'Anna7', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'f93eeb110633c5ba271a992e7bb209ef030708c967f30d6406072d700741289f', '30', 'cancelled', '2026-01-04 09:07:34', '2026-01-04 09:08:19');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('13', 'fixed', 'diagnose', '2026-01-15', '12:00:00', NULL, 'anna8@pc-wittfoot.de', 'PLZ Test', NULL, 'Anna8', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '309bdd07d94bdc27e7fe46c33100f65f72aedd8f223e98d9c07ce371e99be197', '31', 'cancelled', '2026-01-04 09:16:37', '2026-01-04 09:17:26');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('14', 'fixed', 'diagnose', '2026-01-15', '12:00:00', NULL, 'anna8@pc-wittfoot.de', 'mailadressentest', NULL, 'Anna8', 'Nas', '', '+49', '12345', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '933cb2a5178a19f8574232228330e3066fa9782f7f0a6038231421abd02c4b4c', '31', 'cancelled', '2026-01-04 09:20:53', '2026-01-04 09:21:39');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('15', 'fixed', 'reparatur', '2026-01-15', '12:00:00', NULL, 'anna8@pc-wittfoot.de', '', NULL, 'Anna8', 'Nas', '', '+49', '12345', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'a6e49d53b34e77e1ca93838314234fed548bda9f6ed16290f2b3e7d080bd9773', '31', 'pending', '2026-01-04 09:22:16', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('16', 'fixed', 'sonstiges', '2026-01-16', '11:00:00', NULL, 'anna9@pc-wittfoot.de', '', NULL, 'anna9', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f', '32', 'cancelled', '2026-01-04 09:25:56', '2026-01-04 11:01:03');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('17', 'fixed', 'diagnose', '2026-01-16', '12:00:00', NULL, 'anna10@pc-wittfoot.de', 'Abschliessender Test', NULL, 'Anna10', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '83ab833421cde2851269f64e7ca6b3fe52948105f5f8c1d42d1c1da027a37562', '33', 'pending', '2026-01-04 10:49:49', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('18', 'fixed', 'beratung', '2026-01-13', '11:00:00', NULL, 'anna10@pc-wittfoot.de', '', NULL, 'Anna10', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'c76b246dfaf80151d25b830051705c6d2fde5a8e592ab8e287a310e6678a2d17', '33', 'cancelled', '2026-01-04 10:57:02', '2026-01-04 10:58:10');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('19', 'walkin', 'beratung', '2026-01-06', '14:00:00', NULL, 'anna10@pc-wittfoot.de', '', NULL, 'Anna10', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26000', 'Oldenburg', '268ed1a4e0d1b6c6ac5773f216a03f6b70fe9c75327978955297af99c10f43f0', '33', 'pending', '2026-01-04 13:56:32', '2026-01-04 14:45:46');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('20', 'walkin', 'verkauf', '2026-01-06', '15:00:00', NULL, 'anna8@pc-wittfoot.de', '', NULL, 'Anna8', 'Nas', '', '+49', '1234', '1234', 'Annanasweg', '1', '26123', 'Oldenburg', '7dcf2066214deb4d70d2273fc031f3db6ea39688e7c2f064610a829ce0a48061', '31', 'pending', '2026-01-04 14:11:04', '2026-01-04 14:45:46');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('21', 'walkin', 'installation', '2026-01-06', '16:00:00', NULL, 'anna6@pc-wittfoot.de', 'Doppeltermin', NULL, 'Anna6', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '7ff18a73df1df88e0abde4c785beb0fd137c046d8c4e9da4c85be1f25eb83044', '29', 'pending', '2026-01-04 14:19:04', '2026-01-04 14:45:46');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('22', 'walkin', 'installation', '2026-01-08', '14:00:00', NULL, 'Anna5@pc-wittfoot.de', 'jojojo', NULL, 'Anna5', 'Nas', '', '+49', '1234', NULL, 'Annasweg', '1', '26123', 'Oldenburg', 'f5d734f33a66b18b591635d135edb9e1253aa7874ffc6f18dd52844e9499d541', '28', 'pending', '2026-01-04 14:39:47', '2026-01-04 14:45:46');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('23', 'walkin', 'diagnose', '2026-01-08', '15:00:00', NULL, 'anna10@pc-wittfoot.de', 'Doppelbuchung', NULL, 'anna10', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '417a54cbc0e69f3a99b9c17297a52210002dbabea76827229eafbb03c3127350', '33', 'pending', '2026-01-04 14:42:01', '2026-01-04 14:45:46');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('24', 'walkin', 'reparatur', '2026-01-09', '14:00:00', NULL, 'anna20@pc-wittfoot.de', 'Festnetz-Test', NULL, 'Anna20', 'Nas', '', '+49', '1234', '1234', 'Annanasweg', '1', '26123', 'Oldenburg', '425702bd58b8a896c2d080a19b56ddf0ec484a835583dbfaf2f32d4ef0d179da', '34', 'confirmed', '2026-01-04 15:01:15', '2026-01-04 19:38:10');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('25', 'walkin', 'sonstiges', '2026-01-09', '15:00:00', NULL, 'anna21@pc-wittfoot.de', '', NULL, 'Anna21', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '0b61e8774cf907db66b7082d78969288753f45228cb95ab34915b9b1f737cdb0', '35', 'pending', '2026-01-04 15:04:35', '2026-01-04 15:07:33');
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('26', 'walkin', 'diagnose', '2026-01-09', '16:00:00', NULL, 'anna22@pc-wittfoot.de', '', NULL, 'Anna22', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '0a43d2d29a8439d751e078c84a507de464a17e86b74ec8e74d9b05ec87e82f9b', '36', 'pending', '2026-01-04 15:08:53', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('27', 'walkin', 'reparatur', '2026-01-09', '14:00:00', NULL, 'anna23@pc-wittfoot.de', '', NULL, 'Anna23', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'e958dc5a3f6a674a7e6810328eef95077c010e2d64c7584d1bdb208786ed83d8', '37', 'pending', '2026-01-04 15:15:57', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('28', 'walkin', 'reparatur', '2026-01-09', '15:00:00', NULL, 'anna24@pc-wittfoot.de', '', NULL, 'Anna24', 'Nas', 'Meine Firma', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'a1166ad3bf29c6f72060abb405dd77e9a87141137acc9c7c4f7f2a43a3d9d236', '38', 'pending', '2026-01-04 15:41:41', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('29', 'walkin', 'diagnose', '2026-01-10', '14:00:00', NULL, 'anna25@pc-wittfoot.de', '', NULL, 'Anna25', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '246e4a23e26a2a1665b1c5c9e7eb6d3d8ea5952f59f20a829153dea66d1996c6', '39', 'pending', '2026-01-04 15:45:44', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('30', 'walkin', 'verkauf', '2026-01-10', '13:00:00', NULL, 'anna26@pc-wittfoot.de', '', NULL, 'Anna26', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '1ab1e1212cd9d6403bd61da97620461df2e639c73db69965d190fe096bd2aa1d', '40', 'pending', '2026-01-04 18:34:30', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('31', 'fixed', 'verkauf', '2026-01-09', '11:00:00', NULL, 'anna26@pc-wittfoot.de', '', NULL, 'Anna27', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '72cf2d176340fec7591b7056e32ee981338958698d6be745305b53a519ee95cc', '41', 'pending', '2026-01-04 19:12:20', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('32', 'walkin', 'beratung', '2026-01-09', '16:00:00', NULL, 'anna28@pc-wittfoot.de', '', NULL, 'Anna27', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'a3026edb515cea59967ad772e4fcbee0dfb0af8a61382c42a98241f2ecd69fec', '42', 'pending', '2026-01-04 19:16:55', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('33', 'fixed', 'verkauf', '2026-01-09', '12:00:00', NULL, 'anna26@pc-wittfoot.de', '', NULL, 'Anna27', 'Nas', '', '+49', '1234', NULL, 'An', '1', '26123', 'Ol', 'd0a55a2737705003a4292544b3fc5ba53111e82f67fba1ede73042b700e18bf1', '41', 'pending', '2026-01-04 19:22:37', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('34', 'fixed', 'verkauf', '2026-01-09', '11:30:00', NULL, 'anna28@pc-wittfoot.de', '', NULL, 'Anna28', 'Nas', '', '+49', '12234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', 'e491e0a5eab4ae5e953dad88954bd656a029782e62887b3680aff5a10a290325', '43', 'pending', '2026-01-04 19:41:28', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('35', 'fixed', 'beratung', '2026-01-09', '11:00:00', NULL, 'anna26@pc-wittfoot.de', '', NULL, 'Anna26', 'Nas', '', '+49', '1234', NULL, 'Annanasweg', '1', '26123', 'Oldenburg', '6a686f9f2cd0d9bcbb14fd1afb91d6988ff394b9d35d380feaa432fdd71df913', '44', 'pending', '2026-01-04 19:43:49', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('36', 'fixed', 'beratung', '2026-01-06', '11:00:00', NULL, 'langetexte@pc-wittfoot.de', '', NULL, 'Anna Frieda Marion Ursel Nicole Linde Sabine haste nicht gesehen und was weiß ich noch', 'Ein langer Nachname muss her damit ich das hier testen kan und weiß was mit soch langen Texten passiert', '', '+49', '1234', NULL, 'So eine lange Straße gibt es bestimmt wie der Name jedoch keinen Straßennamen der so lang ist', '123456', '26123', 'Oldenburg', 'bac058222677f206db37389555c062d661a7b3d22c363fdd53358d455ae6b0f5', '45', 'pending', '2026-01-04 19:56:01', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('37', 'fixed', 'beratung', '2026-01-20', '11:00:00', NULL, 'test@test.de', NULL, NULL, '<script>alert(1)</script>', 'Test', NULL, '+49', '1234567890', NULL, 'Test', '1', '12345', 'Berlin', 'a47f3a8498734af89d0a19cd6500f9158b32fd983196743796a39de262a0de76', '46', 'pending', '2026-01-04 20:00:35', NULL);
INSERT INTO `bookings` (`id`, `booking_type`, `service_type`, `booking_date`, `booking_time`, `booking_end_time`, `customer_email`, `customer_notes`, `admin_notes`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone_country`, `customer_phone_mobile`, `customer_phone_landline`, `customer_street`, `customer_house_number`, `customer_postal_code`, `customer_city`, `manage_token`, `hellocash_customer_id`, `status`, `created_at`, `updated_at`) VALUES ('38', 'fixed', 'beratung', '2026-01-13', '11:00:00', NULL, 'hans@pc-wittfoot.de', 'geht nicht mehr an', NULL, 'Hans', 'Hammel', '', '+49', '17888777666', NULL, 'Hammelgang', '12', '26123', 'Oldenburg', '14f601b4b83fc61d2a6cd13c118f02f8d4ff3319d963d70d4d6d8946cbdd1232', '47', 'pending', '2026-01-10 22:54:45', NULL);

-- Tabelle: categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_active` (`is_active`),
  KEY `idx_sort` (`sort_order`),
  CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'Laptops & Notebooks', 'laptops-notebooks', 'Tragbare Computer für unterwegs', NULL, '1', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'Desktop-PCs', 'desktop-pcs', 'Standcomputer für Büro und Zuhause', NULL, '2', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'Monitore', 'monitore', 'Bildschirme in verschiedenen Größen', NULL, '3', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'Tablets & Handys', 'tablets-handys', 'Mobile Geräte', NULL, '4', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('5', 'Peripherie', 'peripherie', 'Mäuse, Tastaturen, Headsets', NULL, '5', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('6', 'Drucker', 'drucker', 'Drucker und Multifunktionsgeräte', NULL, '6', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('7', 'Netzwerk', 'netzwerk', 'Router, Switches, Access Points', NULL, '7', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES ('8', 'Zubehör', 'zubehoer', 'Kabel, Adapter, Tinte, Toner', NULL, '8', '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');

-- Tabelle: contact_submissions
DROP TABLE IF EXISTS `contact_submissions`;
CREATE TABLE `contact_submissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `status` enum('new','read','replied','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created` (`created_at`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `subject`, `message`, `ip_address`, `user_agent`, `status`, `created_at`) VALUES ('1', 'Anna26 Nas', 'anna26@pc-wittfoot.de', '', 'Produktanfrage', 'joa', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'new', '2026-01-05 21:00:44');
INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `subject`, `message`, `ip_address`, `user_agent`, `status`, `created_at`) VALUES ('2', 'Anna26 Nas', 'anna26@pc-wittfoot.de', '', 'Produktanfrage', 'joa', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'new', '2026-01-05 21:05:03');
INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `subject`, `message`, `ip_address`, `user_agent`, `status`, `created_at`) VALUES ('3', 'Anna26', 'anna26@pc-wittfoot.de', '', 'Produktanfrage', 'test2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'new', '2026-01-05 21:07:38');
INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `subject`, `message`, `ip_address`, `user_agent`, `status`, `created_at`) VALUES ('4', 'CSRF Test', 'csrf-test@example.com', '123456789', 'CSRF Schutz Test', 'Dies ist ein Test mit gültigem CSRF-Token.', '127.0.0.1', 'curl/8.5.0', 'new', '2026-01-05 21:11:49');

-- Tabelle: email_log
DROP TABLE IF EXISTS `email_log`;
CREATE TABLE `email_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `email_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'confirmation, reminder_24h, reminder_1h',
  `recipient_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('sent','failed','pending') COLLATE utf8mb4_unicode_ci DEFAULT 'sent',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_booking` (`booking_id`),
  KEY `idx_type` (`email_type`),
  KEY `idx_sent` (`sent_at`),
  CONSTRAINT `email_log_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('3', '6', 'confirmation', 'Anna1@pc-wittfoot.de', 'Terminbestätigung #6 - PC-Wittfoot UG', 'Moin Anna1 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════════╗
║         TERMINDETAILS             ║
╚════════════════════════════════════════╝

Buchungsnummer: #6
Terminart:      Fester Termin
Dienstleistung: PC-Reparatur
Datum:          Mittwoch, 14. Januar 2026
Uhrzeit:        11:00 Uhr



╔════════════════════════════════════════╗
║     BITTE MITBRINGEN                 ║
╚════════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz

PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-03 19:05:35', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('4', '7', 'confirmation', 'anna2@pc-wittfoot.de', 'Terminbestätigung #7 - PC-Wittfoot UG', 'Moin Ann2 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════════╗
║         TERMINDETAILS                 ║
╚════════════════════════════════════════╝

Buchungsnummer: #7
Terminart:      Fester Termin
Dienstleistung: Notebook-Reparatur
Datum:          Mittwoch, 14. Januar 2026
Uhrzeit:        11:00 Uhr

Ihre Anmerkungen:
Anna ist nass


╔════════════════════════════════════════╗
║     BITTE MITBRINGEN                   ║
╚════════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-03 19:37:18', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('5', '7', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #7 - Ann2 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #7
Terminart:        Fester Termin
Dienstleistung:   Notebook-Reparatur
Datum:            Mittwoch, 14. Januar 2026
Uhrzeit:          11:00 Uhr

--- KUNDENDATEN ---

Name:             Ann2 Nas
E-Mail:           anna2@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annasweg 2
26123 Oldenburg

Ihre Anmerkungen:
Anna ist nass


--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=7



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-03 19:37:18', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('6', '8', 'confirmation', 'anna3@pc-wittfoot.de', 'Terminbestätigung #8 - PC-Wittfoot UG', 'Moin Anna3 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════════╗
║         TERMINDETAILS                 ║
╚════════════════════════════════════════╝

Buchungsnummer: #8
Terminart:      Fester Termin
Dienstleistung: Notebook-Reparatur
Datum:          Mittwoch, 14. Januar 2026
Uhrzeit:        12:00 Uhr



╔════════════════════════════════════════╗
║     BITTE MITBRINGEN                   ║
╚════════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-03 20:05:05', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('7', '8', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #8 - Anna3 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #8
Terminart:        Fester Termin
Dienstleistung:   Notebook-Reparatur
Datum:            Mittwoch, 14. Januar 2026
Uhrzeit:          12:00 Uhr

--- KUNDENDATEN ---

Name:             Anna3 Nas
E-Mail:           anna3@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg



--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=8



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-03 20:05:05', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('8', '9', 'confirmation', 'anna4@pc-wittfoot.de', 'Terminbestätigung #9 - PC-Wittfoot UG', 'Moin Anna4 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════════╗
║         TERMINDETAILS                 ║
╚════════════════════════════════════════╝

Buchungsnummer: #9
Terminart:      Fester Termin
Dienstleistung: Beratung
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        11:00 Uhr

Ihre Anmerkungen:
Noch nen Gedicht


╔════════════════════════════════════════╗
║     BITTE MITBRINGEN                   ║
╚════════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 00:33:58', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('9', '9', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #9 - Anna4 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #9
Terminart:        Fester Termin
Dienstleistung:   Beratung
Datum:            Donnerstag, 15. Januar 2026
Uhrzeit:          11:00 Uhr

--- KUNDENDATEN ---

Name:             Anna4 Nas
E-Mail:           anna4@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg

Ihre Anmerkungen:
Noch nen Gedicht


--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=9



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 00:33:59', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('10', '10', 'confirmation', 'Anna5@pc-wittfoot.de', 'Terminbestätigung #10 - PC-Wittfoot UG', 'Moin Anna5 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #10
Terminart:      Fester Termin
Dienstleistung: Software-Installation
Datum:          Freitag, 16. Januar 2026
Uhrzeit:        11:00 Uhr

Ihre Anmerkungen:
jojojo


╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=fea8cd97c4f695b11e2b31ec6fa2542d8c16ed47226bea8c917b251376aff4c7

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 01:23:06', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('11', '10', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #10 - Anna5 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #10
Terminart:        Fester Termin
Dienstleistung:   Software-Installation
Datum:            Freitag, 16. Januar 2026
Uhrzeit:          11:00 Uhr

--- KUNDENDATEN ---

Name:             Anna5 Nas
E-Mail:           Anna5@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annasweg 1
26123 Oldenburg

Ihre Anmerkungen:
jojojo


--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=10



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 01:23:07', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('12', '10', 'cancellation', 'Anna5@pc-wittfoot.de', 'Stornierung bestätigt - Buchung #10', 'Moin Anna5 Nas,

Ihre Terminbuchung wurde erfolgreich storniert.

╔════════════════════════════════════╗
║    STORNIERTE BUCHUNG              ║
╚════════════════════════════════════╝

Buchungsnummer: #10
Terminart:      Fester Termin
Dienstleistung: Software-Installation
Datum:          Freitag, 16. Januar 2026
Uhrzeit:        11:00 Uhr

Sie erhalten keine weitere Bestätigung.

╔════════════════════════════════════╗
║    NEUEN TERMIN BUCHEN             ║
╚════════════════════════════════════╝

Sie können jederzeit einen neuen Termin online buchen unter:
http://localhost:8000/termin

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir hoffen, Sie bald wieder begrüßen zu dürfen!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 01:29:48', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('13', '11', 'confirmation', 'anna6@pc-wittfoot.de', 'Terminbestätigung #11 - PC-Wittfoot UG', 'Moin Anna6 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #11
Terminart:      Fester Termin
Dienstleistung: Installation
Datum:          Freitag, 16. Januar 2026
Uhrzeit:        11:00 Uhr



╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=8192e1dcc34b2c1dc239ff43f1a97ee17aee3a5c8911a1dd6982c3cf7a498270

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 01:36:47', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('14', '11', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #11 - Anna6 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #11
Terminart:        Fester Termin
Dienstleistung:   Installation
Datum:            Freitag, 16. Januar 2026
Uhrzeit:          11:00 Uhr

--- KUNDENDATEN ---

Name:             Anna6 Nas
E-Mail:           anna6@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg



--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=11



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 01:36:48', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('15', '11', 'cancellation', 'anna6@pc-wittfoot.de', 'Stornierung bestätigt - Buchung #11', 'Moin Anna6 Nas,

Ihre Terminbuchung wurde erfolgreich storniert.

╔════════════════════════════════════╗
║    STORNIERTE BUCHUNG              ║
╚════════════════════════════════════╝

Buchungsnummer: #11
Terminart:      Fester Termin
Dienstleistung: Installation
Datum:          Freitag, 16. Januar 2026
Uhrzeit:        11:00 Uhr

Sie erhalten keine weitere Bestätigung.

╔════════════════════════════════════╗
║    NEUEN TERMIN BUCHEN             ║
╚════════════════════════════════════╝

Sie können jederzeit einen neuen Termin online buchen unter:
http://localhost:8000/termin

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir hoffen, Sie bald wieder begrüßen zu dürfen!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 01:37:13', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('16', '12', 'confirmation', 'anna7@pc-wittfoot.de', 'Terminbestätigung #12 - PC-Wittfoot UG', 'Moin Anna7 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #12
Terminart:      Fester Termin
Dienstleistung: Installation
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        12:00 Uhr

Ihre Anmerkungen:
gääääähhhhhhn


╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=f93eeb110633c5ba271a992e7bb209ef030708c967f30d6406072d700741289f

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:07:36', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('17', '12', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #12 - Anna7 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #12
Terminart:        Fester Termin
Dienstleistung:   Installation
Datum:            Donnerstag, 15. Januar 2026
Uhrzeit:          12:00 Uhr

--- KUNDENDATEN ---

Name:             Anna7 Nas
E-Mail:           anna7@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg

Ihre Anmerkungen:
gääääähhhhhhn


--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=12



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:07:36', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('18', '12', 'cancellation', 'anna7@pc-wittfoot.de', 'Stornierung bestätigt - Buchung #12', 'Moin Anna7 Nas,

Ihre Terminbuchung wurde erfolgreich storniert.

╔════════════════════════════════════╗
║    STORNIERTE BUCHUNG              ║
╚════════════════════════════════════╝

Buchungsnummer: #12
Terminart:      Fester Termin
Dienstleistung: Installation
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        12:00 Uhr

Sie erhalten keine weitere Bestätigung.

╔════════════════════════════════════╗
║    NEUEN TERMIN BUCHEN             ║
╚════════════════════════════════════╝

Sie können jederzeit einen neuen Termin online buchen unter:
http://localhost:8000/termin

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir hoffen, Sie bald wieder begrüßen zu dürfen!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:08:20', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('19', '13', 'confirmation', 'anna8@pc-wittfoot.de', 'Terminbestätigung #13 - PC-Wittfoot UG', 'Moin Anna8 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #13
Terminart:      Fester Termin
Dienstleistung: Diagnose
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        12:00 Uhr

Ihre Anmerkungen:
PLZ Test


╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=309bdd07d94bdc27e7fe46c33100f65f72aedd8f223e98d9c07ce371e99be197

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:16:37', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('20', '13', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #13 - Anna8 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #13
Terminart:        Fester Termin
Dienstleistung:   Diagnose
Datum:            Donnerstag, 15. Januar 2026
Uhrzeit:          12:00 Uhr

--- KUNDENDATEN ---

Name:             Anna8 Nas
E-Mail:           anna8@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg

Ihre Anmerkungen:
PLZ Test


--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=13



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:16:38', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('21', '13', 'cancellation', 'anna8@pc-wittfoot.de', 'Stornierung bestätigt - Buchung #13', 'Moin Anna8 Nas,

Ihre Terminbuchung wurde erfolgreich storniert.

╔════════════════════════════════════╗
║    STORNIERTE BUCHUNG              ║
╚════════════════════════════════════╝

Buchungsnummer: #13
Terminart:      Fester Termin
Dienstleistung: Diagnose
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        12:00 Uhr

Sie erhalten keine weitere Bestätigung.

╔════════════════════════════════════╗
║    NEUEN TERMIN BUCHEN             ║
╚════════════════════════════════════╝

Sie können jederzeit einen neuen Termin online buchen unter:
http://localhost:8000/termin

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir hoffen, Sie bald wieder begrüßen zu dürfen!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:17:27', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('22', '14', 'confirmation', 'anna8@pc-wittfoot.de', 'Terminbestätigung #14 - PC-Wittfoot UG', 'Moin Anna8 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #14
Terminart:      Fester Termin
Dienstleistung: Diagnose
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        12:00 Uhr

Ihre Anmerkungen:
mailadressentest


╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=933cb2a5178a19f8574232228330e3066fa9782f7f0a6038231421abd02c4b4c

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:20:53', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('23', '14', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #14 - Anna8 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #14
Terminart:        Fester Termin
Dienstleistung:   Diagnose
Datum:            Donnerstag, 15. Januar 2026
Uhrzeit:          12:00 Uhr

--- KUNDENDATEN ---

Name:             Anna8 Nas
E-Mail:           anna8@pc-wittfoot.de
Telefon (Mobil):  +49 12345
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg

Ihre Anmerkungen:
mailadressentest


--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=14



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:20:54', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('24', '14', 'cancellation', 'anna8@pc-wittfoot.de', 'Stornierung bestätigt - Buchung #14', 'Moin Anna8 Nas,

Ihre Terminbuchung wurde erfolgreich storniert.

╔════════════════════════════════════╗
║    STORNIERTE BUCHUNG              ║
╚════════════════════════════════════╝

Buchungsnummer: #14
Terminart:      Fester Termin
Dienstleistung: Diagnose
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        12:00 Uhr

Sie erhalten keine weitere Bestätigung.

╔════════════════════════════════════╗
║    NEUEN TERMIN BUCHEN             ║
╚════════════════════════════════════╝

Sie können jederzeit einen neuen Termin online buchen unter:
http://localhost:8000/termin

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir hoffen, Sie bald wieder begrüßen zu dürfen!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:21:39', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('25', '15', 'confirmation', 'anna8@pc-wittfoot.de', 'Terminbestätigung #15 - PC-Wittfoot UG', 'Moin Anna8 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #15
Terminart:      Fester Termin
Dienstleistung: Reparatur
Datum:          Donnerstag, 15. Januar 2026
Uhrzeit:        12:00 Uhr



╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=a6e49d53b34e77e1ca93838314234fed548bda9f6ed16290f2b3e7d080bd9773

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:22:17', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('26', '15', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #15 - Anna8 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #15
Terminart:        Fester Termin
Dienstleistung:   Reparatur
Datum:            Donnerstag, 15. Januar 2026
Uhrzeit:          12:00 Uhr

--- KUNDENDATEN ---

Name:             Anna8 Nas
E-Mail:           anna8@pc-wittfoot.de
Telefon (Mobil):  +49 12345
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg



--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=15



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:22:18', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('27', '16', 'confirmation', 'anna9@pc-wittfoot.de', 'Terminbestätigung #16 - PC-Wittfoot UG', 'Moin anna9 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #16
Terminart:      Fester Termin
Dienstleistung: Sonstiges
Datum:          Freitag, 16. Januar 2026
Uhrzeit:        11:00 Uhr



╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:25:56', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('28', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #16 - anna9 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #16
Terminart:        Fester Termin
Dienstleistung:   Sonstiges
Datum:            Freitag, 16. Januar 2026
Uhrzeit:          11:00 Uhr

--- KUNDENDATEN ---

Name:             anna9 Nas
E-Mail:           anna9@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg



--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=16



Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 09:25:57', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('29', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - {booking_number}', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    {customer_phone}
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:03:31', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('30', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - {booking_number}', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    {customer_phone}
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:12:26', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('31', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung {booking_number}', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> {customer_phone}</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"{admin_link}\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:12:27', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('32', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:14:17', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('33', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:14:18', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('34', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 11:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:23:41', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('35', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 11:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:23:42', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('36', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:25:32', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('37', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:25:33', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('38', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 11:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:27:00', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('39', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 11:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:27:00', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('40', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:28:04', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('41', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:28:05', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('42', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 11:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:30:07', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('43', '16', 'booking_notification', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 11:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-04 10:30:09', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('44', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:33:35', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('45', '16', 'admin_reschedule', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> 2026-01-16</li>
    <li><strong>Uhrzeit:</strong> 12:00:00</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:33:36', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('46', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:36:06', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('47', '16', 'admin_reschedule', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:36:07', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('48', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Walk-in</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:40:58', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('49', '16', 'admin_reschedule', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Walk-in</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:40:59', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('50', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong>  Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:46:32', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('51', '16', 'admin_reschedule', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong>  Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:46:33', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('52', '16', 'reschedule', 'anna9@pc-wittfoot.de', 'Terminänderung bestätigt - 000016', '<h2>Terminänderung bestätigt</h2>

<p>Hallo anna9 Nas,</p>

<p>Ihr Termin wurde erfolgreich geändert.</p>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Ihre Kontaktdaten:</h3>
<p>
    anna9 Nas<br>
    anna9@pc-wittfoot.de<br>
    +49 1234
</p>

<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit einsehen, ändern oder stornieren:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=52aa46903a14de34c19aef9fdb0627870fd748f30053fac3fe3cd9ea0ddd4c3f\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    <strong>Wichtig:</strong><br>
    • Terminänderungen sind bis 48 Stunden vor dem Termin möglich<br>
    • Stornierungen sind bis 24 Stunden vor dem Termin möglich<br>
    • Bei späteren Änderungen kontaktieren Sie uns bitte telefonisch
</small></p>

<hr>

<p>
    Bei Fragen stehen wir Ihnen gerne zur Verfügung:<br>
    <strong>PC-Wittfoot UG</strong><br>
    Telefon: +49 123 456789<br>
    E-Mail: info@pc-wittfoot.de
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:47:07', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('53', '16', 'admin_reschedule', 'admin@pc-wittfoot.de', 'Terminänderung: Buchung 000016', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:47:07', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('54', '17', 'confirmation', 'anna10@pc-wittfoot.de', 'Terminbestätigung #17 - PC-Wittfoot UG', 'Moin Anna10 Nas,

vielen Dank für Ihre Terminbuchung!

╔════════════════════════════════════╗
║         TERMINDETAILS              ║
╚════════════════════════════════════╝

Buchungsnummer: #17
Terminart:      Fester Termin
Dienstleistung: Diagnose
Datum:          Freitag, 16. Januar 2026
Uhrzeit:        12:00 Uhr

Ihre Anmerkungen:
Abschliessender Test


╔════════════════════════════════════╗
║  TERMIN VERWALTEN                  ║
╚════════════════════════════════════╝

Sie können Ihren Termin jederzeit online verwalten:
http://localhost:8000/termin/verwalten?token=83ab833421cde2851269f64e7ca6b3fe52948105f5f8c1d42d1c1da027a37562

Hier können Sie:
✓ Ihre Buchungsdetails einsehen
✓ Den Termin ändern (bis 48h vorher)
✓ Den Termin stornieren (bis 24h vorher)

╔════════════════════════════════════╗
║     BITTE MITBRINGEN               ║
╚════════════════════════════════════╝

Evt. nach Absprache:
✓ Ihr Gerät (PC/Notebook)
✓ Netzteil
✓ Wichtige Zugangsdaten/Passwörter aufschreiben

Bei Fragen erreichen Sie uns unter:
E-Mail: info@pc-wittfoot.de
Telefon: +49 (0) 123 456789

Wir freuen uns auf Ihren Besuch!

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:49:49', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('55', '17', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #17 - Anna10 Nas', 'NEUE TERMINBUCHUNG

Es wurde ein neuer Termin gebucht:

--- TERMINDETAILS ---

Buchungs-ID:      #17
Terminart:        Fester Termin
Dienstleistung:   Diagnose
Datum:            Freitag, 16. Januar 2026
Uhrzeit:          12:00 Uhr

--- KUNDENDATEN ---

Name:             Anna10 Nas
E-Mail:           anna10@pc-wittfoot.de
Telefon (Mobil):  +49 1234
Telefon (Fest):   -
Firma:            -

Adresse:
Annanasweg 1
26123 Oldenburg

Ihre Anmerkungen:
Abschliessender Test


--- ADMIN-BEREICH ---

Details ansehen: http://localhost:8000/admin/booking-detail?id=17



Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:49:50', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('56', '18', 'confirmation', 'anna10@pc-wittfoot.de', 'Terminbestätigung #18 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna10 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000018</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 13. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>



<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit online verwalten:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=c76b246dfaf80151d25b830051705c6d2fde5a8e592ab8e287a310e6678a2d17\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    Hier können Sie:<br>
    ✓ Ihre Buchungsdetails einsehen<br>
    ✓ Den Termin ändern (bis 48h vorher)<br>
    ✓ Den Termin stornieren (bis 24h vorher)
</small></p>

<hr>

<h3>Bitte mitbringen</h3>
<p><small>
    Evt. nach Absprache:<br>
    ✓ Ihr Gerät (PC/Notebook)<br>
    ✓ Netzteil<br>
    ✓ Wichtige Zugangsdaten/Passwörter aufschreiben
</small></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:57:03', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('57', '18', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #18 - Anna10 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000018</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 13. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna10 Nas</li>
    <li><strong>E-Mail:</strong> anna10@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=18\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:57:04', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('58', '18', 'cancellation', 'anna10@pc-wittfoot.de', 'Stornierung bestätigt - Buchung #18', '<h2>Stornierung bestätigt</h2>

<p>Moin Anna10 Nas,</p>

<p>Ihre Terminbuchung wurde erfolgreich storniert.</p>

<h3>Stornierte Buchung</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000018</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 13. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<p>Sie erhalten keine weitere Bestätigung.</p>

<hr>

<h3>Neuen Termin buchen</h3>
<p>Sie können jederzeit einen neuen Termin online buchen:</p>
<p><a href=\"http://localhost:8000/termin\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Neuen Termin buchen</a></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir hoffen, Sie bald wieder begrüßen zu dürfen!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 10:58:10', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('59', '16', 'cancellation', 'anna9@pc-wittfoot.de', 'Stornierung bestätigt - Buchung #16', '<h2>Stornierung bestätigt</h2>

<p>Moin anna9 Nas,</p>

<p>Ihre Terminbuchung wurde erfolgreich storniert.</p>

<h3>Stornierte Buchung</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<p>Sie erhalten keine weitere Bestätigung.</p>

<hr>

<h3>Neuen Termin buchen</h3>
<p>Sie können jederzeit einen neuen Termin online buchen:</p>
<p><a href=\"http://localhost:8000/termin\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Neuen Termin buchen</a></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir hoffen, Sie bald wieder begrüßen zu dürfen!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 11:01:03', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('60', '16', 'admin_cancellation', 'admin@pc-wittfoot.de', 'Stornierung: Buchung 000016', '<h2>Terminbuchung storniert</h2>

<p>Ein Kunde hat seinen Termin storniert.</p>

<h3>Buchungsdetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000016</li>
    <li><strong>Kunde:</strong> anna9 Nas</li>
    <li><strong>Email:</strong> anna9@pc-wittfoot.de</li>
    <li><strong>Telefon:</strong> +49 1234</li>
</ul>

<h3>Stornierter Termin</h3>
<ul>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 16. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=16\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 11:01:04', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('61', '19', 'confirmation', 'anna10@pc-wittfoot.de', 'Terminbestätigung #19 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna10 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000019</li>
    <li><strong>Terminart:</strong> Walk-in</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>



<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit online verwalten:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=268ed1a4e0d1b6c6ac5773f216a03f6b70fe9c75327978955297af99c10f43f0\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    Hier können Sie:<br>
    ✓ Ihre Buchungsdetails einsehen<br>
    ✓ Den Termin ändern (bis 48h vorher)<br>
    ✓ Den Termin stornieren (bis 24h vorher)
</small></p>

<hr>

<h3>Bitte mitbringen</h3>
<p><small>
    Evt. nach Absprache:<br>
    ✓ Ihr Gerät (PC/Notebook)<br>
    ✓ Netzteil<br>
    ✓ Wichtige Zugangsdaten/Passwörter aufschreiben
</small></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 13:56:32', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('62', '19', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #19 - Anna10 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000019</li>
    <li><strong>Terminart:</strong> Walk-in</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna10 Nas</li>
    <li><strong>E-Mail:</strong> anna10@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26000 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=19\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 13:56:35', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('63', '20', 'confirmation', 'anna8@pc-wittfoot.de', 'Terminbestätigung #20 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna8 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000020</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>



<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit online verwalten:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=7dcf2066214deb4d70d2273fc031f3db6ea39688e7c2f064610a829ce0a48061\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    Hier können Sie:<br>
    ✓ Ihre Buchungsdetails einsehen<br>
    ✓ Den Termin ändern (bis 48h vorher)<br>
    ✓ Den Termin stornieren (bis 24h vorher)
</small></p>

<hr>

<h3>Bitte mitbringen</h3>
<p><small>
    Evt. nach Absprache:<br>
    ✓ Ihr Gerät (PC/Notebook)<br>
    ✓ Netzteil<br>
    ✓ Wichtige Zugangsdaten/Passwörter aufschreiben
</small></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:11:05', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('64', '20', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #20 - Anna8 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000020</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna8 Nas</li>
    <li><strong>E-Mail:</strong> anna8@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> +49 1234</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=20\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:11:06', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('65', '21', 'confirmation', 'anna6@pc-wittfoot.de', 'Terminbestätigung #21 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna6 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000021</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Installation</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>

Ihre Anmerkungen:
Doppeltermin


<hr>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin jederzeit online verwalten:</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=7ff18a73df1df88e0abde4c785beb0fd137c046d8c4e9da4c85be1f25eb83044\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><small>
    Hier können Sie:<br>
    ✓ Ihre Buchungsdetails einsehen<br>
    ✓ Den Termin ändern (bis 48h vorher)<br>
    ✓ Den Termin stornieren (bis 24h vorher)
</small></p>

<hr>

<h3>Bitte mitbringen</h3>
<p><small>
    Evt. nach Absprache:<br>
    ✓ Ihr Gerät (PC/Notebook)<br>
    ✓ Netzteil<br>
    ✓ Wichtige Zugangsdaten/Passwörter aufschreiben
</small></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:19:04', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('66', '21', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #21 - Anna6 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000021</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Installation</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Walk-in ab 14:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna6 Nas</li>
    <li><strong>E-Mail:</strong> anna6@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

Ihre Anmerkungen:
Doppeltermin


<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=21\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:19:05', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('67', '22', 'confirmation', 'Anna5@pc-wittfoot.de', 'Terminbestätigung #22 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna5 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000022</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Installation</li>
    <li><strong>Datum:</strong> Donnerstag, 08. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>

Ihre Anmerkungen:
jojojo


<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=f5d734f33a66b18b591635d135edb9e1253aa7874ffc6f18dd52844e9499d541\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:39:47', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('68', '22', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #22 - Anna5 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000022</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Installation</li>
    <li><strong>Datum:</strong> Donnerstag, 08. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna5 Nas</li>
    <li><strong>E-Mail:</strong> Anna5@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annasweg 1<br>
    26123 Oldenburg
</p>

Ihre Anmerkungen:
jojojo


<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=22\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:39:48', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('69', '23', 'confirmation', 'anna10@pc-wittfoot.de', 'Terminbestätigung #23 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin anna10 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000023</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Diagnose</li>
    <li><strong>Datum:</strong> Donnerstag, 08. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>

Ihre Anmerkungen:
Doppelbuchung


<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=417a54cbc0e69f3a99b9c17297a52210002dbabea76827229eafbb03c3127350\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:42:02', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('70', '23', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #23 - anna10 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000023</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Diagnose</li>
    <li><strong>Datum:</strong> Donnerstag, 08. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> anna10 Nas</li>
    <li><strong>E-Mail:</strong> anna10@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

Ihre Anmerkungen:
Doppelbuchung


<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=23\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 14:42:02', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('71', '24', 'confirmation', 'anna20@pc-wittfoot.de', 'Terminbestätigung #24 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna20 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000024</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Reparatur</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>

Ihre Anmerkungen:
Festnetz-Test


<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=425702bd58b8a896c2d080a19b56ddf0ec484a835583dbfaf2f32d4ef0d179da\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:01:16', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('72', '24', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #24 - Anna20 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000024</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Reparatur</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna20 Nas</li>
    <li><strong>E-Mail:</strong> anna20@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> +49 1234</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>

Ihre Anmerkungen:
Festnetz-Test


<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=24\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:01:16', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('73', '25', 'confirmation', 'anna21@pc-wittfoot.de', 'Terminbestätigung #25 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna21 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000025</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=0b61e8774cf907db66b7082d78969288753f45228cb95ab34915b9b1f737cdb0\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:04:36', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('74', '25', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #25 - Anna21 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000025</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Flexible Ankunft zwischen 14:00-17:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna21 Nas</li>
    <li><strong>E-Mail:</strong> anna21@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=25\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:04:36', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('75', '26', 'confirmation', 'anna22@pc-wittfoot.de', 'Terminbestätigung #26 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna22 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000026</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Diagnose</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 16:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=0a43d2d29a8439d751e078c84a507de464a17e86b74ec8e74d9b05ec87e82f9b\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:08:53', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('76', '26', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #26 - Anna22 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000026</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Diagnose</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 16:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna22 Nas</li>
    <li><strong>E-Mail:</strong> anna22@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=26\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:08:54', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('77', '24', 'confirmation', 'anna20@pc-wittfoot.de', 'Terminbestätigung #24 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna20 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000024</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Reparatur</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 14:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>

Ihre Anmerkungen:
Festnetz-Test


<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=425702bd58b8a896c2d080a19b56ddf0ec484a835583dbfaf2f32d4ef0d179da\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:13:25', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('78', '25', 'confirmation', 'anna21@pc-wittfoot.de', 'Terminbestätigung #25 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna21 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000025</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Sonstiges</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 15:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=0b61e8774cf907db66b7082d78969288753f45228cb95ab34915b9b1f737cdb0\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:13:26', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('79', '27', 'confirmation', 'anna23@pc-wittfoot.de', 'Terminbestätigung #27 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna23 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000027</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Reparatur</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 14:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=e958dc5a3f6a674a7e6810328eef95077c010e2d64c7584d1bdb208786ed83d8\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:15:58', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('80', '27', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #27 - Anna23 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000027</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Reparatur</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 14:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna23 Nas</li>
    <li><strong>E-Mail:</strong> anna23@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=27\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:15:59', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('81', '28', 'confirmation', 'anna24@pc-wittfoot.de', 'Terminbestätigung #28 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna24 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000028</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Reparatur</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 15:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=a1166ad3bf29c6f72060abb405dd77e9a87141137acc9c7c4f7f2a43a3d9d236\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:41:42', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('82', '28', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #28 - Anna24 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000028</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Reparatur</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 15:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna24 Nas</li>
    <li><strong>E-Mail:</strong> anna24@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> Meine Firma</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=28\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:41:42', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('83', '29', 'confirmation', 'anna25@pc-wittfoot.de', 'Terminbestätigung #29 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna25 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000029</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Diagnose</li>
    <li><strong>Datum:</strong> Samstag, 10. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 14:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=246e4a23e26a2a1665b1c5c9e7eb6d3d8ea5952f59f20a829153dea66d1996c6\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:45:44', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('84', '29', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #29 - Anna25 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000029</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Diagnose</li>
    <li><strong>Datum:</strong> Samstag, 10. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 14:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna25 Nas</li>
    <li><strong>E-Mail:</strong> anna25@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=29\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 15:45:45', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('85', '30', 'confirmation', 'anna26@pc-wittfoot.de', 'Terminbestätigung #30 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna26 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000030</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Samstag, 10. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 13:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 12:00-16:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=1ab1e1212cd9d6403bd61da97620461df2e639c73db69965d190fe096bd2aa1d\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 18:34:31', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('86', '30', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #30 - Anna26 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000030</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Samstag, 10. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 13:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna26 Nas</li>
    <li><strong>E-Mail:</strong> anna26@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=30\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 18:34:31', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('87', '31', 'confirmation', 'anna26@pc-wittfoot.de', 'Terminbestätigung #31 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna27 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000031</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<p></p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=72cf2d176340fec7591b7056e32ee981338958698d6be745305b53a519ee95cc\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:12:21', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('88', '31', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #31 - Anna27 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000031</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna27 Nas</li>
    <li><strong>E-Mail:</strong> anna26@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=31\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:12:21', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('89', '32', 'confirmation', 'anna28@pc-wittfoot.de', 'Terminbestätigung #32 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna27 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000032</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 16:00 Uhr</li>
</ul>

<p>Sie können flexibel zwischen 14:00-17:00 Uhr vorbeikommen. Die empfohlene Zeit hilft uns, Wartezeiten zu minimieren.</p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=a3026edb515cea59967ad772e4fcbee0dfb0af8a61382c42a98241f2ecd69fec\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:16:55', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('90', '32', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #32 - Anna27 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000032</li>
    <li><strong>Terminart:</strong> Ich komme vorbei</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> Empfohlene Ankunftszeit: 16:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna27 Nas</li>
    <li><strong>E-Mail:</strong> anna28@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=32\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:16:56', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('91', '33', 'confirmation', 'anna26@pc-wittfoot.de', 'Terminbestätigung #33 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna27 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000033</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<p></p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=d0a55a2737705003a4292544b3fc5ba53111e82f67fba1ede73042b700e18bf1\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:22:37', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('92', '33', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #33 - Anna27 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000033</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 12:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna27 Nas</li>
    <li><strong>E-Mail:</strong> anna26@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    An 1<br>
    26123 Ol
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=33\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:22:38', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('93', '34', 'confirmation', 'anna28@pc-wittfoot.de', 'Terminbestätigung #34 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna28 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000034</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:30 Uhr</li>
</ul>

<p></p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=e491e0a5eab4ae5e953dad88954bd656a029782e62887b3680aff5a10a290325\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:41:28', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('94', '34', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #34 - Anna28 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000034</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Verkauf</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:30 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna28 Nas</li>
    <li><strong>E-Mail:</strong> anna28@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 12234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=34\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:41:29', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('95', '35', 'confirmation', 'anna26@pc-wittfoot.de', 'Terminbestätigung #35 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna26 Nas,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000035</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<p></p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=6a686f9f2cd0d9bcbb14fd1afb91d6988ff394b9d35d380feaa432fdd71df913\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:43:50', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('96', '35', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #35 - Anna26 Nas', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000035</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Freitag, 09. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna26 Nas</li>
    <li><strong>E-Mail:</strong> anna26@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Annanasweg 1<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=35\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:43:50', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('97', '36', 'confirmation', 'langetexte@pc-wittfoot.de', 'Terminbestätigung #36 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Anna Frieda Marion Ursel Nicole Linde Sabine haste nicht gesehen und was weiß ich noch Ein langer Nachname muss her damit ich das hier testen kan und weiß was mit soch langen Texten passiert,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000036</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<p></p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=bac058222677f206db37389555c062d661a7b3d22c363fdd53358d455ae6b0f5\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:56:02', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('98', '36', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #36 - Anna Frieda Marion Ursel Nicole Linde Sabine haste nicht gesehen und was weiß ich noch Ein langer Nachname muss her damit ich das hier testen kan und weiß was mit soch langen Texten passiert', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000036</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 06. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Anna Frieda Marion Ursel Nicole Linde Sabine haste nicht gesehen und was weiß ich noch Ein langer Nachname muss her damit ich das hier testen kan und weiß was mit soch langen Texten passiert</li>
    <li><strong>E-Mail:</strong> langetexte@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    So eine lange Straße gibt es bestimmt wie der Name jedoch keinen Straßennamen der so lang ist 123456<br>
    26123 Oldenburg
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=36\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 19:56:03', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('99', '37', 'confirmation', 'test@test.de', 'Terminbestätigung #37 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin <script>alert(1)</script> Test,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000037</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 20. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<p></p>



<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=a47f3a8498734af89d0a19cd6500f9158b32fd983196743796a39de262a0de76\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 20:00:36', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('100', '37', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #37 - <script>alert(1)</script> Test', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000037</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 20. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> <script>alert(1)</script> Test</li>
    <li><strong>E-Mail:</strong> test@test.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 1234567890</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Test 1<br>
    12345 Berlin
</p>



<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=37\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-04 20:00:37', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('101', '38', 'confirmation', 'hans@pc-wittfoot.de', 'Terminbestätigung #38 - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin Hans Hammel,</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> 000038</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 13. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<p></p>

Ihre Anmerkungen:
geht nicht mehr an


<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"http://localhost:8000/termin/verwalten?token=14f601b4b83fc61d2a6cd13c118f02f8d4ff3319d963d70d4d6d8946cbdd1232\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-10 22:54:46', 'sent', NULL);
INSERT INTO `email_log` (`id`, `booking_id`, `email_type`, `recipient_email`, `subject`, `body`, `sent_at`, `status`, `error_message`) VALUES ('102', '38', 'booking_notification', 'admin@pc-wittfoot.de', 'Neue Terminbuchung #38 - Hans Hammel', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> 000038</li>
    <li><strong>Terminart:</strong> Fester Termin</li>
    <li><strong>Dienstleistung:</strong> Beratung</li>
    <li><strong>Datum:</strong> Dienstag, 13. Januar 2026</li>
    <li><strong>Uhrzeit:</strong> 11:00 Uhr</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> Hans Hammel</li>
    <li><strong>E-Mail:</strong> hans@pc-wittfoot.de</li>
    <li><strong>Telefon (Mobil):</strong> +49 17888777666</li>
    <li><strong>Telefon (Fest):</strong> -</li>
    <li><strong>Firma:</strong> -</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    Hammelgang 12<br>
    26123 Oldenburg
</p>

Ihre Anmerkungen:
geht nicht mehr an


<hr>

<p><a href=\"http://localhost:8000/admin/booking-detail?id=38\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>

Mit freundlichen Grüßen<br />
<br />
Ihr Systemhäuschen<br />
IT-Fachbetrieb mit Herz<br />
<br />
<br />
PC-Wittfoot UG (haftungsbeschränkt)<br />
Melkbrink 61<br />
26121 Oldenburg<br />
Deutschland<br />
<br />
Handelsregister: HRB 215517, Amtsgericht Oldenburg<br />
USt-ID-Nr.: DE331470711<br />
Geschäftsführung: Nicole Wittfoot<br />
<br />
Öffnungszeiten:<br />
Montag:	geschlossen<br />
Dienstag:	14:00 – 17:00 Uhr<br />
Mittwoch:	14:00 – 17:00 Uhr<br />
Donnerstag:	14:00 – 17:00 Uhr<br />
Freitag:	14:00 – 17:00 Uhr<br />
Samstag:	12:00 – 16:00 Uhr<br />
<br />
Telefon: +49 441 40576020<br />
E-Mail: info@pc-wittfoot.de<br />
Website/Shop:', '2026-01-10 22:54:46', 'sent', NULL);

-- Tabelle: email_signature
DROP TABLE IF EXISTS `email_signature`;
CREATE TABLE `email_signature` (
  `id` int NOT NULL AUTO_INCREMENT,
  `signature_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `email_signature` (`id`, `signature_text`, `updated_at`) VALUES ('1', 'Mit freundlichen Grüßen

Ihr Systemhäuschen
IT-Fachbetrieb mit Herz


PC-Wittfoot UG (haftungsbeschränkt)
Melkbrink 61
26121 Oldenburg
Deutschland

Handelsregister: HRB 215517, Amtsgericht Oldenburg
USt-ID-Nr.: DE331470711
Geschäftsführung: Nicole Wittfoot

Öffnungszeiten:
Montag:	geschlossen
Dienstag:	14:00 – 17:00 Uhr
Mittwoch:	14:00 – 17:00 Uhr
Donnerstag:	14:00 – 17:00 Uhr
Freitag:	14:00 – 17:00 Uhr
Samstag:	12:00 – 16:00 Uhr

Telefon: +49 441 40576020
E-Mail: info@pc-wittfoot.de
Website/Shop:', '2026-01-03 19:29:33');

-- Tabelle: email_templates
DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `template_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `placeholders` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON array of available placeholders',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template_type` (`template_type`),
  KEY `idx_type` (`template_type`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('1', 'confirmation', 'Buchung - Bestätigung (Kunde)', 'Terminbestätigung #{booking_id} - PC-Wittfoot UG', '<h2>Terminbestätigung</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>vielen Dank für Ihre Terminbuchung!</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>{flexibility_note}</p>

{customer_notes_section}

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin online verwalten (umbuchen oder stornieren):</p>
<p><a href=\"{manage_link}\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p><strong>Wichtig:</strong></p>
<ul>
    <li>Terminänderungen sind bis 48 Stunden vorher möglich</li>
    <li>Stornierungen sind bis 24 Stunden vorher möglich</li>
    <li>Bei kurzfristigeren Änderungen kontaktieren Sie uns bitte telefonisch</li>
</ul>

<p>Wir freuen uns auf Ihren Besuch!</p>', '[\"customer_firstname\", \"customer_lastname\", \"booking_id\", \"booking_type_label\", \"service_type_label\", \"booking_date_formatted\", \"booking_time_formatted\", \"customer_notes_section\"]', '1', '2026-01-01 15:32:07', '2026-01-04 14:35:39');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('2', 'reminder_24h', 'Buchung - Erinnerung 24h (Kunde)', 'Erinnerung: Ihr Termin morgen - PC-Wittfoot UG', '<h2>Terminerinnerung</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>dies ist eine freundliche Erinnerung an Ihren morgigen Termin:</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>{flexibility_note}</p>

<p><strong>Hinweis:</strong> Stornierungen sind nur noch bis heute Abend möglich. Bei kurzfristigen Änderungen kontaktieren Sie uns bitte telefonisch.</p>

<p>Wir freuen uns auf Ihren Besuch!</p>', '[\"customer_firstname\", \"customer_lastname\", \"booking_date_formatted\", \"booking_time_formatted\", \"service_type_label\"]', '1', '2026-01-01 15:32:07', '2026-01-04 14:35:39');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('3', 'reminder_1h', 'Buchung - Erinnerung 1h (Kunde)', 'Erinnerung: Ihr Termin heute - PC-Wittfoot UG', '<h2>Ihr Termin steht bevor</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>Ihr Termin bei PC-Wittfoot findet in Kürze statt:</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>{flexibility_note}</p>

<p>Wir freuen uns, Sie gleich bei uns begrüßen zu dürfen!</p>', '[\"customer_firstname\", \"customer_lastname\", \"booking_time_formatted\", \"service_type_label\"]', '1', '2026-01-01 15:32:07', '2026-01-04 14:35:39');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('4', 'order_confirmation', 'Shop - Bestätigung (Kunde)', 'Bestellbestätigung #{order_number} - PC-Wittfoot UG', '<h2>Bestellbestätigung</h2>

<p>Hallo {customer_firstname} {customer_lastname},</p>

<p>vielen Dank für Ihre Bestellung bei PC-Wittfoot UG!</p>

<h3>Bestelldetails</h3>
<ul>
    <li><strong>Bestellnummer:</strong> {order_number}</li>
    <li><strong>Bestelldatum:</strong> {order_date}</li>
</ul>

<hr>

<h3>Ihre Bestellung</h3>
{order_items}

<p>
    <strong>Zwischensumme:</strong> {order_subtotal}<br>
    <strong>MwSt (19%):</strong> {order_tax}<br>
    <strong>Gesamt:</strong> {order_total}
</p>

<p>
    <strong>Lieferart:</strong> {delivery_method}<br>
    <strong>Zahlungsart:</strong> {payment_method}
</p>

{invoice_link_section}

<hr>

<p>Wir melden uns in Kürze bei Ihnen mit weiteren Details.</p>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Mit freundlichen Grüßen<br>
Ihr Team von PC-Wittfoot UG</p>', NULL, '1', '2026-01-01 21:50:08', '2026-01-04 11:10:28');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('5', 'order_notification', 'Shop - Neue Bestellung (Admin)', 'Neue Bestellung #{order_number} im Shop', '<h2>Neue Bestellung</h2>

<p>Eine neue Bestellung ist eingegangen.</p>

<h3>Bestelldetails</h3>
<ul>
    <li><strong>Bestellnummer:</strong> {order_number}</li>
    <li><strong>Bestelldatum:</strong> {order_date}</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> {customer_firstname} {customer_lastname}</li>
    {customer_company_line}
    <li><strong>E-Mail:</strong> {customer_email}</li>
    {customer_phone_line}
    <li><strong>Adresse:</strong> {customer_address}</li>
</ul>

<hr>

<h3>Bestellpositionen</h3>
{order_items}

<p><strong>Gesamt:</strong> {order_total}</p>

<p>
    <strong>Lieferart:</strong> {delivery_method}<br>
    <strong>Zahlungsart:</strong> {payment_method}
</p>

{order_notes_section}
{invoice_link_section}

<hr>

<p><a href=\"{admin_order_link}\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Bestellung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>', NULL, '1', '2026-01-01 21:50:08', '2026-01-04 10:54:04');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('6', 'booking_notification', 'Buchung - Neue Buchung (Admin)', 'Neue Terminbuchung #{booking_id} - {customer_firstname} {customer_lastname}', '<h2>Neue Terminbuchung</h2>

<p>Es wurde ein neuer Termin gebucht.</p>

<h3>Termindetails</h3>
<ul>
    <li><strong>Buchungs-ID:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Kundendaten</h3>
<ul>
    <li><strong>Name:</strong> {customer_firstname} {customer_lastname}</li>
    <li><strong>E-Mail:</strong> {customer_email}</li>
    <li><strong>Telefon (Mobil):</strong> {customer_phone}</li>
    <li><strong>Telefon (Fest):</strong> {customer_phone_landline}</li>
    <li><strong>Firma:</strong> {customer_company}</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    {customer_street} {customer_house_number}<br>
    {customer_postal_code} {customer_city}
</p>

{customer_notes_section}

<hr>

<p><a href=\"{admin_link}\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>', NULL, '1', '2026-01-03 19:17:49', '2026-01-04 10:54:04');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('8', 'cancellation', 'Terminbuchung - Stornierungsbestätigung', 'Stornierung bestätigt - Buchung #{booking_id}', '<h2>Stornierung bestätigt</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>Ihre Terminbuchung wurde erfolgreich storniert.</p>

<h3>Stornierte Buchung</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>Sie erhalten keine weitere Bestätigung.</p>

<hr>

<h3>Neuen Termin buchen</h3>
<p>Sie können jederzeit einen neuen Termin online buchen:</p>
<p><a href=\"http://localhost:8000/termin\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Neuen Termin buchen</a></p>

<hr>

<p>
    Bei Fragen erreichen Sie uns unter:<br>
    <strong>PC-Wittfoot UG</strong><br>
    E-Mail: info@pc-wittfoot.de<br>
    Telefon: +49 441 40576020
</p>

<p>Wir hoffen, Sie bald wieder begrüßen zu dürfen!</p>', NULL, '1', '2026-01-04 01:19:38', '2026-01-04 10:54:04');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('9', 'reschedule', 'Terminbuchung - Terminänderung (Kunde)', 'Terminänderung bestätigt - {booking_number}', '<h2>Terminänderung bestätigt</h2>

<p>Moin {customer_firstname} {customer_lastname},</p>

<p>Ihre Terminänderung wurde erfolgreich gespeichert.</p>

<h3>Alte Termindaten</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neue Termindaten</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<p>{flexibility_note}</p>

<h3>Termin verwalten</h3>
<p>Sie können Ihren Termin weiterhin online verwalten:</p>
<p><a href=\"{manage_link}\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Termin verwalten</a></p>

<p>Wir freuen uns auf Ihren Besuch!</p>', NULL, '1', '2026-01-04 09:33:32', '2026-01-04 14:35:39');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('10', 'admin_reschedule', 'Admin - Terminänderung (Benachrichtigung)', 'Terminänderung: Buchung {booking_number}', '<h2>Terminänderung</h2>

<p>Ein Kunde hat seinen Termin geändert.</p>

<h3>Buchungsdetails:</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Kunde:</strong> {customer_firstname} {customer_lastname}</li>
    <li><strong>Email:</strong> {customer_email}</li>
    <li><strong>Telefon:</strong> {customer_phone}</li>
</ul>

<h3>Alter Termin:</h3>
<ul>
    <li><strong>Datum:</strong> {old_date}</li>
    <li><strong>Uhrzeit:</strong> {old_time}</li>
</ul>

<h3>Neuer Termin:</h3>
<ul>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Kundenadresse:</h3>
<p>
    {customer_street} {customer_house_number}<br>
    {customer_postal_code} {customer_city}
</p>

<p><a href=\"{admin_link}\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>', NULL, '1', '2026-01-04 10:11:15', '2026-01-04 10:11:15');
INSERT INTO `email_templates` (`id`, `template_type`, `template_name`, `subject`, `body`, `placeholders`, `is_active`, `created_at`, `updated_at`) VALUES ('11', 'admin_cancellation', 'Admin - Stornierung (Benachrichtigung)', 'Stornierung: Buchung {booking_number}', '<h2>Terminbuchung storniert</h2>

<p>Ein Kunde hat seinen Termin storniert.</p>

<h3>Buchungsdetails</h3>
<ul>
    <li><strong>Buchungsnummer:</strong> {booking_number}</li>
    <li><strong>Kunde:</strong> {customer_firstname} {customer_lastname}</li>
    <li><strong>Email:</strong> {customer_email}</li>
    <li><strong>Telefon:</strong> {customer_phone}</li>
</ul>

<h3>Stornierter Termin</h3>
<ul>
    <li><strong>Terminart:</strong> {booking_type}</li>
    <li><strong>Dienstleistung:</strong> {service_type}</li>
    <li><strong>Datum:</strong> {booking_date}</li>
    <li><strong>Uhrzeit:</strong> {booking_time}</li>
</ul>

<h3>Kundenadresse</h3>
<p>
    {customer_street} {customer_house_number}<br>
    {customer_postal_code} {customer_city}
</p>

<p><a href=\"{admin_link}\" style=\"background-color: #8BC34A; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Buchung im Admin-Bereich ansehen</a></p>

<hr>

<p><small>Diese Benachrichtigung wurde automatisch generiert.</small></p>', NULL, '1', '2026-01-04 10:59:45', '2026-01-04 10:59:45');

-- Tabelle: login_attempts
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attempted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `success` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_ip_time` (`ip_address`,`attempted_at`),
  KEY `idx_username_time` (`username`,`attempted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `attempted_at`, `success`) VALUES ('19', '127.0.0.1', 'admin@pc-wittfoot.de', '2026-01-10 21:28:31', '1');
INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `attempted_at`, `success`) VALUES ('20', '127.0.0.1', 'admin@pc-wittfoot.de', '2026-01-10 22:55:16', '1');
INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `attempted_at`, `success`) VALUES ('21', '127.0.0.1', 'admin@pc-wittfoot.de', '2026-01-10 23:43:38', '1');
INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `attempted_at`, `success`) VALUES ('22', '127.0.0.1', 'admin@pc-wittfoot.de', '2026-01-11 00:22:11', '1');
INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `attempted_at`, `success`) VALUES ('23', '127.0.0.1', 'admin@pc-wittfoot.de', '2026-01-11 01:11:34', '1');

-- Tabelle: order_items
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('1', '1', '1', 'Dell Latitude E7470', 'DELL-E7470-001', '1', '449.00', '449.00', '2025-12-31 01:57:57');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('2', '1', '9', 'HP E273q 27\"', 'HP-27-001', '1', '249.00', '249.00', '2025-12-31 01:57:57');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('3', '1', '10', 'Logitech MX Master 3', 'LOGI-MX-001', '1', '89.00', '89.00', '2025-12-31 01:57:57');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('4', '1', '1', 'Dell Latitude E7470', 'DELL-E7470-001', '1', '449.00', '449.00', '2025-12-31 02:06:38');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('5', '1', '9', 'HP E273q 27\"', 'HP-27-001', '1', '249.00', '249.00', '2025-12-31 02:06:38');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('6', '1', '10', 'Logitech MX Master 3', 'LOGI-MX-001', '1', '89.00', '89.00', '2025-12-31 02:06:38');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('7', '6', '11', 'Cherry KC 6000 Slim', NULL, '1', '39.00', '39.00', '2026-01-01 21:18:17');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('8', '6', '4', 'Dell Precision 5520', NULL, '1', '749.00', '749.00', '2026-01-01 21:18:17');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('9', '7', '9', 'HP E273q 27\"', NULL, '1', '249.00', '249.00', '2026-01-01 21:32:17');
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES ('10', '8', '9', 'HP E273q 27\"', NULL, '1', '249.00', '249.00', '2026-01-01 22:20:14');

-- Tabelle: orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_housenumber` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_zip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_housenumber` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_zip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_method` enum('billing','pickup','shipping') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hellocash_customer_id` int DEFAULT NULL,
  `hellocash_invoice_id` int DEFAULT NULL,
  `hellocash_invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hellocash_invoice_link` text COLLATE utf8mb4_unicode_ci,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('paypal','sumup','vorkasse','prepayment') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `order_status` enum('pending','new','processing','shipped','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `order_notes` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_status` (`order_status`),
  KEY `idx_payment` (`payment_status`),
  KEY `idx_email` (`customer_email`),
  KEY `idx_created` (`created_at`),
  KEY `idx_hellocash_customer` (`hellocash_customer_id`),
  KEY `idx_hellocash_invoice` (`hellocash_invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Shop-Bestellungen mit HelloCash-Integration';

INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone`, `customer_street`, `customer_housenumber`, `customer_zip`, `customer_city`, `shipping_firstname`, `shipping_lastname`, `shipping_street`, `shipping_housenumber`, `shipping_zip`, `shipping_city`, `delivery_method`, `hellocash_customer_id`, `hellocash_invoice_id`, `hellocash_invoice_number`, `hellocash_invoice_link`, `shipping_address`, `billing_address`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `order_notes`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('1', 'ORD-2025-0001', 'Max Mustermann', 'max@example.com', NULL, NULL, NULL, '0123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Musterstraße 123
12345 Musterstadt', 'Musterstraße 123
12345 Musterstadt', '778.00', 'vorkasse', 'paid', 'completed', NULL, NULL, NULL, NULL, '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone`, `customer_street`, `customer_housenumber`, `customer_zip`, `customer_city`, `shipping_firstname`, `shipping_lastname`, `shipping_street`, `shipping_housenumber`, `shipping_zip`, `shipping_city`, `delivery_method`, `hellocash_customer_id`, `hellocash_invoice_id`, `hellocash_invoice_number`, `hellocash_invoice_link`, `shipping_address`, `billing_address`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `order_notes`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('3', 'ORD-2026-9786', 'Test Testa', 'testi@testa.de', 'Test', 'Testa', '', '017877777777', 'Testweg', '4', '26123', 'Oldenburg', NULL, NULL, NULL, NULL, NULL, NULL, 'pickup', '18', NULL, NULL, NULL, 'Test Testa
Testweg 4
26123 Oldenburg', NULL, '449.00', 'prepayment', 'pending', 'pending', '', '377.31', '71.69', '449.00', '2026-01-01 20:22:40', '2026-01-01 20:22:40');
INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone`, `customer_street`, `customer_housenumber`, `customer_zip`, `customer_city`, `shipping_firstname`, `shipping_lastname`, `shipping_street`, `shipping_housenumber`, `shipping_zip`, `shipping_city`, `delivery_method`, `hellocash_customer_id`, `hellocash_invoice_id`, `hellocash_invoice_number`, `hellocash_invoice_link`, `shipping_address`, `billing_address`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `order_notes`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('4', 'ORD-2026-2593', 'Mada Mader', 'madama@mader.de', 'Mada', 'Mader', '', '01956765432', 'Muselstr.', '10', '26123', 'Oldenburg', NULL, NULL, NULL, NULL, NULL, NULL, 'pickup', '19', NULL, NULL, NULL, 'Mada Mader
Muselstr. 10
26123 Oldenburg', NULL, '449.00', 'prepayment', 'pending', 'pending', '', '377.31', '71.69', '449.00', '2026-01-01 20:42:58', '2026-01-01 20:42:58');
INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone`, `customer_street`, `customer_housenumber`, `customer_zip`, `customer_city`, `shipping_firstname`, `shipping_lastname`, `shipping_street`, `shipping_housenumber`, `shipping_zip`, `shipping_city`, `delivery_method`, `hellocash_customer_id`, `hellocash_invoice_id`, `hellocash_invoice_number`, `hellocash_invoice_link`, `shipping_address`, `billing_address`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `order_notes`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('5', 'ORD-2026-3449', 'Thoma Thomsen', 'thoma@thomsen.de', 'Thoma', 'Thomsen', '', '017866666666', 'Gasse', '5', '26123', 'Oldenburg', NULL, NULL, NULL, NULL, NULL, NULL, 'pickup', '20', NULL, NULL, NULL, 'Thoma Thomsen
Gasse 5
26123 Oldenburg', NULL, '817.00', 'prepayment', 'pending', 'pending', '', '686.55', '130.45', '817.00', '2026-01-01 20:49:10', '2026-01-01 20:49:10');
INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone`, `customer_street`, `customer_housenumber`, `customer_zip`, `customer_city`, `shipping_firstname`, `shipping_lastname`, `shipping_street`, `shipping_housenumber`, `shipping_zip`, `shipping_city`, `delivery_method`, `hellocash_customer_id`, `hellocash_invoice_id`, `hellocash_invoice_number`, `hellocash_invoice_link`, `shipping_address`, `billing_address`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `order_notes`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('6', 'ORD-2026-3544', 'Mathe Willhelmsen', 'st@wl.de', 'Mathe', 'Willhelmsen', '', '', 'Eichenweg', '7', '26123', 'Oldenburg', 'Lisa', 'Fitz', 'Eumelstr.', '8', '24123', 'Wulfsburg', 'shipping', '21', '190217940', '1', NULL, 'Lisa Fitz
Eumelstr. 8
24123 Wulfsburg', NULL, '788.00', 'paypal', 'pending', 'pending', '', '662.18', '125.82', '788.00', '2026-01-01 21:18:17', '2026-01-01 21:18:17');
INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone`, `customer_street`, `customer_housenumber`, `customer_zip`, `customer_city`, `shipping_firstname`, `shipping_lastname`, `shipping_street`, `shipping_housenumber`, `shipping_zip`, `shipping_city`, `delivery_method`, `hellocash_customer_id`, `hellocash_invoice_id`, `hellocash_invoice_number`, `hellocash_invoice_link`, `shipping_address`, `billing_address`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `order_notes`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('7', 'ORD-2026-3593', 'Mathe Wilhelmsen', 'st@wl.de', 'Mathe', 'Wilhelmsen', '', '', 'Eichenweg', '7', '26123', 'Oldenburg', NULL, NULL, NULL, NULL, NULL, NULL, 'pickup', '21', '190218128', '2', NULL, 'Mathe Wilhelmsen
Eichenweg 7
26123 Oldenburg', NULL, '249.00', 'prepayment', 'pending', 'pending', '', '209.24', '39.76', '249.00', '2026-01-01 21:32:17', '2026-01-01 21:32:18');
INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_firstname`, `customer_lastname`, `customer_company`, `customer_phone`, `customer_street`, `customer_housenumber`, `customer_zip`, `customer_city`, `shipping_firstname`, `shipping_lastname`, `shipping_street`, `shipping_housenumber`, `shipping_zip`, `shipping_city`, `delivery_method`, `hellocash_customer_id`, `hellocash_invoice_id`, `hellocash_invoice_number`, `hellocash_invoice_link`, `shipping_address`, `billing_address`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `order_notes`, `subtotal`, `tax`, `total`, `created_at`, `updated_at`) VALUES ('8', 'ORD-2026-7918', 'Maxi Faxi', 'ma@fa.de', 'Maxi', 'Faxi', '', '', 'Muselgang', '15', '26123', 'Oldenburg', NULL, NULL, NULL, NULL, NULL, NULL, 'billing', '22', '190218742', NULL, 'https://myhellocash.com/invoice/190218742?token=e3ffb9dd617416c86e601b21a8335d6df81eabda&preview=1', 'Maxi Faxi
Muselgang 15
26123 Oldenburg', NULL, '249.00', 'prepayment', 'pending', 'pending', 'Wohnun 3', '209.24', '39.76', '249.00', '2026-01-01 22:20:14', '2026-01-01 22:20:14');

-- Tabelle: password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  KEY `idx_token` (`token`),
  KEY `idx_expires` (`expires_at`),
  CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `password_reset_tokens` (`id`, `user_id`, `token`, `expires_at`, `used`, `ip_address`, `user_agent`, `created_at`, `used_at`) VALUES ('4', '1', 'a3053e926418670674f64886635605c407ed7061058410df12169bb376dd0586', '2026-01-01 18:48:14', '0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-01 17:48:14', NULL);

-- Tabelle: product_import_logs
DROP TABLE IF EXISTS `product_import_logs`;
CREATE TABLE `product_import_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int NOT NULL,
  `status` enum('running','completed','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'running',
  `imported_count` int DEFAULT '0' COMMENT 'Neue Produkte',
  `updated_count` int DEFAULT '0' COMMENT 'Aktualisierte Produkte',
  `skipped_count` int DEFAULT '0' COMMENT 'Übersprungene Zeilen',
  `error_count` int DEFAULT '0' COMMENT 'Fehlerhafte Zeilen',
  `log_details` json DEFAULT NULL COMMENT 'Detaillierte Fehler und Warnungen',
  `duration_seconds` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_supplier` (`supplier_id`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `product_import_logs_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle: products
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ean` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `short_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) DEFAULT '19.00',
  `stock` int DEFAULT '0',
  `category_id` int DEFAULT NULL,
  `brand` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_type` enum('neu','refurbished','gebraucht') COLLATE utf8mb4_unicode_ci DEFAULT 'refurbished',
  `warranty_months` int DEFAULT '24' COMMENT 'Garantie in Monaten',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` json DEFAULT NULL COMMENT 'Zusätzliche Produktbilder (Array mit bis zu 5 URLs)',
  `specifications` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `source` enum('csv_import','hellocash','manual') COLLATE utf8mb4_unicode_ci DEFAULT 'manual',
  `supplier_id` int DEFAULT NULL,
  `supplier_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_stock` int DEFAULT '0',
  `in_showroom` tinyint(1) DEFAULT '0',
  `free_shipping` tinyint(1) DEFAULT '0',
  `sync_with_hellocash` tinyint(1) DEFAULT '0',
  `last_csv_sync` datetime DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_category` (`category_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_brand` (`brand`),
  KEY `idx_condition` (`condition_type`),
  KEY `idx_slug` (`slug`),
  KEY `idx_source` (`source`),
  KEY `idx_supplier` (`supplier_id`),
  KEY `idx_showroom` (`in_showroom`),
  KEY `idx_free_shipping_products` (`free_shipping`),
  KEY `idx_ean` (`ean`),
  KEY `idx_tax_rate` (`tax_rate`),
  FULLTEXT KEY `idx_search` (`name`,`description`,`short_description`),
  CONSTRAINT `fk_products_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('1', 'DELL-E7470-001', NULL, 'Dell Latitude E7470', 'dell-latitude-e7470', 'Dell Latitude E7470 - Professioneller Business-Laptop. Intel Core i5-6300U, 8GB RAM, 256GB SSD, 14\" Full HD Display. Perfekt für Büroarbeit und mobiles Arbeiten.', 'Business-Laptop mit SSD', '449.00', '19.00', '8', '1', 'Dell', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('2', 'HP-840-G5-001', NULL, 'HP EliteBook 840 G5', 'hp-elitebook-840-g5', 'HP EliteBook 840 G5 - Top Business-Notebook mit Intel Core i7-8550U, 16GB RAM, 512GB SSD, 14\" Full HD IPS Display. Sehr guter Zustand.', 'Premium Business-Notebook', '599.00', '19.00', '4', '1', 'HP', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('3', 'LENOVO-T470-001', NULL, 'Lenovo ThinkPad T470', 'lenovo-thinkpad-t470', 'Lenovo ThinkPad T470 - Zuverlässiges Business-Notebook. Intel Core i5-7200U, 8GB RAM, 256GB SSD, 14\" Full HD, legendäre ThinkPad-Tastatur.', 'Robustes Arbeitsgerät', '379.00', '19.00', '6', '1', 'Lenovo', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '0', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('4', 'DELL-P5520-001', NULL, 'Dell Precision 5520', 'dell-precision-5520', 'Dell Precision 5520 - Mobile Workstation für anspruchsvolle Aufgaben. Intel Core i7-7820HQ, 16GB RAM, 512GB SSD, 15.6\" 4K Touch Display, NVIDIA Quadro.', 'Workstation-Laptop', '749.00', '19.00', '2', '1', 'Dell', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '0', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('5', 'HP-800-G3-001', NULL, 'HP EliteDesk 800 G3 SFF', 'hp-elitedesk-800-g3-sff', 'HP EliteDesk 800 G3 Small Form Factor - Platzsparender Büro-PC. Intel Core i5-7500, 8GB RAM, 256GB SSD, Windows 10 Pro. Ideal für Büroarbeit.', 'Kompakter Desktop-PC', '329.00', '19.00', '12', '2', 'HP', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('6', 'EXONE-BIZ-3000', NULL, 'exone Business 3000', 'exone-business-3000', 'exone Business 3000 - Brandneuer Desktop-PC von Extracomputer. Intel Core i5-13400, 16GB RAM, 512GB NVMe SSD, Windows 11 Pro. Made in Germany.', 'Neuer Office-PC', '799.00', '19.00', '5', '2', 'exone', 'neu', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '1', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('7', 'DELL-3060-001', NULL, 'Dell OptiPlex 3060 MT', 'dell-optiplex-3060-mt', 'Dell OptiPlex 3060 MiniTower - Solider Office-PC. Intel Core i3-8100, 8GB RAM, 256GB SSD, DVD-RW, Windows 10 Pro.', 'Zuverlässiger Tower-PC', '279.00', '19.00', '8', '2', 'Dell', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '0', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('8', 'BENQ-24-001', NULL, 'BenQ GW2480 24\"', 'benq-gw2480-24', 'BenQ GW2480 - 24\" Full HD Monitor (1920x1080), IPS-Panel, HDMI, DisplayPort, VGA. Augenschonende Technologie, perfekt für Büro.', 'Full HD Monitor', '159.00', '19.00', '6', '3', 'BenQ', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '0', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('9', 'HP-27-001', NULL, 'HP E273q 27\"', 'hp-e273q-27', 'HP E273q - 27\" QHD Monitor (2560x1440), IPS-Panel, höhenverstellbar, HDMI, DisplayPort, USB-Hub. Professioneller Business-Monitor.', 'QHD Business-Monitor', '249.00', '19.00', '2', '3', 'HP', 'refurbished', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '1', '2025-12-31 01:57:57', '2026-01-01 22:20:14');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('10', 'LOGI-MX-001', NULL, 'Logitech MX Master 3', 'logitech-mx-master-3', 'Logitech MX Master 3 - Ergonomische Premium-Maus mit präzisem Sensor, mehreren programmierbaren Tasten und USB-C Schnellladung.', 'Premium Maus', '89.00', '19.00', '15', '5', 'Logitech', 'neu', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '0', '2025-12-31 01:57:57', '2025-12-31 01:57:57');
INSERT INTO `products` (`id`, `sku`, `ean`, `name`, `slug`, `description`, `short_description`, `price`, `tax_rate`, `stock`, `category_id`, `brand`, `condition_type`, `warranty_months`, `image_url`, `images`, `specifications`, `is_active`, `source`, `supplier_id`, `supplier_name`, `supplier_stock`, `in_showroom`, `free_shipping`, `sync_with_hellocash`, `last_csv_sync`, `is_featured`, `created_at`, `updated_at`) VALUES ('11', 'CHERRY-KC6000', NULL, 'Cherry KC 6000 Slim', 'cherry-kc-6000-slim', 'Cherry KC 6000 Slim - Hochwertige flache Tastatur mit leisen Tasten, USB-Anschluss, deutsches Layout (QWERTZ).', 'Flache Tastatur', '39.00', '19.00', '10', '5', 'Cherry', 'neu', '24', NULL, NULL, NULL, '1', 'manual', NULL, NULL, '0', '0', '0', '0', NULL, '0', '2025-12-31 01:57:57', '2025-12-31 01:57:57');

-- Tabelle: sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `session_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  KEY `idx_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle: smtp_settings
DROP TABLE IF EXISTS `smtp_settings`;
CREATE TABLE `smtp_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `smtp_enabled` tinyint(1) DEFAULT '0' COMMENT '0 = PHP mail(), 1 = SMTP',
  `smtp_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'smtp.gmail.com',
  `smtp_port` int DEFAULT '587',
  `smtp_encryption` enum('tls','ssl','none') COLLATE utf8mb4_unicode_ci DEFAULT 'tls',
  `smtp_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `smtp_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `smtp_debug` int DEFAULT '0' COMMENT '0 = off, 1 = errors, 2 = verbose',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `smtp_settings` (`id`, `smtp_enabled`, `smtp_host`, `smtp_port`, `smtp_encryption`, `smtp_username`, `smtp_password`, `smtp_debug`, `updated_at`) VALUES ('1', '1', 'smtp.dcpserver.de', '465', 'ssl', 'info@pc-wittfoot.de', 'pcwI2021!', '0', '2026-01-02 23:12:26');

-- Tabelle: suppliers
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `csv_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL oder Pfad zur CSV-Datei',
  `csv_delimiter` char(1) COLLATE utf8mb4_unicode_ci DEFAULT ',' COMMENT 'CSV-Trennzeichen',
  `csv_encoding` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'UTF-8' COMMENT 'CSV-Encoding',
  `column_mapping` json DEFAULT NULL COMMENT 'Mapping CSV-Spalten zu Produkt-Feldern',
  `description_filter` text COLLATE utf8mb4_unicode_ci,
  `category_mapping` json DEFAULT NULL,
  `price_markup` decimal(5,2) DEFAULT '0.00' COMMENT 'Aufschlag in % auf Lieferanten-Preis',
  `free_shipping` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `last_import_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_free_shipping` (`free_shipping`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle: trusted_devices
DROP TABLE IF EXISTS `trusted_devices`;
CREATE TABLE `trusted_devices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `device_fingerprint` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_token` (`device_fingerprint`),
  UNIQUE KEY `device_fingerprint` (`device_fingerprint`),
  KEY `idx_user_device` (`user_id`,`device_fingerprint`),
  KEY `idx_expires` (`expires_at`),
  CONSTRAINT `trusted_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `trusted_devices` (`id`, `user_id`, `device_fingerprint`, `device_name`, `ip_address`, `user_agent`, `last_used_at`, `expires_at`, `created_at`) VALUES ('1', '1', '53f4c9b7c27c9f39fffbb7a136a2850fbc7a43c430fadcd960ead1a5d2748533', 'Firefox auf Windows', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-11 01:11:34', '2026-01-31 19:57:24', '2026-01-01 19:57:24');

-- Tabelle: user_2fa
DROP TABLE IF EXISTS `user_2fa`;
CREATE TABLE `user_2fa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `secret` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) DEFAULT '0',
  `backup_codes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `user_2fa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_2fa` (`id`, `user_id`, `secret`, `enabled`, `backup_codes`, `created_at`, `last_used_at`) VALUES ('1', '1', 'ZSK5N4CICGCKJZKJ', '1', '[\"6556-9190\",\"5410-3810\",\"5557-8245\",\"1115-3636\",\"3753-4744\",\"3172-8315\",\"3987-6998\",\"6826-0664\"]', '2026-01-01 17:54:17', NULL);

-- Tabelle: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','editor') COLLATE utf8mb4_unicode_ci DEFAULT 'editor',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_active` (`is_active`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `password_hash`, `email`, `full_name`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES ('1', 'admin', '$2y$10$JIwsPwMq04q6d0OwU30t.u5xQK40.5/mfzho4LqM3r5SAdwRL8IUq', 'admin@pc-wittfoot.de', 'Administrator', 'admin', '1', NULL, '2025-12-31 01:57:57', '2026-01-01 17:44:52');

SET FOREIGN_KEY_CHECKS=1;
