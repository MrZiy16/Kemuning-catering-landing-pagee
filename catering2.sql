-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2025 at 08:08 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catering2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-4d134bc072212ace2df385dae143139da74ec0ef', 'i:1;', 1757480620),
('laravel-cache-4d134bc072212ace2df385dae143139da74ec0ef:timer', 'i:1757480620;', 1757480620),
('laravel-cache-b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f', 'i:1;', 1756460681),
('laravel-cache-b3f0c7f6bb763af1be91d9e74eabfeb199dc1f1f:timer', 'i:1756460681;', 1756460681);

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaction_box`
--

CREATE TABLE `detail_transaction_box` (
  `id_penjualan` varchar(20) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `tanggal_acara` date NOT NULL,
  `alamat_acara` text NOT NULL,
  `catatan` varchar(150) DEFAULT NULL,
  `total_harga` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaction_box`
--

INSERT INTO `detail_transaction_box` (`id_penjualan`, `menu_id`, `kuantitas`, `harga_satuan`, `tanggal_acara`, `alamat_acara`, `catatan`, `total_harga`) VALUES
('TRXB20250906001', 11, 10, 25000.00, '2025-09-06', 'pp', 'pp', 250000.00),
('TRXB20250906002', 2, 10, 30000.00, '2025-09-06', 'ppp', 'ppp', 300000.00),
('TRXB20250906003', 2, 10, 30000.00, '2025-09-06', 'pp', 'pp', 300000.00),
('TRXB20250906004', 2, 10, 30000.00, '2025-09-06', 'qq', 'qq', 300000.00),
('TRXB20250910001', 2, 10, 30000.00, '2025-09-10', 'hvhfghvjbj', 'bnvhgvhj', 300000.00),
('TRXB20250910002', 2, 10, 30000.00, '2025-09-10', 'zszdsdzas', 'sdadsaad', 300000.00),
('TRXB20250911001', 2, 10, 30000.00, '2025-09-11', 'cvzdsz', 'dszdzs', 300000.00),
('TRXB20250913001', 2, 10, 30000.00, '2025-09-13', 'dasadsa', 'adasdas', 300000.00),
('TRXB20250914001', 11, 10, 25000.00, '2025-09-14', 'fsffsd', 'sfsdfs', 250000.00),
('TRXB20250915001', 2, 15, 30000.00, '2025-09-16', 'zsds', 'dsadasda', 450000.00);

--
-- Triggers `detail_transaction_box`
--
DELIMITER $$
CREATE TRIGGER `tr_update_total_after_delete` AFTER DELETE ON `detail_transaction_box` FOR EACH ROW BEGIN
    UPDATE master_transaction 
    SET total_penjualan = (
        SELECT COALESCE(SUM(total_harga), 0) 
        FROM detail_transaction_box 
        WHERE id_penjualan = OLD.id_penjualan
    )
    WHERE no_penjualan = OLD.id_penjualan;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_update_total_after_insert` AFTER INSERT ON `detail_transaction_box` FOR EACH ROW BEGIN
    UPDATE master_transaction 
    SET total_penjualan = (
        SELECT COALESCE(SUM(total_harga), 0) 
        FROM detail_transaction_box 
        WHERE id_penjualan = NEW.id_penjualan
    )
    WHERE no_penjualan = NEW.id_penjualan;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_update_total_after_update` AFTER UPDATE ON `detail_transaction_box` FOR EACH ROW BEGIN
    -- Update untuk transaksi yang lama (jika id_penjualan berubah)
    IF OLD.id_penjualan != NEW.id_penjualan THEN
        UPDATE master_transaction 
        SET total_penjualan = (
            SELECT COALESCE(SUM(total_harga), 0) 
            FROM detail_transaction_box 
            WHERE id_penjualan = OLD.id_penjualan
        )
        WHERE no_penjualan = OLD.id_penjualan;
    END IF;
    
    -- Update untuk transaksi yang baru
    UPDATE master_transaction 
    SET total_penjualan = (
        SELECT COALESCE(SUM(total_harga), 0) 
        FROM detail_transaction_box 
        WHERE id_penjualan = NEW.id_penjualan
    )
    WHERE no_penjualan = NEW.id_penjualan;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaction_prasmanan`
--

CREATE TABLE `detail_transaction_prasmanan` (
  `id_penjualan` varchar(20) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `paket_id` int(11) DEFAULT NULL,
  `jumlah_tamu` int(11) NOT NULL,
  `harga_perporsi` decimal(12,2) NOT NULL,
  `catatan` varchar(150) DEFAULT NULL,
  `tanggal_acara` date NOT NULL,
  `alamat_acara` text NOT NULL,
  `total_harga` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_layanan`
