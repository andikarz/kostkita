-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for kostkita
CREATE DATABASE IF NOT EXISTS `kostkita` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kostkita`;

-- Dumping structure for table kostkita.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.admins: ~0 rows (approximately)
INSERT INTO `admins` (`id`, `nama`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin@example.com', '$2y$12$fkPCB0FNmyxB59xgnRPqFOPKfvONOcMR2AO0Cz5gu6YN.YIO6lLli', NULL, '2025-12-02 01:48:18', '2025-12-02 01:48:18');

-- Dumping structure for table kostkita.bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kost_id` bigint unsigned NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `lama_sewa` int NOT NULL DEFAULT '1',
  `harga_per_bulan` int NOT NULL,
  `pajak` int NOT NULL DEFAULT '20000',
  `total` int NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_pembayaran',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_kost_id_foreign` (`kost_id`),
  CONSTRAINT `bookings_kost_id_foreign` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.bookings: ~2 rows (approximately)
INSERT INTO `bookings` (`id`, `user_id`, `kost_id`, `tanggal_mulai`, `lama_sewa`, `harga_per_bulan`, `pajak`, `total`, `status`, `created_at`, `updated_at`) VALUES
	(1, 2, 4, '2025-12-11', 1, 900000, 20000, 920000, 'menunggu_pembayaran', '2025-12-11 06:45:02', '2025-12-11 06:45:02'),
	(2, 2, 4, '2025-12-11', 1, 900000, 20000, 920000, 'menunggu_pembayaran', '2025-12-11 06:45:19', '2025-12-11 06:45:19');

-- Dumping structure for table kostkita.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.cache: ~0 rows (approximately)

-- Dumping structure for table kostkita.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.cache_locks: ~0 rows (approximately)

-- Dumping structure for table kostkita.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table kostkita.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.jobs: ~0 rows (approximately)

-- Dumping structure for table kostkita.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.job_batches: ~0 rows (approximately)

-- Dumping structure for table kostkita.kosts
CREATE TABLE IF NOT EXISTS `kosts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('putra','putri','campur') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'campur',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `fasilitas` json DEFAULT NULL,
  `fasilitas_kmandi` json DEFAULT NULL,
  `fasilitas_umum` json DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Purwokerto',
  `harga_bulanan` int NOT NULL,
  `stok_kamar` int NOT NULL DEFAULT '0',
  `ukuran_kamar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `listrik_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_recommended` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kosts_owner_id_foreign` (`owner_id`),
  CONSTRAINT `kosts_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.kosts: ~3 rows (approximately)
