-- ============================================================
-- MENARA Database — Migration 005
-- BAZNAS Microfinance Masjid (BMM) — Phase 1 lanjutan:
--   1. Operasional Kas Penerimaan & Pengeluaran (kas operasional
--      terpisah dari dana bergulir)
--   2. Riwayat Pemutihan / Penghapusbukuan
--
-- Sumber: backend/scriptmicrofinance/normalisasi_db_menara_backup2.sql
-- (dump legacy BMM production). Nama tabel & kolom DIPERTAHANKAN
-- PERSIS seperti skema asli (keputusan yang sama dengan migration 004).
--
-- Jalankan SEKALI pada database yang sudah ada.
-- ============================================================

-- ---------- Master: label info dana kas operasional ----------

CREATE TABLE `bmm_label_info_dana_kas_operasional` (
  `id_bmm_label_info_dana_kas_operasional` int NOT NULL AUTO_INCREMENT,
  `nama_bmm_label_info_dana_kas_operasional` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Masjid',
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bmm_label_info_dana_kas_operasional`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `bmm_label_info_dana_kas_operasional` VALUES
(21,'Dana Mandiri Masjid','Masjid','2022-08-10 10:43:29',NULL),
(22,'Dana dari Infak Peserta','Masjid','2024-02-21 09:59:13',NULL),
(23,'Dana dari Baznas Pusat','Masjid','2024-02-21 09:59:15',NULL),
(24,'Dana dari Baznas Kab / Kota','Masjid','2024-02-21 09:59:16',NULL);

-- ---------- Master: label penggunaan kas operasional ----------

CREATE TABLE `bmm_label_penggunaan_kas_operasional` (
  `id_bmm_label_penggunaan_kas_operasional` int NOT NULL AUTO_INCREMENT,
  `nama_bmm_label_penggunaan_kas_operasional` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Masjid',
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bmm_label_penggunaan_kas_operasional`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `bmm_label_penggunaan_kas_operasional` VALUES
(21,'Operasional','Masjid','2022-08-10 10:43:29',NULL),
(22,'Sosialisasi','Masjid','2024-02-21 09:59:13',NULL),
(23,'Aset','Masjid','2024-02-21 09:59:15',NULL),
(24,'Honor','Masjid','2024-02-21 09:59:16',NULL);

-- ---------- Transaksional: operasional kas penerimaan ----------

CREATE TABLE `bmfi_bmm_operasional_kas_penerimaan` (
  `id_bmfi_bmm_operasional_kas_penerimaan` int NOT NULL AUTO_INCREMENT,
  `jalur_akses` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `nominal_masuk` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_donatur` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `lampiran` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bmm_label_info_dana_kas_operasional` int DEFAULT '21',
  `id_level` int DEFAULT NULL,
  `id_jalur_akses` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_provinsi` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kabupaten_kota` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kecamatan` char(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kelurahan_desa` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `kode_org_baznas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug_bmfi_bmm_operasional_kas_penerimaan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_transaksi_bmfi_bmm_operasional_kas_penerimaan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_bmm_transaksi_microfinance_pengembalian_dan_infak_peserta` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_status_validasi_bukukas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `terbilang_nominal` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_bmm_operasional_kas_penerimaan`) USING BTREE,
  KEY `Tanggal` (`tanggal`) USING BTREE,
  KEY `Jalur_Akses` (`jalur_akses`) USING BTREE,
  KEY `id_label_info_dana` (`id_bmm_label_info_dana_kas_operasional`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ---------- Transaksional: operasional kas pengeluaran ----------

CREATE TABLE `bmfi_bmm_operasional_kas_pengeluaran` (
  `id_bmfi_bmm_operasional_kas_pengeluaran` int NOT NULL AUTO_INCREMENT,
  `jalur_akses` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `nominal_keluar` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `jumlah_penerima_manfaat` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `lampiran` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bmm_label_penggunaan_kas_operasional` int DEFAULT '21',
  `id_level` int DEFAULT NULL,
  `id_jalur_akses` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_provinsi` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kabupaten_kota` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kecamatan` char(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kelurahan_desa` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `kode_org_baznas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug_bmfi_bmm_operasional_kas_pengeluaran` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_transaksi_bmfi_bmm_operasional_kas_pengeluaran` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_bmm_transaksi_microfinance` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_status_validasi_bukukas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `terbilang_nominal` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_bmm_operasional_kas_pengeluaran`) USING BTREE,
  KEY `Tanggal` (`tanggal`) USING BTREE,
  KEY `Jalur_Akses` (`jalur_akses`) USING BTREE,
  KEY `id_label_penggunaan` (`id_bmm_label_penggunaan_kas_operasional`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- ---------- Master: kategori pemutihan / penghapusbukuan ----------

CREATE TABLE `bmfi_kategori_pemutihan_penghapusbukuan` (
  `id_bmfi_kategori_pemutihan_penghapusbukuan` int NOT NULL AUTO_INCREMENT,
  `nama_bmfi_kategori_pemutihan_penghapusbukuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_kategori_pemutihan_penghapusbukuan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `bmfi_kategori_pemutihan_penghapusbukuan` VALUES
(1,'Meninggal','bmfi_kategori_pemutihan_penghapusbukuan',NULL,NULL),
(2,'Diputihkan','bmfi_kategori_pemutihan_penghapusbukuan',NULL,NULL);

-- ---------- Transaksional: riwayat pemutihan / penghapusbukuan ----------

CREATE TABLE `bmfi_pemutihan_penghapusbukuan` (
  `id_bmfi_pemutihan_penghapusbukuan` int NOT NULL AUTO_INCREMENT,
  `tgl_bmfi_pemutihan_penghapusbukuan` date DEFAULT NULL,
  `judul` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_nominal_pemutihan_penghapusbukuan` int DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `gambar_foto` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug_bmfi_pemutihan_penghapusbukuan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_bmfi_pemutihan_penghapusbukuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_bmfi_kategori_pemutihan_penghapusbukuan` int DEFAULT NULL,
  `peserta_program_bmfi_pemutihan_penghapusbukuan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pendamping_bmfi_pemutihan_penghapusbukuan` varchar(191) DEFAULT NULL,
  `no_bmm_transaksi_microfinance` varchar(191) DEFAULT NULL,
  `id_user` int NOT NULL,
  `id_masjid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `video_bmfi_pemutihan_penghapusbukuan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `id_provinsi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kabupaten_kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kecamatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kelurahan_desa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_org_baznas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_pemutihan_penghapusbukuan`) USING BTREE,
  KEY `id_masjid` (`id_masjid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;