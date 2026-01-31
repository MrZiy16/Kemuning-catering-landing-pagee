-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: kemuning_catering
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-7b52009b64fd0a2a49e6d8a939753077792b0554','i:2;',1762743157),('laravel-cache-7b52009b64fd0a2a49e6d8a939753077792b0554:timer','i:1762743157;',1762743157),('laravel-cache-f1abd670358e036c31296e66b3b66c382ac00812','i:2;',1762685219),('laravel-cache-f1abd670358e036c31296e66b3b66c382ac00812:timer','i:1762685219;',1762685219),('laravel-cache-fa35e192121eabf3dabf9f5ea6abdbcbc107ac3b','i:2;',1762434594),('laravel-cache-fa35e192121eabf3dabf9f5ea6abdbcbc107ac3b:timer','i:1762434594;',1762434594),('laravel-cache-fiaiyah@gmail.com|114.10.44.75','i:1;',1762483626),('laravel-cache-fiaiyah@gmail.com|114.10.44.75:timer','i:1762483626;',1762483626),('laravel-cache-fiaiyyah@gmail.com|114.10.44.75','i:1;',1762483639),('laravel-cache-fiaiyyah@gmail.com|114.10.44.75:timer','i:1762483639;',1762483639),('laravel-cache-nailulseina@gmail.com|114.10.44.195','i:1;',1762685203),('laravel-cache-nailulseina@gmail.com|114.10.44.195:timer','i:1762685203;',1762685203);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_transaksi`
--

DROP TABLE IF EXISTS `detail_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `id_produk` int DEFAULT NULL,
  `id_menu` int DEFAULT NULL,
  `qty` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  UNIQUE KEY `unique_transaksi_item` (`id_transaksi`,`id_produk`,`id_menu`),
  KEY `id_produk` (`id_produk`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `master_transaksi` (`id_transaksi`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  CONSTRAINT `detail_transaksi_ibfk_3` FOREIGN KEY (`id_menu`) REFERENCES `master_menu` (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi`
--