--

CREATE TABLE `jenis_layanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ikon` varchar(100) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_layanan`
--

INSERT INTO `jenis_layanan` (`id`, `nama`, `slug`, `deskripsi`, `ikon`, `status_aktif`, `dibuat_pada`, `diperbarui_pada`) VALUES
(1, 'Nasi Box', 'nasi-box', 'Layanan nasi box dengan berbagai pilihan menu dalam kemasan praktis', NULL, 1, '2025-08-13 05:04:58', '2025-08-13 05:04:58'),
(2, 'Prasmanan', 'prasmanan', 'Layanan prasmanan untuk acara dengan sistem buffet dan harga bertingkat', NULL, 1, '2025-08-13 05:04:58', '2025-08-13 05:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `master_transaction`
--

CREATE TABLE `master_transaction` (
  `no_penjualan` varchar(15) NOT NULL,
  `kode_customer` int(11) NOT NULL,
  `tanggal_trx` datetime NOT NULL DEFAULT current_timestamp(),
  `total_penjualan` bigint(20) DEFAULT NULL,
  `total_pembayaran` bigint(20) DEFAULT NULL,
  `jenis_layanan` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','dp_paid','paid','cancelled') DEFAULT 'pending',
  `status_pengiriman` enum('pending','proses','dikirim','selesai') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_transaction`
--

INSERT INTO `master_transaction` (`no_penjualan`, `kode_customer`, `tanggal_trx`, `total_penjualan`, `total_pembayaran`, `jenis_layanan`, `updated_at`, `status`, `status_pengiriman`) VALUES
('TRXB20250906001', 6, '2025-09-06 08:21:50', 250000, 250000, 1, '2025-09-11 12:59:35', 'paid', 'selesai'),
('TRXB20250906002', 6, '2025-09-06 15:33:52', 300000, 300000, 1, '2025-09-11 09:00:35', 'paid', 'proses'),
('TRXB20250906003', 6, '2025-09-06 15:43:26', 300000, 0, 1, '2025-09-11 08:15:13', 'cancelled', 'pending'),
('TRXB20250906004', 6, '2025-09-06 15:54:37', 300000, 105000, 1, '2025-09-06 08:55:09', 'dp_paid', 'proses'),
('TRXB20250910001', 6, '2025-09-10 10:09:43', 300000, 0, 1, '2025-09-11 08:15:13', 'cancelled', 'pending'),
('TRXB20250910002', 6, '2025-09-10 10:12:41', 300000, 300000, 1, '2025-09-13 05:56:34', 'paid', 'selesai'),
('TRXB20250911001', 6, '2025-09-11 15:58:49', 300000, 0, 1, '2025-09-11 08:59:11', 'cancelled', 'pending'),
('TRXB20250913001', 6, '2025-09-13 13:13:44', 300000, 0, 1, '2025-09-13 06:20:34', 'cancelled', 'pending'),
('TRXB20250914001', 6, '2025-09-14 15:43:22', 250000, 250000, 1, '2025-09-14 08:45:39', 'paid', 'selesai'),
('TRXB20250915001', 6, '2025-09-16 00:44:06', 450000, 0, 1, '2025-09-15 17:44:06', 'pending', 'pending');

