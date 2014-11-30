-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 30, 2014 at 10:30 PM
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_accounts`
--

INSERT INTO `cms_accounts` (`id`, `user_id`, `role_id`, `username`, `email`, `password`, `last_login`, `created`, `created_by`, `modified`, `modified_by`) VALUES
(1, 1, 1, 'admin', 'admin@yahoo.com', '169e781bd52860b584879cbe117085da596238f3', '2014-11-30 21:27:30', '2013-01-04 00:00:00', 1, '2013-01-04 00:00:00', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `cms_entries`
--

INSERT INTO `cms_entries` (`id`, `entry_type`, `title`, `slug`, `description`, `main_image`, `parent_id`, `status`, `count`, `created`, `created_by`, `modified`, `modified_by`, `sort_order`, `lang_code`) VALUES
(1, 'customer', 'Andy Basuki', 'andy-basuki', 'just a test', 0, 0, 1, 0, '2014-11-23 10:59:31', 1, '2014-11-23 10:59:32', 1, 1, 'en-1'),
(2, 'supplier', 'He Xin Min', 'he-xin-min', 'fake supplier', 0, 0, 1, 0, '2014-11-23 11:27:33', 1, '2014-11-23 11:27:33', 1, 2, 'en-2'),
(3, 'ekspedisi', 'Pak Gunawan Santoso', 'pak-gunawan-santoso', 'Ekspedisi Sby - Kudus OKE', 0, 0, 1, 0, '2014-11-23 14:37:16', 1, '2014-11-23 14:37:16', 1, 3, 'en-3'),
(4, 'gudang', 'Pengampon', 'pengampon', 'Gudang satu-satunya.', 0, 0, 1, 15, '2014-11-25 11:05:06', 1, '2014-11-26 17:36:24', 1, 4, 'en-4'),
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
(33, 'gudang', ' Romokalisari', 'romokalisari', 'Gudang coba coba.\r\ntest oke', 0, 0, 1, 2, '2014-11-27 09:38:12', 1, '2014-11-27 09:38:39', 1, 33, 'en-33'),
(34, 'barang-gudang', 'mesin-hardcover-maker', 'mesin-hardcover-maker-2', '', 0, 33, 1, 0, '2014-11-27 09:38:38', 1, '2014-11-27 09:38:38', 1, 34, 'en-34'),
(35, 'pindah-masuk', 'automatic_mesin-hardcover-maker', 'automatic-mesin-hardcover-maker-9', 'Penambahan stok barang oleh administrator.', 12, 33, 1, 0, '2014-11-27 09:38:38', 1, '2014-11-27 09:38:39', 1, 35, 'en-35'),
(36, 'purchase-order', 'TEST001', 'test001', 'test aja gan.\r\nThankyou.\r\nGBU', 0, 0, 1, 0, '2014-11-28 17:20:14', 1, '2014-11-28 17:20:14', 1, 36, 'en-36');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=88 ;

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
(49, 4, 'count-barang-gudang', '1'),
(72, 16, 'form-stock', '28'),
(52, 4, 'count-pindah-masuk', '7'),
(53, 8, 'form-stock', '40'),
(55, 4, 'count-pindah-keluar', '7'),
(57, 18, 'form-jenis_barang', 'bahan-baku'),
(58, 18, 'form-satuan', 'Galon'),
(59, 18, 'form-supplier', 'he-xin-min'),
(60, 18, 'form-harga_beli', '345000'),
(61, 18, 'form-harga_jual', '600000'),
(73, 33, 'form-alamat', 'Jl. Romokalisari No 12'),
(63, 18, 'form-stock', '0'),
(74, 33, 'form-kota', 'Raden'),
(75, 33, 'form-kode_pos', '60969'),
(76, 33, 'form-telepon', '031 372 9191'),
(77, 33, 'form-nama_pegawai', 'Pak Sutejo'),
(78, 33, 'form-handphone', '081 737 1234'),
(79, 33, 'count-barang-gudang', '1'),
(80, 34, 'form-stock', '12'),
(81, 33, 'count-pindah-masuk', '1'),
(82, 36, 'form-tanggal', '11/28/2014'),
(83, 36, 'form-supplier', 'he-xin-min'),
(84, 36, 'form-status_bayar', 'Tunggak'),
(85, 36, 'form-status_kirim', 'Diproses'),
(86, 36, 'form-nama_pegawai', 'Bejo'),
(87, 36, 'form-total_harga', '150000');

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
(2, 'Admin', 'Administrator from the clients.', NULL),
(3, 'Regular User', 'Anyone with no access to admin panel.', NULL);

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
(19, 'custom-pagination', '10'),
(20, 'custom-email_contact', 'andybasuki88@gmail.com');

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
(13, 'Purchase Order', 'purchase-order', 'Surat pemesanan barang terhadap supplier.', 0, 4, '2014-11-28 15:31:36', 1, '2014-11-28 17:12:52', 1),
(14, 'Purchase Detail', 'purchase-detail', 'Seluruh barang yg terdaftar di faktur pembelian.', 13, 0, '2014-11-28 15:49:50', 1, '2014-11-28 15:49:50', 1),
(15, 'Barang Masuk', 'barang-masuk', 'Seluruh pencatatan barang yang masuk dari supplier berdasarkan faktur pembelian.', 13, 0, '2014-11-28 16:06:25', 1, '2014-11-28 16:06:25', 1),
(16, 'Hutang', 'hutang', 'Catatan beban hutang terhadap supplier (Rekening Koran)', 13, 0, '2014-11-28 16:21:10', 1, '2014-11-28 16:21:10', 1),
(17, 'Retur Beli', 'retur-beli', 'Seluruh catatan barang pembelian yang diretur ke supplier.', 13, 0, '2014-11-28 17:12:52', 1, '2014-11-28 17:12:52', 1),
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
(21, 6, 'title_key', 'Nama Pegawai', NULL, NULL, NULL),
(2, 4, 'title_key', 'Nama Lengkap', NULL, NULL, NULL),
(3, 4, 'form-perusahaan', '', 'text', 'not_empty|', 'Nama Perusahaan tempat bekerja.'),
(4, 4, 'form-alamat', '', 'textarea', '', 'Alamat Perusahaan yang terkait.'),
(5, 4, 'form-kota', '', 'text', '', ''),
(6, 4, 'form-kode_pos', '', 'text', '', ''),
(7, 4, 'form-telepon', '', 'text', '', 'Nomer telepon yang dapat dihubungi.'),
(8, 4, 'form-handphone', '', 'text', 'not_empty|', 'Nomer handphone yang dapat dihubungi.'),
(9, 4, 'form-fax', '', 'text', '', 'Nomer Fax yang dapat terhubung.'),
(10, 4, 'form-email', '', 'text', 'is_email|', 'Alamat E-mail yang dapat dihubungi.'),
(11, 5, 'title_key', 'Nama Lengkap', NULL, NULL, NULL),
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
(31, 7, 'title_key', 'Nama', NULL, NULL, NULL),
(32, 7, 'form-alamat', '', 'textarea', '', ''),
(33, 7, 'form-kota', '', 'text', '', ''),
(34, 7, 'form-kode_pos', '', 'text', '', ''),
(35, 7, 'form-telepon', '', 'text', '', 'Nomer telepon yang dapat dihubungi.'),
(36, 7, 'form-fax', '', 'text', '', 'Nomer Fax yang dapat terhubung.'),
(37, 7, 'form-nama_pegawai', '', 'text', 'not_empty|', 'Pegawai yang bertanggung jawab atas kepengurusan gudang tersebut.'),
(38, 7, 'form-handphone', '', 'text', 'not_empty|', 'Nomer handphone yang dapat dihubungi.'),
(39, 8, 'title_key', 'Nama', NULL, NULL, NULL),
(40, 9, 'title_key', 'Nama', NULL, NULL, NULL),
(41, 9, 'form-jenis_barang', '', 'browse', 'not_empty|', ''),
(42, 9, 'form-stock', '', 'text', '', 'Jumlah stock barang berjalan.'),
(43, 9, 'form-satuan', '', 'text', 'not_empty|', 'Nama satuan barang yang sesuai.<br>Misalnya : unit, kg, liter, sak, dll'),
(44, 9, 'form-supplier', '', 'multibrowse', '', 'Para Supplier yang menyediakan barang tersebut.'),
(45, 9, 'form-harga_beli', '', 'text', 'is_numeric|', 'Standard patokan harga beli produk.<br><span style=''color:red''>NB : KETIK nominal harganya saja, tanpa Rp / tanda baca lainnya.</span>'),
(46, 9, 'form-harga_jual', '', 'text', 'is_numeric|', 'Standard patokan harga jual produk.<br><span style=''color:red''>NB : KETIK nominal harganya saja, tanpa Rp / tanda baca lainnya.</span>'),
(47, 10, 'title_key', 'Barang Dagang', NULL, NULL, NULL),
(48, 10, 'form-stock', '', 'text', 'not_empty|is_numeric|', 'Banyaknya barang yg tersimpan.'),
(49, 13, 'title_key', 'Kode Invoice', NULL, NULL, NULL),
(50, 13, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(51, 13, 'form-supplier', '', 'browse', 'not_empty|', ''),
(52, 13, 'form-status_bayar', 'Tunggak\r\nLunas', 'radio', 'not_empty|', 'Status pembayaran lunas atau belum.'),
(53, 13, 'form-status_kirim', 'Diproses\r\nTerkirim', 'radio', 'not_empty|', 'Status pengiriman barang selesai atau belum.'),
(54, 13, 'form-nama_pegawai', '', 'text', '', 'Pegawai toko yang mengeluarkan invoice.'),
(55, 13, 'form-total_harga', '', 'text', 'not_empty|is_numeric|', 'Harga total pembelian barang.'),
(56, 14, 'title_key', 'Barang Dagang', NULL, NULL, NULL),
(57, 14, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', ''),
(58, 14, 'form-harga', '', 'text', 'not_empty|is_numeric|', ''),
(59, 14, 'form-terkirim', '', 'text', 'is_numeric|', 'Jumlah barang yg sudah terkirim.'),
(60, 14, 'form-retur', '', 'text', 'is_numeric|', 'Jumlah barang yg diretur.'),
(61, 15, 'title_key', 'Barang Dagang', NULL, NULL, NULL),
(62, 15, 'form-tanggal', '', 'datepicker', 'not_empty|', 'Tanggal pengiriman barang.'),
(63, 15, 'form-jumlah_datang', '', 'text', 'not_empty|is_numeric|', 'jumlah barang dikirim.'),
(64, 15, 'form-sisa', '', 'text', 'is_numeric|', 'Jumlah sisa order yang belum datang.'),
(65, 15, 'form-gudang', '', 'browse', 'not_empty|', 'Gudang tempat pengiriman.'),
(66, 16, 'title_key', 'Kode Referensi', NULL, NULL, NULL),
(67, 16, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(68, 16, 'form-mutasi_debet', '', 'text', 'is_numeric|', ''),
(69, 16, 'form-mutasi_kredit', '', 'text', 'is_numeric|', ''),
(70, 16, 'form-saldo', '', 'text', 'is_numeric|', 'Saldo akhir perhitungan.'),
(71, 17, 'title_key', 'Surat Jalan', NULL, NULL, NULL),
(72, 18, 'title_key', 'Kode Invoice', NULL, NULL, NULL),
(73, 18, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(74, 18, 'form-customer', '', 'browse', 'not_empty|', ''),
(75, 18, 'form-status_bayar', 'Tunggak\r\nLunas', 'radio', 'not_empty|', 'Status pembayaran lunas atau belum.'),
(76, 18, 'form-status_kirim', 'Diproses\r\nTerkirim', 'radio', 'not_empty|', 'Status pengiriman barang selesai atau belum.'),
(77, 18, 'form-nama_pegawai', '', 'text', '', 'Pegawai toko yang mengeluarkan invoice.'),
(78, 18, 'form-diskon_nota', '', 'text', 'is_numeric|', 'Diskon langsung dlm satuan rupiah.'),
(79, 18, 'form-total_harga', '', 'text', 'not_empty|is_numeric|', 'Harga total penjualan barang.'),
(80, 18, 'form-uang_muka', '', 'text', 'is_numeric|', 'Uang Muka yg dibayarkan.'),
(81, 18, 'form-ongkos_tambahan', '', 'text', 'is_numeric|', 'Ongkos Lain-lain (biaya bensin, jasa supir, portal, dll)'),
(82, 18, 'form-laba_bersih', '', 'text', 'is_numeric|', 'Keuntungan bersih yang didapat.'),
(83, 19, 'title_key', 'Barang Dagang', NULL, NULL, NULL),
(84, 19, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', ''),
(85, 19, 'form-harga', '', 'text', 'not_empty|is_numeric|', ''),
(86, 19, 'form-diskon', '', 'text', 'is_numeric|', 'Diskon langsung dlm satuan rupiah.'),
(87, 19, 'form-profit', '', 'text', 'is_numeric|', ''),
(88, 19, 'form-terkirim', '', 'text', 'is_numeric|', 'Jumlah barang yg sudah terkirim.'),
(89, 19, 'form-retur', '', 'text', 'is_numeric|', 'Jumlah barang yg diretur.'),
(90, 20, 'title_key', 'Kode Referensi', NULL, NULL, NULL),
(91, 20, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(92, 20, 'form-mutasi_debet', '', 'text', 'is_numeric|', ''),
(93, 20, 'form-mutasi_kredit', '', 'text', 'is_numeric|', ''),
(94, 20, 'form-saldo', '', 'text', 'is_numeric|', 'Saldo akhir perhitungan.'),
(95, 21, 'title_key', 'Barang Dagang', NULL, NULL, NULL),
(96, 21, 'form-tanggal', '', 'datepicker', 'not_empty|', 'Tanggal pengiriman barang.'),
(97, 21, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', 'jumlah barang yg diretur.'),
(98, 21, 'form-gudang', '', 'browse', 'not_empty|', 'Gudang tempat pengiriman.'),
(99, 22, 'title_key', 'Kode', NULL, NULL, NULL),
(106, 22, 'form-purchase_order', '', 'browse', '', 'Surat Jalan untuk meretur barang supplier sesuai PO yg dipilih.'),
(105, 22, 'form-customer', '', 'browse', '', 'Tujuan Surat Jalan.'),
(103, 22, 'form-tanggal', '', 'datepicker', 'not_empty|', 'Tanggal kirim / jalan.'),
(104, 22, 'form-sales_order', '', 'browse', '', 'Surat Jalan untuk mengirim pesanan barang customer sesuai SO yg dipilih.'),
(107, 22, 'form-supplier', '', 'browse', '', 'Tujuan Surat Jalan.'),
(108, 22, 'form-ekspedisi', '', 'browse', '', 'Kosongkan bila mengantar sendiri tanpa jasa ekspedisi.'),
(109, 23, 'title_key', 'Barang Dagang', NULL, NULL, NULL),
(110, 23, 'form-jumlah', '', 'text', 'not_empty|is_numeric|', ''),
(111, 23, 'form-gudang', '', 'browse', '', 'Tempat pengambilan barang.'),
(112, 24, 'title_key', 'Nomer Resi', NULL, NULL, NULL),
(113, 24, 'form-tanggal', '', 'datepicker', 'not_empty|', ''),
(114, 24, 'form-surat_jalan', '', 'browse', 'not_empty|', ''),
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