LOCK TABLES `detail_transaksi` WRITE;
/*!40000 ALTER TABLE `detail_transaksi` DISABLE KEYS */;
INSERT INTO `detail_transaksi` VALUES (1,'TRX-20251106001',81,NULL,80,17000.00,1360000.00),(2,'TRX-20251109001',81,NULL,100,17000.00,1700000.00);
/*!40000 ALTER TABLE `detail_transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_menu`
--

DROP TABLE IF EXISTS `master_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_menu` (
  `id_menu` int NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `harga_satuan` decimal(10,2) NOT NULL,
  `kategori_menu` enum('makanan_utama','sayuran','lauk','minuman','dessert') COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `gambar` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(70) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_menu`),
  KEY `idx_menu_kategori` (`kategori_menu`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_menu`
--

LOCK TABLES `master_menu` WRITE;
/*!40000 ALTER TABLE `master_menu` DISABLE KEYS */;
INSERT INTO `master_menu` VALUES (1,'Nasi Putih','Nasi putih pulen',5000.00,'makanan_utama','active',NULL,'nasi-putih','2025-10-29 08:11:30'),(2,'Nasi Kuning','Nasi kuning dengan bumbu kunyit dan santan',7000.00,'makanan_utama','active',NULL,'nasi-kuning','2025-10-29 08:11:30'),(3,'Nasi Bakar','Nasi bakar dengan bumbu rempah',8000.00,'makanan_utama','active',NULL,'nasi-bakar','2025-10-29 08:11:30'),(4,'Mie Goreng','Mie goreng dengan bumbu spesial',6000.00,'makanan_utama','active',NULL,'mie-goreng','2025-10-29 08:11:30'),(5,'Mie','Mie rebus',5000.00,'makanan_utama','active',NULL,'mie','2025-10-29 08:11:30'),(6,'Bihun Goreng','Bihun goreng dengan sayuran',6000.00,'makanan_utama','active',NULL,'bihun-goreng','2025-10-29 08:11:30'),(10,'Ayam Goreng','Ayam goreng gurih renyah',15000.00,'lauk','active',NULL,'ayam-goreng','2025-10-29 08:11:30'),(11,'Ayam Bakar','Ayam bakar bumbu kecap',16000.00,'lauk','active',NULL,'ayam-bakar','2025-10-29 08:11:30'),(12,'Ayam Serundeng','Ayam dengan taburan serundeng kelapa',16000.00,'lauk','active',NULL,'ayam-serundeng','2025-10-29 08:11:30'),(13,'Ayam Kremes','Ayam goreng kremes dengan remahan renyah',16000.00,'lauk','active',NULL,'ayam-kremes','2025-10-29 08:11:30'),(14,'Ayam Goreng Mentega','Ayam goreng dengan saus mentega',17000.00,'lauk','active',NULL,'ayam-goreng-mentega','2025-10-29 08:11:30'),(20,'Ikan Lele Goreng','Ikan lele goreng kering',12000.00,'lauk','active',NULL,'ikan-lele-goreng','2025-10-29 08:11:30'),(21,'Ikan Bawal Goreng','Ikan bawal goreng crispy',18000.00,'lauk','active',NULL,'ikan-bawal-goreng','2025-10-29 08:11:30'),(22,'Gurame Asam Manis','Ikan gurame dengan saus asam manis',25000.00,'lauk','active',NULL,'gurame-asam-manis','2025-10-29 08:11:30'),(30,'Rendang','Rendang daging sapi dengan bumbu khas Padang',20000.00,'lauk','active',NULL,'rendang','2025-10-29 08:11:30'),(31,'Rolade','Rolade daging cincang dengan telur',12000.00,'lauk','active',NULL,'rolade','2025-10-29 08:11:30'),(32,'Daging Teriyaki','Daging sapi dengan saus teriyaki',18000.00,'lauk','active',NULL,'daging-teriyaki','2025-10-29 08:11:30'),(40,'Telur Dadar','Telur dadar gurih',5000.00,'lauk','active',NULL,'telur-dadar','2025-10-29 08:11:30'),(41,'Telur Asin','Telur asin rebus',6000.00,'lauk','active',NULL,'telur-asin','2025-10-29 08:11:30'),(42,'Telur Balado','Telur dengan sambal balado pedas',7000.00,'lauk','active',NULL,'telur-balado','2025-10-29 08:11:30'),(43,'Telur','Telur rebus/goreng',5000.00,'lauk','active',NULL,'telur','2025-10-29 08:11:30'),(44,'Tahu Goreng','Tahu goreng garing',4000.00,'lauk','active',NULL,'tahu-goreng','2025-10-29 08:11:30'),(45,'Tempe Terik','Tempe goreng terik dengan bumbu manis pedas',5000.00,'lauk','active',NULL,'tempe-terik','2025-10-29 08:11:30'),(46,'Oseng Tempe','Tempe oseng bumbu kecap',5000.00,'lauk','active',NULL,'oseng-tempe','2025-10-29 08:11:30'),(47,'Perkedel','Perkedel kentang goreng',4000.00,'lauk','active',NULL,'perkedel','2025-10-29 08:11:30'),(50,'Sayur Gudangan','Sayuran gudangan dengan kelapa parut',5000.00,'sayuran','active',NULL,'sayur-gudangan','2025-10-29 08:11:30'),(51,'Urap Sayur','Sayuran urap dengan kelapa berbumbu',5000.00,'sayuran','active',NULL,'urap-sayur','2025-10-29 08:11:30'),(52,'Megono','Sayur nangka muda bumbu kelapa',5000.00,'sayuran','active',NULL,'megono','2025-10-29 08:11:30'),(53,'Capcay','Tumis sayuran aneka warna',8000.00,'sayuran','active',NULL,'capcay','2025-10-29 08:11:30'),(54,'Lalapan','Lalapan segar (timun, kol, kemangi)',3000.00,'sayuran','active',NULL,'lalapan','2025-10-29 08:11:30'),(55,'Selada Bangkok','Selada segar Bangkok',5000.00,'sayuran','active',NULL,'selada-bangkok','2025-10-29 08:11:30'),(60,'Soup Jamur Bakso Sosis','Soup jamur dengan bakso dan sosis',8000.00,'sayuran','active',NULL,'soup-jamur-bakso-sosis','2025-10-29 08:11:30'),(61,'Soup Ayam Sosis','Soup ayam dengan sosis',7000.00,'sayuran','active',NULL,'soup-ayam-sosis','2025-10-29 08:11:30'),(62,'Soup Ayam Jagung','Soup ayam jagung manis',7000.00,'sayuran','active',NULL,'soup-ayam-jagung','2025-10-29 08:11:30'),(70,'Sambel Goreng Kentang','Sambal goreng kentang pedas manis',5000.00,'lauk','active',NULL,'sambel-goreng-kentang','2025-10-29 08:11:30'),(71,'Oseng Soon Cabe Ijo','Oseng soon dengan cabe hijau',6000.00,'sayuran','active',NULL,'oseng-soon-cabe-ijo','2025-10-29 08:11:30'),(72,'Acar','Acar timun wortel',3000.00,'sayuran','active',NULL,'acar','2025-10-29 08:11:30'),(73,'Kerupuk Udang','Kerupuk udang renyah',2500.00,'lauk','active',NULL,'kerupuk-udang','2025-10-29 08:11:30'),(74,'Sambal','Sambal terasi/rawit',2000.00,'lauk','active',NULL,'sambal','2025-10-29 08:11:30'),(75,'Pisang','Pisang segar',3000.00,'dessert','active',NULL,'pisang','2025-10-29 08:11:30'),(76,'Keripik','Keripik singkong/pisang',2000.00,'dessert','active',NULL,'keripik','2025-10-29 08:11:30'),(80,'Aneka Buah Potong','Buah potong segar (melon, semangka, nanas)',8000.00,'dessert','active',NULL,'aneka-buah-potong','2025-10-29 08:11:30'),(90,'Air Mineral','Air mineral kemasan',3000.00,'minuman','active',NULL,'air-mineral','2025-10-29 08:11:30');
/*!40000 ALTER TABLE `master_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `master_transaksi`
--

DROP TABLE IF EXISTS `master_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `master_transaksi` (
  `id_transaksi` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `id_customer` int NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `tanggal_acara` date NOT NULL,
  `waktu_acara` time DEFAULT NULL,
  `alamat_pengiriman` text COLLATE utf8mb4_general_ci NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `status` enum('draft','pending','confirmed','preparing','ready','delivered','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'draft',
  `catatan_customer` text COLLATE utf8mb4_general_ci,
  `catatan_admin` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`),
  KEY `idx_transaksi_customer` (`id_customer`,`tanggal_transaksi`),
  KEY `idx_transaksi_status` (`status`,`tanggal_transaksi`),
  KEY `idx_transaksi_status_acara` (`status`,`tanggal_acara`),
  CONSTRAINT `master_transaksi_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `master_transaksi`
--

LOCK TABLES `master_transaksi` WRITE;
/*!40000 ALTER TABLE `master_transaksi` DISABLE KEYS */;
INSERT INTO `master_transaksi` VALUES ('TRX-20251106001',14,'2025-11-06','2025-11-09','16:00:00','Poncol GG 14E Kemuning No. 40 Pekalongan Timur Pekalongan, Jawa Tengah 51122',1360000.00,'completed','Hubungi wa kalo ada kebingungan dengan alamat nya\r\n089673335832',NULL,'2025-11-06 13:15:33','2025-11-09 10:44:02'),('TRX-20251109001',15,'2025-11-09','2025-11-11','08:00:00','Jl. Bina Griya B VI, Medono, Jawa Tengah, Kota Pekalongan, Jawa Tengah 51111',1700000.00,'completed','SD MEDONO 07 \r\nwa : 085643539077',NULL,'2025-11-09 10:56:48','2025-11-11 02:32:47');
/*!40000 ALTER TABLE `master_transaksi` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_status_log_insert` AFTER INSERT ON `master_transaksi` FOR EACH ROW BEGIN
    INSERT INTO status_log (id_transaksi, status_from, status_to, keterangan, created_by)
    VALUES (NEW.id_transaksi, NULL, NEW.status, 'Transaksi dibuat', 'system');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_status_log_update` AFTER UPDATE ON `master_transaksi` FOR EACH ROW BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO status_log (id_transaksi, status_from, status_to, keterangan, created_by)
        VALUES (NEW.id_transaksi, OLD.status, NEW.status, 
                CONCAT('Status berubah dari ', OLD.status, ' ke ', NEW.status), 'system');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',2),(3,'0001_01_01_000002_create_jobs_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paket_box_detail`
--

DROP TABLE IF EXISTS `paket_box_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paket_box_detail` (
  `id_produk` int NOT NULL,
  `id_menu` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_produk`,`id_menu`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `paket_box_detail_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  CONSTRAINT `paket_box_detail_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `master_menu` (`id_menu`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_box_detail`
--

LOCK TABLES `paket_box_detail` WRITE;
/*!40000 ALTER TABLE `paket_box_detail` DISABLE KEYS */;
INSERT INTO `paket_box_detail` VALUES (76,2,1),(76,5,1),(76,31,1),(76,43,1),(76,47,1),(76,70,1),(77,1,1),(77,5,1),(77,10,1),(77,43,1),(77,45,1),(77,52,1),(78,1,1),(78,10,1),(78,41,1),(78,54,1),(78,70,1),(79,1,1),(79,5,1),(79,30,1),(79,41,1),(79,70,1),(79,73,1),(80,2,1),(80,5,1),(80,12,1),(80,43,1),(80,47,1),(80,70,1),(81,3,1),(81,10,1),(81,44,1),(81,54,1),(81,76,1),(82,1,1),(82,10,1),(82,44,1),(82,54,1),(82,75,1),(83,1,1),(83,13,1),(83,44,1),(83,54,1),(83,74,1),(87,1,1),(87,20,1),(87,42,1),(87,70,1),(87,72,1),(88,1,1),(88,5,1),(88,11,1),(88,53,1),(88,70,1),(89,1,1),(89,21,1),(89,42,1),(89,46,1),(89,53,1);
/*!40000 ALTER TABLE `paket_box_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `master_transaction_id` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `method` enum('offline','online','manual_transfer') COLLATE utf8mb4_general_ci NOT NULL,
  `bank_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` enum('full','dp') COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_status` enum('pending','paid','failed','cancelled','remaining') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `midtrans_order_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `midtrans_transaction_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `midtrans_status` text COLLATE utf8mb4_general_ci,
  `proof_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `master_transaction_id` (`master_transaction_id`),
  KEY `idx_payment_status` (`payment_status`,`created_at`),
  KEY `idx_payment_method` (`method`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`master_transaction_id`) REFERENCES `master_transaksi` (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,'TRX-20251106001','online',NULL,'full',1360000.00,'paid','PAY-1-1762434953','a1c82b92-df10-423f-a04a-04c9adc4ba51','\"{\\\"status_code\\\":\\\"200\\\",\\\"transaction_id\\\":\\\"a1c82b92-df10-423f-a04a-04c9adc4ba51\\\",\\\"gross_amount\\\":\\\"1364440.00\\\",\\\"currency\\\":\\\"IDR\\\",\\\"order_id\\\":\\\"PAY-1-1762434953\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"signature_key\\\":\\\"26329d9706e1d708edfaca695985b3bfb61a6c72535a1320519737e644bdd95f4ec5aa0b2e2d74c8e8c813789b50c41ff06f44491cb34203561c84ff6352b1e1\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"status_message\\\":\\\"Success, transaction is found\\\",\\\"merchant_id\\\":\\\"G734607332\\\",\\\"permata_va_number\\\":\\\"8778001760672215\\\",\\\"transaction_time\\\":\\\"2025-11-06 20:16:02\\\",\\\"settlement_time\\\":\\\"2025-11-06 20:17:34\\\",\\\"expiry_time\\\":\\\"2025-11-07 20:16:02\\\"}\"',NULL,'2025-11-06 13:17:38','2025-11-06 18:15:53','2025-11-06 18:17:38'),(2,'TRX-20251109001','online',NULL,'full',1700000.00,'failed','PAY-2-1762689443','36dcac92-1690-4efd-9b0b-506ef9f42604','\"{\\\"status_code\\\":\\\"407\\\",\\\"transaction_id\\\":\\\"36dcac92-1690-4efd-9b0b-506ef9f42604\\\",\\\"gross_amount\\\":\\\"1704440.00\\\",\\\"currency\\\":\\\"IDR\\\",\\\"order_id\\\":\\\"PAY-2-1762689443\\\",\\\"payment_type\\\":\\\"echannel\\\",\\\"signature_key\\\":\\\"fd99b698f7d1bb645d3f21f9c935c8d5bd677daf8cd3e4862b541375be1a80b6016d2f5ea70fabaa8e8e18dbca3660139cf448da9fa1dc9f988e92ca04e0d31b\\\",\\\"transaction_status\\\":\\\"expire\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"status_message\\\":\\\"Success, transaction is found\\\",\\\"merchant_id\\\":\\\"G734607332\\\",\\\"bill_key\\\":\\\"78287581779\\\",\\\"biller_code\\\":\\\"70012\\\",\\\"transaction_time\\\":\\\"2025-11-09 18:57:34\\\",\\\"expiry_time\\\":\\\"2025-11-10 18:57:34\\\"}\"',NULL,NULL,'2025-11-09 16:57:23','2025-11-10 16:58:39'),(3,'TRX-20251109001','online',NULL,'full',1700000.00,'paid','PAY-3-1762689756','e0bcb09c-d031-442f-8aef-f8078aafd7af','\"{\\\"status_code\\\":\\\"200\\\",\\\"transaction_id\\\":\\\"e0bcb09c-d031-442f-8aef-f8078aafd7af\\\",\\\"gross_amount\\\":\\\"1704440.00\\\",\\\"currency\\\":\\\"IDR\\\",\\\"order_id\\\":\\\"PAY-3-1762689756\\\",\\\"payment_type\\\":\\\"bank_transfer\\\",\\\"signature_key\\\":\\\"c9f404f0d4287c31dc686189dc0b86aa880f8c59791a466b1b7a8d466e942fe8f025bf8c00fd512e97139234b2e1b99e3509fe9a61fb68b773ad5c39f4c31b6a\\\",\\\"transaction_status\\\":\\\"settlement\\\",\\\"fraud_status\\\":\\\"accept\\\",\\\"status_message\\\":\\\"Success, transaction is found\\\",\\\"merchant_id\\\":\\\"G734607332\\\",\\\"va_numbers\\\":[{\\\"bank\\\":\\\"bri\\\",\\\"va_number\\\":\\\"124122877579550399\\\"}],\\\"payment_amounts\\\":[],\\\"transaction_time\\\":\\\"2025-11-09 19:02:40\\\",\\\"settlement_time\\\":\\\"2025-11-09 19:04:20\\\",\\\"expiry_time\\\":\\\"2025-11-10 19:02:39\\\"}\"',NULL,'2025-11-09 12:04:23','2025-11-09 17:02:36','2025-11-09 17:04:23');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prasmanan_detail`
--

DROP TABLE IF EXISTS `prasmanan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prasmanan_detail` (
  `id_produk` int NOT NULL,
  `id_menu` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_produk`,`id_menu`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `prasmanan_detail_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  CONSTRAINT `prasmanan_detail_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `master_menu` (`id_menu`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prasmanan_detail`
--

LOCK TABLES `prasmanan_detail` WRITE;
/*!40000 ALTER TABLE `prasmanan_detail` DISABLE KEYS */;
INSERT INTO `prasmanan_detail` VALUES (101,1,1),(101,11,1),(101,22,1),(101,60,1),(101,71,1),(101,73,1),(101,74,1),(101,90,1),(102,1,1),(102,14,1),(102,31,1),(102,55,1),(102,61,1),(102,73,1),(102,80,1),(102,90,1),(103,1,1),(103,6,1),(103,32,1),(103,62,1),(103,73,1),(103,80,1),(103,90,1);
/*!40000 ALTER TABLE `prasmanan_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id_produk` int NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `harga` decimal(10,2) NOT NULL,
  `kategori_produk` enum('paket_box','prasmanan','pondokan','tumpeng') COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah_orang` int DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `gambar` varchar(70) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`),
  KEY `idx_produk_kategori` (`kategori_produk`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES (76,'Nasi Kuning Box Rolade','Nasi Kuning, Rolade ,Perkedel, Mie, Telur, Sambel Goreng Kentang',35000.00,'paket_box',NULL,'active','produk/FvwqHcb5FjEgjTT7GnBTd23BYCydbX4ReWWm3XYQ.jpg','nasi-kuning-box-rolade','2025-10-28 09:48:18'),(77,'Nasi Box Ayam','Nasi putih, Ayam Goreng, Tempe Terik, Telur ,Megono ,Mie',34000.00,'paket_box',NULL,'active','produk/cr1HfRxc0YZmCGnfvz15uSAZOcptw9CxeHxbigjv.jpg','nasi-box-ayam','2025-10-28 09:50:41'),(78,'Nasi Box Ayam Goreng','Nasi Putih, Ayam goreng ,Sambel goreng kentang,Telur Asin,Lalapan',30000.00,'paket_box',NULL,'active','produk/SntU459pc2ak9yVj4og3EEHOnzbOXnlZHO8iDE2E.jpg','nasi-box-ayam-goreng','2025-10-28 09:52:55'),(79,'Nasi Box Rendang','Mie, Rendang , Telur asin, Sayur , Kentang, Kerupuk',35000.00,'paket_box',NULL,'active','produk/xW7y3FdXq6V0YsAtxVrNcScvtrJiKq3tCdJplgNa.jpg','nasi-box-rendang','2025-10-28 09:55:45'),(80,'Nasi Kuning Box Ayam','Nasi Kuning, Ayam serundeng, Sambel goreng kentang, perkedel, telur ,mie',35000.00,'paket_box',NULL,'active','produk/LU3kY6UnOcR3wOBnp4qWRCh6qI7fPpQbLdphQAQg.jpg','nasi-kuning-box-ayam','2025-10-28 10:06:01'),(81,'Nasi Bakar Box Ayam','Nasi Bakar Ayam, Tahu, Lalapan,Keripik',17000.00,'paket_box',NULL,'active','produk/vBXZXPaR5GtnzR6Q4tirxmoskk3ljqS1dfxOC0ix.jpg','nasi-bakar-box-ayam','2025-10-28 10:08:39'),(82,'Nasi Box Ayam Goreng 2','Nasi putih, Ayam goreng, Tahu,lalapan ,Pisang',32000.00,'paket_box',NULL,'active','produk/HY8T8uc9VUPrIy3tnDP3e0RVBexoshrivlq2GaTG.jpg','nasi-box-ayam-goreng-2','2025-10-28 10:10:51'),(83,'Nasi Box Ayam Kremez','Nasi Putih, Ayam Kremes, Tahu ,Lalapan, Sambel',34000.00,'paket_box',NULL,'active','produk/zzn8qAbDuyWcyhVhBAoSL1e3KVRa0E1pW4fd3KMN.jpg','nasi-box-ayam-kremez','2025-10-28 10:13:02'),(84,'Nasi Tumpeng Tamahan Lamongan','Nasi Putih ,lele, sayur gudangan, tempe terik, mie goreng',40000.00,'tumpeng',NULL,'active','produk/JE8jNcGOtKHGKPmH4QHHDhRE7y3ubbBLUx6SgoGf.jpg','nasi-tumpeng-tamahan-lamongan','2025-10-28 10:24:17'),(85,'Nasi Tumpeng Sedoan Ayam','Nasi putih ,Ayam ,Mie, Telur dadar, Tempe Terik, Urap sayur,',40000.00,'tumpeng',NULL,'active','produk/xmf1OvTqGwIeDuxIdPvqapkZ8FLdnLCtPvFGwJ6e.jpg','nasi-tumpeng-sedoan-ayam','2025-10-28 10:27:56'),(86,'Nasi Kuning Tumpeng Ayam','Nasi Kuning, Ayam serundeng, Perkedel, Oseng Tempe, Telur, Mie , sambel goreng kentang',40000.00,'tumpeng',NULL,'active','produk/1qBAvkA4cNWS5XfiOPjYd2x0FjqlrGVukX40Tiwz.jpg','nasi-kuning-tumpeng-ayam','2025-10-28 10:39:48'),(87,'Nasi Box Ikan Lele','Nasi Putih, Ikan Lele, Acar, Telur Balado,Sambal goreng kentang',30000.00,'paket_box',NULL,'active','produk/cB70ucB9iibOhgbfkWd5ZRgca3qePICmMokxsDyt.jpg','nasi-box-ikan-lele','2025-10-28 10:53:46'),(88,'Nasi box Ayam Bakar Capcay','Nasi Putih, Ayam bakar, Capcay, Mie ,Sambel goreng kentang',34000.00,'paket_box',NULL,'active','produk/0NfUzMt5LxJaJQ08XqBSkOhiZNiPqRMosdWMCpXa.jpg','nasi-box-ayam-bakar-capcay','2025-10-29 03:27:31'),(89,'Nasi Box Bawal Goreng','Nasi Putih , Ikan Bawal Goreng, Capcay, Telur balado, Oseng tempe',32000.00,'paket_box',NULL,'active','produk/kU5jvIxdaBRxEdxGf7z9PKGqGtVj5eprRh4bxXfK.jpg','nasi-box-bawal-goreng','2025-10-29 03:30:03'),(90,'Siomay','Harga tertera untuk / porsi',8000.00,'pondokan',NULL,'active','produk/8Bu5vwLQ0F4gVzZlXgQVXnCFmlnx5aQCquWtFTyA.jpg','siomay','2025-10-29 03:41:52'),(91,'Bakso','Harga tertera untuk / porsi',10000.00,'pondokan',NULL,'active','produk/iuNrDzwrnjfsZ2Yr6GofdNW29ZSVsZx5eOqGPbg6.png','bakso','2025-10-29 03:44:26'),(92,'Mie Jawa','Harga tertera untuk / porsi',10000.00,'pondokan',NULL,'active','produk/Lb0WRfvDr9T0TlkLpGUzxC4Nh9Usb29qYzzpaEfP.jpg','mie-jawa','2025-10-29 03:50:34'),(93,'Sate Ayam','Harga tertera untuk / porsi',11000.00,'pondokan',NULL,'active','produk/pwhf1etCUi9v6nOxdC2cHeVFkxXTn9Miv0tNkXGm.jpg','sate-ayam','2025-10-29 03:53:08'),(94,'Soto Ayam','Harga tertera untuk / porsi',10500.00,'pondokan',NULL,'active','produk/5oC7pD7uvBDvsmEBWaTyYF2vBap5k6CPD9SPFbmP.jpg','soto-ayam','2025-10-29 03:55:57'),(95,'Pempek','Harga tertera untuk / porsi',11700.00,'pondokan',NULL,'active','produk/0aNMYpIdUrniP9ycuAzJNPhyH6GcZyWmTjXRkuPo.jpg','pempek','2025-10-29 04:07:28'),(96,'Sup buah','Harga tertera untuk / porsi',9500.00,'pondokan',NULL,'active','produk/lXh6d6Z7iPqRLPFWusUH2CW7GwmwdcE4JwqZZSsX.jpg','sup-buah','2025-10-29 04:09:09'),(97,'Es Dawet','Harga tertera untuk / porsi',7400.00,'pondokan',NULL,'active','produk/tDteHMUDCPwClzymBgSdmdaS7lqQrN4obfX6hHD3.jpg','es-dawet','2025-10-29 04:12:01'),(98,'Dimsum','Harga tertera untuk / porsi isi 3',13500.00,'pondokan',NULL,'active','produk/Ne4WyKUNkO55SDke5zS7U6bWbPJA2jgf6HSvtyfD.jpg','dimsum','2025-10-29 04:15:45'),(99,'Tengkleng','Harga tertera untuk / porsi',15000.00,'pondokan',NULL,'active','produk/JjoLVH2oQnXzZACs7tKSMJ2Us1SVNsz2mTjBiKzB.jpg','tengkleng','2025-10-29 04:20:16'),(100,'Kebab Turki','Harga tertera untuk / porsi',7000.00,'pondokan',NULL,'active','produk/cZr48RLEnnu3PEpaRbB05j7yF4fZG5JIQJHYW4oO.jpg','kebab-turki','2025-10-29 05:16:16'),(101,'Paket Prasmanan 1','- Nasi - Ayam Bakar - Oseng Soon Cabe Ijo - Soup Jamur Bakso Sosis - Gurame Asam Manis - Krupuk - Sambal - Air Mineral',3500000.00,'prasmanan',100,'active','produk/2GCAef4oWCRXaoCGHWfVSEs23FAEDTdUtDFgCZor.jpg','paket-prasmanan-1','2025-10-29 06:10:22'),(102,'Paket Prasmanan 2','Nasi putih\r\nSoup Ayam Sosis\r\nAyam Goreng Mentega\r\n Rolade daging\r\nSelada Bangkok\r\nKrupuk Udang\r\nAneka buah potong\r\nAir mineral',2700000.00,'prasmanan',100,'active','produk/EhHMvizEC4ZL3YvdCxm8EsZNdAJrSE9BMG4VoW30.jpg','paket-prasmanan-2','2025-10-29 06:24:41'),(103,'Paket Prasmanan 3','Nasi putih, Soup Ayam Jagung,Daging Teriyaki,Bihun Goreng,Krupuk Udang\r\nAneka buah potong\r\nAir mineral',2500000.00,'prasmanan',100,'active','produk/TUtUDzAyjWGwgdwaJVRjVl31rduruEZjPPTMQo1J.jpg','paket-prasmanan-3','2025-10-29 06:28:54'),(104,'test','qq',1.00,'paket_box',NULL,'active',NULL,'test','2025-11-01 17:01:22');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('74qr1qD8XqZ086Ot2byvOBlBrmahjCih1U4jUEBS',NULL,'184.105.247.196','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0lWV0E3azlJU3hTczlHOUlOTjZjN0U5VVg5emViVzhldDJqenFDTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMTQ2LjIzNS4xOTYuMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762820541),('cSIjbuiHvDsFgYMzxL9oeKotrcsC7kTshdBKZ2ef',NULL,'196.251.88.64','Mozilla/5.0 (Linux; Android 7.0; Redmi Note 4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.111 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWNnSEhsejR2enNiSEpScGZHSTJyWnF5eXRvckFVWU5RMmMwN3RHeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vbmFzaXR1bXBlbmdiYXRhbmdjYXRlcmluZy5teS5pZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762824627),('eNdxC0VkqOu8VCHD9n6ZrKL7vY2j8IYLh0hQjKRX',NULL,'185.242.226.121','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMjFyeVFEaXByZ0swVW1QdmlJcGlhYXBGa0pLTXdiS0NPd3MwRlZ4SSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMTQ2LjIzNS4xOTYuMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762822551),('G3kKnyLcWHWFe7R0Qlu9JmkaOD771XQp3OeCZN5J',NULL,'123.160.223.73','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic2hDM0dUNTZJQTFYb2haSkRHNGhkZ0h4NFVFR1pSaEJ6N0cwVXZtYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMTQ2LjIzNS4xOTYuMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762823049),('LcmotEkPk8p04PZxvGL0tHtDqGVrhOqTNsejDErs',NULL,'123.160.223.73','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidWVnYXAxYXZ2WUNiNGQ5SXpQR0dRRUVCc3JIMEE4UlVqM250OEtkMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMTQ2LjIzNS4xOTYuMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762823049),('MIQyEJ2TrHekh014PAuBqh6x1RaQXADZQ1PTDfoZ',NULL,'184.105.247.196','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXFQd21sRzNubnV2ZWZwZG0zVkZBdm91akxuUXJFNHB1aWVDU0hyNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMTQ2LjIzNS4xOTYuMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762819851),('nqj02lkPk6RXsv9lGyoU3K8CmxY8FeaY2hJLJWy4',NULL,'47.251.91.169','curl/7.64.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXphTjBmelpxTTN4QXcxUFZmdENidFRnQkI4QjA5Y2hBeWVlTGJmUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMTQ2LjIzNS4xOTYuMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762823012),('vE4k0XJ0V88iQpLRZyag07mfZSxvZXmGgsbfuoIA',12,'114.10.44.195','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSG1OcXlxT1BoWDV1Tm4xMm9Xdk92WHB6WDdLaE9MY2JzYjFHakpYYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzE6Imh0dHBzOi8vbmFzaXR1bXBlbmdiYXRhbmdjYXRlcmluZy5teS5pZC9hZG1pbi90cmFuc2Frc2kvVFJYLTIwMjUxMTA5MDAxIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7fQ==',1762828432),('zCspm98hni7HVhn1TnUhaWJmNPbPd1jY3qv72UGG',NULL,'5.189.130.33','Mozilla/5.0 (CentOS; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibHVOME1QRXNMTndCSnQ5RHlmdm5EMFVVUGhzdWxXd05SajdqdUlHaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vMTQ2LjIzNS4xOTYuMzQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1762819565);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_log`
--

DROP TABLE IF EXISTS `status_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_log` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `status_from` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_to` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_customer_id` int DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `idx_status_log_transaksi` (`id_transaksi`,`created_at`),
  KEY `fk_status_log_user` (`created_by`),
  CONSTRAINT `status_log_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `master_transaksi` (`id_transaksi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_log`
--

LOCK TABLES `status_log` WRITE;
/*!40000 ALTER TABLE `status_log` DISABLE KEYS */;
INSERT INTO `status_log` VALUES (1,'TRX-20251106001',NULL,'pending','Transaksi dibuat',0,'2025-11-06 13:15:33',NULL),(2,'TRX-20251106001',NULL,'pending','Pesanan dibuat',14,'2025-11-06 13:15:33',NULL),(3,'TRX-20251106001','pending','confirmed','Status berubah dari pending ke confirmed',0,'2025-11-06 13:17:38',NULL),(4,'TRX-20251106001','confirmed','preparing','Status berubah dari confirmed ke preparing',0,'2025-11-07 02:45:24',NULL),(5,'TRX-20251106001','preparing','delivered','Status berubah dari preparing ke delivered',0,'2025-11-09 10:43:20',NULL),(6,'TRX-20251106001','delivered','completed','Status berubah dari delivered ke completed',0,'2025-11-09 10:44:02',NULL),(7,'TRX-20251109001',NULL,'pending','Transaksi dibuat',0,'2025-11-09 10:56:48',NULL),(8,'TRX-20251109001',NULL,'pending','Pesanan dibuat',15,'2025-11-09 10:56:48',NULL),(9,'TRX-20251109001','pending','confirmed','Status berubah dari pending ke confirmed',0,'2025-11-09 12:04:23',NULL),(10,'TRX-20251109001','confirmed','delivered','Status berubah dari confirmed ke delivered',0,'2025-11-10 02:52:27',NULL),(11,'TRX-20251109001','delivered','preparing','Status berubah dari delivered ke preparing',0,'2025-11-10 02:52:32',NULL),(12,'TRX-20251109001','preparing','ready','Status berubah dari preparing ke ready',0,'2025-11-11 02:31:48',NULL),(13,'TRX-20251109001','ready','delivered','Status berubah dari ready ke delivered',0,'2025-11-11 02:31:59',NULL),(14,'TRX-20251109001','delivered','completed','Status berubah dari delivered ke completed',0,'2025-11-11 02:32:47',NULL);
/*!40000 ALTER TABLE `status_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_menu_custom`
--

DROP TABLE IF EXISTS `transaksi_menu_custom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi_menu_custom` (
  `id_transaksi` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `id_menu` int NOT NULL,
  `qty` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_transaksi`,`id_menu`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `transaksi_menu_custom_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `master_transaksi` (`id_transaksi`) ON DELETE CASCADE,
  CONSTRAINT `transaksi_menu_custom_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `master_menu` (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_menu_custom`
--

LOCK TABLES `transaksi_menu_custom` WRITE;
/*!40000 ALTER TABLE `transaksi_menu_custom` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksi_menu_custom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(70) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `role` enum('pelanggan','admin','super_admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pelanggan',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (11,'masnuu1@gmail.com','$2y$12$Bf2N17oak1vnOiNp3lGq6.659VgEcmUlq0zQ968rzGQFQSjy.iX.e','mas nuuu','085678901234','hdgdf','pelanggan','2025-09-18 04:11:16',1,'2025-09-18 11:11:16'),(12,'kemuningcatering7@gmail.com','$2y$12$1L8ivoWezweGfdBH102wR.BfjA6sUL3MjQk//YqIXswG2yRCq//x6','Owner','082217463605',NULL,'super_admin','2025-11-10 07:52:12',1,'2025-09-29 10:26:10'),(14,'fiaiyah82@gmail.com','$2y$12$iga4Bzkt2HofbavM33qRP.3LDgGpYfPTA3bUyIzuwVtDo3uEnHE.W','Fia Uswa','089673335832','Poncol GG 14E Kemuning No. 40 Pekalongan Timur Pekalongan, Jawa Tengah 51122','pelanggan','2025-11-06 18:08:54',1,'2025-11-06 13:08:31'),(15,'nailulseinaa@gmail.com','$2y$12$5TkSlH0ttkSSj/BeyJ9cuObu4/ZKvxC.SgPpG9flC9Jzsrng/Ws4i','Nailul','085643539077',NULL,'pelanggan','2025-11-09 15:46:10',1,'2025-11-09 10:42:09');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-10 22:13:24