INSERT INTO `kosts` (`id`, `owner_id`, `nama`, `jenis`, `deskripsi`, `fasilitas`, `fasilitas_kmandi`, `fasilitas_umum`, `alamat`, `kecamatan`, `kota`, `harga_bulanan`, `stok_kamar`, `ukuran_kamar`, `listrik_status`, `cover`, `is_recommended`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Kost Bujang', 'putra', 'Hunian ternyaman di purwokerto khususnya bagi kamu yang sedang merantau, kost bebas jam malam', '["Kipas", "Meja", "Kursi", "Kasur Single", "Lemari", "Jendela"]', '["K. Mandi Dalam", "Kloset Duduk"]', '["Ruang Tamu", "WiFi", "Jemuran", "CCTV", "Dapur Bersama", "Balkon"]', 'Purwokerto, Indonesia', 'Purwokerto Selatan', 'Purwokerto', 800000, 15, '3x4', 'termasuk', 'covers/t9arVb6EuDFLoYlpQuufdjJjItSxLi43NOE5Jbdw.jpg', 0, '2025-12-02 01:49:36', '2025-12-02 01:49:36'),
	(2, 1, 'Kost Pagi Sore', 'campur', 'Kost bebas asal sopan', '["AC", "Meja", "Kursi", "Kasur Double", "Lemari", "Jendela"]', '["K. Mandi Dalam", "Kloset Duduk"]', '["Ruang Tamu", "WiFi", "Jemuran", "CCTV", "Kulkas", "Penjaga Kost", "Dapur Bersama", "Balkon"]', 'Purwokerto, Indonesia', 'Purwokerto Timur', 'Purwokerto', 1200000, 20, '3x5', 'tidak', 'covers/rUytPkQgB4li1YEVwM31VDNg9hmtMQ2c0URoXiTk.jpg', 0, '2025-12-03 22:34:16', '2025-12-03 22:34:16'),
	(3, 1, 'Kost Situ', 'putri', 'Kost anak-anak sholehah :D', '["AC", "Meja", "Kursi", "Kasur Single", "Lemari", "Jendela"]', '["K. Mandi Dalam", "Kloset Duduk"]', '["Ruang Tamu", "WiFi", "Jemuran", "CCTV", "Kulkas", "Dapur Bersama", "Balkon"]', 'Purwokerto, Indonesia', 'Purwokerto Utara', 'Purwokerto', 850000, 15, '3x4', 'tidak', 'covers/WHWynpeHnwH3tlx2f0B8n2m2wVmJLcMkMveoqg84.jpg', 0, '2025-12-03 22:37:46', '2025-12-03 22:37:46'),
	(4, 1, 'Kost Simak', 'campur', 'Kost nyaman buat kamu', '["AC", "Meja", "Kursi", "Kasur Single", "Lemari", "Jendela"]', '["K. Mandi Dalam", "Kloset Duduk"]', '["Ruang Tamu", "WiFi", "Jemuran", "CCTV", "Kulkas", "Dapur Bersama", "Balkon"]', 'Purwokerto, Indonesia', 'Purwokerto Selatan', 'Purwokerto', 900000, 10, '3x4', 'tidak', 'covers/aqlJp4l8oAYwmg3JkhIVaRVoiPk1dGzQ8AKorncq.jpg', 0, '2025-12-03 22:39:30', '2025-12-03 22:39:30');

-- Dumping structure for table kostkita.kost_photos
CREATE TABLE IF NOT EXISTS `kost_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kost_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kost_photos_kost_id_foreign` (`kost_id`),
  CONSTRAINT `kost_photos_kost_id_foreign` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.kost_photos: ~8 rows (approximately)
INSERT INTO `kost_photos` (`id`, `kost_id`, `path`, `created_at`, `updated_at`) VALUES
	(1, 1, 'photos/26SIXQJxqnR3LD7cM11HpsWimhkTp7hvnHYAMLGt.jpg', '2025-12-02 01:49:36', '2025-12-02 01:49:36'),
	(2, 1, 'photos/NUINfwqkUxuf8RU758mHEW10j4O3mxxx3zK8CyRs.jpg', '2025-12-02 01:49:36', '2025-12-02 01:49:36'),
	(3, 2, 'photos/HuFxxgwLsVKTsSg3jARPpAJWZ6n1ljw82VwLEg9U.jpg', '2025-12-03 22:34:16', '2025-12-03 22:34:16'),
	(4, 2, 'photos/yFcp2DfOr6ifqXJofeNzxQPvWjhbXoyNiEOokK2o.jpg', '2025-12-03 22:34:16', '2025-12-03 22:34:16'),
	(5, 3, 'photos/U2IxsfJ5TJ843lxanF1WfsDltZW7hrbfaXWkjoOx.webp', '2025-12-03 22:37:46', '2025-12-03 22:37:46'),
	(6, 3, 'photos/QPdDEunKWpU8T78uyofH6L06WDk2cw9aKfd7lcXC.webp', '2025-12-03 22:37:46', '2025-12-03 22:37:46'),
	(7, 4, 'photos/NC4lwSMdOdc49kp43wscq9KbAWMpHzKfNnLwVQ5f.jpg', '2025-12-03 22:39:30', '2025-12-03 22:39:30'),
	(8, 4, 'photos/rPfClZ0zPdPKGO4gdl1loD3bekr4b3eFSz0ZpkZZ.jpg', '2025-12-03 22:39:30', '2025-12-03 22:39:30');

-- Dumping structure for table kostkita.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_11_02_112342_add_fields_to_users_table', 1),
	(5, '2025_11_02_112641_create_kosts_table', 1),
	(6, '2025_11_02_112713_create_kost_photos_table', 1),
	(7, '2025_11_02_112740_create_bookings_table', 1),
	(8, '2025_11_02_112759_create_payments_table', 1),
	(9, '2025_11_02_120511_create_permission_tables', 1),
	(10, '2025_11_25_054423_create_admins_table', 1),
	(11, '2025_11_26_092837_create_ratings_table', 1),
	(12, '2025_12_02_084307_add_spesifikasi_and_fasilitas_to_kosts_table', 1);

-- Dumping structure for table kostkita.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table kostkita.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.model_has_roles: ~0 rows (approximately)

-- Dumping structure for table kostkita.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table kostkita.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `jumlah` int NOT NULL,
  `metode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_user_id_foreign` (`user_id`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.payments: ~0 rows (approximately)

-- Dumping structure for table kostkita.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.permissions: ~0 rows (approximately)

-- Dumping structure for table kostkita.ratings
CREATE TABLE IF NOT EXISTS `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kost_id` bigint unsigned NOT NULL,
  `booking_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ratings_user_id_kost_id_booking_id_unique` (`user_id`,`kost_id`,`booking_id`),
  KEY `ratings_kost_id_foreign` (`kost_id`),
  KEY `ratings_booking_id_foreign` (`booking_id`),
  CONSTRAINT `ratings_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_kost_id_foreign` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.ratings: ~0 rows (approximately)

-- Dumping structure for table kostkita.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.roles: ~2 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'pencari', 'web', '2025-12-02 01:48:17', '2025-12-02 01:48:17'),
	(2, 'pemilik', 'web', '2025-12-02 01:48:18', '2025-12-02 01:48:18'),
	(3, 'admin', 'web', '2025-12-02 01:48:18', '2025-12-02 01:48:18');

-- Dumping structure for table kostkita.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.role_has_permissions: ~0 rows (approximately)

-- Dumping structure for table kostkita.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.sessions: ~3 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('3WnN5iwS17gK62y1OXdUGHTGDUWz6M1ziOkfjzaM', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid1pFbThPNHF3RlVCeXIzZk1UMUdkQ25rZFZZR3RTN1BGd0F1RGtyMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib29raW5nLzIvcGF5bWVudCI7czo1OiJyb3V0ZSI7czoxNToiYm9va2luZy5wYXltZW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1765460720),
	('9f2Qe1xiPq9XJ4ykhGUErvD9Sfl8txs6V3Zrtrl3', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSVFlR3I2ekFvWnFSRUdZeGR4YmFKb1kzcnVyNHpxaWJiRFNwZkFiYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9rb3N0L2NyZWF0ZSI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ua29zdC5jcmVhdGUiO31zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1765707947),
	('MSFDXdpLnYesyg5WOwDkhrSOWjOdNeHkbm2XAiey', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3hualVJUEt3RGlUaDV4OVo1bXYwaEZZUUZCRlZlMU4xWko4M0JPSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1765606260);

-- Dumping structure for table kostkita.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kostkita.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `google_id`) VALUES
	(1, 'Test User', 'test@example.com', NULL, '$2y$12$8rqWm2039/YNpjS1xSPt1.JNaxlIC0CobBWmSWvm7MFCegJwkDRve', NULL, '2025-12-02 01:48:18', '2025-12-02 01:48:18', NULL, NULL),
	(2, 'Elvares Hadni Hameed', 'elvareshadni@gmail.com', NULL, '$2y$12$/.oFTA.yTNx8eJftvUPTyOaFG9gbRqnw0pOteQFKWENND4hYoDyoO', NULL, '2025-12-02 03:09:43', '2025-12-02 03:09:43', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