--
-- Triggers `master_transaction`
--
DELIMITER $$
CREATE TRIGGER `tr_sync_status_pengiriman` BEFORE UPDATE ON `master_transaction` FOR EACH ROW BEGIN
    -- Jika status berubah menjadi pending
    IF NEW.status = 'pending' THEN
        SET NEW.status_pengiriman = 'pending';
    END IF;
    
    -- Jika status berubah menjadi paid/dp_paid dan status_pengiriman masih pending
    IF (NEW.status IN ('paid', 'dp_paid')) AND (OLD.status_pengiriman = 'pending' OR OLD.status_pengiriman IS NULL) THEN
        SET NEW.status_pengiriman = 'proses';
    END IF;
    
    -- Jika status dibatalkan → status_pengiriman ikut pending
    IF NEW.status = 'cancelled' THEN
        SET NEW.status_pengiriman = 'pending';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_sync_status_pengiriman_insert` BEFORE INSERT ON `master_transaction` FOR EACH ROW BEGIN
    -- Set default status_pengiriman berdasarkan status
    IF NEW.status = 'pending' THEN
        SET NEW.status_pengiriman = 'pending';
    ELSEIF NEW.status IN ('paid', 'dp_paid') THEN
        SET NEW.status_pengiriman = 'proses';
    ELSEIF NEW.status = 'cancelled' THEN
        SET NEW.status_pengiriman = 'pending'; -- diubah ke pending
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_prasmanan`
--

CREATE TABLE `menu_prasmanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `slug` varchar(20) NOT NULL,
  `kategori` enum('nasi','lauk','sayur','appetizer','dessert','minuman') NOT NULL,
  `foto` varchar(100) NOT NULL,
  `harga_per_porsi` decimal(12,2) NOT NULL,
  `layanan` int(11) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_prasmanan`
--

