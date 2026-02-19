/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Android (aarch64)
--
-- Host: localhost    Database: penjualan_umkm
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `admins` VALUES
(1,'DANNYS MARTHA','$2y$12$cyhN2BD1YB8ZBQtsiJKYzOQkJQ6NrqszvMrq5Q7R7Sizn2kR5mqeq');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pembeli` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `status` enum('pending','setuju','tolak') DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `pesanan` VALUES
(1,'DANNYS MARTHA FAVRILLIA','6285645837298','Genteng',1,100000.00,14,'setuju'),
(2,'DANNYS MARTHA FAVRILLIA','6285645837298','Genteng',1,15000.00,15,'pending'),
(3,'ROHMAN ROHIM','6285645837298','Genteng',1,15000.00,15,'pending'),
(4,'Jihan','6285645837298','Genteng',1,15000.00,15,'pending'),
(5,'Julia','6285645837298','Genteng',1,15000.00,15,'pending'),
(6,'Mas apip','6285645837298','Genteng',1,15000.00,15,'pending'),
(7,'Mas dimas','6285645837298','Genteng',1,15000.00,15,'pending'),
(8,'Mas Prapto','6285645837298','Genteng',1,15000.00,15,'pending'),
(9,'Mas ringga','6285645837298','Genteng',1,15000.00,15,'pending'),
(10,'Mas Ndoro','6285645837298','Genteng',1,15000.00,15,'pending'),
(11,'Mbak Calisa','6285645837298','Genteng',1,15000.00,15,'pending');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produk`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `produk` VALUES
(12,'Nasi goreng',20000.00,0,'Nasi goreng adalah sebuah makanan berupa nasi yang digoreng dan dicampur dalam minyak goreng, margarin, atau mentega. Biasanya ditambah dengan kecap manis, bawang merah, bawang putih, asam jawa, lada, dan bahan yang lainnya; seperti telur, daging ayam, dan kerupuk. Ada pula nasi goreng jenis lain yang dibuat bersama dengan ikan asin yang juga populer di seluruh wilayah Indonesia.','1771158623-images (22) (13).jpeg','2026-02-15 12:30:23'),
(13,'Soto Ayam Recipe',26000.00,0,'Soto Ayam adalah sup ayam tradisional Indonesia yang sangat populer, berciri khas kuah kuning gurih yang kaya rempah. Kuah kuning ini diperoleh dari penggunaan kunyit, berpadu dengan bumbu aromatik seperti serai, daun jeruk, dan jahe.','1771159160-images.webp','2026-02-15 12:39:20'),
(14,'Nasi kebuli',100000.00,0,'Nasi kebuli adalah hidangan nasi berbumbu kaya rempah khas Timur Tengah yang populer di Indonesia, khususnya Betawi, bercita rasa gurih dengan aroma dominan kapulaga dan minyak samin. Nasi ini dimasak bersama kaldu daging kambing, susu kambing, dan minyak samin, biasanya disajikan dengan daging kambing goreng/bakar, acar, dan kismis.','1771159621-images (22) (14).jpeg','2026-02-15 12:47:01'),
(15,'Bakso Amba',15000.00,1,'Bakso adalah makanan khas Indonesia berbentuk bulatan yang terbuat dari campuran daging giling (sapi, ayam, atau ikan), tepung tapioka, dan bumbu. Bertekstur kenyal, bakso umumnya disajikan panas dengan kuah kaldu gurih, mie, bihun, tahu, dan sayuran. Istilah ini berasal dari bahasa Hokkien \"bak-so\" (肉酥) yang berarti daging giling.','1771291605-ebf13568.png','2026-02-17 01:26:45');
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `testimoni`
--

DROP TABLE IF EXISTS `testimoni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `bintang` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimoni`
--

LOCK TABLES `testimoni` WRITE;
/*!40000 ALTER TABLE `testimoni` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `testimoni` VALUES
(6,'Dannys Martha','Web Developer','Nasi kebulinya lumayan enak, rempahnya berasa tapi nggak yang nusuk banget di hidung. Daging kambingnya empuk, nggak bau prengus juga. Porsinya cukup ngenyangin buat harga segini. Pas banget dimakan bareng acar nanasnya biar nggak enek.',5),
(7,'Dimas Subakti','Buruh Pabrik','Awalnya penasaran gimana rasanya nasi goreng soto, ternyata unik juga. Bumbunya ngeresep, ada aroma kunyit sama rempah sotonya tapi tetep dapet tekstur nasi goreng yang kering. Potongan ayamnya nggak pelit. Cocok buat yang bosen sama nasi goreng kecap biasa.',5),
(8,'Agung Cronos','CEO PT GARENA','Baksonya oke sih, dagingnya berasa banget bukan yang tipe kebanyakan tepung. Kuahnya gurih bening gitu, nggak yang bikin serik di tenggorokan. Sambalnya lumayan nendang, porsi juga pas buat makan siang. Bakal repeat order lagi sih kalau lagi pengen yang seger-seger.',5);
/*!40000 ALTER TABLE `testimoni` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-02-18 23:58:49
