-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 05, 2015 at 04:10 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tokomaju`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_accounts`
--

CREATE TABLE IF NOT EXISTS `cms_accounts` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cms_accounts`
--

INSERT INTO `cms_accounts` (`id`, `user_id`, `role_id`, `username`, `email`, `password`, `last_login`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 1, 1, 'admin', 'admin@yahoo.com', '169e781bd52860b584879cbe117085da596238f3', '2015-01-05 15:17:44', '2013-01-04 00:00:00', 1, '2013-01-04 00:00:00', 1),
(3, 1, 2, 'adminbiasa', 'andybasuki88@gmail.com', '62412f00317caaa6a74f790d6fc058f30cc6e8c0', '2015-01-05 12:39:50', '2015-01-05 12:01:24', 1, '2015-01-05 12:01:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_entries`
--

CREATE TABLE IF NOT EXISTS `cms_entries` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=139 ;

--
-- Dumping data for table `cms_entries`
--

INSERT INTO `cms_entries` (`id`, `entry_type`, `title`, `slug`, `description`, `main_image`, `parent_id`, `status`, `count`, `created`, `created_by`, `modified`, `modified_by`, `sort_order`, `lang_code`) VALUES
(1, 'customer', 'Andy Basuki', 'andy-basuki', 'just a test', 0, 0, 1, 0, '2014-11-23 10:59:31', 1, '2014-11-23 10:59:32', 1, 1, 'en-1'),
(2, 'supplier', 'He Xin Min', 'he-xin-min', 'fake supplier', 0, 0, 1, 0, '2014-11-23 11:27:33', 1, '2014-11-23 11:27:33', 1, 2, 'en-2'),
(3, 'ekspedisi', 'Pak Gunawan Santoso', 'pak-gunawan-santoso', 'Ekspedisi Sby - Kudus OKE', 0, 0, 1, 0, '2014-11-23 14:37:16', 1, '2014-11-23 14:37:16', 1, 3, 'en-3'),
(4, 'gudang', 'Pengampon', 'pengampon', 'Gudang satu-satunya.', 0, 0, 1, 30, '2014-11-25 11:05:06', 1, '2015-01-03 15:24:27', 1, 4, 'en-4'),
(5, 'jenis-barang', 'Bahan Baku', 'bahan-baku', 'test bahan', 0, 0, 1, 0, '2014-11-25 11:24:04', 1, '2014-11-25 11:24:04', 1, 5, 'en-5'),
(6, 'jenis-barang', 'Mesin', 'mesin', 'test jenis mesin.\r\nOke.', 0, 0, 1, 0, '2014-11-25 11:24:23', 1, '2014-11-25 11:24:23', 1, 6, 'en-6'),
(7, 'jenis-barang', 'Sparepart', 'sparepart', 'berbagai barang pendukung', 0, 0, 1, 0, '2014-11-25 11:26:10', 1, '2014-11-25 11:26:10', 1, 7, 'en-7'),
(8, 'barang-dagang', 'Mesin Hardcover Maker', 'mesin-hardcover-maker', 'test barang dagang', 0, 0, 1, 0, '2014-11-25 12:16:57', 1, '2014-11-25 12:21:47', 1, 8, 'en-8'),
(9, 'supplier', 'Indah Pertiiwi', 'indah-pertiiwi', 'Specialist Mesin', 0, 0, 1, 0, '2014-11-25 12:21:11', 1, '2014-11-25 12:21:11', 1, 9, 'en-9'),
(14, 'pindah-masuk', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker', 'Ditambahkan oleh administrator.', 5, 4, 1, 0, '2014-11-25 16:38:42', 1, '2014-11-25 16:38:42', 1, 14, 'en-14'),
(16, 'barang-gudang', 'mesin-hardcover-maker', 'mesin-hardcover-maker-1', 'masukan lagi dan lagi gan.\r\nThankyou\r\nGBU', 0, 4, 1, 0, '2014-11-25 17:41:11', 1, '2014-11-26 17:36:24', 1, 16, 'en-16'),
(15, 'pindah-keluar', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-1', 'Ditiadakan oleh administrator.', 5, 4, 1, 0, '2014-11-25 17:39:55', 1, '2014-11-25 17:39:56', 1, 15, 'en-15'),
(17, 'pindah-masuk', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-2', 'Ditambahkan oleh administrator.', 12, 4, 1, 0, '2014-11-25 17:41:11', 1, '2014-11-25 17:41:11', 1, 17, 'en-17'),
(18, 'barang-dagang', 'Bahan Tinta', 'bahan-tinta', 'tinta terhebat di dunia\r\nOke', 0, 0, 1, 0, '2014-11-25 17:43:29', 1, '2014-11-25 17:43:29', 1, 18, 'en-18'),
(22, 'pindah-keluar', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-3', 'Dihapus oleh administrator.', 5, 4, 1, 0, '2014-11-26 12:07:35', 1, '2014-11-26 12:07:35', 1, 22, 'en-22'),
(20, 'pindah-masuk', 'automatic_bahan-tinta', 'automatic-bahan-tinta', 'Ditambahkan oleh administrator.', 15, 4, 1, 0, '2014-11-25 17:44:34', 1, '2014-11-25 17:44:34', 1, 20, 'en-20'),
(21, 'pindah-keluar', 'automatic_bahan-tinta', 'automatic-bahan-tinta-1', 'Dihapus oleh administrator.', 15, 4, 1, 0, '2014-11-25 17:45:55', 1, '2014-11-25 17:45:55', 1, 21, 'en-21'),
(23, 'pindah-masuk', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-4', 'Ditambahkan oleh administrator.', 13, 4, 1, 0, '2014-11-26 12:08:45', 1, '2014-11-26 12:08:45', 1, 23, 'en-23'),
(24, 'pindah-keluar', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-5', 'Dihapus oleh administrator.', 12, 4, 1, 0, '2014-11-26 12:11:47', 1, '2014-11-26 12:11:48', 1, 24, 'en-24'),
(31, 'pindah-keluar', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-6', 'Pengurangan stok barang oleh administrator.', 2, 4, 1, 0, '2014-11-26 17:34:22', 1, '2014-11-26 17:34:22', 1, 31, 'en-31'),
(30, 'pindah-masuk', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-7', 'Ditambahkan oleh administrator.', 10, 4, 1, 0, '2014-11-26 14:04:08', 1, '2014-11-26 14:04:08', 1, 30, 'en-30'),
(27, 'pindah-masuk', 'automatic_bahan-tinta', 'automatic-bahan-tinta-2', 'Ditambahkan oleh administrator.', 3, 4, 1, 0, '2014-11-26 12:13:27', 1, '2014-11-26 12:13:27', 1, 27, 'en-27'),
(28, 'pindah-keluar', 'automatic_bahan-tinta', 'automatic-bahan-tinta-3', 'Dihapus oleh administrator.', 2, 4, 1, 0, '2014-11-26 12:14:14', 1, '2014-11-26 12:14:14', 1, 28, 'en-28'),
(29, 'pindah-keluar', 'automatic_bahan-tinta', 'automatic-bahan-tinta-4', 'Dihapus oleh administrator.', 1, 4, 1, 0, '2014-11-26 12:14:32', 1, '2014-11-26 12:14:32', 1, 29, 'en-29'),
(32, 'pindah-masuk', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-8', 'Penambahan stok barang oleh administrator.', 5, 4, 1, 0, '2014-11-26 17:36:24', 1, '2014-11-26 17:36:24', 1, 32, 'en-32'),
(33, 'gudang', 'Romokalisari', 'romokalisari', 'Gudang coba coba.\r\ntest oke', 0, 0, 1, 13, '2014-11-27 09:38:12', 1, '2015-01-03 16:04:20', 1, 33, 'en-33'),
(34, 'barang-gudang', 'mesin-hardcover-maker', 'mesin-hardcover-maker-2', '', 0, 33, 1, 0, '2014-11-27 09:38:38', 1, '2014-11-27 09:38:38', 1, 34, 'en-34'),
(35, 'pindah-masuk', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-9', 'Penambahan stok barang oleh administrator.', 12, 33, 1, 0, '2014-11-27 09:38:38', 1, '2014-11-27 09:38:39', 1, 35, 'en-35'),
(36, 'purchase-order', 'TEST001', 'test001', 'test aja gan.\r\nThankyou.\r\nGBU', 0, 0, 1, 0, '2014-11-28 17:20:14', 1, '2014-11-28 17:20:14', 1, 36, 'en-36'),
(39, 'purchase-order', 'PUR141204001', 'pur141204001', 'First Purchase Test !!\r\nthanks\r\noke\r\nGBU', 0, 0, 1, 11, '2014-12-04 10:01:11', 1, '2014-12-17 11:35:22', 1, 39, 'en-39'),
(40, 'purchase-detail', 'mesin-hardcover-maker', 'mesin-hardcover-maker-3', '', 0, 39, 1, 0, '2014-12-04 10:01:11', 1, '2014-12-04 10:01:11', 1, 40, 'en-40'),
(41, 'hutang', 'HUT141204001', 'hut141204001', 'Beli <strong>Mesin Hardcover Maker</strong> sebanyak 3 unit @Rp 6.000.000,-', 0, 39, 1, 0, '2014-12-04 10:01:11', 1, '2014-12-04 10:01:11', 1, 41, 'en-41'),
(42, 'purchase-detail', 'bahan-tinta', 'bahan-tinta-1', '', 0, 39, 1, 0, '2014-12-04 10:01:11', 1, '2014-12-04 10:01:11', 1, 42, 'en-42'),
(43, 'hutang', 'HUT141204002', 'hut141204002', 'Beli <strong>Bahan Tinta</strong> sebanyak 7 Galon @Rp 345.000,-', 0, 39, 1, 0, '2014-12-04 10:01:12', 1, '2014-12-04 10:01:12', 1, 43, 'en-43'),
(44, 'hutang', 'HUT141204003', 'hut141204003', 'Pembayaran PUR141204001 telah lunas.', 0, 39, 1, 0, '2014-12-04 10:01:12', 1, '2014-12-04 10:01:12', 1, 44, 'en-44'),
(45, 'pages', 'Database Sequence', 'database-sequence', '#MASTER\r\nCUSTOMER\r\nSUPPLIER\r\nEKSPEDISI\r\nGUDANG\r\nJENIS BARANG\r\nBARANG DAGANG\r\n#PEMBELIAN\r\nPURCHASE ORDER\r\nBARANG MASUK\r\nHUTANG\r\n#PENJUALAN\r\nSALES ORDER\r\nSURAT JALAN\r\nRESI\r\nPIUTANG\r\n#RETUR\r\nRETUR BELI\r\nRETUR JUAL', 0, 0, 1, 0, '2014-12-05 17:12:50', 1, '2014-12-05 17:12:50', 1, 45, 'en-45'),
(46, 'barang-masuk', 'bahan-tinta', 'bahan-tinta-2', 'masuk tinta', 0, 39, 1, 0, '2014-12-16 09:48:06', 1, '2014-12-16 09:48:06', 1, 46, 'en-46'),
(47, 'barang-gudang', 'bahan-tinta', 'bahan-tinta-3', 'Kiriman dari supplier.', 0, 33, 1, 0, '2014-12-16 09:48:06', 1, '2014-12-16 09:48:06', 1, 47, 'en-47'),
(48, 'pindah-masuk', 'he-xin-min_bahan-tinta', 'he-xin-min-bahan-tinta', 'Pembelian INVOICE kode PUR141204001', 3, 33, 1, 0, '2013-12-25 00:00:00', 1, '2014-12-16 09:48:06', 1, 48, 'en-48'),
(49, 'barang-masuk', 'mesin-hardcover-maker', 'mesin-hardcover-maker-4', 'masuk mesin', 0, 39, 1, 0, '2014-12-16 09:48:07', 1, '2014-12-16 09:48:07', 1, 49, 'en-49'),
(50, 'pindah-masuk', 'he-xin-min_mesin-hardcover-maker', 'he-xin-min-mesin-hardcover-maker', 'Pembelian INVOICE kode PUR141204001', 2, 33, 1, 0, '2013-12-25 00:00:00', 1, '2014-12-16 09:48:07', 1, 50, 'en-50'),
(51, 'barang-masuk', 'mesin-hardcover-maker', 'mesin-hardcover-maker-5', '', 0, 39, 1, 0, '2014-12-16 10:55:27', 1, '2014-12-16 10:55:27', 1, 51, 'en-51'),
(52, 'pindah-masuk', 'he-xin-min_mesin-hardcover-maker', 'he-xin-min-mesin-hardcover-maker-1', 'Pembelian INVOICE kode PUR141204001', 1, 4, 1, 0, '2014-12-16 00:00:00', 1, '2014-12-16 10:55:27', 1, 52, 'en-52'),
(53, 'barang-masuk', 'bahan-tinta', 'bahan-tinta-4', '', 0, 39, 1, 0, '2014-12-16 11:55:29', 1, '2014-12-16 11:55:29', 1, 53, 'en-53'),
(54, 'pindah-masuk', 'he-xin-min_bahan-tinta', 'he-xin-min-bahan-tinta-1', 'Pembelian INVOICE kode PUR141204001', 4, 33, 1, 0, '2014-12-16 00:00:00', 1, '2014-12-16 11:55:29', 1, 54, 'en-54'),
(57, 'hutang', '1234-5678-9012', '1234-5678-9012', 'kembalikan kelebihan bayar\r\nOKE thankyou :)', 0, 39, 1, 0, '2014-12-17 11:35:22', 1, '2014-12-17 11:35:22', 1, 57, 'en-57'),
(56, 'hutang', 'HUT141217001', 'hut141217001', 'Lebih bayar OKE', 0, 39, 1, 0, '2014-12-17 11:33:04', 1, '2014-12-17 11:33:04', 1, 56, 'en-56'),
(60, 'purchase-order', 'PUR141219002', 'pur141219002', '', 0, 0, 1, 2, '2014-12-19 10:27:53', 1, '2014-12-19 10:27:54', 1, 60, 'en-60'),
(59, 'purchase-order', 'PUR141219001', 'pur141219001', '', 0, 0, 1, 0, '2014-12-19 10:22:23', 1, '2014-12-19 10:22:23', 1, 59, 'en-59'),
(61, 'purchase-detail', 'mesin-hardcover-maker', 'mesin-hardcover-maker-6', '', 0, 60, 1, 0, '2014-12-19 10:27:53', 1, '2014-12-19 10:27:53', 1, 61, 'en-61'),
(62, 'hutang', 'HUT141219001', 'hut141219001', 'Beli <strong>Mesin Hardcover Maker</strong> sebanyak 3 unit @Rp.6.000.000,-', 0, 60, 1, 0, '2014-12-19 10:27:53', 1, '2014-12-19 10:27:53', 1, 62, 'en-62'),
(64, 'sales-order', 'SAL301205001', 'sal301205001', 'sales order pertamaku gan.\r\nhaha thx', 0, 0, 1, 10, '2014-12-19 14:59:17', 1, '2015-01-03 16:04:20', 1, 64, 'en-64'),
(65, 'sales-detail', 'mesin-hardcover-maker', 'mesin-hardcover-maker-7', '', 0, 64, 1, 0, '2014-12-19 14:59:17', 1, '2014-12-19 14:59:17', 1, 65, 'en-65'),
(66, 'piutang', 'PIU141219001', 'piu141219001', 'Jual <strong>Mesin Hardcover Maker</strong> sebanyak 5 unit @Rp.15.000.000,- dengan total diskon Rp.250.000,-', 0, 64, 1, 0, '2014-12-19 14:59:17', 1, '2014-12-19 14:59:17', 1, 66, 'en-66'),
(67, 'sales-detail', 'bahan-tinta', 'bahan-tinta-5', '', 0, 64, 1, 0, '2014-12-19 14:59:18', 1, '2014-12-19 14:59:18', 1, 67, 'en-67'),
(68, 'piutang', 'PIU141219002', 'piu141219002', 'Jual <strong>Bahan Tinta</strong> sebanyak 7 Galon @Rp.600.000,-', 0, 64, 1, 0, '2014-12-19 14:59:18', 1, '2014-12-19 14:59:18', 1, 68, 'en-68'),
(69, 'piutang', 'PIU141219003', 'piu141219003', 'Mendapat potongan diskon nota secara keseluruhan.', 0, 64, 1, 0, '2014-12-19 14:59:18', 1, '2014-12-19 14:59:18', 1, 69, 'en-69'),
(70, 'piutang', 'PIU141219004', 'piu141219004', 'Pembayaran Uang Muka / Uang DP.', 0, 64, 1, 0, '2014-12-19 14:59:18', 1, '2014-12-19 14:59:18', 1, 70, 'en-70'),
(71, 'customer', 'Hana Tania', 'hana-tania', '', 0, 0, 1, 0, '2014-12-23 15:33:41', 1, '2014-12-23 15:33:42', 1, 71, 'en-71'),
(72, 'barang-dagang', 'Mesin VCut', 'mesin-vcut', '', 0, 0, 1, 0, '2014-12-23 17:22:34', 1, '2014-12-23 17:22:34', 1, 72, 'en-72'),
(73, 'barang-gudang', 'mesin-vcut', 'mesin-vcut-1', 'masuk barang pertama', 0, 4, 1, 0, '2014-12-23 17:28:16', 1, '2014-12-23 17:28:16', 1, 73, 'en-73'),
(74, 'pindah-masuk', 'automatic_mesin-vcut', 'automatic-mesin-vcut', 'Penambahan stok barang oleh administrator.', 8, 4, 1, 0, '2014-12-23 17:28:16', 1, '2014-12-23 17:28:16', 1, 74, 'en-74'),
(75, 'barang-dagang', 'Paku Tancep X', 'paku-tancep-x', 'test barang lagi', 0, 0, 1, 0, '2014-12-23 17:40:10', 1, '2014-12-23 17:40:11', 1, 75, 'en-75'),
(78, 'barang-surat-jalan', 'mesin-hardcover-maker', 'mesin-hardcover-maker-8', '', 0, 77, 1, 0, '2014-12-29 15:31:03', 1, '2014-12-29 15:31:04', 1, 78, 'en-78'),
(77, 'surat-jalan', 'SRJ141229001', 'srj141229001', 'test surat jalan', 0, 0, 1, 2, '2014-12-29 15:31:03', 1, '2014-12-30 19:31:55', 1, 77, 'en-77'),
(79, 'pindah-keluar', 'andy-basuki_mesin-hardcover-maker', 'andy-basuki-mesin-hardcover-maker', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj141229001''>SRJ141229001</a>', 5, 4, 1, 0, '2014-12-29 00:00:00', 1, '2014-12-29 15:31:04', 1, 79, 'en-79'),
(80, 'barang-surat-jalan', 'bahan-tinta', 'bahan-tinta-6', '', 0, 77, 1, 0, '2014-12-29 15:31:04', 1, '2014-12-29 15:31:04', 1, 80, 'en-80'),
(81, 'pindah-keluar', 'andy-basuki_bahan-tinta', 'andy-basuki-bahan-tinta', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj141229001''>SRJ141229001</a>', 5, 33, 1, 0, '2014-12-29 00:00:00', 1, '2014-12-29 15:31:04', 1, 81, 'en-81'),
(82, 'surat-jalan', 'SRJ141229002', 'srj141229002', 'test surat jalan kedua', 0, 0, 0, 1, '2014-12-29 15:31:53', 1, '2014-12-29 15:31:54', 1, 82, 'en-82'),
(83, 'barang-surat-jalan', 'bahan-tinta', 'bahan-tinta-7', '', 0, 82, 1, 0, '2014-12-29 15:31:53', 1, '2014-12-29 15:31:54', 1, 83, 'en-83'),
(84, 'pindah-keluar', 'andy-basuki_bahan-tinta', 'andy-basuki-bahan-tinta-1', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj141229002''>SRJ141229002</a>', 2, 33, 1, 0, '2014-12-29 00:00:00', 1, '2014-12-29 15:31:54', 1, 84, 'en-84'),
(87, 'purchase-order', 'PUR150102001', 'pur150102001', 'nambah stock aja gan', 0, 0, 1, 7, '2015-01-02 14:46:13', 1, '2015-01-02 14:47:20', 1, 87, 'en-87'),
(86, 'resi', 'QZ8501XZPBAS', 'qz8501xzpbas', '', 0, 0, 1, 0, '2014-12-30 19:31:55', 1, '2014-12-30 19:31:55', 1, 86, 'en-86'),
(88, 'purchase-detail', 'bahan-tinta', 'bahan-tinta-8', '', 0, 87, 1, 0, '2015-01-02 14:46:13', 1, '2015-01-02 14:46:13', 1, 88, 'en-88'),
(89, 'hutang', 'HUT150102001', 'hut150102001', 'Beli <strong>Bahan Tinta</strong> sebanyak 12 Galon @Rp.345.000,-', 0, 87, 1, 0, '2015-01-02 14:46:13', 1, '2015-01-02 14:46:14', 1, 89, 'en-89'),
(90, 'purchase-detail', 'paku-tancep-x', 'paku-tancep-x-1', '', 0, 87, 1, 0, '2015-01-02 14:46:14', 1, '2015-01-02 14:46:14', 1, 90, 'en-90'),
(91, 'hutang', 'HUT150102002', 'hut150102002', 'Beli <strong>Paku Tancep X</strong> sebanyak 8 kg @Rp.3.500,-', 0, 87, 1, 0, '2015-01-02 14:46:14', 1, '2015-01-02 14:46:14', 1, 91, 'en-91'),
(92, 'barang-masuk', 'bahan-tinta', 'bahan-tinta-9', '', 0, 87, 1, 0, '2015-01-02 14:46:45', 1, '2015-01-02 14:46:45', 1, 92, 'en-92'),
(93, 'barang-gudang', 'bahan-tinta', 'bahan-tinta-10', 'Kiriman dari supplier.', 0, 4, 1, 0, '2015-01-02 14:46:46', 1, '2015-01-02 14:46:46', 1, 93, 'en-93'),
(94, 'pindah-masuk', 'he-xin-min_bahan-tinta', 'he-xin-min-bahan-tinta-2', 'Pembelian INVOICE kode PUR150102001', 12, 4, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 14:46:46', 1, 94, 'en-94'),
(95, 'barang-masuk', 'paku-tancep-x', 'paku-tancep-x-2', '', 0, 87, 1, 0, '2015-01-02 14:46:46', 1, '2015-01-02 14:46:46', 1, 95, 'en-95'),
(96, 'barang-gudang', 'paku-tancep-x', 'paku-tancep-x-3', 'Kiriman dari supplier.', 0, 4, 1, 0, '2015-01-02 14:46:46', 1, '2015-01-02 14:46:46', 1, 96, 'en-96'),
(97, 'pindah-masuk', 'he-xin-min_paku-tancep-x', 'he-xin-min-paku-tancep-x', 'Pembelian INVOICE kode PUR150102001', 8, 4, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 14:46:46', 1, 97, 'en-97'),
(98, 'hutang', 'HUT150102003', 'hut150102003', 'Invoice lunas (Instant Paid Off)', 0, 87, 1, 0, '2015-01-02 14:47:20', 1, '2015-01-02 14:47:20', 1, 98, 'en-98'),
(99, 'surat-jalan', 'SRJ150102001', 'srj150102001', 'coba retur beli gan.', 0, 0, 0, 1, '2015-01-02 15:19:44', 1, '2015-01-02 15:52:28', 1, 99, 'en-99'),
(100, 'barang-surat-jalan', 'bahan-tinta', 'bahan-tinta-11', '', 0, 99, 1, 0, '2015-01-02 15:19:45', 1, '2015-01-02 15:19:45', 1, 100, 'en-100'),
(101, 'pindah-keluar', 'he-xin-min_bahan-tinta', 'he-xin-min-bahan-tinta-3', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj150102001''>SRJ150102001</a>', 3, 4, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 15:19:45', 1, 101, 'en-101'),
(102, 'piutang', 'PIU150102001', 'piu150102001', 'Invoice lunas (Instant Paid Off)', 0, 64, 1, 0, '2015-01-02 15:41:00', 1, '2015-01-02 15:41:00', 1, 102, 'en-102'),
(104, 'purchase-order', 'PUR150102002', 'pur150102002', 'nambah stok edisi 2 gan\r\nbahan tinta', 0, 0, 1, 3, '2015-01-02 15:54:47', 1, '2015-01-02 15:55:58', 1, 104, 'en-104'),
(105, 'purchase-detail', 'bahan-tinta', 'bahan-tinta-12', '', 0, 104, 1, 0, '2015-01-02 15:54:47', 1, '2015-01-02 15:54:47', 1, 105, 'en-105'),
(106, 'hutang', 'HUT150102004', 'hut150102004', 'Beli <strong>Bahan Tinta</strong> sebanyak 5 Galon @Rp.345.000,-', 0, 104, 1, 0, '2015-01-02 15:54:47', 1, '2015-01-02 15:54:47', 1, 106, 'en-106'),
(107, 'barang-masuk', 'bahan-tinta', 'bahan-tinta-13', '', 0, 104, 1, 0, '2015-01-02 15:55:58', 1, '2015-01-02 15:55:58', 1, 107, 'en-107'),
(108, 'pindah-masuk', 'he-xin-min_bahan-tinta', 'he-xin-min-bahan-tinta-4', 'Pembelian INVOICE kode PUR150102002', 5, 33, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 15:55:58', 1, 108, 'en-108'),
(132, 'pindah-masuk', 'andy-basuki_mesin-hardcover-maker', 'andy-basuki-mesin-hardcover-maker-1', 'Retur Penjualan INVOICE kode <a target=''_blank'' href=''/tokomaju/admin/entries/sales-order/edit/sal301205001''>SAL301205001</a>', 2, 4, 1, 0, '2015-01-03 00:00:00', 1, '2015-01-03 15:24:26', 1, 132, 'en-132'),
(131, 'retur-jual', 'mesin-hardcover-maker', 'mesin-hardcover-maker-10', 'tes retur mesin', 0, 64, 1, 0, '2015-01-03 15:24:26', 1, '2015-01-03 15:24:26', 1, 131, 'en-131'),
(111, 'pindah-keluar', 'he-xin-min_bahan-tinta', 'he-xin-min-bahan-tinta-5', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj150102002''>SRJ150102002</a>', 5, 4, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 15:59:01', 1, 111, 'en-111'),
(113, 'pindah-keluar', 'he-xin-min_bahan-tinta', 'he-xin-min-bahan-tinta-6', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj150102002''>SRJ150102002</a>', 4, 33, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 15:59:02', 1, 113, 'en-113'),
(114, 'surat-jalan', 'SRJ150102003', 'srj150102003', '', 0, 0, 0, 1, '2015-01-02 16:06:23', 1, '2015-01-02 16:06:23', 1, 114, 'en-114'),
(115, 'barang-surat-jalan', 'paku-tancep-x', 'paku-tancep-x-4', '', 0, 114, 1, 0, '2015-01-02 16:06:23', 1, '2015-01-02 16:06:23', 1, 115, 'en-115'),
(116, 'pindah-keluar', 'he-xin-min_paku-tancep-x', 'he-xin-min-paku-tancep-x-1', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj150102003''>SRJ150102003</a>', 3, 4, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 16:06:23', 1, 116, 'en-116'),
(117, 'purchase-order', 'PUR150102003', 'pur150102003', 'dari indah rek', 0, 0, 1, 3, '2015-01-02 16:11:35', 1, '2015-01-02 16:11:58', 1, 117, 'en-117'),
(118, 'purchase-detail', 'mesin-vcut', 'mesin-vcut-2', '', 0, 117, 1, 0, '2015-01-02 16:11:35', 1, '2015-01-02 16:11:35', 1, 118, 'en-118'),
(119, 'hutang', 'HUT150102005', 'hut150102005', 'Beli <strong>Mesin VCut</strong> sebanyak 5 unit @Rp.12.000.000,-', 0, 117, 1, 0, '2015-01-02 16:11:35', 1, '2015-01-02 16:11:35', 1, 119, 'en-119'),
(120, 'barang-masuk', 'mesin-vcut', 'mesin-vcut-3', '', 0, 117, 1, 0, '2015-01-02 16:11:58', 1, '2015-01-02 16:11:58', 1, 120, 'en-120'),
(121, 'pindah-masuk', 'indah-pertiiwi_mesin-vcut', 'indah-pertiiwi-mesin-vcut', 'Pembelian INVOICE kode PUR150102003', 5, 4, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 16:11:58', 1, 121, 'en-121'),
(122, 'surat-jalan', 'SRJ150102004', 'srj150102004', 'last retur beli gan', 0, 0, 0, 1, '2015-01-02 16:15:59', 1, '2015-01-02 16:15:59', 1, 122, 'en-122'),
(123, 'barang-surat-jalan', 'mesin-hardcover-maker', 'mesin-hardcover-maker-9', '', 0, 122, 1, 0, '2015-01-02 16:15:59', 1, '2015-01-02 16:15:59', 1, 123, 'en-123'),
(124, 'pindah-keluar', 'he-xin-min_mesin-hardcover-maker', 'he-xin-min-mesin-hardcover-maker-2', 'Pengiriman Surat Jalan <a target=''_blank'' href=''/tokomaju/admin/entries/surat-jalan/edit/srj150102004''>SRJ150102004</a>', 2, 4, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 16:15:59', 1, 124, 'en-124'),
(125, 'purchase-order', 'PUR150102004', 'pur150102004', '', 0, 0, 1, 3, '2015-01-02 16:26:23', 1, '2015-01-02 16:26:42', 1, 125, 'en-125'),
(126, 'purchase-detail', 'mesin-vcut', 'mesin-vcut-4', '', 0, 125, 1, 0, '2015-01-02 16:26:23', 1, '2015-01-02 16:26:23', 1, 126, 'en-126'),
(127, 'hutang', 'HUT150102006', 'hut150102006', 'Beli <strong>Mesin VCut</strong> sebanyak 7 unit @Rp.12.000.000,-', 0, 125, 1, 0, '2015-01-02 16:26:23', 1, '2015-01-02 16:26:23', 1, 127, 'en-127'),
(128, 'barang-masuk', 'mesin-vcut', 'mesin-vcut-5', '', 0, 125, 1, 0, '2015-01-02 16:26:42', 1, '2015-01-02 16:26:42', 1, 128, 'en-128'),
(129, 'barang-gudang', 'mesin-vcut', 'mesin-vcut-6', 'Kiriman dari supplier.', 0, 33, 1, 0, '2015-01-02 16:26:42', 1, '2015-01-02 16:26:42', 1, 129, 'en-129'),
(130, 'pindah-masuk', 'indah-pertiiwi_mesin-vcut', 'indah-pertiiwi-mesin-vcut-1', 'Pembelian INVOICE kode PUR150102004', 3, 33, 1, 0, '2015-01-02 00:00:00', 1, '2015-01-02 16:26:42', 1, 130, 'en-130'),
(133, 'retur-jual', 'bahan-tinta', 'bahan-tinta-14', 'tes retur jual bahan baku', 0, 64, 1, 0, '2015-01-03 15:24:26', 1, '2015-01-03 15:24:27', 1, 133, 'en-133'),
(134, 'pindah-masuk', 'andy-basuki_bahan-tinta', 'andy-basuki-bahan-tinta-2', 'Retur Penjualan INVOICE kode <a target=''_blank'' href=''/tokomaju/admin/entries/sales-order/edit/sal301205001''>SAL301205001</a>', 3, 4, 1, 0, '2015-01-03 00:00:00', 1, '2015-01-03 15:24:27', 1, 134, 'en-134'),
(135, 'retur-jual', 'bahan-tinta', 'bahan-tinta-15', 'test aja nih KB', 0, 64, 1, 0, '2015-01-03 16:04:19', 1, '2015-01-03 16:04:20', 1, 135, 'en-135'),
(136, 'pindah-masuk', 'andy-basuki_bahan-tinta', 'andy-basuki-bahan-tinta-3', 'Retur Penjualan INVOICE kode <a target=''_blank'' href=''/tokomaju/admin/entries/sales-order/edit/sal301205001''>SAL301205001</a>', 1, 33, 1, 0, '2015-01-15 00:00:00', 1, '2015-01-03 16:04:20', 1, 136, 'en-136');

-- --------------------------------------------------------

--
-- Table structure for table `cms_entry_metas`
--

CREATE TABLE IF NOT EXISTS `cms_entry_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `key` varchar(500) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=422 ;

--
-- Dumping data for table `cms_entry_metas`
--

INSERT INTO `cms_entry_metas` (`id`, `entry_id`, `key`, `value`) VALUES
(1, 1, 'form-perusahaan', 'PT. Creazi Citra Cemerlang'),
(2, 1, 'form-alamat', 'Jl. Nginden Semolo 101\r\nJl. DHI Barat 43 B'),
(3, 1, 'form-kota', 'Surabaya, Indonesia'),
(4, 1, 'form-kode_pos', '60285'),
(5, 1, 'form-telepon', '031 5995 630'),
(6, 1, 'form-handphone', '081 7525 5381'),
(7, 1, 'form-email', 'andybasuki88@gmail.com'),
(8, 2, 'form-perusahaan', 'Huangsha Gongsi'),
(9, 2, 'form-alamat', 'Jl. Huangsha Dadao no 1232'),
(10, 2, 'form-kota', 'Hangzhou'),
(11, 2, 'form-kode_pos', '60717'),
(12, 2, 'form-telepon', '031 870 4371'),
(13, 2, 'form-handphone', '089 67367 1110'),
(14, 2, 'form-fax', '718298'),
(15, 2, 'form-email', 'harusrajin@gmail.com'),
(16, 2, 'form-rekening_bank', 'BCA - 388 035 6860'),
(17, 3, 'form-perusahaan', 'PT. Karunia Persada'),
(18, 3, 'form-alamat', 'Jl. Kalibutuh\r\nJl. Kudus'),
(19, 3, 'form-rute_jalan_awal', 'Kudus'),
(20, 3, 'form-rute_jalan_akhir', 'Surabaya'),
(21, 3, 'form-handphone', '081 737 5678'),
(22, 3, 'form-email', 'gunawan@gmail.com'),
(23, 3, 'form-rekening_bank', 'Mandiri - 0620 567 1213'),
(24, 4, 'form-alamat', 'Jl. Pengampon Square C6'),
(25, 4, 'form-kota', 'Surabaya'),
(26, 4, 'form-kode_pos', '879028'),
(27, 4, 'form-telepon', '031 372 4582'),
(28, 4, 'form-fax', '031 372 4583'),
(29, 4, 'form-nama_pegawai', 'Tony Hermawan'),
(30, 4, 'form-handphone', '081 2345 6789'),
(47, 8, 'form-harga_beli', '6000000'),
(46, 8, 'form-supplier', 'he-xin-min|indah-pertiiwi'),
(45, 8, 'form-satuan', 'unit'),
(44, 8, 'form-jenis_barang', 'mesin'),
(36, 9, 'form-perusahaan', 'PT. Indah Pertiwi'),
(37, 9, 'form-alamat', 'Jl. Ikan Lele No 1'),
(38, 9, 'form-kota', 'Incheon'),
(39, 9, 'form-kode_pos', '8908727'),
(40, 9, 'form-telepon', '7228392192'),
(41, 9, 'form-handphone', '+628612328828128'),
(42, 9, 'form-email', 'indahpertiwi@yahoo.com'),
(43, 9, 'form-rekening_bank', 'BCA - 123 456 7890'),
(48, 8, 'form-harga_jual', '15000000'),
(49, 4, 'count-barang-gudang', '4'),
(72, 16, 'form-stock', '24'),
(52, 4, 'count-pindah-masuk', '14'),
(53, 8, 'form-stock', '38'),
(55, 4, 'count-pindah-keluar', '12'),
(57, 18, 'form-jenis_barang', 'bahan-baku'),
(58, 18, 'form-satuan', 'Galon'),
(59, 18, 'form-supplier', 'he-xin-min'),
(60, 18, 'form-harga_beli', '345000'),
(61, 18, 'form-harga_jual', '600000'),
(63, 18, 'form-stock', '9'),
(226, 33, 'form-telepon', '031 372 9191'),
(225, 33, 'form-kode_pos', '60969'),
(224, 33, 'form-kota', 'Raden'),
(223, 33, 'form-alamat', 'Jl. Romokalisari No 12'),
(79, 33, 'count-barang-gudang', '3'),
(80, 34, 'form-stock', '14'),
(81, 33, 'count-pindah-masuk', '7'),
(82, 36, 'form-tanggal', '11/28/2014'),
(83, 36, 'form-supplier', 'he-xin-min'),
(84, 36, 'form-status_bayar', 'Tunggak'),
(85, 36, 'form-status_kirim', 'Diproses'),
(86, 36, 'form-nama_pegawai', 'Bejo'),
(87, 36, 'form-total_harga', '150000'),
(98, 39, 'form-tanggal', '12/04/2014'),
(99, 39, 'form-supplier', 'he-xin-min'),
(100, 39, 'form-status_bayar', 'Lunas'),
(101, 39, 'form-status_kirim', 'Terkirim'),
(102, 39, 'form-nama_pegawai', 'admin zpanel'),
(103, 39, 'count-purchase-detail', '2'),
(104, 40, 'form-jumlah', '3'),
(105, 40, 'form-harga', '6000000'),
(106, 40, 'form-terkirim', '3'),
(107, 40, 'form-retur', '2'),
(108, 39, 'count-hutang', '5'),
(109, 41, 'form-tanggal', '12/04/2014'),
(110, 41, 'form-mutasi_kredit', '18000000'),
(111, 42, 'form-jumlah', '7'),
(112, 42, 'form-harga', '345000'),
(113, 42, 'form-terkirim', '7'),
(114, 42, 'form-retur', '0'),
(115, 43, 'form-tanggal', '12/04/2014'),
(116, 43, 'form-mutasi_kredit', '2415000'),
(117, 44, 'form-tanggal', '12/04/2014'),
(118, 44, 'form-mutasi_debet', '20415000'),
(119, 39, 'form-balance', '0'),
(120, 39, 'form-total_harga', '20415000'),
(121, 39, 'count-barang-masuk', '4'),
(122, 46, 'form-jumlah_datang', '3'),
(123, 46, 'form-tanggal', '12/25/2013'),
(124, 46, 'form-gudang', 'romokalisari'),
(125, 46, 'form-sisa', '4'),
(126, 47, 'form-stock', '2'),
(127, 49, 'form-jumlah_datang', '2'),
(128, 49, 'form-tanggal', '12/25/2013'),
(129, 49, 'form-gudang', 'romokalisari'),
(130, 49, 'form-sisa', '1'),
(131, 51, 'form-jumlah_datang', '1'),
(132, 51, 'form-tanggal', '12/16/2014'),
(133, 51, 'form-gudang', 'pengampon'),
(134, 51, 'form-sisa', '0'),
(135, 53, 'form-jumlah_datang', '4'),
(136, 53, 'form-tanggal', '12/16/2014'),
(137, 53, 'form-gudang', 'romokalisari'),
(138, 53, 'form-sisa', '0'),
(150, 59, 'form-supplier', 'he-xin-min'),
(145, 57, 'form-mutasi_kredit', '520000'),
(144, 57, 'form-tanggal', '12/20/2014'),
(142, 56, 'form-tanggal', '12/17/2014'),
(143, 56, 'form-mutasi_debet', '520000'),
(151, 59, 'form-status_bayar', 'Tunggak'),
(149, 59, 'form-tanggal', '12/19/2014'),
(152, 59, 'form-status_kirim', 'Diproses'),
(153, 59, 'form-nama_pegawai', 'admin zpanel'),
(154, 59, 'form-balance', '0'),
(155, 59, 'form-total_harga', '0'),
(156, 60, 'form-tanggal', '12/19/2014'),
(157, 60, 'form-supplier', 'he-xin-min'),
(158, 60, 'form-status_bayar', 'Tunggak'),
(159, 60, 'form-status_kirim', 'Diproses'),
(160, 60, 'form-nama_pegawai', 'admin zpanel'),
(161, 60, 'count-purchase-detail', '1'),
(162, 61, 'form-jumlah', '3'),
(163, 61, 'form-harga', '6000000'),
(164, 61, 'form-terkirim', '0'),
(165, 61, 'form-retur', '0'),
(166, 60, 'count-hutang', '1'),
(167, 62, 'form-tanggal', '12/19/2014'),
(168, 62, 'form-mutasi_kredit', '18000000'),
(169, 60, 'form-balance', '18000000'),
(170, 60, 'form-total_harga', '18000000'),
(173, 64, 'form-tanggal', '12/05/2030'),
(174, 64, 'form-customer', 'andy-basuki'),
(175, 64, 'form-status_bayar', 'Lunas'),
(176, 64, 'form-status_kirim', 'Terkirim'),
(177, 64, 'form-nama_pegawai', 'Bejo Sugiantoro'),
(178, 64, 'form-diskon_nota', '350000'),
(179, 64, 'form-uang_muka', '25000000'),
(180, 64, 'form-ongkos_tambahan', '300000'),
(181, 64, 'count-sales-detail', '2'),
(182, 65, 'form-jumlah', '5'),
(183, 65, 'form-harga', '15000000'),
(184, 65, 'form-diskon', '250000'),
(185, 65, 'form-profit', '44750000'),
(186, 65, 'form-terkirim', '5'),
(187, 65, 'form-retur', '2'),
(188, 64, 'count-piutang', '5'),
(189, 66, 'form-tanggal', '12/19/2014'),
(190, 66, 'form-mutasi_debet', '74750000'),
(191, 67, 'form-jumlah', '7'),
(192, 67, 'form-harga', '600000'),
(193, 67, 'form-diskon', '0'),
(194, 67, 'form-profit', '1785000'),
(195, 67, 'form-terkirim', '7'),
(196, 67, 'form-retur', '4'),
(197, 68, 'form-tanggal', '12/19/2014'),
(198, 68, 'form-mutasi_debet', '4200000'),
(199, 69, 'form-tanggal', '12/19/2014'),
(200, 69, 'form-mutasi_kredit', '350000'),
(201, 70, 'form-tanggal', '12/19/2014'),
(202, 70, 'form-mutasi_kredit', '25000000'),
(203, 64, 'form-balance', '0'),
(204, 64, 'form-total_harga', '78600000'),
(205, 64, 'form-laba_bersih', '45885000'),
(206, 71, 'form-perusahaan', 'PT. Fleur ReadyStock'),
(207, 71, 'form-alamat', 'Jl. Baruk Utara 7'),
(208, 71, 'form-kota', 'Surabaya, Indonesia'),
(209, 71, 'form-kode_pos', '60606'),
(210, 71, 'form-handphone', '0817375678'),
(211, 72, 'form-jenis_barang', 'mesin'),
(212, 72, 'form-satuan', 'unit'),
(213, 72, 'form-supplier', 'indah-pertiiwi'),
(214, 72, 'form-harga_beli', '12000000'),
(215, 72, 'form-harga_jual', '21500000'),
(216, 73, 'form-stock', '13'),
(217, 72, 'form-stock', '16'),
(218, 75, 'form-jenis_barang', 'sparepart'),
(219, 75, 'form-satuan', 'kg'),
(220, 75, 'form-supplier', 'he-xin-min'),
(221, 75, 'form-harga_beli', '3500'),
(222, 75, 'form-harga_jual', '6000'),
(227, 33, 'form-nama_pegawai', 'Pak Sutejo'),
(228, 33, 'form-handphone', '081 737 1234'),
(233, 77, 'form-sales_order', 'sal301205001'),
(232, 77, 'form-tanggal', '12/29/2014'),
(234, 77, 'form-customer', 'andy-basuki'),
(235, 77, 'form-ekspedisi', 'pak-gunawan-santoso'),
(236, 77, 'count-barang-surat-jalan', '2'),
(237, 78, 'form-jumlah', '5'),
(238, 78, 'form-gudang', 'pengampon'),
(239, 80, 'form-jumlah', '5'),
(240, 80, 'form-gudang', 'romokalisari'),
(241, 33, 'count-pindah-keluar', '3'),
(242, 82, 'form-tanggal', '12/29/2014'),
(243, 82, 'form-sales_order', 'sal301205001'),
(244, 82, 'form-customer', 'andy-basuki'),
(245, 82, 'form-ekspedisi', 'pak-gunawan-santoso'),
(246, 82, 'count-barang-surat-jalan', '1'),
(247, 83, 'form-jumlah', '2'),
(248, 83, 'form-gudang', 'romokalisari'),
(268, 87, 'form-tanggal', '01/02/2015'),
(267, 86, 'form-sistem_bayar', 'Gratis'),
(266, 86, 'form-pihak_bayar', 'Tujuan'),
(265, 86, 'form-tanggal', '12/11/2014'),
(264, 86, 'form-surat_jalan', 'srj141229001'),
(269, 87, 'form-supplier', 'he-xin-min'),
(270, 87, 'form-status_bayar', 'Lunas'),
(271, 87, 'form-status_kirim', 'Terkirim'),
(272, 87, 'form-nama_pegawai', 'admin zpanel'),
(273, 87, 'count-purchase-detail', '2'),
(274, 88, 'form-jumlah', '12'),
(275, 88, 'form-harga', '345000'),
(276, 88, 'form-terkirim', '12'),
(277, 88, 'form-retur', '12'),
(278, 87, 'count-hutang', '3'),
(279, 89, 'form-tanggal', '01/02/2015'),
(280, 89, 'form-mutasi_kredit', '4140000'),
(281, 90, 'form-jumlah', '8'),
(282, 90, 'form-harga', '3500'),
(283, 90, 'form-terkirim', '8'),
(284, 90, 'form-retur', '3'),
(285, 91, 'form-tanggal', '01/02/2015'),
(286, 91, 'form-mutasi_kredit', '28000'),
(287, 87, 'form-balance', '0'),
(288, 87, 'form-total_harga', '4168000'),
(289, 87, 'count-barang-masuk', '2'),
(290, 92, 'form-jumlah_datang', '12'),
(291, 92, 'form-tanggal', '01/02/2015'),
(292, 92, 'form-gudang', 'pengampon'),
(293, 92, 'form-sisa', '0'),
(294, 93, 'form-stock', '7'),
(295, 95, 'form-jumlah_datang', '8'),
(296, 95, 'form-tanggal', '01/02/2015'),
(297, 95, 'form-gudang', 'pengampon'),
(298, 95, 'form-sisa', '0'),
(299, 96, 'form-stock', '5'),
(300, 75, 'form-stock', '5'),
(301, 98, 'form-tanggal', '01/02/2015'),
(302, 98, 'form-mutasi_debet', '4168000'),
(303, 99, 'form-tanggal', '01/02/2015'),
(304, 99, 'form-purchase_order', 'pur150102001'),
(305, 99, 'form-supplier', 'he-xin-min'),
(306, 99, 'form-ekspedisi', 'pak-gunawan-santoso'),
(307, 99, 'count-barang-surat-jalan', '1'),
(308, 100, 'form-jumlah', '3'),
(309, 100, 'form-gudang', 'pengampon'),
(310, 102, 'form-tanggal', '01/02/2015'),
(311, 102, 'form-mutasi_kredit', '53600000'),
(320, 104, 'form-status_kirim', 'Terkirim'),
(319, 104, 'form-status_bayar', 'Tunggak'),
(318, 104, 'form-supplier', 'he-xin-min'),
(317, 104, 'form-tanggal', '01/02/2015'),
(321, 104, 'form-nama_pegawai', 'admin zpanel'),
(322, 104, 'count-purchase-detail', '1'),
(323, 105, 'form-jumlah', '5'),
(324, 105, 'form-harga', '345000'),
(325, 105, 'form-terkirim', '5'),
(326, 105, 'form-retur', '0'),
(327, 104, 'count-hutang', '1'),
(328, 106, 'form-tanggal', '01/02/2015'),
(329, 106, 'form-mutasi_kredit', '1725000'),
(330, 104, 'form-balance', '1725000'),
(331, 104, 'form-total_harga', '1725000'),
(332, 104, 'count-barang-masuk', '1'),
(333, 107, 'form-jumlah_datang', '5'),
(334, 107, 'form-tanggal', '01/02/2015'),
(335, 107, 'form-gudang', 'romokalisari'),
(336, 107, 'form-sisa', '0'),
(404, 133, 'form-jumlah', '3'),
(403, 131, 'form-gudang', 'pengampon'),
(402, 131, 'form-tanggal', '01/03/2015'),
(401, 131, 'form-jumlah', '2'),
(400, 64, 'count-retur-jual', '3'),
(406, 133, 'form-gudang', 'pengampon'),
(405, 133, 'form-tanggal', '01/03/2015'),
(346, 114, 'form-tanggal', '01/02/2015'),
(347, 114, 'form-purchase_order', 'pur150102001'),
(348, 114, 'form-supplier', 'he-xin-min'),
(349, 114, 'count-barang-surat-jalan', '1'),
(350, 115, 'form-jumlah', '3'),
(351, 115, 'form-gudang', 'pengampon'),
(352, 117, 'form-tanggal', '01/02/2015'),
(353, 117, 'form-supplier', 'indah-pertiiwi'),
(354, 117, 'form-status_bayar', 'Tunggak'),
(355, 117, 'form-status_kirim', 'Terkirim'),
(356, 117, 'form-nama_pegawai', 'admin zpanel'),
(357, 117, 'count-purchase-detail', '1'),
(358, 118, 'form-jumlah', '5'),
(359, 118, 'form-harga', '12000000'),
(360, 118, 'form-terkirim', '5'),
(361, 118, 'form-retur', '0'),
(362, 117, 'count-hutang', '1'),
(363, 119, 'form-tanggal', '01/02/2015'),
(364, 119, 'form-mutasi_kredit', '60000000'),
(365, 117, 'form-balance', '60000000'),
(366, 117, 'form-total_harga', '60000000'),
(367, 117, 'count-barang-masuk', '1'),
(368, 120, 'form-jumlah_datang', '5'),
(369, 120, 'form-tanggal', '01/02/2015'),
(370, 120, 'form-gudang', 'pengampon'),
(371, 120, 'form-sisa', '0'),
(372, 122, 'form-tanggal', '01/02/2015'),
(373, 122, 'form-purchase_order', 'pur141204001'),
(374, 122, 'form-supplier', 'he-xin-min'),
(375, 122, 'form-ekspedisi', 'pak-gunawan-santoso'),
(376, 122, 'count-barang-surat-jalan', '1'),
(377, 123, 'form-jumlah', '2'),
(378, 123, 'form-gudang', 'pengampon'),
(379, 125, 'form-tanggal', '01/02/2015'),
(380, 125, 'form-supplier', 'indah-pertiiwi'),
(381, 125, 'form-status_bayar', 'Tunggak'),
(382, 125, 'form-status_kirim', 'Diproses'),
(383, 125, 'form-nama_pegawai', 'Hana Tania'),
(384, 125, 'count-purchase-detail', '1'),
(385, 126, 'form-jumlah', '7'),
(386, 126, 'form-harga', '12000000'),
(387, 126, 'form-terkirim', '3'),
(388, 126, 'form-retur', '0'),
(389, 125, 'count-hutang', '1'),
(390, 127, 'form-tanggal', '01/02/2015'),
(391, 127, 'form-mutasi_kredit', '84000000'),
(392, 125, 'form-balance', '84000000'),
(393, 125, 'form-total_harga', '84000000'),
(394, 125, 'count-barang-masuk', '1'),
(395, 128, 'form-jumlah_datang', '3'),
(396, 128, 'form-tanggal', '01/02/2015'),
(397, 128, 'form-gudang', 'romokalisari'),
(398, 128, 'form-sisa', '4'),
(399, 129, 'form-stock', '3'),
(407, 135, 'form-jumlah', '1'),
(408, 135, 'form-tanggal', '01/15/2015'),
(409, 135, 'form-gudang', 'romokalisari');

-- --------------------------------------------------------

--
-- Table structure for table `cms_roles`
--

CREATE TABLE IF NOT EXISTS `cms_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `description` text,
  `count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cms_roles`
--

INSERT INTO `cms_roles` (`id`, `name`, `description`, `count`) VALUES
(1, 'Super Admin', 'Administrator who has all access for the web without exceptions.', 1),
(2, 'Admin', 'Administrator from the clients.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_settings`
--

CREATE TABLE IF NOT EXISTS `cms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(500) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cms_settings`
--

INSERT INTO `cms_settings` (`id`, `key`, `value`) VALUES
(1, 'title', 'Toko Maju'),
(2, 'tagline', 'toko, dagang, jualan, supplier, bahan, bangunan, maju, palu, indonesia'),
(3, 'description', 'sistem inventory Toko Maju.'),
(4, 'date_format', 'd F Y'),
(5, 'time_format', 'h:i A'),
(6, 'header', ''),
(7, 'top_insert', ''),
(8, 'bottom_insert', ''),
(9, 'google_analytics_code', ''),
(10, 'display_width', '3200'),
(11, 'display_height', '1800'),
(12, 'display_crop', '0'),
(13, 'thumb_width', '120'),
(14, 'thumb_height', '120'),
(15, 'thumb_crop', '0'),
(16, 'language', 'en_english'),
(17, 'table_view', 'complex'),
(18, 'usd_sell', '9732.00'),
(19, 'custom-pagination', '10');

-- --------------------------------------------------------

--
-- Table structure for table `cms_types`
--

CREATE TABLE IF NOT EXISTS `cms_types` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `cms_types`
--

INSERT INTO `cms_types` (`id`, `name`, `slug`, `description`, `parent_id`, `count`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 'Media Library', 'media', 'All media image is stored here.', 0, 0, '2013-01-15 03:35:14', 1, '2013-01-15 03:35:14', 1),
(6, 'Ekspedisi', 'ekspedisi', 'Langganan Ekspedisi yang biasa digunakan.', 0, 0, '2014-11-23 12:02:07', 1, '2014-11-23 12:02:07', 1),
(4, 'Customer', 'customer', 'Daftar pelanggan Toko Maju.', 0, 0, '2014-11-23 10:34:21', 1, '2014-11-23 10:34:21', 1),
(5, 'Supplier', 'supplier', 'Daftar para Supplier Toko Maju.', 0, 0, '2014-11-23 11:24:18', 1, '2014-11-23 11:24:18', 1),
(7, 'Gudang', 'gudang', 'Daftar Gudang tempat penyimpanan barang dagang.', 0, 3, '2014-11-25 11:03:15', 1, '2014-11-25 15:24:15', 1),
(8, 'Jenis Barang', 'jenis-barang', 'Daftar berbagai jenis barang yg diperdagangkan.', 0, 0, '2014-11-25 11:23:40', 1, '2014-11-25 11:23:40', 1),
(9, 'Barang Dagang', 'barang-dagang', 'Detail Produk Barang Dagang.', 0, 0, '2014-11-25 11:58:26', 1, '2014-11-25 11:58:26', 1),
(10, 'Barang Gudang', 'barang-gudang', 'Pencatatan stok penyimpanan barang di gudang.', 7, 0, '2014-11-25 13:43:09', 1, '2014-11-25 13:43:09', 1),
(11, 'History Masuk', 'pindah-masuk', 'Seluruh pencatatan history barang yg masuk ke gudang ini.', 7, 0, '2014-11-25 15:23:57', 1, '2014-11-25 15:23:57', 1),
(12, 'History Keluar', 'pindah-keluar', 'Seluruh pencatatan history barang yg keluar dari gudang ini.', 7, 0, '2014-11-25 15:24:15', 1, '2014-11-25 15:24:15', 1),
(13, 'Purchase Order', 'purchase-order', 'Surat pemesanan barang terhadap supplier.', 0, 3, '2014-11-28 15:31:36', 1, '2014-12-29 15:41:48', 1),
(14, 'Purchase Detail', 'purchase-detail', 'Seluruh barang yg terdaftar di faktur pembelian.', 13, 0, '2014-11-28 15:49:50', 1, '2014-11-28 15:49:50', 1),
(15, 'Barang Masuk', 'barang-masuk', 'Seluruh pencatatan barang yang masuk dari supplier berdasarkan faktur pembelian.', 13, 0, '2014-11-28 16:06:25', 1, '2014-11-28 16:06:25', 1),
(16, 'Hutang', 'hutang', 'Catatan beban hutang terhadap supplier (Rekening Koran)', 13, 0, '2014-11-28 16:21:10', 1, '2014-11-28 16:21:10', 1),
(18, 'Sales Order', 'sales-order', 'Dokumen konfirmasi order penjualan terhadap customer.', 0, 3, '2014-11-30 12:27:50', 1, '2014-11-30 13:08:54', 1),
(19, 'Sales Detail', 'sales-detail', 'Seluruh barang yg terdaftar di faktur penjualan.', 18, 0, '2014-11-30 12:43:20', 1, '2014-11-30 12:43:20', 1),
(20, 'Piutang', 'piutang', 'Catatan pendapatan piutang dari customer (Rekening Koran)', 18, 0, '2014-11-30 12:52:14', 1, '2014-11-30 12:52:14', 1),
(21, 'Retur Jual', 'retur-jual', 'Seluruh catatan barang penjualan yang diretur dari customer.', 18, 0, '2014-11-30 13:08:54', 1, '2014-11-30 13:08:54', 1),
(22, 'Surat Jalan', 'surat-jalan', 'Surat Jalan untuk pengiriman barang ke customer / supplier.', 0, 1, '2014-11-30 13:29:28', 1, '2014-11-30 22:06:22', 1),
(23, 'Barang Surat Jalan', 'barang-surat-jalan', 'Seluruh detail barang dlm suatu surat jalan.', 22, 0, '2014-11-30 22:06:22', 1, '2014-11-30 22:06:22', 1),
(24, 'Resi', 'resi', 'Pemberian keterangan resi pada setiap Surat Jalan.', 0, 0, '2014-11-30 22:18:22', 1, '2014-11-30 22:18:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_type_metas`
--

CREATE TABLE IF NOT EXISTS `cms_type_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `key` varchar(500) NOT NULL,
  `value` text,
  `input_type` varchar(500) DEFAULT NULL,
  `validation` text,
  `instruction` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=118 ;

--
-- Dumping data for table `cms_type_metas`
--

INSERT INTO `cms_type_metas` (`id`, `type_id`, `key`, `value`, `input_type`, `validation`, `instruction`) VALUES
(21, 6, 'title_key', 'Nama Pegawai', '', '', ''),
(2, 4, 'title_key', 'Nama Lengkap', '', '', ''),
(3, 4, 'form-perusahaan', '', 'text', 'not_empty|', 'Nama Perusahaan tempat bekerja.'),
(4, 4, 'form-alamat', '', 'textarea', '', 'Alamat Perusahaan yang terkait.'),
(5, 4, 'form-kota', '', 'text', '', ''),
(6, 4, 'form-kode_pos', '', 'text', '', ''),
(7, 4, 'form-telepon', '', 'text', '', 'Nomer telepon yang dapat dihubungi.'),
(8, 4, 'form-handphone', '', 'text', 'not_empty|', 'Nomer handphone yang dapat dihubungi.'),
(9, 4, 'form-fax', '', 'text', '', 'Nomer Fax yang dapat terhubung.'),
(10, 4, 'form-email', '', 'text', 'is_email|', 'Alamat E-mail yang dapat dihubungi.'),
(11, 5, 'title_key', 'Nama Lengkap', '', '', ''),
(12, 5, 'form-perusahaan', '', 'text', 'not_empty|', 'Nama Perusahaan tempat bekerja.'),
(13, 5, 'form-alamat', '', 'textarea', '', 'Alamat Perusahaan yang terkait.'),
(14, 5, 'form-kota', '', 'text', '', ''),
(15, 5, 'form-kode_pos', '', 'text', '', ''),
(16, 5, 'form-telepon', '', 'text', '', 'Nomer telepon yang dapat dihubungi.'),
(17, 5, 'form-handphone', '', 'text', 'not_empty|', 'Nomer handphone yang dapat dihubungi.'),
(18, 5, 'form-fax', '', 'text', '', 'Nomer Fax yang dapat terhubung.'),
(19, 5, 'form-email', '', 'text', 'is_email|', 'Alamat E-mail yang dapat dihubungi.'),
(20, 5, 'form-rekening_bank', '', 'text', 'not_empty|', 'Nama bank dan Nomer rekening yang dituju.'),
(22, 6, 'form-perusahaan', '', 'text', 'not_empty|', 'Nama Perusahaan tempat bekerja.'),
(23, 6, 'form-alamat', '', 'textarea', '', 'Alamat Lengkap Perusahaan yang terkait.'),
(24, 6, 'form-rute_jalan_awal', '', 'text', 'not_empty|', 'Cabang / Kota Rute Asal.'),
(25, 6, 'form-rute_jalan_akhir', '', 'text', 'not_empty|', 'Cabang / Kota Rute Tujuan.'),
(26, 6, 'form-telepon', '', 'text', '', 'Nomer telepon yang dapat dihubungi.'),
(27, 6, 'form-handphone', '', 'text', 'not_empty|', 'Nomer handphone yang dapat dihubungi.'),
(28, 6, 'form-fax', '', 'text', '', 'Nomer Fax yang dapat terhubung.'),
(29, 6, 'form-email', '', 'text', 'is_email|', 'Alamat E-mail yang dapat dihubungi.'),
(30, 6, 'form-rekening_bank', '', 'text', 'not_empty|', 'Nama bank dan Nomer rekening yang dituju.'),
(31, 7, 'title_key', 'Nama', '', '', ''),
(32, 7, 'form-alamat', '', 'textarea', '', ''),
(33, 7, 'form-kota', '', 'text', '', ''),
(34, 7, 'form-kode_pos', '', 'text', '', ''),
(35, 7, 'form-telepon', '', 'text', '', 'Nomer telepon yang dapat dihubungi.'),
(36, 7, 'form-fax', '', 'text', '', 'Nomer Fax yang dapat terhubung.'),
(37, 7, 'form-nama_pegawai', '', 'text', 'not_empty|', 'Pegawai yang bertanggung jawab atas kepengurusan gudang tersebut.'),
(38, 7, 'form-handphone', '', 'text', 'not_empty|', 'Nomer handphone yang dapat dihubungi.'),
(39, 8, 'title_key', 'Nama', '', '', ''),
(40, 9, 'title_key', 'Nama', '', '', ''),
(41, 9, 'form-jenis_barang', '', 'browse', 'not_empty|', ''),
(42, 9, 'form-stock', '', 'text', '', 'Jumlah stock barang berjalan.'),
(43, 9, 'form-satuan', '', 'text', 'not_empty|', 'Nama satuan barang yang sesuai.<br>Misalnya : unit, kg, liter, sak, dll'),
(44, 9, 'form-supplier', '', 'multibrowse', 'not_empty|', 'Para Supplier yang menyediakan barang tersebut.'),
(45, 9, 'form-harga_beli', '', 'text', 'is_numeric|not_empty|', 'Standard patokan harga beli produk.<br><span style=''color:red''>NB : KETIK nominal harganya saja, tanpa Rp / tanda baca lainnya.</span>'),
(46, 9, 'form-harga_jual', '', 'text', 'is_numeric|not_empty|', 'Standard patokan harga jual produk.<br><span style=''color:red''>NB : KETIK nominal harganya saja, tanpa Rp / tanda baca lainnya.</span>'),
(47, 10, 'title_key', 'Barang Dagang', '', '', ''),
(48, 10, 'form-stock', '', 'text', 'not_empty|is_numeric|', 'Banyaknya barang yg tersimpan.'),
(49, 13, 'title_key', 'Kode Invoice', '', '', ''),
(50, 13, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(51, 13, 'form-supplier', '', 'browse', 'not_empty|', ''),
(52, 13, 'form-status_bayar', 'Tunggak\r\nLunas', 'radio', 'not_empty|', 'Status pembayaran lunas atau belum.'),
(53, 13, 'form-status_kirim', 'Diproses\r\nTerkirim', 'radio', 'not_empty|', 'Status pengiriman barang selesai atau belum.'),
(54, 13, 'form-nama_pegawai', '', 'text', '', 'Pegawai toko yang mengeluarkan invoice.'),
(55, 13, 'form-total_harga', '', 'text', 'is_numeric|', 'Harga total pembelian barang.'),
(56, 14, 'title_key', 'Barang Dagang', '', '', ''),
(57, 14, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', ''),
(58, 14, 'form-harga', '', 'text', 'not_empty|is_numeric|', ''),
(59, 14, 'form-terkirim', '', 'text', 'is_numeric|', 'Jumlah barang yg sudah terkirim.'),
(60, 14, 'form-retur', '', 'text', 'is_numeric|', 'Jumlah barang yg diretur.'),
(61, 15, 'title_key', 'Barang Dagang', '', '', ''),
(62, 15, 'form-tanggal', '', 'datepicker', 'not_empty|', 'Tanggal pengiriman barang.'),
(63, 15, 'form-jumlah_datang', '', 'text', 'not_empty|is_numeric|', 'jumlah barang dikirim.'),
(64, 15, 'form-sisa', '', 'text', 'is_numeric|', 'Jumlah sisa order yang belum datang.'),
(65, 15, 'form-gudang', '', 'browse', 'not_empty|', 'Gudang tempat pengiriman.'),
(66, 16, 'title_key', 'Kode Referensi', '', '', ''),
(67, 16, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(68, 16, 'form-mutasi_debet', '', 'text', 'is_numeric|', 'Masukan nominal untuk <strong>pembayaran</strong> hutang.'),
(69, 16, 'form-mutasi_kredit', '', 'text', 'is_numeric|', 'Masukan nominal untuk <strong>menambah beban</strong> hutang.'),
(72, 18, 'title_key', 'Kode Invoice', '', '', ''),
(73, 18, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(74, 18, 'form-customer', '', 'browse', 'not_empty|', ''),
(75, 18, 'form-status_bayar', 'Tunggak\r\nLunas', 'radio', 'not_empty|', 'Status pembayaran lunas atau belum.'),
(76, 18, 'form-status_kirim', 'Diproses\r\nTerkirim', 'radio', 'not_empty|', 'Status pengiriman barang selesai atau belum.'),
(77, 18, 'form-nama_pegawai', '', 'text', '', 'Pegawai toko yang mengeluarkan invoice.'),
(78, 18, 'form-diskon_nota', '', 'text', 'is_numeric|', 'Diskon langsung dlm satuan rupiah.'),
(79, 18, 'form-total_harga', '', 'text', 'is_numeric|', 'Harga total penjualan barang.'),
(80, 18, 'form-uang_muka', '', 'text', 'is_numeric|', 'Uang Muka yg dibayarkan.'),
(81, 18, 'form-ongkos_tambahan', '', 'text', 'is_numeric|', 'Ongkos Lain-lain (biaya bensin, jasa supir, portal, dll)'),
(82, 18, 'form-laba_bersih', '', 'text', 'is_numeric|', 'Keuntungan bersih yang didapat.'),
(83, 19, 'title_key', 'Barang Dagang', '', '', ''),
(84, 19, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', ''),
(85, 19, 'form-harga', '', 'text', 'not_empty|is_numeric|', ''),
(86, 19, 'form-diskon', '', 'text', 'is_numeric|', 'Diskon langsung dlm satuan rupiah.'),
(87, 19, 'form-profit', '', 'text', 'is_numeric|', ''),
(88, 19, 'form-terkirim', '', 'text', 'is_numeric|', 'Jumlah barang yg sudah terkirim.'),
(89, 19, 'form-retur', '', 'text', 'is_numeric|', 'Jumlah barang yg diretur.'),
(90, 20, 'title_key', 'Kode Referensi', '', '', ''),
(91, 20, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(92, 20, 'form-mutasi_debet', '', 'text', 'is_numeric|', 'Masukan nominal untuk <strong>menambah beban</strong> piutang.'),
(93, 20, 'form-mutasi_kredit', '', 'text', 'is_numeric|', 'Masukan nominal untuk <strong>pembayaran</strong> piutang.'),
(95, 21, 'title_key', 'Barang Dagang', '', '', ''),
(96, 21, 'form-tanggal', '', 'datepicker', 'not_empty|', 'Tanggal pengiriman barang.'),
(97, 21, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', 'jumlah barang yg diretur.'),
(98, 21, 'form-gudang', '', 'browse', 'not_empty|', 'Gudang tempat pengiriman.'),
(99, 22, 'title_key', 'Kode', '', '', ''),
(106, 22, 'form-purchase_order', '', 'browse', '', 'Surat Jalan untuk meretur barang supplier sesuai PO yg dipilih.'),
(105, 22, 'form-customer', '', 'browse', '', 'Tujuan Surat Jalan.'),
(103, 22, 'form-tanggal', '', 'datepicker', 'not_empty|', 'Tanggal kirim / jalan.'),
(104, 22, 'form-sales_order', '', 'browse', '', 'Surat Jalan untuk mengirim pesanan barang customer sesuai SO yg dipilih.'),
(107, 22, 'form-supplier', '', 'browse', '', 'Tujuan Surat Jalan.'),
(108, 22, 'form-ekspedisi', '', 'browse', '', 'Kosongkan bila mengantar sendiri tanpa jasa ekspedisi.'),
(109, 23, 'title_key', 'Barang Dagang', '', '', ''),
(110, 23, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', ''),
(111, 23, 'form-gudang', '', 'browse', '', 'Tempat pengambilan barang.'),
(112, 24, 'title_key', 'Nomer Resi', '', '', ''),
(114, 24, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(113, 24, 'form-surat_jalan', '', 'browse', 'not_empty|', ''),
(115, 24, 'form-pihak_bayar', 'Tujuan\r\nPengirim', 'radio', 'not_empty|', 'Pihak yang membayar pengiriman.'),
(116, 24, 'form-sistem_bayar', 'Gratis\r\nTagih', 'radio', 'not_empty|', ''),
(117, 24, 'form-harga', '', 'text', 'is_numeric|', 'Harga biaya yg dikeluarkan.');

-- --------------------------------------------------------

--
-- Table structure for table `cms_users`
--

CREATE TABLE IF NOT EXISTS `cms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(500) NOT NULL,
  `lastname` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cms_users`
--

INSERT INTO `cms_users` (`id`, `firstname`, `lastname`, `created`, `created_by`, `modified`, `modified_by`, `status`) VALUES
(1, 'admin', 'zpanel', '2013-01-04 00:00:00', 1, '2014-02-06 10:50:29', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_metas`
--

CREATE TABLE IF NOT EXISTS `cms_user_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(500) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `cms_user_metas`
--

INSERT INTO `cms_user_metas` (`id`, `user_id`, `key`, `value`) VALUES
(1, 1, 'gender', 'male'),
(2, 1, 'address', 'Jl. Dharmahusada Indah 43'),
(3, 1, 'zip_code', '60258'),
(4, 1, 'city', 'Surabaya, Indonesia'),
(5, 1, 'mobile_phone', '089 67367 1110'),
(6, 1, 'dob_day', '28'),
(7, 1, 'dob_month', '10'),
(8, 1, 'dob_year', '1988'),
(9, 1, 'job', 'Web Developer'),
(10, 1, 'company', 'PT. Creazi'),
(11, 1, 'company_address', 'Jl. Nginden Semolo 101');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