INSERT INTO `menu_prasmanan` (`id`, `nama`, `deskripsi`, `slug`, `kategori`, `foto`, `harga_per_porsi`, `layanan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(7, 'Siomay', NULL, 'siomay', 'appetizer', '', 8000.00, 2, 1, NULL, NULL),
(8, 'Sate', NULL, 'sate', 'appetizer', '', 9000.00, 2, 1, NULL, NULL),
(11, 'qq', 'qq', 'qq', 'appetizer', '1756472289_qq.jpg', 7000.00, 2, 1, '2025-08-29 05:58:09', '2025-08-29 05:58:09'),
(13, 'QP1', 'PP', 'qp1', 'sayur', '1757057645_qqpp.jpeg', 6000.00, 2, 1, '2025-09-05 00:34:05', '2025-09-05 00:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pesan` text NOT NULL,
  `jenis` enum('info','sukses','peringatan','error','update_pesanan') NOT NULL DEFAULT 'info',
  `id_pesanan_terkait` int(11) DEFAULT NULL,
  `sudah_dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `dibaca_pada` timestamp NULL DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paket_menu_box`
--

CREATE TABLE `paket_menu_box` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `layanan` int(11) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket_menu_box`
--

INSERT INTO `paket_menu_box` (`id`, `nama`, `slug`, `deskripsi`, `harga`, `foto`, `layanan`, `status_aktif`, `dibuat_pada`, `diperbarui_pada`) VALUES
(2, 'Paket Premium 1', 'paket-premium-1', 'Nasi Putih,Ayam Goreng,Capcay,sambal', 30000.00, '1756117009_paket-premium-1.jpeg', 1, 1, '2025-08-13 05:04:58', '2025-08-25 03:16:49'),
(11, 'PP', 'pp', 'QWER', 25000.00, '1757057777_pp.jpeg', 1, 1, '2025-09-05 00:36:18', '2025-09-05 00:36:18');

-- --------------------------------------------------------

--
-- Table structure for table `paket_prasmanan`
--

CREATE TABLE `paket_prasmanan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `harga_per_porsi` decimal(12,1) NOT NULL,
  `minimal_porsi` int(11) NOT NULL DEFAULT 50,
  `layanan` int(11) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket_prasmanan`
--

INSERT INTO `paket_prasmanan` (`id`, `nama`, `slug`, `deskripsi`, `foto`, `harga_per_porsi`, `minimal_porsi`, `layanan`, `status_aktif`, `dibuat_pada`, `diperbarui_pada`) VALUES
(3, 'pp', 'pp', 'pp,qq,zz', '1756116150_pp2.jpg', 50000.0, 50, 2, 1, '2025-08-19 07:21:30', '2025-08-25 11:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_transaction_id` varchar(15) NOT NULL,
  `method` enum('offline','midtrans') NOT NULL,
  `type` enum('full','dp') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_status` enum('pending','paid','failed','cancelled') DEFAULT 'pending',
  `midtrans_order_id` varchar(100) DEFAULT NULL,
  `midtrans_transaction_id` varchar(100) DEFAULT NULL,
  `midtrans_status` text DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `master_transaction_id`, `method`, `type`, `amount`, `payment_status`, `midtrans_order_id`, `midtrans_transaction_id`, `midtrans_status`, `paid_at`, `created_at`, `updated_at`) VALUES
(82, 'TRXB20250906001', 'midtrans', 'full', 250000.00, 'paid', 'ORDER-82-1757146917', 'cf8d1fdc-0569-4ffa-861e-3fc7208743c4', '\"{\\\"transaction_id\\\":\\\"cf8d1fdc-0569-4ffa-861e-3fc7208743c4\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"fraud_status\\\":\\\"accept\\\"}\"', '2025-09-06 08:22:24', '2025-09-06 01:21:57', '2025-09-06 01:22:24'),
(83, 'TRXB20250906002', 'midtrans', 'full', 300000.00, 'paid', 'ORDER-83-1757147637', '6a303270-c8e1-4e9e-b957-4400bb000549', '\"{\\\"transaction_id\\\":\\\"6a303270-c8e1-4e9e-b957-4400bb000549\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"fraud_status\\\":\\\"accept\\\"}\"', '2025-09-06 08:39:00', '2025-09-06 01:33:57', '2025-09-06 01:39:00'),
(84, 'TRXB20250906003', 'offline', 'full', 300000.00, 'pending', NULL, NULL, NULL, NULL, '2025-09-06 01:43:32', '2025-09-06 01:43:32'),
(85, 'TRXB20250906004', 'midtrans', 'dp', 105000.00, 'paid', 'ORDER-85-1757148882', 'acc510da-721b-4823-a7ba-3c08a7fd0f7f', '\"{\\\"transaction_id\\\":\\\"acc510da-721b-4823-a7ba-3c08a7fd0f7f\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"fraud_status\\\":\\\"accept\\\"}\"', '2025-09-06 08:55:09', '2025-09-06 01:54:42', '2025-09-06 01:55:09'),
(86, 'TRXB20250910001', 'midtrans', 'full', 300000.00, 'pending', 'ORDER-86-1757473797', NULL, NULL, NULL, '2025-09-09 20:09:57', '2025-09-09 20:09:57'),
(87, 'TRXB20250910002', 'midtrans', 'full', 300000.00, 'paid', 'ORDER-87-1757473964', '5fe5b3ef-9e85-47fb-af6d-f73599edae0b', '\"{\\\"transaction_id\\\":\\\"5fe5b3ef-9e85-47fb-af6d-f73599edae0b\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"fraud_status\\\":\\\"accept\\\"}\"', '2025-09-10 03:12:58', '2025-09-09 20:12:44', '2025-09-09 20:12:58'),
(89, 'TRXB20250911001', 'midtrans', 'full', 300000.00, 'pending', 'ORDER-89-1757581132', NULL, NULL, NULL, '2025-09-11 01:58:52', '2025-09-11 01:58:52'),
(90, 'TRXB20250913001', 'midtrans', 'full', 300000.00, 'pending', 'ORDER-90-1757744082', NULL, NULL, NULL, '2025-09-12 23:13:49', '2025-09-12 23:14:42'),
(91, 'TRXB20250906004', 'midtrans', 'full', 195000.00, 'pending', 'ORDER-91-1757744610', NULL, NULL, NULL, '2025-09-12 23:23:30', '2025-09-12 23:23:30'),
(92, 'TRXB20250906004', 'midtrans', 'full', 195000.00, 'pending', 'ORDER-92-1757835959', NULL, NULL, NULL, '2025-09-14 00:45:59', '2025-09-14 00:45:59'),
(93, 'TRXB20250914001', 'midtrans', 'full', 250000.00, 'paid', 'ORDER-93-1757839406', '3624aec5-3ffd-4fd2-93d6-21d6106efeb8', '\"{\\\"transaction_id\\\":\\\"3624aec5-3ffd-4fd2-93d6-21d6106efeb8\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"fraud_status\\\":\\\"accept\\\"}\"', '2025-09-14 08:43:53', '2025-09-14 01:43:26', '2025-09-14 01:43:53'),
(94, 'TRXB20250915001', 'midtrans', 'full', 450000.00, 'pending', 'ORDER-94-1757958266', NULL, NULL, NULL, '2025-09-15 10:44:25', '2025-09-15 10:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('P5NVR3fHueA7nzeNkZrSfCFJbSYf9BMIYIRlywME', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSFlOZVRjSHphd0JkbEdBZFlCVEpVVVE2RDlSSUhDUkZxQVJnNDFRSCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYXltZW50L215LW9yZGVycz85ND0iO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1757958273),
('pUcyktCPdD2Aqh8jMI33ADeURdm0cILLY5BeCT6n', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVAyN0pWRllnSGVlZjNOYU9hUDFmaHRXTGZjQWMyY2ZBa0dOQmgyRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1757958174);

-- --------------------------------------------------------

--
-- Table structure for table `ulasan_produk`
--

CREATE TABLE `ulasan_produk` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `id_penjualan` varchar(20) NOT NULL,
  `paket_box_id` int(11) DEFAULT NULL,
  `paket_prasmanan_id` int(11) DEFAULT NULL,
  `menu_prasmanan_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `status_tampilkan` tinyint(1) NOT NULL DEFAULT 0,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `produk_reference` varchar(50) GENERATED ALWAYS AS (case when `paket_box_id` is not null then concat('box_',`paket_box_id`) when `paket_prasmanan_id` is not null then concat('prasmanan_',`paket_prasmanan_id`) when `menu_prasmanan_id` is not null then concat('menu_',`menu_prasmanan_id`) else NULL end) STORED
) ;

