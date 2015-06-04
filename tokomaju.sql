-- MySQL dump 10.13  Distrib 5.6.24, for Win32 (x86)
--
-- Host: localhost    Database: tokomaju
-- ------------------------------------------------------
-- Server version	5.6.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cms_accounts`
--

DROP TABLE IF EXISTS `cms_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` tinyint(4) NOT NULL,
  `username` varchar(500) DEFAULT NULL,
  `email` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `last_login` datetime NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_accounts`
--

LOCK TABLES `cms_accounts` WRITE;
/*!40000 ALTER TABLE `cms_accounts` DISABLE KEYS */;
INSERT INTO `cms_accounts` VALUES (1,1,1,'admin','admin@yahoo.com','169e781bd52860b584879cbe117085da596238f3','2015-06-03 10:26:34','2013-01-04 00:00:00',1,'2014-05-05 15:15:38',1);
INSERT INTO `cms_accounts` VALUES (3,2,2,'alex','alex_mallian@yahoo.com','25c06f36934b477b25d3421195ba70f0fe5cf52b','2015-01-05 22:09:09','2015-01-05 15:55:28',1,'2015-01-05 15:55:28',1);
/*!40000 ALTER TABLE `cms_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_entries`
--

DROP TABLE IF EXISTS `cms_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_type` varchar(500) NOT NULL,
  `title` varchar(500) NOT NULL,
  `slug` varchar(500) NOT NULL,
  `description` text,
  `main_image` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `count` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `lang_code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=182 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_entries`
--

LOCK TABLES `cms_entries` WRITE;
/*!40000 ALTER TABLE `cms_entries` DISABLE KEYS */;
INSERT INTO `cms_entries` VALUES (1,'customer','Andy Basuki','andy-basuki','just a test',0,0,1,0,'2014-11-23 10:59:31',1,'2014-11-23 10:59:32',1,1,'en-1');
INSERT INTO `cms_entries` VALUES (2,'supplier','He Xin Min','he-xin-min','fake supplier',0,0,1,0,'2014-11-23 11:27:33',1,'2014-11-23 11:27:33',1,2,'en-2');
INSERT INTO `cms_entries` VALUES (3,'ekspedisi','Pak Gunawan Santoso','pak-gunawan-santoso','Ekspedisi Sby - Kudus OKE',0,0,1,0,'2014-11-23 14:37:16',1,'2014-11-23 14:37:16',1,3,'en-3');
INSERT INTO `cms_entries` VALUES (4,'gudang','Pengampon','pengampon','Gudang satu-satunya.',0,0,1,32,'2014-11-25 11:05:06',1,'2015-01-05 22:17:59',1,4,'en-4');
INSERT INTO `cms_entries` VALUES (5,'jenis-barang','Bahan Baku','bahan-baku','test bahan',0,0,1,0,'2014-11-25 11:24:04',1,'2014-11-25 11:24:04',1,5,'en-5');
INSERT INTO `cms_entries` VALUES (6,'jenis-barang','Mesin','mesin','test jenis mesin.\r\nOke.',0,0,1,0,'2014-11-25 11:24:23',1,'2014-11-25 11:24:23',1,6,'en-6');
INSERT INTO `cms_entries` VALUES (7,'jenis-barang','Sparepart','sparepart','berbagai barang pendukung',0,0,1,0,'2014-11-25 11:26:10',1,'2014-11-25 11:26:10',1,7,'en-7');
INSERT INTO `cms_entries` VALUES (8,'barang-dagang','Mesin Hardcover Maker','mesin-hardcover-maker','test barang dagang',0,0,1,0,'2014-11-25 12:16:57',1,'2014-11-25 12:21:47',1,8,'en-8');
INSERT INTO `cms_entries` VALUES (9,'supplier','Indah Pertiiwi','indah-pertiiwi','Specialist Mesin',0,0,1,0,'2014-11-25 12:21:11',1,'2014-11-25 12:21:11',1,9,'en-9');
INSERT INTO `cms_entries` VALUES (14,'pindah-masuk','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker','Ditambahkan oleh administrator.',5,4,1,0,'2014-11-25 16:38:42',1,'2014-11-25 16:38:42',1,14,'en-14');
INSERT INTO `cms_entries` VALUES (16,'barang-gudang','mesin-hardcover-maker','mesin-hardcover-maker-1','masukan lagi dan lagi gan.\r\nThankyou\r\nGBU',0,4,1,0,'2014-11-25 17:41:11',1,'2014-11-26 17:36:24',1,16,'en-16');
INSERT INTO `cms_entries` VALUES (15,'pindah-keluar','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-1','Ditiadakan oleh administrator.',5,4,1,0,'2014-11-25 17:39:55',1,'2014-11-25 17:39:56',1,15,'en-15');
INSERT INTO `cms_entries` VALUES (17,'pindah-masuk','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-2','Ditambahkan oleh administrator.',12,4,1,0,'2014-11-25 17:41:11',1,'2014-11-25 17:41:11',1,17,'en-17');
INSERT INTO `cms_entries` VALUES (18,'barang-dagang','Bahan Tinta','bahan-tinta','tinta terhebat di dunia\r\nOke',0,0,1,0,'2014-11-25 17:43:29',1,'2014-11-25 17:43:29',1,18,'en-18');
INSERT INTO `cms_entries` VALUES (22,'pindah-keluar','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-3','Dihapus oleh administrator.',5,4,1,0,'2014-11-26 12:07:35',1,'2014-11-26 12:07:35',1,22,'en-22');
INSERT INTO `cms_entries` VALUES (20,'pindah-masuk','automatic_bahan-tinta','automatic-bahan-tinta','Ditambahkan oleh administrator.',15,4,1,0,'2014-11-25 17:44:34',1,'2014-11-25 17:44:34',1,20,'en-20');
INSERT INTO `cms_entries` VALUES (21,'pindah-keluar','automatic_bahan-tinta','automatic-bahan-tinta-1','Dihapus oleh administrator.',15,4,1,0,'2014-11-25 17:45:55',1,'2014-11-25 17:45:55',1,21,'en-21');
INSERT INTO `cms_entries` VALUES (23,'pindah-masuk','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-4','Ditambahkan oleh administrator.',13,4,1,0,'2014-11-26 12:08:45',1,'2014-11-26 12:08:45',1,23,'en-23');
INSERT INTO `cms_entries` VALUES (24,'pindah-keluar','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-5','Dihapus oleh administrator.',12,4,1,0,'2014-11-26 12:11:47',1,'2014-11-26 12:11:48',1,24,'en-24');
INSERT INTO `cms_entries` VALUES (31,'pindah-keluar','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-6','Pengurangan stok barang oleh administrator.',2,4,1,0,'2014-11-26 17:34:22',1,'2014-11-26 17:34:22',1,31,'en-31');
INSERT INTO `cms_entries` VALUES (30,'pindah-masuk','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-7','Ditambahkan oleh administrator.',10,4,1,0,'2014-11-26 14:04:08',1,'2014-11-26 14:04:08',1,30,'en-30');
INSERT INTO `cms_entries` VALUES (27,'pindah-masuk','automatic_bahan-tinta','automatic-bahan-tinta-2','Ditambahkan oleh administrator.',3,4,1,0,'2014-11-26 12:13:27',1,'2014-11-26 12:13:27',1,27,'en-27');
INSERT INTO `cms_entries` VALUES (28,'pindah-keluar','automatic_bahan-tinta','automatic-bahan-tinta-3','Dihapus oleh administrator.',2,4,1,0,'2014-11-26 12:14:14',1,'2014-11-26 12:14:14',1,28,'en-28');
INSERT INTO `cms_entries` VALUES (29,'pindah-keluar','automatic_bahan-tinta','automatic-bahan-tinta-4','Dihapus oleh administrator.',1,4,1,0,'2014-11-26 12:14:32',1,'2014-11-26 12:14:32',1,29,'en-29');
INSERT INTO `cms_entries` VALUES (32,'pindah-masuk','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-8','Penambahan stok barang oleh administrator.',5,4,1,0,'2014-11-26 17:36:24',1,'2014-11-26 17:36:24',1,32,'en-32');
INSERT INTO `cms_entries` VALUES (33,'gudang','Romokalisari','romokalisari','Gudang coba coba.\r\ntest oke',0,0,1,13,'2014-11-27 09:38:12',1,'2015-01-03 16:04:20',1,33,'en-33');
INSERT INTO `cms_entries` VALUES (34,'barang-gudang','mesin-hardcover-maker','mesin-hardcover-maker-2','',0,33,1,0,'2014-11-27 09:38:38',1,'2014-11-27 09:38:38',1,34,'en-34');
INSERT INTO `cms_entries` VALUES (35,'pindah-masuk','automatic_mesin-hardcover-maker','automatic-mesin-hardcover-maker-9','Penambahan stok barang oleh administrator.',12,33,1,0,'2014-11-27 09:38:38',1,'2014-11-27 09:38:39',1,35,'en-35');
INSERT INTO `cms_entries` VALUES (160,'hutang','HUT150108001XXXX','hut150108001xxxx','',0,104,1,0,'2015-01-05 21:55:19',3,'2015-01-05 21:55:19',3,160,'en-160');
INSERT INTO `cms_entries` VALUES (39,'purchase-order','PUR141204001','pur141204001','First Purchase Test !!\r\nthanks\r\noke\r\nGBU',0,0,1,11,'2014-12-04 10:01:11',1,'2014-12-17 11:35:22',1,39,'en-39');
INSERT INTO `cms_entries` VALUES (40,'purchase-detail','mesin-hardcover-maker','mesin-hardcover-maker-3',NULL,0,39,1,0,'2014-12-04 10:01:11',1,'2014-12-04 10:01:11',1,40,'en-40');
INSERT INTO `cms_entries` VALUES (41,'hutang','HUT141204001','hut141204001','Beli <strong>Mesin Hardcover Maker</strong> sebanyak 3 unit @Rp 6.000.000,-',0,39,1,0,'2014-12-04 10:01:11',1,'2014-12-04 10:01:11',1,41,'en-41');
INSERT INTO `cms_entries` VALUES (42,'purchase-detail','bahan-tinta','bahan-tinta-1',NULL,0,39,1,0,'2014-12-04 10:01:11',1,'2014-12-04 10:01:11',1,42,'en-42');
INSERT INTO `cms_entries` VALUES (43,'hutang','HUT141204002','hut141204002','Beli <strong>Bahan Tinta</strong> sebanyak 7 Galon @Rp 345.000,-',0,39,1,0,'2014-12-04 10:01:12',1,'2014-12-04 10:01:12',1,43,'en-43');
INSERT INTO `cms_entries` VALUES (44,'hutang','HUT141204003','hut141204003','Pembayaran PUR141204001 telah lunas.',0,39,1,0,'2014-12-04 10:01:12',1,'2014-12-04 10:01:12',1,44,'en-44');
INSERT INTO `cms_entries` VALUES (45,'pages','Database Sequence','database-sequence','#MASTER\r\nCUSTOMER\r\nSUPPLIER\r\nEKSPEDISI\r\nGUDANG\r\nJENIS BARANG\r\nBARANG DAGANG\r\n#PEMBELIAN\r\nPURCHASE ORDER\r\nBARANG MASUK\r\nHUTANG\r\n#PENJUALAN\r\nSALES ORDER\r\nSURAT JALAN\r\nRESI\r\nPIUTANG\r\n#RETUR\r\nRETUR BELI\r\nRETUR JUAL',0,0,1,0,'2014-12-05 17:12:50',1,'2014-12-05 17:12:50',1,45,'en-45');
INSERT INTO `cms_entries` VALUES (46,'barang-masuk','bahan-tinta','bahan-tinta-2','masuk tinta',0,39,1,0,'2014-12-16 09:48:06',1,'2014-12-16 09:48:06',1,46,'en-46');
INSERT INTO `cms_entries` VALUES (47,'barang-gudang','bahan-tinta','bahan-tinta-3','Kiriman dari supplier.',0,33,1,0,'2014-12-16 09:48:06',1,'2014-12-16 09:48:06',1,47,'en-47');
INSERT INTO `cms_entries` VALUES (48,'pindah-masuk','he-xin-min_bahan-tinta','he-xin-min-bahan-tinta','Pembelian INVOICE kode PUR141204001',3,33,1,0,'2013-12-25 00:00:00',1,'2014-12-16 09:48:06',1,48,'en-48');
INSERT INTO `cms_entries` VALUES (49,'barang-masuk','mesin-hardcover-maker','mesin-hardcover-maker-4','masuk mesin',0,39,1,0,'2014-12-16 09:48:07',1,'2014-12-16 09:48:07',1,49,'en-49');
INSERT INTO `cms_entries` VALUES (50,'pindah-masuk','he-xin-min_mesin-hardcover-maker','he-xin-min-mesin-hardcover-maker','Pembelian INVOICE kode PUR141204001',2,33,1,0,'2013-12-25 00:00:00',1,'2014-12-16 09:48:07',1,50,'en-50');
INSERT INTO `cms_entries` VALUES (51,'barang-masuk','mesin-hardcover-maker','mesin-hardcover-maker-5','',0,39,1,0,'2014-12-16 10:55:27',1,'2014-12-16 10:55:27',1,51,'en-51');
INSERT INTO `cms_entries` VALUES (52,'pindah-masuk','he-xin-min_mesin-hardcover-maker','he-xin-min-mesin-hardcover-maker-1','Pembelian INVOICE kode PUR141204001',1,4,1,0,'2014-12-16 00:00:00',1,'2014-12-16 10:55:27',1,52,'en-52');
INSERT INTO `cms_entries` VALUES (53,'barang-masuk','bahan-tinta','bahan-tinta-4','',0,39,1,0,'2014-12-16 11:55:29',1,'2014-12-16 11:55:29',1,53,'en-53');
INSERT INTO `cms_entries` VALUES (54,'pindah-masuk','he-xin-min_bahan-tinta','he-xin-min-bahan-tinta-1','Pembelian INVOICE kode PUR141204001',4,33,1,0,'2014-12-16 00:00:00',1,'2014-12-16 11:55:29',1,54,'en-54');
INSERT INTO `cms_entries` VALUES (57,'hutang','1234-5678-9012','1234-5678-9012','kembalikan kelebihan bayar\r\nOKE thankyou :)',0,39,1,0,'2014-12-17 11:35:22',1,'2014-12-17 11:35:22',1,57,'en-57');
INSERT INTO `cms_entries` VALUES (56,'hutang','HUT141217001','hut141217001','Lebih bayar OKE',0,39,1,0,'2014-12-17 11:33:04',1,'2014-12-17 11:33:04',1,56,'en-56');
INSERT INTO `cms_entries` VALUES (60,'purchase-order','PUR141219002','pur141219002','',0,0,1,2,'2014-12-19 10:27:53',1,'2014-12-19 10:27:54',1,60,'en-60');
INSERT INTO `cms_entries` VALUES (59,'purchase-order','PUR141219001','pur141219001','',0,0,1,0,'2014-12-19 10:22:23',1,'2014-12-19 10:22:23',1,59,'en-59');
INSERT INTO `cms_entries` VALUES (61,'purchase-detail','mesin-hardcover-maker','mesin-hardcover-maker-6',NULL,0,60,1,0,'2014-12-19 10:27:53',1,'2014-12-19 10:27:53',1,61,'en-61');
INSERT INTO `cms_entries` VALUES (62,'hutang','HUT141219001','hut141219001','Beli <strong>Mesin Hardcover Maker</strong> sebanyak 3 unit @Rp.6.000.000,-',0,60,1,0,'2014-12-19 10:27:53',1,'2014-12-19 10:27:53',1,62,'en-62');
INSERT INTO `cms_entries` VALUES (64,'sales-order','SAL301205001','sal301205001','sales order pertamaku gan.\r\nhaha thx',0,0,1,10,'2014-12-19 14:59:17',1,'2015-01-03 16:04:20',1,64,'en-64');
INSERT INTO `cms_entries` VALUES (65,'sales-detail','mesin-hardcover-maker','mesin-hardcover-maker-7',NULL,0,64,1,0,'2014-12-19 14:59:17',1,'2014-12-19 14:59:17',1,65,'en-65');
INSERT INTO `cms_entries` VALUES (66,'piutang','PIU141219001','piu141219001','Jual <strong>Mesin Hardcover Maker</strong> sebanyak 5 unit @Rp.15.000.000,- dengan total diskon Rp.250.000,-',0,64,1,0,'2014-12-19 14:59:17',1,'2014-12-19 14:59:17',1,66,'en-66');
INSERT INTO `cms_entries` VALUES (67,'sales-detail','bahan-tinta','bahan-tinta-5',NULL,0,64,1,0,'2014-12-19 14:59:18',1,'2014-12-19 14:59:18',1,67,'en-67');
INSERT INTO `cms_entries` VALUES (68,'piutang','PIU141219002','piu141219002','Jual <strong>Bahan Tinta</strong> sebanyak 7 Galon @Rp.600.000,-',0,64,1,0,'2014-12-19 14:59:18',1,'2014-12-19 14:59:18',1,68,'en-68');
INSERT INTO `cms_entries` VALUES (69,'piutang','PIU141219003','piu141219003','Mendapat potongan diskon nota secara keseluruhan.',0,64,1,0,'2014-12-19 14:59:18',1,'2014-12-19 14:59:18',1,69,'en-69');
INSERT INTO `cms_entries` VALUES (70,'piutang','PIU141219004','piu141219004','Pembayaran Uang Muka / Uang DP.',0,64,1,0,'2014-12-19 14:59:18',1,'2014-12-19 14:59:18',1,70,'en-70');
INSERT INTO `cms_entries` VALUES (71,'customer','Hana Tania','hana-tania','',0,0,1,0,'2014-12-23 15:33:41',1,'2014-12-23 15:33:42',1,71,'en-71');
INSERT INTO `cms_entries` VALUES (72,'barang-dagang','Mesin VCut','mesin-vcut','',0,0,1,0,'2014-12-23 17:22:34',1,'2014-12-23 17:22:34',1,72,'en-72');
INSERT INTO `cms_entries` VALUES (73,'barang-gudang','mesin-vcut','mesin-vcut-1','masuk barang pertama',0,4,1,0,'2014-12-23 17:28:16',1,'2014-12-23 17:28:16',1,73,'en-73');
INSERT INTO `cms_entries` VALUES (74,'pindah-masuk','automatic_mesin-vcut','automatic-mesin-vcut','Penambahan stok barang oleh administrator.',8,4,1,0,'2014-12-23 17:28:16',1,'2014-12-23 17:28:16',1,74,'en-74');
INSERT INTO `cms_entries` VALUES (75,'barang-dagang','Paku Tancep X','paku-tancep-x','test barang lagi',0,0,1,0,'2014-12-23 17:40:10',1,'2014-12-23 17:40:11',1,75,'en-75');
INSERT INTO `cms_entries` VALUES (78,'barang-surat-jalan','mesin-hardcover-maker','mesin-hardcover-maker-8',NULL,0,77,1,0,'2014-12-29 15:31:03',1,'2014-12-29 15:31:04',1,78,'en-78');
INSERT INTO `cms_entries` VALUES (77,'surat-jalan','SRJ141229001','srj141229001','test surat jalan',0,0,1,2,'2014-12-29 15:31:03',1,'2014-12-30 19:31:55',1,77,'en-77');
INSERT INTO `cms_entries` VALUES (79,'pindah-keluar','andy-basuki_mesin-hardcover-maker','andy-basuki-mesin-hardcover-maker','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj141229001\'>SRJ141229001</a>',5,4,1,0,'2014-12-29 00:00:00',1,'2014-12-29 15:31:04',1,79,'en-79');
INSERT INTO `cms_entries` VALUES (80,'barang-surat-jalan','bahan-tinta','bahan-tinta-6',NULL,0,77,1,0,'2014-12-29 15:31:04',1,'2014-12-29 15:31:04',1,80,'en-80');
INSERT INTO `cms_entries` VALUES (81,'pindah-keluar','andy-basuki_bahan-tinta','andy-basuki-bahan-tinta','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj141229001\'>SRJ141229001</a>',5,33,1,0,'2014-12-29 00:00:00',1,'2014-12-29 15:31:04',1,81,'en-81');
INSERT INTO `cms_entries` VALUES (82,'surat-jalan','SRJ141229002','srj141229002','test surat jalan kedua',0,0,0,1,'2014-12-29 15:31:53',1,'2014-12-29 15:31:54',1,82,'en-82');
INSERT INTO `cms_entries` VALUES (83,'barang-surat-jalan','bahan-tinta','bahan-tinta-7',NULL,0,82,1,0,'2014-12-29 15:31:53',1,'2014-12-29 15:31:54',1,83,'en-83');
INSERT INTO `cms_entries` VALUES (84,'pindah-keluar','andy-basuki_bahan-tinta','andy-basuki-bahan-tinta-1','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj141229002\'>SRJ141229002</a>',2,33,1,0,'2014-12-29 00:00:00',1,'2014-12-29 15:31:54',1,84,'en-84');
INSERT INTO `cms_entries` VALUES (87,'purchase-order','PUR150102001','pur150102001','nambah stock aja gan',0,0,1,7,'2015-01-02 14:46:13',1,'2015-01-02 14:47:20',1,87,'en-87');
INSERT INTO `cms_entries` VALUES (86,'resi','QZ8501XZPBAS','qz8501xzpbas','',0,0,1,0,'2014-12-30 19:31:55',1,'2014-12-30 19:31:55',1,86,'en-86');
INSERT INTO `cms_entries` VALUES (88,'purchase-detail','bahan-tinta','bahan-tinta-8',NULL,0,87,1,0,'2015-01-02 14:46:13',1,'2015-01-02 14:46:13',1,88,'en-88');
INSERT INTO `cms_entries` VALUES (89,'hutang','HUT150102001','hut150102001','Beli <strong>Bahan Tinta</strong> sebanyak 12 Galon @Rp.345.000,-',0,87,1,0,'2015-01-02 14:46:13',1,'2015-01-02 14:46:14',1,89,'en-89');
INSERT INTO `cms_entries` VALUES (90,'purchase-detail','paku-tancep-x','paku-tancep-x-1',NULL,0,87,1,0,'2015-01-02 14:46:14',1,'2015-01-02 14:46:14',1,90,'en-90');
INSERT INTO `cms_entries` VALUES (91,'hutang','HUT150102002','hut150102002','Beli <strong>Paku Tancep X</strong> sebanyak 8 kg @Rp.3.500,-',0,87,1,0,'2015-01-02 14:46:14',1,'2015-01-02 14:46:14',1,91,'en-91');
INSERT INTO `cms_entries` VALUES (92,'barang-masuk','bahan-tinta','bahan-tinta-9','',0,87,1,0,'2015-01-02 14:46:45',1,'2015-01-02 14:46:45',1,92,'en-92');
INSERT INTO `cms_entries` VALUES (93,'barang-gudang','bahan-tinta','bahan-tinta-10','Kiriman dari supplier.',0,4,1,0,'2015-01-02 14:46:46',1,'2015-01-02 14:46:46',1,93,'en-93');
INSERT INTO `cms_entries` VALUES (94,'pindah-masuk','he-xin-min_bahan-tinta','he-xin-min-bahan-tinta-2','Pembelian INVOICE kode PUR150102001',12,4,1,0,'2015-01-02 00:00:00',1,'2015-01-02 14:46:46',1,94,'en-94');
INSERT INTO `cms_entries` VALUES (95,'barang-masuk','paku-tancep-x','paku-tancep-x-2','',0,87,1,0,'2015-01-02 14:46:46',1,'2015-01-02 14:46:46',1,95,'en-95');
INSERT INTO `cms_entries` VALUES (96,'barang-gudang','paku-tancep-x','paku-tancep-x-3','Kiriman dari supplier.',0,4,1,0,'2015-01-02 14:46:46',1,'2015-01-02 14:46:46',1,96,'en-96');
INSERT INTO `cms_entries` VALUES (97,'pindah-masuk','he-xin-min_paku-tancep-x','he-xin-min-paku-tancep-x','Pembelian INVOICE kode PUR150102001',8,4,1,0,'2015-01-02 00:00:00',1,'2015-01-02 14:46:46',1,97,'en-97');
INSERT INTO `cms_entries` VALUES (98,'hutang','HUT150102003','hut150102003','Invoice lunas (Instant Paid Off)',0,87,1,0,'2015-01-02 14:47:20',1,'2015-01-02 14:47:20',1,98,'en-98');
INSERT INTO `cms_entries` VALUES (99,'surat-jalan','SRJ150102001','srj150102001','coba retur beli gan.',0,0,0,1,'2015-01-02 15:19:44',1,'2015-01-02 15:52:28',1,99,'en-99');
INSERT INTO `cms_entries` VALUES (100,'barang-surat-jalan','bahan-tinta','bahan-tinta-11',NULL,0,99,1,0,'2015-01-02 15:19:45',1,'2015-01-02 15:19:45',1,100,'en-100');
INSERT INTO `cms_entries` VALUES (101,'pindah-keluar','he-xin-min_bahan-tinta','he-xin-min-bahan-tinta-3','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj150102001\'>SRJ150102001</a>',3,4,1,0,'2015-01-02 00:00:00',1,'2015-01-02 15:19:45',1,101,'en-101');
INSERT INTO `cms_entries` VALUES (102,'piutang','PIU150102001','piu150102001','Invoice lunas (Instant Paid Off)',0,64,1,0,'2015-01-02 15:41:00',1,'2015-01-02 15:41:00',1,102,'en-102');
INSERT INTO `cms_entries` VALUES (104,'purchase-order','PUR150102002','pur150102002','nambah stok edisi 2 gan\r\nbahan tinta',0,0,1,5,'2015-01-02 15:54:47',1,'2015-01-05 21:55:30',1,104,'en-104');
INSERT INTO `cms_entries` VALUES (105,'purchase-detail','bahan-tinta','bahan-tinta-12',NULL,0,104,1,0,'2015-01-02 15:54:47',1,'2015-01-02 15:54:47',1,105,'en-105');
INSERT INTO `cms_entries` VALUES (106,'hutang','HUT150102004','hut150102004','Beli <strong>Bahan Tinta</strong> sebanyak 5 Galon @Rp.345.000,-',0,104,1,0,'2015-01-02 15:54:47',1,'2015-01-02 15:54:47',1,106,'en-106');
INSERT INTO `cms_entries` VALUES (107,'barang-masuk','bahan-tinta','bahan-tinta-13','',0,104,1,0,'2015-01-02 15:55:58',1,'2015-01-02 15:55:58',1,107,'en-107');
INSERT INTO `cms_entries` VALUES (108,'pindah-masuk','he-xin-min_bahan-tinta','he-xin-min-bahan-tinta-4','Pembelian INVOICE kode PUR150102002',5,33,1,0,'2015-01-02 00:00:00',1,'2015-01-02 15:55:58',1,108,'en-108');
INSERT INTO `cms_entries` VALUES (132,'pindah-masuk','andy-basuki_mesin-hardcover-maker','andy-basuki-mesin-hardcover-maker-1','Retur Penjualan INVOICE kode <a target=\'_blank\' href=\'/tokomaju/admin/entries/sales-order/edit/sal301205001\'>SAL301205001</a>',2,4,1,0,'2015-01-03 00:00:00',1,'2015-01-03 15:24:26',1,132,'en-132');
INSERT INTO `cms_entries` VALUES (131,'retur-jual','mesin-hardcover-maker','mesin-hardcover-maker-10','tes retur mesin',0,64,1,0,'2015-01-03 15:24:26',1,'2015-01-03 15:24:26',1,131,'en-131');
INSERT INTO `cms_entries` VALUES (111,'pindah-keluar','he-xin-min_bahan-tinta','he-xin-min-bahan-tinta-5','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj150102002\'>SRJ150102002</a>',5,4,1,0,'2015-01-02 00:00:00',1,'2015-01-02 15:59:01',1,111,'en-111');
INSERT INTO `cms_entries` VALUES (113,'pindah-keluar','he-xin-min_bahan-tinta','he-xin-min-bahan-tinta-6','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj150102002\'>SRJ150102002</a>',4,33,1,0,'2015-01-02 00:00:00',1,'2015-01-02 15:59:02',1,113,'en-113');
INSERT INTO `cms_entries` VALUES (114,'surat-jalan','SRJ150102003','srj150102003','',0,0,1,1,'2015-01-02 16:06:23',1,'2015-01-05 16:13:29',1,114,'en-114');
INSERT INTO `cms_entries` VALUES (115,'barang-surat-jalan','paku-tancep-x','paku-tancep-x-4',NULL,0,114,1,0,'2015-01-02 16:06:23',1,'2015-01-02 16:06:23',1,115,'en-115');
INSERT INTO `cms_entries` VALUES (116,'pindah-keluar','he-xin-min_paku-tancep-x','he-xin-min-paku-tancep-x-1','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj150102003\'>SRJ150102003</a>',3,4,1,0,'2015-01-02 00:00:00',1,'2015-01-02 16:06:23',1,116,'en-116');
INSERT INTO `cms_entries` VALUES (117,'purchase-order','PUR150102003','pur150102003','dari indah rek',0,0,1,3,'2015-01-02 16:11:35',1,'2015-01-02 16:11:58',1,117,'en-117');
INSERT INTO `cms_entries` VALUES (118,'purchase-detail','mesin-vcut','mesin-vcut-2',NULL,0,117,1,0,'2015-01-02 16:11:35',1,'2015-01-02 16:11:35',1,118,'en-118');
INSERT INTO `cms_entries` VALUES (119,'hutang','HUT150102005','hut150102005','Beli <strong>Mesin VCut</strong> sebanyak 5 unit @Rp.12.000.000,-',0,117,1,0,'2015-01-02 16:11:35',1,'2015-01-02 16:11:35',1,119,'en-119');
INSERT INTO `cms_entries` VALUES (120,'barang-masuk','mesin-vcut','mesin-vcut-3','',0,117,1,0,'2015-01-02 16:11:58',1,'2015-01-02 16:11:58',1,120,'en-120');
INSERT INTO `cms_entries` VALUES (121,'pindah-masuk','indah-pertiiwi_mesin-vcut','indah-pertiiwi-mesin-vcut','Pembelian INVOICE kode PUR150102003',5,4,1,0,'2015-01-02 00:00:00',1,'2015-01-02 16:11:58',1,121,'en-121');
INSERT INTO `cms_entries` VALUES (122,'surat-jalan','SRJ150102004','srj150102004','last retur beli gan',0,0,0,1,'2015-01-02 16:15:59',1,'2015-01-02 16:15:59',1,122,'en-122');
INSERT INTO `cms_entries` VALUES (123,'barang-surat-jalan','mesin-hardcover-maker','mesin-hardcover-maker-9',NULL,0,122,1,0,'2015-01-02 16:15:59',1,'2015-01-02 16:15:59',1,123,'en-123');
INSERT INTO `cms_entries` VALUES (124,'pindah-keluar','he-xin-min_mesin-hardcover-maker','he-xin-min-mesin-hardcover-maker-2','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/tokomaju/admin/entries/surat-jalan/edit/srj150102004\'>SRJ150102004</a>',2,4,1,0,'2015-01-02 00:00:00',1,'2015-01-02 16:15:59',1,124,'en-124');
INSERT INTO `cms_entries` VALUES (125,'purchase-order','PUR150102004','pur150102004','',0,0,1,3,'2015-01-02 16:26:23',1,'2015-01-02 16:26:42',1,125,'en-125');
INSERT INTO `cms_entries` VALUES (126,'purchase-detail','mesin-vcut','mesin-vcut-4',NULL,0,125,1,0,'2015-01-02 16:26:23',1,'2015-01-02 16:26:23',1,126,'en-126');
INSERT INTO `cms_entries` VALUES (127,'hutang','HUT150102006','hut150102006','Beli <strong>Mesin VCut</strong> sebanyak 7 unit @Rp.12.000.000,-',0,125,1,0,'2015-01-02 16:26:23',1,'2015-01-02 16:26:23',1,127,'en-127');
INSERT INTO `cms_entries` VALUES (128,'barang-masuk','mesin-vcut','mesin-vcut-5','',0,125,1,0,'2015-01-02 16:26:42',1,'2015-01-02 16:26:42',1,128,'en-128');
INSERT INTO `cms_entries` VALUES (129,'barang-gudang','mesin-vcut','mesin-vcut-6','Kiriman dari supplier.',0,33,1,0,'2015-01-02 16:26:42',1,'2015-01-02 16:26:42',1,129,'en-129');
INSERT INTO `cms_entries` VALUES (130,'pindah-masuk','indah-pertiiwi_mesin-vcut','indah-pertiiwi-mesin-vcut-1','Pembelian INVOICE kode PUR150102004',3,33,1,0,'2015-01-02 00:00:00',1,'2015-01-02 16:26:42',1,130,'en-130');
INSERT INTO `cms_entries` VALUES (133,'retur-jual','bahan-tinta','bahan-tinta-14','tes retur jual bahan baku',0,64,1,0,'2015-01-03 15:24:26',1,'2015-01-03 15:24:27',1,133,'en-133');
INSERT INTO `cms_entries` VALUES (134,'pindah-masuk','andy-basuki_bahan-tinta','andy-basuki-bahan-tinta-2','Retur Penjualan INVOICE kode <a target=\'_blank\' href=\'/tokomaju/admin/entries/sales-order/edit/sal301205001\'>SAL301205001</a>',3,4,1,0,'2015-01-03 00:00:00',1,'2015-01-03 15:24:27',1,134,'en-134');
INSERT INTO `cms_entries` VALUES (135,'retur-jual','bahan-tinta','bahan-tinta-15','test aja nih KB',0,64,1,0,'2015-01-03 16:04:19',1,'2015-01-03 16:04:20',1,135,'en-135');
INSERT INTO `cms_entries` VALUES (136,'pindah-masuk','andy-basuki_bahan-tinta','andy-basuki-bahan-tinta-3','Retur Penjualan INVOICE kode <a target=\'_blank\' href=\'/tokomaju/admin/entries/sales-order/edit/sal301205001\'>SAL301205001</a>',1,33,1,0,'2015-01-15 00:00:00',1,'2015-01-03 16:04:20',1,136,'en-136');
INSERT INTO `cms_entries` VALUES (139,'resi','XV15112015','xv15112015','resi sudah kembali.\r\nThankyou.',0,0,1,0,'2015-01-05 16:13:29',3,'2015-01-05 16:13:29',3,139,'en-139');
INSERT INTO `cms_entries` VALUES (145,'gudang','Gudang Besi Oke','gudang-besi-oke','',0,0,1,7,'2015-01-05 21:31:26',3,'2015-01-05 22:20:23',3,145,'en-145');
INSERT INTO `cms_entries` VALUES (144,'barang-dagang','Tripleks 3 ML','tripleks-3-ml','',0,0,1,0,'2015-01-05 21:29:04',3,'2015-01-05 21:29:04',3,144,'en-144');
INSERT INTO `cms_entries` VALUES (143,'jenis-barang','Tripleks','tripleks-2','contoh jenis barang',0,0,1,0,'2015-01-05 21:24:40',3,'2015-01-05 21:24:40',3,143,'en-143');
INSERT INTO `cms_entries` VALUES (146,'purchase-order','PUR380112001','pur380112001','',0,0,1,8,'2015-01-05 21:44:46',3,'2015-01-05 21:50:30',3,146,'en-146');
INSERT INTO `cms_entries` VALUES (147,'purchase-detail','tripleks-3-ml','tripleks-3-ml-1',NULL,0,146,1,0,'2015-01-05 21:44:46',3,'2015-01-05 21:44:46',3,147,'en-147');
INSERT INTO `cms_entries` VALUES (148,'hutang','HUT150105001','hut150105001','Beli <strong>Tripleks 3 ML</strong> sebanyak 500 lembar @Rp.41.000,-',0,146,1,0,'2015-01-05 21:44:46',3,'2015-01-05 21:44:46',3,148,'en-148');
INSERT INTO `cms_entries` VALUES (149,'purchase-detail','mesin-hardcover-maker','mesin-hardcover-maker-11',NULL,0,146,1,0,'2015-01-05 21:44:47',3,'2015-01-05 21:44:47',3,149,'en-149');
INSERT INTO `cms_entries` VALUES (150,'hutang','HUT150105002','hut150105002','Beli <strong>Mesin Hardcover Maker</strong> sebanyak 3 unit @Rp.6.000.000,-',0,146,1,0,'2015-01-05 21:44:47',3,'2015-01-05 21:44:47',3,150,'en-150');
INSERT INTO `cms_entries` VALUES (151,'hutang','HUT150105003','hut150105003','Pembayaran PUR380112001 telah lunas.',0,146,1,0,'2015-01-05 21:44:47',3,'2015-01-05 21:44:47',3,151,'en-151');
INSERT INTO `cms_entries` VALUES (152,'barang-masuk','tripleks-3-ml','tripleks-3-ml-2','kurang 200 lagi',0,146,1,0,'2015-01-05 21:47:20',3,'2015-01-05 21:47:20',3,152,'en-152');
INSERT INTO `cms_entries` VALUES (153,'barang-gudang','tripleks-3-ml','tripleks-3-ml-3','Kiriman dari supplier.',0,145,1,0,'2015-01-05 21:47:20',3,'2015-01-05 21:47:20',3,153,'en-153');
INSERT INTO `cms_entries` VALUES (154,'pindah-masuk','he-xin-min_tripleks-3-ml','he-xin-min-tripleks-3-ml','Pembelian INVOICE kode <a target=\'_blank\' href=\'/admin/entries/purchase-order/edit/pur380112001\'>PUR380112001</a>',300,145,1,0,'2015-01-05 00:00:00',3,'2015-01-05 21:47:20',3,154,'en-154');
INSERT INTO `cms_entries` VALUES (155,'barang-masuk','mesin-hardcover-maker','mesin-hardcover-maker-12','',0,146,1,0,'2015-01-05 21:47:20',3,'2015-01-05 21:47:20',3,155,'en-155');
INSERT INTO `cms_entries` VALUES (156,'barang-gudang','mesin-hardcover-maker','mesin-hardcover-maker-13','Kiriman dari supplier.',0,145,1,0,'2015-01-05 21:47:20',3,'2015-01-05 21:47:20',3,156,'en-156');
INSERT INTO `cms_entries` VALUES (157,'pindah-masuk','he-xin-min_mesin-hardcover-maker','he-xin-min-mesin-hardcover-maker-3','Pembelian INVOICE kode <a target=\'_blank\' href=\'/admin/entries/purchase-order/edit/pur380112001\'>PUR380112001</a>',3,145,1,0,'2015-01-05 00:00:00',3,'2015-01-05 21:47:20',3,157,'en-157');
INSERT INTO `cms_entries` VALUES (158,'barang-masuk','tripleks-3-ml','tripleks-3-ml-4','',0,146,1,0,'2015-01-05 21:50:30',3,'2015-01-05 21:50:30',3,158,'en-158');
INSERT INTO `cms_entries` VALUES (159,'pindah-masuk','he-xin-min_tripleks-3-ml','he-xin-min-tripleks-3-ml-1','Pembelian INVOICE kode <a target=\'_blank\' href=\'/admin/entries/purchase-order/edit/pur380112001\'>PUR380112001</a>',200,145,1,0,'2015-01-05 00:00:00',3,'2015-01-05 21:50:30',3,159,'en-159');
INSERT INTO `cms_entries` VALUES (161,'hutang','HUT150105004','hut150105004','Invoice lunas (Instant Paid Off)',0,104,1,0,'2015-01-05 21:55:29',3,'2015-01-05 21:55:29',3,161,'en-161');
INSERT INTO `cms_entries` VALUES (162,'sales-order','SAL150105001','sal150105001','oke',0,0,1,9,'2015-01-05 22:04:34',3,'2015-01-05 22:20:23',3,162,'en-162');
INSERT INTO `cms_entries` VALUES (163,'sales-detail','tripleks-3-ml','tripleks-3-ml-5',NULL,0,162,1,0,'2015-01-05 22:04:34',3,'2015-01-05 22:04:34',3,163,'en-163');
INSERT INTO `cms_entries` VALUES (164,'piutang','PIU150105001','piu150105001','Jual <strong>Tripleks 3 ML</strong> sebanyak 50 lembar @Rp.47.000,- dengan total diskon Rp.500,-',0,162,1,0,'2015-01-05 22:04:34',3,'2015-01-05 22:04:34',3,164,'en-164');
INSERT INTO `cms_entries` VALUES (165,'sales-detail','paku-tancep-x','paku-tancep-x-5',NULL,0,162,1,0,'2015-01-05 22:04:34',3,'2015-01-05 22:04:34',3,165,'en-165');
INSERT INTO `cms_entries` VALUES (166,'piutang','PIU150105002','piu150105002','Jual <strong>Paku Tancep X</strong> sebanyak 5 kg @Rp.6.000,-',0,162,1,0,'2015-01-05 22:04:35',3,'2015-01-05 22:04:35',3,166,'en-166');
INSERT INTO `cms_entries` VALUES (167,'piutang','PIU150105003','piu150105003','Mendapat potongan diskon nota secara keseluruhan.',0,162,1,0,'2015-01-05 22:04:35',3,'2015-01-05 22:04:35',3,167,'en-167');
INSERT INTO `cms_entries` VALUES (168,'piutang','PIU150105004','piu150105004','Pembayaran Uang Muka / Uang DP.',0,162,1,0,'2015-01-05 22:04:35',3,'2015-01-05 22:04:35',3,168,'en-168');
INSERT INTO `cms_entries` VALUES (169,'piutang','PIU150105005','piu150105005','tes lebih bayar',0,162,1,0,'2015-01-05 22:06:49',3,'2015-01-05 22:06:49',3,169,'en-169');
INSERT INTO `cms_entries` VALUES (170,'piutang','PIU150105006','piu150105006','balikno kelebihan bayar',0,162,1,0,'2015-01-05 22:07:29',3,'2015-01-05 22:07:29',3,170,'en-170');
INSERT INTO `cms_entries` VALUES (171,'surat-jalan','SRJ150105001','srj150105001','',0,0,1,2,'2015-01-05 22:12:14',3,'2015-01-05 22:14:41',3,171,'en-171');
INSERT INTO `cms_entries` VALUES (172,'barang-surat-jalan','paku-tancep-x','paku-tancep-x-6',NULL,0,171,1,0,'2015-01-05 22:12:14',3,'2015-01-05 22:12:15',3,172,'en-172');
INSERT INTO `cms_entries` VALUES (173,'pindah-keluar','hana-tania_paku-tancep-x','hana-tania-paku-tancep-x','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/admin/entries/surat-jalan/edit/srj150105001\'>SRJ150105001</a>',5,4,1,0,'2015-01-05 00:00:00',3,'2015-01-05 22:12:15',3,173,'en-173');
INSERT INTO `cms_entries` VALUES (174,'barang-surat-jalan','tripleks-3-ml','tripleks-3-ml-6',NULL,0,171,1,0,'2015-01-05 22:12:15',3,'2015-01-05 22:12:15',3,174,'en-174');
INSERT INTO `cms_entries` VALUES (175,'pindah-keluar','hana-tania_tripleks-3-ml','hana-tania-tripleks-3-ml','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/admin/entries/surat-jalan/edit/srj150105001\'>SRJ150105001</a>',50,145,1,0,'2015-01-05 00:00:00',3,'2015-01-05 22:12:15',3,175,'en-175');
INSERT INTO `cms_entries` VALUES (176,'resi','RSI123456','rsi123456','',0,0,1,0,'2015-01-05 22:14:41',3,'2015-01-05 22:14:41',3,176,'en-176');
INSERT INTO `cms_entries` VALUES (177,'surat-jalan','SRJ150105002','srj150105002','',0,0,0,1,'2015-01-05 22:17:58',3,'2015-01-05 22:17:59',3,177,'en-177');
INSERT INTO `cms_entries` VALUES (178,'barang-surat-jalan','mesin-vcut','mesin-vcut-7',NULL,0,177,1,0,'2015-01-05 22:17:59',3,'2015-01-05 22:17:59',3,178,'en-178');
INSERT INTO `cms_entries` VALUES (179,'pindah-keluar','indah-pertiiwi_mesin-vcut','indah-pertiiwi-mesin-vcut-2','Pengiriman Surat Jalan <a target=\'_blank\' href=\'/admin/entries/surat-jalan/edit/srj150105002\'>SRJ150105002</a>',3,4,1,0,'2015-01-05 00:00:00',3,'2015-01-05 22:17:59',3,179,'en-179');
INSERT INTO `cms_entries` VALUES (180,'retur-jual','tripleks-3-ml','tripleks-3-ml-7','rusak soalnya, suwek kabeh',0,162,1,0,'2015-01-05 22:20:22',3,'2015-01-05 22:20:23',3,180,'en-180');
INSERT INTO `cms_entries` VALUES (181,'pindah-masuk','hana-tania_tripleks-3-ml','hana-tania-tripleks-3-ml-1','Retur Penjualan INVOICE kode <a target=\'_blank\' href=\'/admin/entries/sales-order/edit/sal150105001\'>SAL150105001</a>',20,145,1,0,'2015-01-09 00:00:00',3,'2015-01-05 22:20:23',3,181,'en-181');
/*!40000 ALTER TABLE `cms_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_entry_metas`
--

DROP TABLE IF EXISTS `cms_entry_metas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_entry_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `key` varchar(500) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_entry_metas`
--

LOCK TABLES `cms_entry_metas` WRITE;
/*!40000 ALTER TABLE `cms_entry_metas` DISABLE KEYS */;
INSERT INTO `cms_entry_metas` VALUES (1,1,'form-perusahaan','PT. Creazi Citra Cemerlang');
INSERT INTO `cms_entry_metas` VALUES (2,1,'form-alamat','Jl. Nginden Semolo 101\r\nJl. DHI Barat 43 B');
INSERT INTO `cms_entry_metas` VALUES (3,1,'form-kota','Surabaya, Indonesia');
INSERT INTO `cms_entry_metas` VALUES (4,1,'form-kode_pos','60285');
INSERT INTO `cms_entry_metas` VALUES (5,1,'form-telepon','031 5995 630');
INSERT INTO `cms_entry_metas` VALUES (6,1,'form-handphone','081 7525 5381');
INSERT INTO `cms_entry_metas` VALUES (7,1,'form-email','andybasuki88@gmail.com');
INSERT INTO `cms_entry_metas` VALUES (8,2,'form-perusahaan','Huangsha Gongsi');
INSERT INTO `cms_entry_metas` VALUES (9,2,'form-alamat','Jl. Huangsha Dadao no 1232');
INSERT INTO `cms_entry_metas` VALUES (10,2,'form-kota','Hangzhou');
INSERT INTO `cms_entry_metas` VALUES (11,2,'form-kode_pos','60717');
INSERT INTO `cms_entry_metas` VALUES (12,2,'form-telepon','031 870 4371');
INSERT INTO `cms_entry_metas` VALUES (13,2,'form-handphone','089 67367 1110');
INSERT INTO `cms_entry_metas` VALUES (14,2,'form-fax','718298');
INSERT INTO `cms_entry_metas` VALUES (15,2,'form-email','harusrajin@gmail.com');
INSERT INTO `cms_entry_metas` VALUES (16,2,'form-rekening_bank','BCA - 388 035 6860');
INSERT INTO `cms_entry_metas` VALUES (17,3,'form-perusahaan','PT. Karunia Persada');
INSERT INTO `cms_entry_metas` VALUES (18,3,'form-alamat','Jl. Kalibutuh\r\nJl. Kudus');
INSERT INTO `cms_entry_metas` VALUES (19,3,'form-rute_jalan_awal','Kudus');
INSERT INTO `cms_entry_metas` VALUES (20,3,'form-rute_jalan_akhir','Surabaya');
INSERT INTO `cms_entry_metas` VALUES (21,3,'form-handphone','081 737 5678');
INSERT INTO `cms_entry_metas` VALUES (22,3,'form-email','gunawan@gmail.com');
INSERT INTO `cms_entry_metas` VALUES (23,3,'form-rekening_bank','Mandiri - 0620 567 1213');
INSERT INTO `cms_entry_metas` VALUES (24,4,'form-alamat','Jl. Pengampon Square C6');
INSERT INTO `cms_entry_metas` VALUES (25,4,'form-kota','Surabaya');
INSERT INTO `cms_entry_metas` VALUES (26,4,'form-kode_pos','879028');
INSERT INTO `cms_entry_metas` VALUES (27,4,'form-telepon','031 372 4582');
INSERT INTO `cms_entry_metas` VALUES (28,4,'form-fax','031 372 4583');
INSERT INTO `cms_entry_metas` VALUES (29,4,'form-nama_pegawai','Tony Hermawan');
INSERT INTO `cms_entry_metas` VALUES (30,4,'form-handphone','081 2345 6789');
INSERT INTO `cms_entry_metas` VALUES (47,8,'form-harga_beli','6000000');
INSERT INTO `cms_entry_metas` VALUES (46,8,'form-supplier','he-xin-min|indah-pertiiwi');
INSERT INTO `cms_entry_metas` VALUES (45,8,'form-satuan','unit');
INSERT INTO `cms_entry_metas` VALUES (44,8,'form-jenis_barang','mesin');
INSERT INTO `cms_entry_metas` VALUES (36,9,'form-perusahaan','PT. Indah Pertiwi');
INSERT INTO `cms_entry_metas` VALUES (37,9,'form-alamat','Jl. Ikan Lele No 1');
INSERT INTO `cms_entry_metas` VALUES (38,9,'form-kota','Incheon');
INSERT INTO `cms_entry_metas` VALUES (39,9,'form-kode_pos','8908727');
INSERT INTO `cms_entry_metas` VALUES (40,9,'form-telepon','7228392192');
INSERT INTO `cms_entry_metas` VALUES (41,9,'form-handphone','+628612328828128');
INSERT INTO `cms_entry_metas` VALUES (42,9,'form-email','indahpertiwi@yahoo.com');
INSERT INTO `cms_entry_metas` VALUES (43,9,'form-rekening_bank','BCA - 123 456 7890');
INSERT INTO `cms_entry_metas` VALUES (48,8,'form-harga_jual','15000000');
INSERT INTO `cms_entry_metas` VALUES (49,4,'count-barang-gudang','4');
INSERT INTO `cms_entry_metas` VALUES (72,16,'form-stock','24');
INSERT INTO `cms_entry_metas` VALUES (52,4,'count-pindah-masuk','14');
INSERT INTO `cms_entry_metas` VALUES (53,8,'form-stock','41');
INSERT INTO `cms_entry_metas` VALUES (55,4,'count-pindah-keluar','14');
INSERT INTO `cms_entry_metas` VALUES (57,18,'form-jenis_barang','bahan-baku');
INSERT INTO `cms_entry_metas` VALUES (58,18,'form-satuan','Galon');
INSERT INTO `cms_entry_metas` VALUES (59,18,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (60,18,'form-harga_beli','345000');
INSERT INTO `cms_entry_metas` VALUES (61,18,'form-harga_jual','600000');
INSERT INTO `cms_entry_metas` VALUES (63,18,'form-stock','9');
INSERT INTO `cms_entry_metas` VALUES (226,33,'form-telepon','031 372 9191');
INSERT INTO `cms_entry_metas` VALUES (225,33,'form-kode_pos','60969');
INSERT INTO `cms_entry_metas` VALUES (224,33,'form-kota','Raden');
INSERT INTO `cms_entry_metas` VALUES (223,33,'form-alamat','Jl. Romokalisari No 12');
INSERT INTO `cms_entry_metas` VALUES (79,33,'count-barang-gudang','3');
INSERT INTO `cms_entry_metas` VALUES (80,34,'form-stock','14');
INSERT INTO `cms_entry_metas` VALUES (81,33,'count-pindah-masuk','7');
INSERT INTO `cms_entry_metas` VALUES (483,162,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (482,161,'form-mutasi_debet','725000');
INSERT INTO `cms_entry_metas` VALUES (481,161,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (480,160,'form-mutasi_debet','1000000');
INSERT INTO `cms_entry_metas` VALUES (479,160,'form-tanggal','01/08/2015');
INSERT INTO `cms_entry_metas` VALUES (98,39,'form-tanggal','12/04/2014');
INSERT INTO `cms_entry_metas` VALUES (99,39,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (100,39,'form-status_bayar','Lunas');
INSERT INTO `cms_entry_metas` VALUES (101,39,'form-status_kirim','Terkirim');
INSERT INTO `cms_entry_metas` VALUES (102,39,'form-nama_pegawai','admin zpanel');
INSERT INTO `cms_entry_metas` VALUES (103,39,'count-purchase-detail','2');
INSERT INTO `cms_entry_metas` VALUES (104,40,'form-jumlah','3');
INSERT INTO `cms_entry_metas` VALUES (105,40,'form-harga','6000000');
INSERT INTO `cms_entry_metas` VALUES (106,40,'form-terkirim','3');
INSERT INTO `cms_entry_metas` VALUES (107,40,'form-retur','2');
INSERT INTO `cms_entry_metas` VALUES (108,39,'count-hutang','5');
INSERT INTO `cms_entry_metas` VALUES (109,41,'form-tanggal','12/04/2014');
INSERT INTO `cms_entry_metas` VALUES (110,41,'form-mutasi_kredit','18000000');
INSERT INTO `cms_entry_metas` VALUES (111,42,'form-jumlah','7');
INSERT INTO `cms_entry_metas` VALUES (112,42,'form-harga','345000');
INSERT INTO `cms_entry_metas` VALUES (113,42,'form-terkirim','7');
INSERT INTO `cms_entry_metas` VALUES (114,42,'form-retur','0');
INSERT INTO `cms_entry_metas` VALUES (115,43,'form-tanggal','12/04/2014');
INSERT INTO `cms_entry_metas` VALUES (116,43,'form-mutasi_kredit','2415000');
INSERT INTO `cms_entry_metas` VALUES (117,44,'form-tanggal','12/04/2014');
INSERT INTO `cms_entry_metas` VALUES (118,44,'form-mutasi_debet','20415000');
INSERT INTO `cms_entry_metas` VALUES (119,39,'form-balance','0');
INSERT INTO `cms_entry_metas` VALUES (120,39,'form-total_harga','20415000');
INSERT INTO `cms_entry_metas` VALUES (121,39,'count-barang-masuk','4');
INSERT INTO `cms_entry_metas` VALUES (122,46,'form-jumlah_datang','3');
INSERT INTO `cms_entry_metas` VALUES (123,46,'form-tanggal','12/25/2013');
INSERT INTO `cms_entry_metas` VALUES (124,46,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (125,46,'form-sisa','4');
INSERT INTO `cms_entry_metas` VALUES (126,47,'form-stock','2');
INSERT INTO `cms_entry_metas` VALUES (127,49,'form-jumlah_datang','2');
INSERT INTO `cms_entry_metas` VALUES (128,49,'form-tanggal','12/25/2013');
INSERT INTO `cms_entry_metas` VALUES (129,49,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (130,49,'form-sisa','1');
INSERT INTO `cms_entry_metas` VALUES (131,51,'form-jumlah_datang','1');
INSERT INTO `cms_entry_metas` VALUES (132,51,'form-tanggal','12/16/2014');
INSERT INTO `cms_entry_metas` VALUES (133,51,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (134,51,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (135,53,'form-jumlah_datang','4');
INSERT INTO `cms_entry_metas` VALUES (136,53,'form-tanggal','12/16/2014');
INSERT INTO `cms_entry_metas` VALUES (137,53,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (138,53,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (150,59,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (145,57,'form-mutasi_kredit','520000');
INSERT INTO `cms_entry_metas` VALUES (144,57,'form-tanggal','12/20/2014');
INSERT INTO `cms_entry_metas` VALUES (142,56,'form-tanggal','12/17/2014');
INSERT INTO `cms_entry_metas` VALUES (143,56,'form-mutasi_debet','520000');
INSERT INTO `cms_entry_metas` VALUES (151,59,'form-status_bayar','Tunggak');
INSERT INTO `cms_entry_metas` VALUES (149,59,'form-tanggal','12/19/2014');
INSERT INTO `cms_entry_metas` VALUES (152,59,'form-status_kirim','Diproses');
INSERT INTO `cms_entry_metas` VALUES (153,59,'form-nama_pegawai','admin zpanel');
INSERT INTO `cms_entry_metas` VALUES (154,59,'form-balance','0');
INSERT INTO `cms_entry_metas` VALUES (155,59,'form-total_harga','0');
INSERT INTO `cms_entry_metas` VALUES (156,60,'form-tanggal','12/19/2014');
INSERT INTO `cms_entry_metas` VALUES (157,60,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (158,60,'form-status_bayar','Tunggak');
INSERT INTO `cms_entry_metas` VALUES (159,60,'form-status_kirim','Diproses');
INSERT INTO `cms_entry_metas` VALUES (160,60,'form-nama_pegawai','admin zpanel');
INSERT INTO `cms_entry_metas` VALUES (161,60,'count-purchase-detail','1');
INSERT INTO `cms_entry_metas` VALUES (162,61,'form-jumlah','3');
INSERT INTO `cms_entry_metas` VALUES (163,61,'form-harga','6000000');
INSERT INTO `cms_entry_metas` VALUES (164,61,'form-terkirim','0');
INSERT INTO `cms_entry_metas` VALUES (165,61,'form-retur','0');
INSERT INTO `cms_entry_metas` VALUES (166,60,'count-hutang','1');
INSERT INTO `cms_entry_metas` VALUES (167,62,'form-tanggal','12/19/2014');
INSERT INTO `cms_entry_metas` VALUES (168,62,'form-mutasi_kredit','18000000');
INSERT INTO `cms_entry_metas` VALUES (169,60,'form-balance','18000000');
INSERT INTO `cms_entry_metas` VALUES (170,60,'form-total_harga','18000000');
INSERT INTO `cms_entry_metas` VALUES (173,64,'form-tanggal','12/05/2030');
INSERT INTO `cms_entry_metas` VALUES (174,64,'form-customer','andy-basuki');
INSERT INTO `cms_entry_metas` VALUES (175,64,'form-status_bayar','Lunas');
INSERT INTO `cms_entry_metas` VALUES (176,64,'form-status_kirim','Terkirim');
INSERT INTO `cms_entry_metas` VALUES (177,64,'form-nama_pegawai','Bejo Sugiantoro');
INSERT INTO `cms_entry_metas` VALUES (178,64,'form-diskon_nota','350000');
INSERT INTO `cms_entry_metas` VALUES (179,64,'form-uang_muka','25000000');
INSERT INTO `cms_entry_metas` VALUES (180,64,'form-ongkos_tambahan','300000');
INSERT INTO `cms_entry_metas` VALUES (181,64,'count-sales-detail','2');
INSERT INTO `cms_entry_metas` VALUES (182,65,'form-jumlah','5');
INSERT INTO `cms_entry_metas` VALUES (183,65,'form-harga','15000000');
INSERT INTO `cms_entry_metas` VALUES (184,65,'form-diskon','250000');
INSERT INTO `cms_entry_metas` VALUES (185,65,'form-profit','44750000');
INSERT INTO `cms_entry_metas` VALUES (186,65,'form-terkirim','5');
INSERT INTO `cms_entry_metas` VALUES (187,65,'form-retur','2');
INSERT INTO `cms_entry_metas` VALUES (188,64,'count-piutang','5');
INSERT INTO `cms_entry_metas` VALUES (189,66,'form-tanggal','12/19/2014');
INSERT INTO `cms_entry_metas` VALUES (190,66,'form-mutasi_debet','74750000');
INSERT INTO `cms_entry_metas` VALUES (191,67,'form-jumlah','7');
INSERT INTO `cms_entry_metas` VALUES (192,67,'form-harga','600000');
INSERT INTO `cms_entry_metas` VALUES (193,67,'form-diskon','0');
INSERT INTO `cms_entry_metas` VALUES (194,67,'form-profit','1785000');
INSERT INTO `cms_entry_metas` VALUES (195,67,'form-terkirim','7');
INSERT INTO `cms_entry_metas` VALUES (196,67,'form-retur','4');
INSERT INTO `cms_entry_metas` VALUES (197,68,'form-tanggal','12/19/2014');
INSERT INTO `cms_entry_metas` VALUES (198,68,'form-mutasi_debet','4200000');
INSERT INTO `cms_entry_metas` VALUES (199,69,'form-tanggal','12/19/2014');
INSERT INTO `cms_entry_metas` VALUES (200,69,'form-mutasi_kredit','350000');
INSERT INTO `cms_entry_metas` VALUES (201,70,'form-tanggal','12/19/2014');
INSERT INTO `cms_entry_metas` VALUES (202,70,'form-mutasi_kredit','25000000');
INSERT INTO `cms_entry_metas` VALUES (203,64,'form-balance','0');
INSERT INTO `cms_entry_metas` VALUES (204,64,'form-total_harga','78600000');
INSERT INTO `cms_entry_metas` VALUES (205,64,'form-laba_bersih','45885000');
INSERT INTO `cms_entry_metas` VALUES (206,71,'form-perusahaan','PT. Fleur ReadyStock');
INSERT INTO `cms_entry_metas` VALUES (207,71,'form-alamat','Jl. Baruk Utara 7');
INSERT INTO `cms_entry_metas` VALUES (208,71,'form-kota','Surabaya, Indonesia');
INSERT INTO `cms_entry_metas` VALUES (209,71,'form-kode_pos','60606');
INSERT INTO `cms_entry_metas` VALUES (210,71,'form-handphone','0817375678');
INSERT INTO `cms_entry_metas` VALUES (211,72,'form-jenis_barang','mesin');
INSERT INTO `cms_entry_metas` VALUES (212,72,'form-satuan','unit');
INSERT INTO `cms_entry_metas` VALUES (213,72,'form-supplier','indah-pertiiwi');
INSERT INTO `cms_entry_metas` VALUES (214,72,'form-harga_beli','12000000');
INSERT INTO `cms_entry_metas` VALUES (215,72,'form-harga_jual','21500000');
INSERT INTO `cms_entry_metas` VALUES (216,73,'form-stock','10');
INSERT INTO `cms_entry_metas` VALUES (217,72,'form-stock','13');
INSERT INTO `cms_entry_metas` VALUES (218,75,'form-jenis_barang','sparepart');
INSERT INTO `cms_entry_metas` VALUES (219,75,'form-satuan','kg');
INSERT INTO `cms_entry_metas` VALUES (220,75,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (221,75,'form-harga_beli','3500');
INSERT INTO `cms_entry_metas` VALUES (222,75,'form-harga_jual','6000');
INSERT INTO `cms_entry_metas` VALUES (227,33,'form-nama_pegawai','Pak Sutejo');
INSERT INTO `cms_entry_metas` VALUES (228,33,'form-handphone','081 737 1234');
INSERT INTO `cms_entry_metas` VALUES (233,77,'form-sales_order','sal301205001');
INSERT INTO `cms_entry_metas` VALUES (232,77,'form-tanggal','12/29/2014');
INSERT INTO `cms_entry_metas` VALUES (234,77,'form-customer','andy-basuki');
INSERT INTO `cms_entry_metas` VALUES (235,77,'form-ekspedisi','pak-gunawan-santoso');
INSERT INTO `cms_entry_metas` VALUES (236,77,'count-barang-surat-jalan','2');
INSERT INTO `cms_entry_metas` VALUES (237,78,'form-jumlah','5');
INSERT INTO `cms_entry_metas` VALUES (238,78,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (239,80,'form-jumlah','5');
INSERT INTO `cms_entry_metas` VALUES (240,80,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (241,33,'count-pindah-keluar','3');
INSERT INTO `cms_entry_metas` VALUES (242,82,'form-tanggal','12/29/2014');
INSERT INTO `cms_entry_metas` VALUES (243,82,'form-sales_order','sal301205001');
INSERT INTO `cms_entry_metas` VALUES (244,82,'form-customer','andy-basuki');
INSERT INTO `cms_entry_metas` VALUES (245,82,'form-ekspedisi','pak-gunawan-santoso');
INSERT INTO `cms_entry_metas` VALUES (246,82,'count-barang-surat-jalan','1');
INSERT INTO `cms_entry_metas` VALUES (247,83,'form-jumlah','2');
INSERT INTO `cms_entry_metas` VALUES (248,83,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (268,87,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (267,86,'form-sistem_bayar','Gratis');
INSERT INTO `cms_entry_metas` VALUES (266,86,'form-pihak_bayar','Tujuan');
INSERT INTO `cms_entry_metas` VALUES (265,86,'form-tanggal','12/11/2014');
INSERT INTO `cms_entry_metas` VALUES (264,86,'form-surat_jalan','srj141229001');
INSERT INTO `cms_entry_metas` VALUES (269,87,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (270,87,'form-status_bayar','Lunas');
INSERT INTO `cms_entry_metas` VALUES (271,87,'form-status_kirim','Terkirim');
INSERT INTO `cms_entry_metas` VALUES (272,87,'form-nama_pegawai','admin zpanel');
INSERT INTO `cms_entry_metas` VALUES (273,87,'count-purchase-detail','2');
INSERT INTO `cms_entry_metas` VALUES (274,88,'form-jumlah','12');
INSERT INTO `cms_entry_metas` VALUES (275,88,'form-harga','345000');
INSERT INTO `cms_entry_metas` VALUES (276,88,'form-terkirim','12');
INSERT INTO `cms_entry_metas` VALUES (277,88,'form-retur','12');
INSERT INTO `cms_entry_metas` VALUES (278,87,'count-hutang','3');
INSERT INTO `cms_entry_metas` VALUES (279,89,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (280,89,'form-mutasi_kredit','4140000');
INSERT INTO `cms_entry_metas` VALUES (281,90,'form-jumlah','8');
INSERT INTO `cms_entry_metas` VALUES (282,90,'form-harga','3500');
INSERT INTO `cms_entry_metas` VALUES (283,90,'form-terkirim','8');
INSERT INTO `cms_entry_metas` VALUES (284,90,'form-retur','3');
INSERT INTO `cms_entry_metas` VALUES (285,91,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (286,91,'form-mutasi_kredit','28000');
INSERT INTO `cms_entry_metas` VALUES (287,87,'form-balance','0');
INSERT INTO `cms_entry_metas` VALUES (288,87,'form-total_harga','4168000');
INSERT INTO `cms_entry_metas` VALUES (289,87,'count-barang-masuk','2');
INSERT INTO `cms_entry_metas` VALUES (290,92,'form-jumlah_datang','12');
INSERT INTO `cms_entry_metas` VALUES (291,92,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (292,92,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (293,92,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (294,93,'form-stock','7');
INSERT INTO `cms_entry_metas` VALUES (295,95,'form-jumlah_datang','8');
INSERT INTO `cms_entry_metas` VALUES (296,95,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (297,95,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (298,95,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (299,96,'form-stock','0');
INSERT INTO `cms_entry_metas` VALUES (300,75,'form-stock','0');
INSERT INTO `cms_entry_metas` VALUES (301,98,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (302,98,'form-mutasi_debet','4168000');
INSERT INTO `cms_entry_metas` VALUES (303,99,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (304,99,'form-purchase_order','pur150102001');
INSERT INTO `cms_entry_metas` VALUES (305,99,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (306,99,'form-ekspedisi','pak-gunawan-santoso');
INSERT INTO `cms_entry_metas` VALUES (307,99,'count-barang-surat-jalan','1');
INSERT INTO `cms_entry_metas` VALUES (308,100,'form-jumlah','3');
INSERT INTO `cms_entry_metas` VALUES (309,100,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (310,102,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (311,102,'form-mutasi_kredit','53600000');
INSERT INTO `cms_entry_metas` VALUES (320,104,'form-status_kirim','Terkirim');
INSERT INTO `cms_entry_metas` VALUES (319,104,'form-status_bayar','Lunas');
INSERT INTO `cms_entry_metas` VALUES (318,104,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (317,104,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (321,104,'form-nama_pegawai','admin zpanel');
INSERT INTO `cms_entry_metas` VALUES (322,104,'count-purchase-detail','1');
INSERT INTO `cms_entry_metas` VALUES (323,105,'form-jumlah','5');
INSERT INTO `cms_entry_metas` VALUES (324,105,'form-harga','345000');
INSERT INTO `cms_entry_metas` VALUES (325,105,'form-terkirim','5');
INSERT INTO `cms_entry_metas` VALUES (326,105,'form-retur','0');
INSERT INTO `cms_entry_metas` VALUES (327,104,'count-hutang','3');
INSERT INTO `cms_entry_metas` VALUES (328,106,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (329,106,'form-mutasi_kredit','1725000');
INSERT INTO `cms_entry_metas` VALUES (330,104,'form-balance','0');
INSERT INTO `cms_entry_metas` VALUES (331,104,'form-total_harga','1725000');
INSERT INTO `cms_entry_metas` VALUES (332,104,'count-barang-masuk','1');
INSERT INTO `cms_entry_metas` VALUES (333,107,'form-jumlah_datang','5');
INSERT INTO `cms_entry_metas` VALUES (334,107,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (335,107,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (336,107,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (404,133,'form-jumlah','3');
INSERT INTO `cms_entry_metas` VALUES (403,131,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (402,131,'form-tanggal','01/03/2015');
INSERT INTO `cms_entry_metas` VALUES (401,131,'form-jumlah','2');
INSERT INTO `cms_entry_metas` VALUES (400,64,'count-retur-jual','3');
INSERT INTO `cms_entry_metas` VALUES (406,133,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (405,133,'form-tanggal','01/03/2015');
INSERT INTO `cms_entry_metas` VALUES (346,114,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (347,114,'form-purchase_order','pur150102001');
INSERT INTO `cms_entry_metas` VALUES (348,114,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (349,114,'count-barang-surat-jalan','1');
INSERT INTO `cms_entry_metas` VALUES (350,115,'form-jumlah','3');
INSERT INTO `cms_entry_metas` VALUES (351,115,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (352,117,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (353,117,'form-supplier','indah-pertiiwi');
INSERT INTO `cms_entry_metas` VALUES (354,117,'form-status_bayar','Tunggak');
INSERT INTO `cms_entry_metas` VALUES (355,117,'form-status_kirim','Terkirim');
INSERT INTO `cms_entry_metas` VALUES (356,117,'form-nama_pegawai','admin zpanel');
INSERT INTO `cms_entry_metas` VALUES (357,117,'count-purchase-detail','1');
INSERT INTO `cms_entry_metas` VALUES (358,118,'form-jumlah','5');
INSERT INTO `cms_entry_metas` VALUES (359,118,'form-harga','12000000');
INSERT INTO `cms_entry_metas` VALUES (360,118,'form-terkirim','5');
INSERT INTO `cms_entry_metas` VALUES (361,118,'form-retur','0');
INSERT INTO `cms_entry_metas` VALUES (362,117,'count-hutang','1');
INSERT INTO `cms_entry_metas` VALUES (363,119,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (364,119,'form-mutasi_kredit','60000000');
INSERT INTO `cms_entry_metas` VALUES (365,117,'form-balance','60000000');
INSERT INTO `cms_entry_metas` VALUES (366,117,'form-total_harga','60000000');
INSERT INTO `cms_entry_metas` VALUES (367,117,'count-barang-masuk','1');
INSERT INTO `cms_entry_metas` VALUES (368,120,'form-jumlah_datang','5');
INSERT INTO `cms_entry_metas` VALUES (369,120,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (370,120,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (371,120,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (372,122,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (373,122,'form-purchase_order','pur141204001');
INSERT INTO `cms_entry_metas` VALUES (374,122,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (375,122,'form-ekspedisi','pak-gunawan-santoso');
INSERT INTO `cms_entry_metas` VALUES (376,122,'count-barang-surat-jalan','1');
INSERT INTO `cms_entry_metas` VALUES (377,123,'form-jumlah','2');
INSERT INTO `cms_entry_metas` VALUES (378,123,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (379,125,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (380,125,'form-supplier','indah-pertiiwi');
INSERT INTO `cms_entry_metas` VALUES (381,125,'form-status_bayar','Tunggak');
INSERT INTO `cms_entry_metas` VALUES (382,125,'form-status_kirim','Diproses');
INSERT INTO `cms_entry_metas` VALUES (383,125,'form-nama_pegawai','Hana Tania');
INSERT INTO `cms_entry_metas` VALUES (384,125,'count-purchase-detail','1');
INSERT INTO `cms_entry_metas` VALUES (385,126,'form-jumlah','7');
INSERT INTO `cms_entry_metas` VALUES (386,126,'form-harga','12000000');
INSERT INTO `cms_entry_metas` VALUES (387,126,'form-terkirim','3');
INSERT INTO `cms_entry_metas` VALUES (388,126,'form-retur','3');
INSERT INTO `cms_entry_metas` VALUES (389,125,'count-hutang','1');
INSERT INTO `cms_entry_metas` VALUES (390,127,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (391,127,'form-mutasi_kredit','84000000');
INSERT INTO `cms_entry_metas` VALUES (392,125,'form-balance','84000000');
INSERT INTO `cms_entry_metas` VALUES (393,125,'form-total_harga','84000000');
INSERT INTO `cms_entry_metas` VALUES (394,125,'count-barang-masuk','1');
INSERT INTO `cms_entry_metas` VALUES (395,128,'form-jumlah_datang','3');
INSERT INTO `cms_entry_metas` VALUES (396,128,'form-tanggal','01/02/2015');
INSERT INTO `cms_entry_metas` VALUES (397,128,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (398,128,'form-sisa','4');
INSERT INTO `cms_entry_metas` VALUES (399,129,'form-stock','3');
INSERT INTO `cms_entry_metas` VALUES (407,135,'form-jumlah','1');
INSERT INTO `cms_entry_metas` VALUES (408,135,'form-tanggal','01/15/2015');
INSERT INTO `cms_entry_metas` VALUES (409,135,'form-gudang','romokalisari');
INSERT INTO `cms_entry_metas` VALUES (422,139,'form-surat_jalan','srj150102003');
INSERT INTO `cms_entry_metas` VALUES (423,139,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (424,139,'form-pihak_bayar','Pengirim');
INSERT INTO `cms_entry_metas` VALUES (425,139,'form-sistem_bayar','Tagih');
INSERT INTO `cms_entry_metas` VALUES (426,139,'form-harga','125000');
INSERT INTO `cms_entry_metas` VALUES (427,144,'form-jenis_barang','tripleks-2');
INSERT INTO `cms_entry_metas` VALUES (428,144,'form-satuan','lembar');
INSERT INTO `cms_entry_metas` VALUES (429,144,'form-supplier','indah-pertiiwi|he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (430,144,'form-harga_beli','42000');
INSERT INTO `cms_entry_metas` VALUES (431,144,'form-harga_jual','47000');
INSERT INTO `cms_entry_metas` VALUES (432,145,'form-alamat','fsfsdfsdfsdf');
INSERT INTO `cms_entry_metas` VALUES (433,145,'form-kota','sdfsdf');
INSERT INTO `cms_entry_metas` VALUES (434,145,'form-kode_pos','Sfsdf');
INSERT INTO `cms_entry_metas` VALUES (435,145,'form-telepon','sdfsdfsdf');
INSERT INTO `cms_entry_metas` VALUES (436,145,'form-nama_pegawai','Hotim');
INSERT INTO `cms_entry_metas` VALUES (437,145,'form-handphone','081 737 1234');
INSERT INTO `cms_entry_metas` VALUES (438,146,'form-tanggal','01/12/1938');
INSERT INTO `cms_entry_metas` VALUES (439,146,'form-supplier','he-xin-min');
INSERT INTO `cms_entry_metas` VALUES (440,146,'form-status_bayar','Lunas');
INSERT INTO `cms_entry_metas` VALUES (441,146,'form-status_kirim','Terkirim');
INSERT INTO `cms_entry_metas` VALUES (442,146,'form-nama_pegawai','Alexander Mallian');
INSERT INTO `cms_entry_metas` VALUES (443,146,'count-purchase-detail','2');
INSERT INTO `cms_entry_metas` VALUES (444,147,'form-jumlah','500');
INSERT INTO `cms_entry_metas` VALUES (445,147,'form-harga','41000');
INSERT INTO `cms_entry_metas` VALUES (446,147,'form-terkirim','500');
INSERT INTO `cms_entry_metas` VALUES (447,147,'form-retur','0');
INSERT INTO `cms_entry_metas` VALUES (448,146,'count-hutang','3');
INSERT INTO `cms_entry_metas` VALUES (449,148,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (450,148,'form-mutasi_kredit','20500000');
INSERT INTO `cms_entry_metas` VALUES (451,149,'form-jumlah','3');
INSERT INTO `cms_entry_metas` VALUES (452,149,'form-harga','6000000');
INSERT INTO `cms_entry_metas` VALUES (453,149,'form-terkirim','3');
INSERT INTO `cms_entry_metas` VALUES (454,149,'form-retur','0');
INSERT INTO `cms_entry_metas` VALUES (455,150,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (456,150,'form-mutasi_kredit','18000000');
INSERT INTO `cms_entry_metas` VALUES (457,151,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (458,151,'form-mutasi_debet','38500000');
INSERT INTO `cms_entry_metas` VALUES (459,146,'form-balance','0');
INSERT INTO `cms_entry_metas` VALUES (460,146,'form-total_harga','38500000');
INSERT INTO `cms_entry_metas` VALUES (461,146,'count-barang-masuk','3');
INSERT INTO `cms_entry_metas` VALUES (462,152,'form-jumlah_datang','300');
INSERT INTO `cms_entry_metas` VALUES (463,152,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (464,152,'form-gudang','gudang-besi-oke');
INSERT INTO `cms_entry_metas` VALUES (465,152,'form-sisa','200');
INSERT INTO `cms_entry_metas` VALUES (466,145,'count-barang-gudang','2');
INSERT INTO `cms_entry_metas` VALUES (467,153,'form-stock','470');
INSERT INTO `cms_entry_metas` VALUES (468,145,'count-pindah-masuk','4');
INSERT INTO `cms_entry_metas` VALUES (469,144,'form-stock','470');
INSERT INTO `cms_entry_metas` VALUES (470,155,'form-jumlah_datang','3');
INSERT INTO `cms_entry_metas` VALUES (471,155,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (472,155,'form-gudang','gudang-besi-oke');
INSERT INTO `cms_entry_metas` VALUES (473,155,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (474,156,'form-stock','3');
INSERT INTO `cms_entry_metas` VALUES (475,158,'form-jumlah_datang','200');
INSERT INTO `cms_entry_metas` VALUES (476,158,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (477,158,'form-gudang','gudang-besi-oke');
INSERT INTO `cms_entry_metas` VALUES (478,158,'form-sisa','0');
INSERT INTO `cms_entry_metas` VALUES (484,162,'form-customer','hana-tania');
INSERT INTO `cms_entry_metas` VALUES (485,162,'form-status_bayar','Lunas');
INSERT INTO `cms_entry_metas` VALUES (486,162,'form-status_kirim','Terkirim');
INSERT INTO `cms_entry_metas` VALUES (487,162,'form-nama_pegawai','Alexander Mallian');
INSERT INTO `cms_entry_metas` VALUES (488,162,'form-diskon_nota','50000');
INSERT INTO `cms_entry_metas` VALUES (489,162,'form-uang_muka','1000000');
INSERT INTO `cms_entry_metas` VALUES (490,162,'count-sales-detail','2');
INSERT INTO `cms_entry_metas` VALUES (491,163,'form-jumlah','50');
INSERT INTO `cms_entry_metas` VALUES (492,163,'form-harga','47000');
INSERT INTO `cms_entry_metas` VALUES (493,163,'form-diskon','500');
INSERT INTO `cms_entry_metas` VALUES (494,163,'form-profit','249500');
INSERT INTO `cms_entry_metas` VALUES (495,163,'form-terkirim','50');
INSERT INTO `cms_entry_metas` VALUES (496,163,'form-retur','20');
INSERT INTO `cms_entry_metas` VALUES (497,162,'count-piutang','6');
INSERT INTO `cms_entry_metas` VALUES (498,164,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (499,164,'form-mutasi_debet','2349500');
INSERT INTO `cms_entry_metas` VALUES (500,165,'form-jumlah','5');
INSERT INTO `cms_entry_metas` VALUES (501,165,'form-harga','6000');
INSERT INTO `cms_entry_metas` VALUES (502,165,'form-diskon','0');
INSERT INTO `cms_entry_metas` VALUES (503,165,'form-profit','12500');
INSERT INTO `cms_entry_metas` VALUES (504,165,'form-terkirim','5');
INSERT INTO `cms_entry_metas` VALUES (505,165,'form-retur','0');
INSERT INTO `cms_entry_metas` VALUES (506,166,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (507,166,'form-mutasi_debet','30000');
INSERT INTO `cms_entry_metas` VALUES (508,167,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (509,167,'form-mutasi_kredit','50000');
INSERT INTO `cms_entry_metas` VALUES (510,168,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (511,168,'form-mutasi_kredit','1000000');
INSERT INTO `cms_entry_metas` VALUES (512,162,'form-balance','0');
INSERT INTO `cms_entry_metas` VALUES (513,162,'form-total_harga','2329500');
INSERT INTO `cms_entry_metas` VALUES (514,162,'form-laba_bersih','212000');
INSERT INTO `cms_entry_metas` VALUES (515,169,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (516,169,'form-mutasi_kredit','1800000');
INSERT INTO `cms_entry_metas` VALUES (517,170,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (518,170,'form-mutasi_debet','470500');
INSERT INTO `cms_entry_metas` VALUES (519,171,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (520,171,'form-sales_order','sal150105001');
INSERT INTO `cms_entry_metas` VALUES (521,171,'form-customer','hana-tania');
INSERT INTO `cms_entry_metas` VALUES (522,171,'form-ekspedisi','pak-gunawan-santoso');
INSERT INTO `cms_entry_metas` VALUES (523,171,'count-barang-surat-jalan','2');
INSERT INTO `cms_entry_metas` VALUES (524,172,'form-jumlah','5');
INSERT INTO `cms_entry_metas` VALUES (525,172,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (526,174,'form-jumlah','50');
INSERT INTO `cms_entry_metas` VALUES (527,174,'form-gudang','gudang-besi-oke');
INSERT INTO `cms_entry_metas` VALUES (528,145,'count-pindah-keluar','1');
INSERT INTO `cms_entry_metas` VALUES (529,176,'form-surat_jalan','srj150105001');
INSERT INTO `cms_entry_metas` VALUES (530,176,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (531,176,'form-pihak_bayar','Pengirim');
INSERT INTO `cms_entry_metas` VALUES (532,176,'form-sistem_bayar','Gratis');
INSERT INTO `cms_entry_metas` VALUES (533,177,'form-tanggal','01/05/2015');
INSERT INTO `cms_entry_metas` VALUES (534,177,'form-purchase_order','pur150102004');
INSERT INTO `cms_entry_metas` VALUES (535,177,'form-supplier','indah-pertiiwi');
INSERT INTO `cms_entry_metas` VALUES (536,177,'count-barang-surat-jalan','1');
INSERT INTO `cms_entry_metas` VALUES (537,178,'form-jumlah','3');
INSERT INTO `cms_entry_metas` VALUES (538,178,'form-gudang','pengampon');
INSERT INTO `cms_entry_metas` VALUES (539,162,'count-retur-jual','1');
INSERT INTO `cms_entry_metas` VALUES (540,180,'form-jumlah','20');
INSERT INTO `cms_entry_metas` VALUES (541,180,'form-tanggal','01/09/2015');
INSERT INTO `cms_entry_metas` VALUES (542,180,'form-gudang','gudang-besi-oke');
/*!40000 ALTER TABLE `cms_entry_metas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_roles`
--

DROP TABLE IF EXISTS `cms_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `description` text,
  `count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_roles`
--

LOCK TABLES `cms_roles` WRITE;
/*!40000 ALTER TABLE `cms_roles` DISABLE KEYS */;
INSERT INTO `cms_roles` VALUES (1,'Super Admin','Administrator who has all access for the web without exceptions.',1);
INSERT INTO `cms_roles` VALUES (2,'Admin','Administrator from the clients.',NULL);
/*!40000 ALTER TABLE `cms_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_settings`
--

DROP TABLE IF EXISTS `cms_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(500) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_settings`
--

LOCK TABLES `cms_settings` WRITE;
/*!40000 ALTER TABLE `cms_settings` DISABLE KEYS */;
INSERT INTO `cms_settings` VALUES (1,'title','Toko Maju');
INSERT INTO `cms_settings` VALUES (2,'tagline','toko, dagang, jualan, supplier, bahan, bangunan, maju, palu, indonesia');
INSERT INTO `cms_settings` VALUES (3,'description','sistem inventory Toko Maju.');
INSERT INTO `cms_settings` VALUES (4,'date_format','d F Y');
INSERT INTO `cms_settings` VALUES (5,'time_format','h:i A');
INSERT INTO `cms_settings` VALUES (6,'header','');
INSERT INTO `cms_settings` VALUES (7,'top_insert','');
INSERT INTO `cms_settings` VALUES (8,'bottom_insert','');
INSERT INTO `cms_settings` VALUES (9,'google_analytics_code','');
INSERT INTO `cms_settings` VALUES (10,'display_width','3200');
INSERT INTO `cms_settings` VALUES (11,'display_height','1800');
INSERT INTO `cms_settings` VALUES (12,'display_crop','0');
INSERT INTO `cms_settings` VALUES (13,'thumb_width','120');
INSERT INTO `cms_settings` VALUES (14,'thumb_height','120');
INSERT INTO `cms_settings` VALUES (15,'thumb_crop','0');
INSERT INTO `cms_settings` VALUES (16,'language','en_english');
INSERT INTO `cms_settings` VALUES (17,'table_view','complex');
INSERT INTO `cms_settings` VALUES (18,'usd_sell','9732.00');
INSERT INTO `cms_settings` VALUES (19,'custom-pagination','10');
/*!40000 ALTER TABLE `cms_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_type_metas`
--

DROP TABLE IF EXISTS `cms_type_metas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_type_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `key` varchar(500) NOT NULL,
  `value` text,
  `input_type` varchar(500) DEFAULT NULL,
  `validation` text,
  `instruction` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_type_metas`
--

LOCK TABLES `cms_type_metas` WRITE;
/*!40000 ALTER TABLE `cms_type_metas` DISABLE KEYS */;
INSERT INTO `cms_type_metas` VALUES (21,6,'title_key','Nama Pegawai',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (2,4,'title_key','Nama Lengkap',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (3,4,'form-perusahaan','','text','not_empty|','Nama Perusahaan tempat bekerja.');
INSERT INTO `cms_type_metas` VALUES (4,4,'form-alamat','','textarea','','Alamat Perusahaan yang terkait.');
INSERT INTO `cms_type_metas` VALUES (5,4,'form-kota','','text','','');
INSERT INTO `cms_type_metas` VALUES (6,4,'form-kode_pos','','text','','');
INSERT INTO `cms_type_metas` VALUES (7,4,'form-telepon','','text','','Nomer telepon yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (8,4,'form-handphone','','text','not_empty|','Nomer handphone yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (9,4,'form-fax','','text','','Nomer Fax yang dapat terhubung.');
INSERT INTO `cms_type_metas` VALUES (10,4,'form-email','','text','is_email|','Alamat E-mail yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (11,5,'title_key','Nama Lengkap',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (12,5,'form-perusahaan','','text','not_empty|','Nama Perusahaan tempat bekerja.');
INSERT INTO `cms_type_metas` VALUES (13,5,'form-alamat','','textarea','','Alamat Perusahaan yang terkait.');
INSERT INTO `cms_type_metas` VALUES (14,5,'form-kota','','text','','');
INSERT INTO `cms_type_metas` VALUES (15,5,'form-kode_pos','','text','','');
INSERT INTO `cms_type_metas` VALUES (16,5,'form-telepon','','text','','Nomer telepon yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (17,5,'form-handphone','','text','not_empty|','Nomer handphone yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (18,5,'form-fax','','text','','Nomer Fax yang dapat terhubung.');
INSERT INTO `cms_type_metas` VALUES (19,5,'form-email','','text','is_email|','Alamat E-mail yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (20,5,'form-rekening_bank','','text','not_empty|','Nama bank dan Nomer rekening yang dituju.');
INSERT INTO `cms_type_metas` VALUES (22,6,'form-perusahaan','','text','not_empty|','Nama Perusahaan tempat bekerja.');
INSERT INTO `cms_type_metas` VALUES (23,6,'form-alamat','','textarea','','Alamat Lengkap Perusahaan yang terkait.');
INSERT INTO `cms_type_metas` VALUES (24,6,'form-rute_jalan_awal','','text','not_empty|','Cabang / Kota Rute Asal.');
INSERT INTO `cms_type_metas` VALUES (25,6,'form-rute_jalan_akhir','','text','not_empty|','Cabang / Kota Rute Tujuan.');
INSERT INTO `cms_type_metas` VALUES (26,6,'form-telepon','','text','','Nomer telepon yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (27,6,'form-handphone','','text','not_empty|','Nomer handphone yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (28,6,'form-fax','','text','','Nomer Fax yang dapat terhubung.');
INSERT INTO `cms_type_metas` VALUES (29,6,'form-email','','text','is_email|','Alamat E-mail yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (30,6,'form-rekening_bank','','text','not_empty|','Nama bank dan Nomer rekening yang dituju.');
INSERT INTO `cms_type_metas` VALUES (31,7,'title_key','Nama',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (32,7,'form-alamat','','textarea','','');
INSERT INTO `cms_type_metas` VALUES (33,7,'form-kota','','text','','');
INSERT INTO `cms_type_metas` VALUES (34,7,'form-kode_pos','','text','','');
INSERT INTO `cms_type_metas` VALUES (35,7,'form-telepon','','text','','Nomer telepon yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (36,7,'form-fax','','text','','Nomer Fax yang dapat terhubung.');
INSERT INTO `cms_type_metas` VALUES (37,7,'form-nama_pegawai','','text','not_empty|','Pegawai yang bertanggung jawab atas kepengurusan gudang tersebut.');
INSERT INTO `cms_type_metas` VALUES (38,7,'form-handphone','','text','not_empty|','Nomer handphone yang dapat dihubungi.');
INSERT INTO `cms_type_metas` VALUES (39,8,'title_key','Nama',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (40,9,'title_key','Nama',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (41,9,'form-jenis_barang','','browse','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (42,9,'form-stock','','text','','Jumlah stock barang berjalan.');
INSERT INTO `cms_type_metas` VALUES (43,9,'form-satuan','','text','not_empty|','Nama satuan barang yang sesuai.<br>Misalnya : unit, kg, liter, sak, dll');
INSERT INTO `cms_type_metas` VALUES (44,9,'form-supplier','','multibrowse','not_empty|','Para Supplier yang menyediakan barang tersebut.');
INSERT INTO `cms_type_metas` VALUES (45,9,'form-harga_beli','','text','is_numeric|not_empty|','Standard patokan harga beli produk.<br><span style=\'color:red\'>NB : KETIK nominal harganya saja, tanpa Rp / tanda baca lainnya.</span>');
INSERT INTO `cms_type_metas` VALUES (46,9,'form-harga_jual','','text','is_numeric|not_empty|','Standard patokan harga jual produk.<br><span style=\'color:red\'>NB : KETIK nominal harganya saja, tanpa Rp / tanda baca lainnya.</span>');
INSERT INTO `cms_type_metas` VALUES (47,10,'title_key','Barang Dagang',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (48,10,'form-stock','','text','not_empty|is_numeric|','Banyaknya barang yg tersimpan.');
INSERT INTO `cms_type_metas` VALUES (49,13,'title_key','Kode Invoice',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (50,13,'form-tanggal','','datepicker','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (51,13,'form-supplier','','browse','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (52,13,'form-status_bayar','Tunggak\r\nLunas','radio','not_empty|','Status pembayaran lunas atau belum.');
INSERT INTO `cms_type_metas` VALUES (53,13,'form-status_kirim','Diproses\r\nTerkirim','radio','not_empty|','Status pengiriman barang selesai atau belum.');
INSERT INTO `cms_type_metas` VALUES (54,13,'form-nama_pegawai','','text','','Pegawai toko yang mengeluarkan invoice.');
INSERT INTO `cms_type_metas` VALUES (55,13,'form-total_harga','','text','is_numeric|','Harga total pembelian barang.');
INSERT INTO `cms_type_metas` VALUES (56,14,'title_key','Barang Dagang',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (57,14,'form-jumlah','','text','not_empty|is_numeric|','');
INSERT INTO `cms_type_metas` VALUES (58,14,'form-harga','','text','not_empty|is_numeric|','');
INSERT INTO `cms_type_metas` VALUES (59,14,'form-terkirim','','text','is_numeric|','Jumlah barang yg sudah terkirim.');
INSERT INTO `cms_type_metas` VALUES (60,14,'form-retur','','text','is_numeric|','Jumlah barang yg diretur.');
INSERT INTO `cms_type_metas` VALUES (61,15,'title_key','Barang Dagang',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (62,15,'form-tanggal','','datepicker','not_empty|','Tanggal pengiriman barang.');
INSERT INTO `cms_type_metas` VALUES (63,15,'form-jumlah_datang','','text','not_empty|is_numeric|','jumlah barang dikirim.');
INSERT INTO `cms_type_metas` VALUES (64,15,'form-sisa','','text','is_numeric|','Jumlah sisa order yang belum datang.');
INSERT INTO `cms_type_metas` VALUES (65,15,'form-gudang','','browse','not_empty|','Gudang tempat pengiriman.');
INSERT INTO `cms_type_metas` VALUES (66,16,'title_key','Kode Referensi',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (67,16,'form-tanggal','','datepicker','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (68,16,'form-mutasi_debet','','text','is_numeric|','Masukan nominal untuk <strong>pembayaran</strong> hutang.');
INSERT INTO `cms_type_metas` VALUES (69,16,'form-mutasi_kredit','','text','is_numeric|','Masukan nominal untuk <strong>menambah beban</strong> hutang.');
INSERT INTO `cms_type_metas` VALUES (72,18,'title_key','Kode Invoice',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (73,18,'form-tanggal','','datepicker','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (74,18,'form-customer','','browse','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (75,18,'form-status_bayar','Tunggak\r\nLunas','radio','not_empty|','Status pembayaran lunas atau belum.');
INSERT INTO `cms_type_metas` VALUES (76,18,'form-status_kirim','Diproses\r\nTerkirim','radio','not_empty|','Status pengiriman barang selesai atau belum.');
INSERT INTO `cms_type_metas` VALUES (77,18,'form-nama_pegawai','','text','','Pegawai toko yang mengeluarkan invoice.');
INSERT INTO `cms_type_metas` VALUES (78,18,'form-diskon_nota','','text','is_numeric|','Diskon langsung dlm satuan rupiah.');
INSERT INTO `cms_type_metas` VALUES (79,18,'form-total_harga','','text','is_numeric|','Harga total penjualan barang.');
INSERT INTO `cms_type_metas` VALUES (80,18,'form-uang_muka','','text','is_numeric|','Uang Muka yg dibayarkan.');
INSERT INTO `cms_type_metas` VALUES (81,18,'form-ongkos_tambahan','','text','is_numeric|','Ongkos Lain-lain (biaya bensin, jasa supir, portal, dll)');
INSERT INTO `cms_type_metas` VALUES (82,18,'form-laba_bersih','','text','is_numeric|','Keuntungan bersih yang didapat.');
INSERT INTO `cms_type_metas` VALUES (83,19,'title_key','Barang Dagang',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (84,19,'form-jumlah','','text','not_empty|is_numeric|','');
INSERT INTO `cms_type_metas` VALUES (85,19,'form-harga','','text','not_empty|is_numeric|','');
INSERT INTO `cms_type_metas` VALUES (86,19,'form-diskon','','text','is_numeric|','Diskon langsung dlm satuan rupiah.');
INSERT INTO `cms_type_metas` VALUES (87,19,'form-profit','','text','is_numeric|','');
INSERT INTO `cms_type_metas` VALUES (88,19,'form-terkirim','','text','is_numeric|','Jumlah barang yg sudah terkirim.');
INSERT INTO `cms_type_metas` VALUES (89,19,'form-retur','','text','is_numeric|','Jumlah barang yg diretur.');
INSERT INTO `cms_type_metas` VALUES (90,20,'title_key','Kode Referensi',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (91,20,'form-tanggal','','datepicker','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (92,20,'form-mutasi_debet','','text','is_numeric|','Masukan nominal untuk <strong>menambah beban</strong> piutang.');
INSERT INTO `cms_type_metas` VALUES (93,20,'form-mutasi_kredit','','text','is_numeric|','Masukan nominal untuk <strong>pembayaran</strong> piutang.');
INSERT INTO `cms_type_metas` VALUES (95,21,'title_key','Barang Dagang',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (96,21,'form-tanggal','','datepicker','not_empty|','Tanggal pengiriman barang.');
INSERT INTO `cms_type_metas` VALUES (97,21,'form-jumlah','','text','not_empty|is_numeric|','jumlah barang yg diretur.');
INSERT INTO `cms_type_metas` VALUES (98,21,'form-gudang','','browse','not_empty|','Gudang tempat pengiriman.');
INSERT INTO `cms_type_metas` VALUES (99,22,'title_key','Kode',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (106,22,'form-purchase_order','','browse','','Surat Jalan untuk meretur barang supplier sesuai PO yg dipilih.');
INSERT INTO `cms_type_metas` VALUES (105,22,'form-customer','','browse','','Tujuan Surat Jalan.');
INSERT INTO `cms_type_metas` VALUES (103,22,'form-tanggal','','datepicker','not_empty|','Tanggal kirim / jalan.');
INSERT INTO `cms_type_metas` VALUES (104,22,'form-sales_order','','browse','','Surat Jalan untuk mengirim pesanan barang customer sesuai SO yg dipilih.');
INSERT INTO `cms_type_metas` VALUES (107,22,'form-supplier','','browse','','Tujuan Surat Jalan.');
INSERT INTO `cms_type_metas` VALUES (108,22,'form-ekspedisi','','browse','','Kosongkan bila mengantar sendiri tanpa jasa ekspedisi.');
INSERT INTO `cms_type_metas` VALUES (109,23,'title_key','Barang Dagang',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (110,23,'form-jumlah','','text','not_empty|is_numeric|','');
INSERT INTO `cms_type_metas` VALUES (111,23,'form-gudang','','browse','','Tempat pengambilan barang.');
INSERT INTO `cms_type_metas` VALUES (112,24,'title_key','Nomer Resi',NULL,NULL,NULL);
INSERT INTO `cms_type_metas` VALUES (114,24,'form-tanggal','','datepicker','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (113,24,'form-surat_jalan','','browse','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (115,24,'form-pihak_bayar','Pengirim\r\nTujuan','radio','not_empty|','Pihak yang membayar pengiriman.');
INSERT INTO `cms_type_metas` VALUES (116,24,'form-sistem_bayar','Gratis\r\nTagih','radio','not_empty|','');
INSERT INTO `cms_type_metas` VALUES (117,24,'form-harga','','text','is_numeric|','Harga biaya yg dikeluarkan.');
/*!40000 ALTER TABLE `cms_type_metas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_types`
--

DROP TABLE IF EXISTS `cms_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `slug` varchar(500) NOT NULL,
  `description` text,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_types`
--

LOCK TABLES `cms_types` WRITE;
/*!40000 ALTER TABLE `cms_types` DISABLE KEYS */;
INSERT INTO `cms_types` VALUES (1,'Media Library','media','All media image is stored here.',0,0,'2013-01-15 03:35:14',1,'2013-01-15 03:35:14',1);
INSERT INTO `cms_types` VALUES (6,'Ekspedisi','ekspedisi','Langganan Ekspedisi yang biasa digunakan.',0,0,'2014-11-23 12:02:07',1,'2014-11-23 12:02:07',1);
INSERT INTO `cms_types` VALUES (4,'Customer','customer','Daftar pelanggan Toko Maju.',0,0,'2014-11-23 10:34:21',1,'2014-11-23 10:34:21',1);
INSERT INTO `cms_types` VALUES (5,'Supplier','supplier','Daftar para Supplier Toko Maju.',0,0,'2014-11-23 11:24:18',1,'2014-11-23 11:24:18',1);
INSERT INTO `cms_types` VALUES (7,'Gudang','gudang','Daftar Gudang tempat penyimpanan barang dagang.',0,3,'2014-11-25 11:03:15',1,'2014-11-25 15:24:15',1);
INSERT INTO `cms_types` VALUES (8,'Jenis Barang','jenis-barang','Daftar berbagai jenis barang yg diperdagangkan.',0,0,'2014-11-25 11:23:40',1,'2014-11-25 11:23:40',1);
INSERT INTO `cms_types` VALUES (9,'Barang Dagang','barang-dagang','Detail Produk Barang Dagang.',0,0,'2014-11-25 11:58:26',1,'2014-11-25 11:58:26',1);
INSERT INTO `cms_types` VALUES (10,'Barang Gudang','barang-gudang','Pencatatan stok penyimpanan barang di gudang.',7,0,'2014-11-25 13:43:09',1,'2014-11-25 13:43:09',1);
INSERT INTO `cms_types` VALUES (11,'History Masuk','pindah-masuk','Seluruh pencatatan history barang yg masuk ke gudang ini.',7,0,'2014-11-25 15:23:57',1,'2014-11-25 15:23:57',1);
INSERT INTO `cms_types` VALUES (12,'History Keluar','pindah-keluar','Seluruh pencatatan history barang yg keluar dari gudang ini.',7,0,'2014-11-25 15:24:15',1,'2014-11-25 15:24:15',1);
INSERT INTO `cms_types` VALUES (13,'Purchase Order','purchase-order','Surat pemesanan barang terhadap supplier.',0,3,'2014-11-28 15:31:36',1,'2014-12-29 15:41:48',1);
INSERT INTO `cms_types` VALUES (14,'Purchase Detail','purchase-detail','Seluruh barang yg terdaftar di faktur pembelian.',13,0,'2014-11-28 15:49:50',1,'2014-11-28 15:49:50',1);
INSERT INTO `cms_types` VALUES (15,'Barang Masuk','barang-masuk','Seluruh pencatatan barang yang masuk dari supplier berdasarkan faktur pembelian.',13,0,'2014-11-28 16:06:25',1,'2014-11-28 16:06:25',1);
INSERT INTO `cms_types` VALUES (16,'Hutang','hutang','Catatan beban hutang terhadap supplier (Rekening Koran)',13,0,'2014-11-28 16:21:10',1,'2014-11-28 16:21:10',1);
INSERT INTO `cms_types` VALUES (18,'Sales Order','sales-order','Dokumen konfirmasi order penjualan terhadap customer.',0,3,'2014-11-30 12:27:50',1,'2014-11-30 13:08:54',1);
INSERT INTO `cms_types` VALUES (19,'Sales Detail','sales-detail','Seluruh barang yg terdaftar di faktur penjualan.',18,0,'2014-11-30 12:43:20',1,'2014-11-30 12:43:20',1);
INSERT INTO `cms_types` VALUES (20,'Piutang','piutang','Catatan pendapatan piutang dari customer (Rekening Koran)',18,0,'2014-11-30 12:52:14',1,'2014-11-30 12:52:14',1);
INSERT INTO `cms_types` VALUES (21,'Retur Jual','retur-jual','Seluruh catatan barang penjualan yang diretur dari customer.',18,0,'2014-11-30 13:08:54',1,'2014-11-30 13:08:54',1);
INSERT INTO `cms_types` VALUES (22,'Surat Jalan','surat-jalan','Surat Jalan untuk pengiriman barang ke customer / supplier.',0,1,'2014-11-30 13:29:28',1,'2014-11-30 22:06:22',1);
INSERT INTO `cms_types` VALUES (23,'Barang Surat Jalan','barang-surat-jalan','Seluruh detail barang dlm suatu surat jalan.',22,0,'2014-11-30 22:06:22',1,'2014-11-30 22:06:22',1);
INSERT INTO `cms_types` VALUES (24,'Resi','resi','Pemberian keterangan resi pada setiap Surat Jalan.',0,0,'2014-11-30 22:18:22',1,'2014-11-30 22:18:22',1);
/*!40000 ALTER TABLE `cms_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_user_metas`
--

DROP TABLE IF EXISTS `cms_user_metas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_user_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(500) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_user_metas`
--

LOCK TABLES `cms_user_metas` WRITE;
/*!40000 ALTER TABLE `cms_user_metas` DISABLE KEYS */;
INSERT INTO `cms_user_metas` VALUES (1,1,'gender','male');
INSERT INTO `cms_user_metas` VALUES (2,1,'address','Jl. Dharmahusada Indah 43');
INSERT INTO `cms_user_metas` VALUES (3,1,'zip_code','60258');
INSERT INTO `cms_user_metas` VALUES (4,1,'city','Surabaya, Indonesia');
INSERT INTO `cms_user_metas` VALUES (5,1,'mobile_phone','089 67367 1110');
INSERT INTO `cms_user_metas` VALUES (6,1,'dob_day','28');
INSERT INTO `cms_user_metas` VALUES (7,1,'dob_month','10');
INSERT INTO `cms_user_metas` VALUES (8,1,'dob_year','1988');
INSERT INTO `cms_user_metas` VALUES (9,1,'job','Web Developer');
INSERT INTO `cms_user_metas` VALUES (10,1,'company','PT. Creazi');
INSERT INTO `cms_user_metas` VALUES (11,1,'company_address','Jl. Nginden Semolo 101');
INSERT INTO `cms_user_metas` VALUES (12,2,'gender','male');
INSERT INTO `cms_user_metas` VALUES (13,2,'address','Jl. Pangeran Hidayat 77');
INSERT INTO `cms_user_metas` VALUES (14,2,'city','Palu - Sulawesi Tengah');
INSERT INTO `cms_user_metas` VALUES (15,2,'phone','(0451) 453 435 - (0451) 453 430');
INSERT INTO `cms_user_metas` VALUES (16,2,'mobile_phone','(62) 81 70322 8283');
INSERT INTO `cms_user_metas` VALUES (17,2,'dob_day','5');
INSERT INTO `cms_user_metas` VALUES (18,2,'dob_month','1');
INSERT INTO `cms_user_metas` VALUES (19,2,'dob_year','1986');
INSERT INTO `cms_user_metas` VALUES (20,2,'job','Supplier Bahan Bangunan');
INSERT INTO `cms_user_metas` VALUES (21,2,'company','Toko Maju');
INSERT INTO `cms_user_metas` VALUES (22,2,'company_address','Jl. Pangeran Hidayat 77');
/*!40000 ALTER TABLE `cms_user_metas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_users`
--

DROP TABLE IF EXISTS `cms_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(500) NOT NULL,
  `lastname` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_users`
--

LOCK TABLES `cms_users` WRITE;
/*!40000 ALTER TABLE `cms_users` DISABLE KEYS */;
INSERT INTO `cms_users` VALUES (1,'admin','zpanel','2013-01-04 00:00:00',1,'2014-02-06 10:50:29',1,1);
INSERT INTO `cms_users` VALUES (2,'Alexander','Mallian','2015-01-05 15:51:55',1,'2015-01-05 15:51:55',1,1);
/*!40000 ALTER TABLE `cms_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-04 22:20:28