--
-- Dumping data for table `ulasan_produk`
--

INSERT INTO `ulasan_produk` (`id`, `id_pengguna`, `id_penjualan`, `paket_box_id`, `paket_prasmanan_id`, `menu_prasmanan_id`, `rating`, `komentar`, `status_tampilkan`, `dibuat_pada`, `diperbarui_pada`) VALUES
(3, 6, 'TRXB20250906001', 11, NULL, NULL, 5, NULL, 1, '2025-09-13 06:07:31', '2025-09-14 10:56:11'),
(6, 6, 'TRXB20250914001', 11, NULL, NULL, 4, 'wkwkw enak', 1, '2025-09-14 10:32:28', '2025-09-14 10:54:46'),
(10, 6, 'TRXB20250910002', 2, NULL, NULL, 5, NULL, 0, '2025-09-14 11:07:37', '2025-09-14 11:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email_terverifikasi_pada` timestamp NULL DEFAULT NULL,
  `peran` enum('pelanggan','admin','super_admin') NOT NULL DEFAULT 'pelanggan',
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `kata_sandi`, `nama`, `telepon`, `alamat`, `email_terverifikasi_pada`, `peran`, `status_aktif`, `created_at`, `updated_at`) VALUES
(6, 'masnuu7@gmail.com', '$2y$12$bwFam4h2OB15tzcMXqV7zuuQx7JzFMI1q0Mb42vi7locIUsoLK4Ru', 'dsaas', '0838137088', 'qq', '2025-08-23 10:53:30', 'pelanggan', 1, '2025-08-12 23:28:54', '2025-08-23 10:53:30'),
(10, 'kemuningcatering7@gmail.com', '$2y$12$33fxt97VpI26Od/bTB95yuzxAFyCYsTf9HCM0KluCfKm2kZrx5vv6', 'Admin', '0897786788', 'pp', '2025-08-17 06:27:52', 'super_admin', 1, '2025-08-17 06:27:01', '2025-08-26 05:15:14'),
(21, 'masnuu2@gmail.com', '$2y$12$2u.ICzph/kNL6tipoEeg2uD4BUlJ1Z/8aR9YOBkB.Ri4COk7kf5Ha', 'Admin 1', '08381370889', 'ppp', '2025-09-04 03:49:40', 'admin', 1, '2025-09-04 03:49:40', '2025-09-05 21:09:07'),
(24, 'fatechalfahrozi12@gmail.com', '$2y$12$6sG/sAcXHgafr/uTbTTRh.aHmuWUL74BQtDYkF/TG42JJ1M5T1i/S', 'Fateh', '0959588898', 'Djfjfnnf', '2025-09-09 22:02:40', 'pelanggan', 1, '2025-09-09 22:02:10', '2025-09-09 22:02:40');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rating_produk`
-- (See below for the actual view)
--
CREATE TABLE `view_rating_produk` (
`produk` varchar(50)
,`total_ulasan` bigint(21)
,`rata_rata_rating` decimal(7,4)
,`rata_rata_rating_bulat` decimal(6,2)
,`rating_terendah` tinyint(4)
,`rating_tertinggi` tinyint(4)
);

-- --------------------------------------------------------

--
-- Structure for view `view_rating_produk`
--
DROP TABLE IF EXISTS `view_rating_produk`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rating_produk`  AS SELECT `ulasan_produk`.`produk_reference` AS `produk`, count(0) AS `total_ulasan`, avg(`ulasan_produk`.`rating`) AS `rata_rata_rating`, round(avg(`ulasan_produk`.`rating`),2) AS `rata_rata_rating_bulat`, min(`ulasan_produk`.`rating`) AS `rating_terendah`, max(`ulasan_produk`.`rating`) AS `rating_tertinggi` FROM `ulasan_produk` WHERE `ulasan_produk`.`produk_reference` is not null GROUP BY `ulasan_produk`.`produk_reference` ORDER BY avg(`ulasan_produk`.`rating`) DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_transaction_box`
--
ALTER TABLE `detail_transaction_box`
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `detail_transaction_box_id_penjualan_foreign` (`id_penjualan`);

--
-- Indexes for table `detail_transaction_prasmanan`
--
ALTER TABLE `detail_transaction_prasmanan`
  ADD KEY `detail_transaction_prasmanan_id_penjualan_foreign` (`id_penjualan`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `detail_transaction_prasmanan_ibfk_2` (`paket_id`);

--
-- Indexes for table `jenis_layanan`
--
ALTER TABLE `jenis_layanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `master_transaction`
--
ALTER TABLE `master_transaction`
  ADD PRIMARY KEY (`no_penjualan`),
  ADD KEY `kode_customer` (`kode_customer`),
  ADD KEY `jenis_layanan` (`jenis_layanan`);

--
-- Indexes for table `menu_prasmanan`
--
ALTER TABLE `menu_prasmanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `layanan` (`layanan`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_pengguna` (`id_pengguna`),
  ADD KEY `idx_sudah_dibaca` (`sudah_dibaca`),
  ADD KEY `idx_jenis` (`jenis`),
  ADD KEY `idx_notifikasi_pengguna_baca` (`id_pengguna`,`sudah_dibaca`);

--
-- Indexes for table `paket_menu_box`
--
ALTER TABLE `paket_menu_box`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_paket_menu_box_layanan` (`layanan`);

--
-- Indexes for table `paket_prasmanan`
--
ALTER TABLE `paket_prasmanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_paket_prasmanan_layanan` (`layanan`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_transaction_id` (`master_transaction_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_ulasan_box` (`id_pengguna`,`id_penjualan`,`paket_box_id`),
  ADD UNIQUE KEY `uniq_ulasan` (`id_pengguna`,`id_penjualan`,`produk_reference`),
  ADD KEY `idx_id_pengguna` (`id_pengguna`),
  ADD KEY `idx_id_penjualan` (`id_penjualan`),
  ADD KEY `idx_paket_box` (`paket_box_id`),
  ADD KEY `idx_paket_prasmanan` (`paket_prasmanan_id`),
  ADD KEY `idx_menu_prasmanan` (`menu_prasmanan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_layanan`
--
ALTER TABLE `jenis_layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_prasmanan`
--
ALTER TABLE `menu_prasmanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paket_menu_box`
--
ALTER TABLE `paket_menu_box`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `paket_prasmanan`
--
ALTER TABLE `paket_prasmanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaction_box`
--
ALTER TABLE `detail_transaction_box`
  ADD CONSTRAINT `detail_transaction_box_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `paket_menu_box` (`id`),
  ADD CONSTRAINT `detail_transaction_box_id_penjualan_foreign` FOREIGN KEY (`id_penjualan`) REFERENCES `master_transaction` (`no_penjualan`);

--
-- Constraints for table `detail_transaction_prasmanan`
--
ALTER TABLE `detail_transaction_prasmanan`
  ADD CONSTRAINT `detail_transaction_prasmanan_ibfk_2` FOREIGN KEY (`paket_id`) REFERENCES `paket_prasmanan` (`id`),
  ADD CONSTRAINT `detail_transaction_prasmanan_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `menu_prasmanan` (`id`),
  ADD CONSTRAINT `detail_transaction_prasmanan_id_penjualan_foreign` FOREIGN KEY (`id_penjualan`) REFERENCES `master_transaction` (`no_penjualan`);

--
-- Constraints for table `master_transaction`
--
ALTER TABLE `master_transaction`
  ADD CONSTRAINT `master_transaction_ibfk_1` FOREIGN KEY (`kode_customer`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `master_transaction_ibfk_2` FOREIGN KEY (`jenis_layanan`) REFERENCES `jenis_layanan` (`id`);

--
-- Constraints for table `menu_prasmanan`
--
ALTER TABLE `menu_prasmanan`
  ADD CONSTRAINT `menu_prasmanan_ibfk_1` FOREIGN KEY (`layanan`) REFERENCES `jenis_layanan` (`id`);

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `paket_menu_box`
--
ALTER TABLE `paket_menu_box`
  ADD CONSTRAINT `fk_paket_menu_box_layanan` FOREIGN KEY (`layanan`) REFERENCES `jenis_layanan` (`id`);

--
-- Constraints for table `paket_prasmanan`
--
ALTER TABLE `paket_prasmanan`
  ADD CONSTRAINT `fk_paket_prasmanan_layanan` FOREIGN KEY (`layanan`) REFERENCES `jenis_layanan` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`master_transaction_id`) REFERENCES `master_transaction` (`no_penjualan`);

--
-- Constraints for table `ulasan_produk`
--
ALTER TABLE `ulasan_produk`
  ADD CONSTRAINT `fk_ulasan_menu_prasmanan` FOREIGN KEY (`menu_prasmanan_id`) REFERENCES `menu_prasmanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ulasan_paket_box` FOREIGN KEY (`paket_box_id`) REFERENCES `paket_menu_box` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ulasan_paket_prasmanan` FOREIGN KEY (`paket_prasmanan_id`) REFERENCES `paket_prasmanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ulasan_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ulasan_transaksi` FOREIGN KEY (`id_penjualan`) REFERENCES `master_transaction` (`no_penjualan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
