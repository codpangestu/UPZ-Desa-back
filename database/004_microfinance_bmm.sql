-- ============================================================
-- MENARA Database — Migration 004
-- BAZNAS Microfinance Masjid (BMM) — Phase 1 (9 Tahap resmi panduan)
--
-- Sumber: backend/scriptmicrofinance/normalisasi_db_menara_backup2.sql
-- (dump legacy BMM production, diberikan langsung oleh tim BAZNAS).
-- Nama tabel & kolom DIPERTAHANKAN PERSIS seperti skema asli (sesuai
-- keputusan: prioritaskan fidelity ke data/skema asli dibanding
-- menyederhanakan penamaan ala tabel `bukukas_penerimaan`, dst).
--
-- Data contoh (INSERT) yang disertakan HANYA milik satu masjid nyata
-- yang sudah diverifikasi lewat uji browser langsung di
-- https://menara.baznas.go.id/ : "Masjid Jami Al-Hidayah Kubang Sepat"
-- (id_masjid / parent_id = 'M367230004042023872') + tabel master/lookup
-- kecil (jenis angsuran, skema pinjaman, kategori pengesahan) diambil utuh.
-- Tabel/data selain bmfi_*/bmm_* di dump asli TIDAK disentuh sama sekali.
--
-- Tabel yang SENGAJA belum dibuat di migration ini (Phase 2 / dukungan
-- opsional, kolom terkait di bmfi_peserta_program tetap ada tapi bebas
-- diisi manual tanpa join, sama seperti skema aslinya yang juga tidak
-- punya FOREIGN KEY constraint eksplisit):
--   bmfi_sektor_usaha_peserta, bmfi_status_hubungan_penjamin,
--   bmfi_kategori_kualitas_angsuran_peserta,
--   bmfi_rating_dampak_kemajuan_peserta,
--   bmfi_jenis_program_pendampingan_peserta
--
-- Jalankan SEKALI pada database yang sudah ada.
-- ============================================================

-- ---------- Master / lookup ----------

CREATE TABLE `bmfi_kategori_pengesahan_microfinance` (
  `id_bmfi_kategori_pengesahan_microfinance` int NOT NULL AUTO_INCREMENT,
  `nama_bmfi_kategori_pengesahan_microfinance` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_kategori_pengesahan_microfinance`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `bmfi_kategori_pengesahan_microfinance` VALUES
(1,'Baznas Pusat','bmfi_kategori_pengesahan_microfinance','2024-02-12 08:15:39','2024-02-12 08:15:43'),
(2,'Baznas Provinsi','bmfi_kategori_pengesahan_microfinance','2024-02-12 08:15:40','2024-02-12 08:15:44'),
(3,'Baznas Daerah','bmfi_kategori_pengesahan_microfinance','2024-02-12 08:15:41','2024-02-12 08:15:46'),
(4,'Mandiri','bmfi_kategori_pengesahan_microfinance','2024-02-12 08:15:42','2024-02-12 08:15:47');

CREATE TABLE `bmfi_jenis_angsuran` (
  `id_bmfi_jenis_angsuran` int NOT NULL AUTO_INCREMENT,
  `nama_bmfi_jenis_angsuran` varchar(25) DEFAULT NULL,
  `parent_id` varchar(25) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_jenis_angsuran`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bmfi_jenis_angsuran` VALUES
(11,'Pekanan','Masjid','2022-08-10 03:38:44','2022-08-10 10:43:29'),
(12,'Dua Pekanan','Masjid','2022-08-10 04:16:28',NULL),
(13,'Bulanan','Masjid','2022-08-10 04:47:19',NULL);

-- Catatan: `bmfi_skema_pinjaman` adalah tabel master kecil terpisah dari
-- `bmfi_pilihan_skema_pinjaman` (di bawah) pada skema legacy — isinya
-- kebetulan sama (duplikasi historis), tetap dipertahankan apa adanya.
CREATE TABLE `bmfi_skema_pinjaman` (
  `id_bmfi_skema_pinjaman` int NOT NULL AUTO_INCREMENT,
  `nama_bmfi_skema_pinjaman` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_skema_pinjaman`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bmfi_skema_pinjaman` VALUES
(11,'Pekanan','Masjid','2022-08-10 03:38:44','2022-08-10 10:43:29'),
(12,'Dua Pekanan','Masjid','2022-08-10 04:16:28',NULL),
(13,'Bulanan','Masjid','2022-08-10 04:47:19',NULL);

-- ---------- Transaksional per-masjid ----------

CREATE TABLE `bmfi_sejarah_dan_profil_microfinance` (
  `id_bmfi_sejarah_dan_profil_microfinance` int NOT NULL AUTO_INCREMENT,
  `tgl_bmfi_sejarah_dan_profil_microfinance` date DEFAULT NULL,
  `judul` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_pengesahan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nomor_kerangka_acuan_kerja` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_peserta_awal_pengesahan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nominal_saldo_awal_pengesahan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `poster` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug_bmfi_sejarah_dan_profil_microfinance` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT NULL,
  `id_bmfi_kategori_pengesahan_microfinance` int DEFAULT NULL,
  `id_user` int NOT NULL,
  `id_masjid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `video_bmfi_sejarah_dan_profil_microfinance` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_sejarah_dan_profil_microfinance`) USING BTREE,
  KEY `id_masjid` (`id_masjid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `bmfi_sejarah_dan_profil_microfinance` VALUES
(14,'2024-02-12','Microfinance Masjid Al-Hidayah Kubang Sepat Kota Cilegon','2023','B/DPBZ-DPDG/020/KAK/II/2023','59','150000000','<p>Microfinance Masjid Al-Hidayah Kubang Sepat Kota Cilegon</p>\r\n','M367230004042023872-masjid_jami_al-hidayah_kubang_sepat-bmfi_sejarah_dan_profil_microfinance-microfinance-masjid-al-hidayah-kubang-sepat-kota-cilegon-20240212132145.png','microfinance-masjid-al-hidayah-kubang-sepat-kota-cilegon-20240212131545-M367230004042023872-Masjid_Jami_Al_Hidayah_Kubang_Sepat',1,1,2768,'M367230004042023872','','2024-04-05 10:03:43',NULL);

CREATE TABLE `bmfi_saldo_microfinance` (
  `id_bmfi_saldo_microfinance` int NOT NULL AUTO_INCREMENT,
  `nama_bmfi_saldo_microfinance` varchar(191) DEFAULT NULL,
  `nominal_saldo_microfinance` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `saldo_awal_penerimaan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `pemberian_saldo_awal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `keterangan` text,
  `berkas_bmfi_saldo_microfinance` varchar(191) DEFAULT 'masjid-default.jpg',
  `parent_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_saldo_microfinance`),
  KEY `id_masjid` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bmfi_saldo_microfinance` VALUES
(10,'Saldo BAZNAS RI','58091000','150000000','150000000','<p>.Pengguliran dana telah dilakukan 100% kepada mustahik</p>\r\n',NULL,'M367230004042023872','2023-12-14 01:29:58','2024-06-15 19:00:57');

CREATE TABLE `bmfi_pilihan_skema_pinjaman` (
  `id_pilihan_skema_pinjaman` int NOT NULL AUTO_INCREMENT,
  `nama_pilihan_skema_pinjaman` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_nominal` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `keterangan` text,
  `berkas_pilihan_skema_pinjaman` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pilihan_skema_pinjaman`),
  KEY `id_masjid` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Catatan: baris id=19 (PINJAMAN VIP, Rp4.000.000) melebihi batas normal
-- Rp3.000.000 yang ditulis di PDF panduan — ini pengecualian nyata utk
-- mustahik yang sudah lunas & mengajukan ulang (lihat keterangan aslinya).
-- Validasi maks 3jt di aplikasi baru berlaku utk entri BARU, tidak
-- menghapus data historis ini.
INSERT INTO `bmfi_pilihan_skema_pinjaman` VALUES
(9,'PINJAMAN MINIMAL A','1000000','<p>Pinjaman Minimal Merupakan Nilai Pinjaman paling kecil yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 12 Bulan</p>\r\n','','M367230004042023872','2023-12-14 01:28:45','2023-12-14 20:21:06'),
(10,'PINJAMAN MINIMAL B','1000000','<p>Pinjaman Minimal Merupakan Nilai Pinjaman paling kecil yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 10&nbsp;Bulan</p>\r\n','','M367230004042023872','2023-12-14 10:25:29','2023-12-14 20:22:08'),
(11,'PINJAMAN REGULER A','1500000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai Sedang&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 12 Bulan</p>\r\n','','M367230004042023872','2023-12-14 10:25:46','2023-12-14 20:24:06'),
(12,'PINJAMAN REGULER B','1500000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai Sedang&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 10&nbsp;Bulan</p>\r\n','','M367230004042023872','2023-12-14 10:26:00','2023-12-14 20:28:52'),
(13,'PINJAMAN EKONOMIS A','2000000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai cukup&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 12 Bulan</p>\r\n','','M367230004042023872','2023-12-14 13:29:41',NULL),
(14,'PINJAMAN EKONOMIS B','2000000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai Cukup&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 10&nbsp;Bulan</p>\r\n','','M367230004042023872','2023-12-14 13:30:27',NULL),
(15,'PINJAMAN PRO A','2500000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai Mendekati Maksimal&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 12 Bulan</p>\r\n','','M367230004042023872','2023-12-14 13:31:32',NULL),
(16,'PINJAMAN PRO B','2500000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai Mendekati Maksimal&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 10&nbsp;Bulan</p>\r\n','','M367230004042023872','2023-12-14 13:32:02',NULL),
(17,'PINJAMAN MAKSIMAL A','3000000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai Maksimal&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 12 Bulan</p>\r\n','','M367230004042023872','2023-12-14 13:32:42',NULL),
(18,'PINJAMAN MAKSIMAL B','3000000','<p>Pinjaman Reguler&nbsp;Merupakan Nilai Pinjaman dengan nilai Maksimal&nbsp;yang direkomendasikan Baznas RI Bagi UMKM dengan Jangka waktu 10&nbsp;Bulan</p>\r\n','','M367230004042023872','2023-12-14 13:33:11',NULL),
(19,'PINJAMAN VIP','4000000','<p>Pinjamana VIP merupakan Pinjaman denga nominal Khusus yang diberikan bagi Mustahik yang lancar pembayaran dan telah melunasi pinjaman kemudian&nbsp;mengajukan Ulang</p>\r\n','','M367230004042023872','2023-12-14 13:35:09',NULL);

CREATE TABLE `bmfi_pendamping` (
  `id_pendamping_bmfi_asesmen` int NOT NULL AUTO_INCREMENT,
  `hash_blockchain_peserta_bmm` varchar(191) NOT NULL DEFAULT '0',
  `hash_blockchain_masjid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_pendamping_bmfi_asesmen` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_ktp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_tanggal_lahir` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` text,
  `foto_profil_pendamping_bmfi_asesmen` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `berkas_riwayat_hidup_pendamping` varchar(191) DEFAULT NULL,
  `parent_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pendamping_bmfi_asesmen`),
  KEY `id_masjid` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bmfi_pendamping` VALUES
(5,'0','0','AGUS FITRIANTO','Laki-laki','3 212 151 407 800 016',' 081 997 387 031','SINDANG AGUNG, 14 JULI 1980','Link Kubang Sepat No 14 RT 01 RW 07, KElurahan CItangkil, Kec. Citangkil, Kota Cilegon - Banten 42441','','ce964aceb68ff7589df4e904cec84fd0.PNG',NULL,'M367230004042023872','2023-12-04 09:29:30',NULL),
(6,'0','0','SUNARTO','Laki-laki','3 672 081 001 620 001','08 128 253 210','Pekalongan','Link Kubang Sepat No 14 RT 01 RW 07, KElurahan CItangkil, Kec. Citangkil, Kota Cilegon - Banten 42441','','b238432e23d6022aea6fb4523849afa5.PNG',NULL,'M367230004042023872','2023-12-13 09:46:45','2023-12-23 08:27:14'),
(7,'0','0','H. ADE MISRONI','Laki-laki','3 672 080 405 630 002','08 128 489 214','Serang','Link Kubang Sepat No 14 RT 01 RW 07, KElurahan CItangkil, Kec. Citangkil, Kota Cilegon - Banten 42441','','5b13c8b2e30f9d7fdadd420e7bd61952.PNG',NULL,'M367230004042023872','2023-12-13 09:47:25','2023-12-23 08:32:21');

CREATE TABLE `bmfi_peserta_program` (
  `id_peserta_program_bmfi_asesmen` int NOT NULL AUTO_INCREMENT,
  `kode_id_unik_peserta_bmm` varchar(50) NOT NULL DEFAULT '0',
  `hash_blockchain_peserta_bmm` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hash_blockchain_masjid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_peserta_program_bmfi_asesmen` varchar(191) DEFAULT NULL,
  `no_ktp` varchar(100) DEFAULT NULL,
  `no_hp` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `id_level` int DEFAULT NULL,
  `id_labels` int DEFAULT NULL,
  `status_aktif` int DEFAULT NULL,
  `jalur_akses` varchar(50) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `jenis_kelamin` varchar(100) DEFAULT NULL,
  `tempat_tanggal_lahir` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_rumah` varchar(191) DEFAULT NULL,
  `foto_rumah` varchar(191) DEFAULT NULL,
  `kordinat_latitude_rumah` varchar(191) DEFAULT NULL,
  `kordinat_longitude_rumah` varchar(191) DEFAULT NULL,
  `foto_lokasi_usaha` varchar(191) DEFAULT NULL,
  `lokasi_usaha` varchar(191) DEFAULT NULL,
  `nama_usaha` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_rekening` varchar(191) DEFAULT NULL,
  `atas_nama_rekening` varchar(191) DEFAULT NULL,
  `nama_bank` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug_peserta_program` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_kode_peserta_program` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah_anggota_keluarga` varchar(191) DEFAULT NULL,
  `nama_penjamin` varchar(191) DEFAULT NULL,
  `id_jenis_asnaf` varchar(191) DEFAULT '2',
  `id_status_hubungan_penjamin` varchar(191) DEFAULT NULL,
  `id_sektor_usaha_peserta` varchar(191) DEFAULT NULL,
  `id_kategori_kualitas_angsuran_peserta` varchar(191) DEFAULT NULL,
  `id_rating_dampak_kemajuan_peserta` varchar(191) DEFAULT NULL,
  `id_jenis_program_pendampingan_peserta` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hasil_dampak_kemajuan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `pendapatan_sebelum` varchar(191) DEFAULT NULL,
  `pendapatan_sesudah` varchar(191) DEFAULT NULL,
  `nilai_jumlah_intervensi_bantuan` varchar(191) DEFAULT NULL,
  `durasi_pendampingan_peserta` varchar(191) DEFAULT NULL,
  `keterangan` text,
  `jumlah_pinjaman` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `sisa_dana_pengembalian` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `perjanjian_tanggal_pengembalian_peserta` int NOT NULL DEFAULT '0',
  `nomor_urut_peserta` int NOT NULL DEFAULT '0',
  `id_jenis_angsuran` varchar(100) DEFAULT NULL,
  `id_anggaran_microfinance` int DEFAULT NULL,
  `lampiran_pelengkap` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_dampak_program` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '-',
  `foto_profil_peserta_program_bmfi_asesmen` varchar(191) DEFAULT NULL,
  `id_pendamping_bmfi_asesmen` int DEFAULT NULL,
  `parent_id` varchar(100) DEFAULT NULL,
  `id_provinsi` varchar(100) DEFAULT NULL,
  `id_kabupaten_kota` varchar(100) DEFAULT NULL,
  `id_kecamatan` varchar(100) DEFAULT NULL,
  `id_kelurahan_desa` varchar(100) DEFAULT NULL,
  `kode_org_baznas` varchar(100) DEFAULT NULL,
  `kordinat_latitude_lokasi_usaha` varchar(100) DEFAULT NULL,
  `kordinat_longitude_lokasi_usaha` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tgl_registrasi_peserta` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_peserta_program_bmfi_asesmen`),
  KEY `id_masjid` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1870 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 50 baris contoh peserta program nyata (dari 88 total) khusus masjid
-- 'M367230004042023872', diekstrak persis dari dump asli.
INSERT INTO `bmfi_peserta_program` VALUES
(26,'0','0','0','MUHASIM','3672081505660002','081906316168',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 15- 05-1966','Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon - Banten',NULL,'-6.01134','106.03229',NULL,'Kubang Sepat Dekat Fasum RT 01 / 07','Ternak Ayam','7083841100102816','MUHASIM','NANK SYAR\'AH MUAMALAH','id3672081505660002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'3 Orang','ABYD FATHUL ADHIM','2','13','1','1',NULL,'1',NULL,NULL,NULL,NULL,NULL,'<p>Dengan Pinjaman BMM ini, ingin mengembangkan ke Peternakan Kambing</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'41936a75201ebe53abe8070ee90ee3ba.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01134','106.03229','2023-12-13 04:37:27','2023-12-13','2024-10-06 17:15:05'),
(27,'0','0','0','NENENG SULHIYATI','3672085906880002','085778778921',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 19-06-1988','Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon - Banten',NULL,'-6.01129','106.03217',NULL,'Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon - Banten','Warung Kecil','7083841100102810','NENENG SULHIYATI','BANK SYARI\"AH MUAMALAH','id3672085906880002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','RIDWAN','2','11','6','1','','1','','','','','','<p>Tujuan Peminjaman untuk pengembangan usaha</p>\r\n','2500000','415000',0,0,'13',10,NULL,NULL,'949c69641f0454ee5eb1a507a32ed756.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01129','106.03217','2023-12-13 04:51:22','2023-12-13','2024-10-14 17:25:24'),
(28,'0','0','0','ULAYAH','3672085611610002','085888516553',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Jakarta, 16-11-1961','Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon - Banten',NULL,'-6.011011','106.032037',NULL,'Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon - Banten','Jual Pakaian Online dan Ofline','7083841100102824','ULAYAH','BANK SYARI\'AH MUAMALAH','id3672085611610002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'2','MILDIYANTO','2','13','1','1','2','1','1. Pendapatan Meningkat\r\n2. Makin berani dalam menerima orderan karena adanya tambahan modal\r\n3. Menambah jenis barang jualan \r\n4. Order Meningkat\r\n5. Pelanggan Makin Banyak','Rp 0 - Rp 500.000','Rp 500.000 - Rp 1.000.000','Rp 2.500.000','3 Bulan','<p>Pengembangan Usaha</p>\r\n','2500000','378000',0,0,'13',10,NULL,'72a4b6ae146090e79fadb93624831bb0.jpeg','634e65726d8aa92763d1165484e7943e.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011011','106.032037','2023-12-13 04:54:58','2023-12-13','2024-10-06 17:17:28'),
(31,'0','0','0','TUTI','3672085406590001','081385530365',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 14-06-1959','Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon – Banten 42441',NULL,'-6.011012','106.032038',NULL,'Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon – Banten 42441(Dekat Masjid)','Jual Nasi Uduk pagi','7083841100102828','TUTI','BANK SYARI\'AH MUAMALAH','id3672085406590001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'1 Orang','TITIN','2','13','5','1','','1','','','','','','<p>Butuh Modal Usaha&lt;<br />\r\nkarena Menerima pesanan kue sperti Pastel dan Arem Arem dan kue kue lain</p>\r\n','2500000','400000',0,0,'13',10,NULL,NULL,'b12534e2ba263de054c93f7f57201803.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011012','106.032038','2023-12-13 05:14:21','2023-12-13','2024-10-14 17:37:02'),
(32,'0','0','0','SITI KOMARIAH','3672084503730002','081286118868',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 05-03-1973','Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon – Banten 42441',NULL,'-6.01019','106.03189',NULL,'Link. Kubang Sepat RT 01/07, Kelurahan Citangkil, Kecamatan Citangkil Kota CIlegon – Banten 42441','Penjual Nasi Uduk dengan Gerobak','7083841100102850','SITI KOMARIAH','BANK SYARI\'AH MUAMALAH','id3672084503730002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','KASPAN','2','11','5','1','','1','','','','','','<p>Memperbesar usaha Produksi kerupuk Khas Palembang</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'c32aafc292b45ccb83d7273638889454.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01008','106.03163','2023-12-13 05:24:05','2023-12-13','2024-10-14 17:42:29'),
(33,'0','0','0','RUSLIA M ARIF','3672086810680001','087772984990',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Palembang, 28-10-1960','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.01065','106.03103',NULL,'Link. Kubang Sepat RT 01/07, Citangkildekat Waduk Nadran ','Kemplang Bakar','7083841100102818','RUSLA M ARIF','BANK SYARI\'AH MUAMALAH','id3672086810680001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'1 Orang','.','2','','2','1','','1','','','','','','<p>Memperbesar usaha Produksi kerupuk Kemplang Bakar Khas Palembang</p>\r\n','1500000','0',0,0,'13',10,NULL,NULL,'0a44a76589b546baf5d6989052fa8a50.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01065','106.03103','2023-12-13 07:07:22','2023-12-13','2024-10-14 17:48:25'),
(36,'0','0','0','SAMSUDIN','3672817704620001','085214886673',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Kroya, 17-04-1962','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.01098','106.03130',NULL,'Link. Kubang Sepat RT 01/07, Citangkil Belakang Masjid ','Jual Jamu Keliling dan usaha pengilingan kelapa di rumah','7083841100102844','SAMSUDIN','BANK SYARI\"AH MUAMALAH','id3672817704620001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','Satinah','2','12','4','1','','1','','','','','','<p>Pengembangan usaha</p>\r\n','3000000','800000',0,0,'13',10,NULL,NULL,'4f1f365a1fce0d0637793d76b4ddf586.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.010980','106.03130','2023-12-13 07:11:06','2023-12-13','2024-10-14 18:26:48'),
(37,'0','0','0','SUMARDI','3672080506600002','08979449793',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Oku, 05-06-1960','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.01061','106.03145',NULL,'Keliling Kampung sekitaran Kelurahan Citangkil','WARUNG SEMBAKO','7084841100102820','SUMARDI','BANK SYARI\'AH MUAMALAH','id3672080506600002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','Maryanah','2','12','6','1','','1','','','','','','<p>Pengembangan usaha</p>\r\n','2500000','490000',0,0,'13',10,NULL,NULL,'f3bf5199916e389778b0e174387f64ed.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01061','106.03145','2023-12-13 07:11:31','2023-12-13','2024-10-14 18:35:46'),
(38,'0','0','0','NASIAH','3672084808610001','087769498144',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 08-08-1961','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.01097','106.03116',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Produksi Kerupuk Kemplang','7083841100102860','NASIAH','BANK SYARI\'AH MUAMALAH','id3672084808610001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'3 Orang','RIKI AGUSTIAN','2','13','1','1',NULL,'1',NULL,NULL,NULL,NULL,NULL,'<p>Pengembangan usaha</p>\r\n','2000000','400000',0,0,'13',10,NULL,NULL,'16d45e4169d5c3b0d33cdd94daf98b93.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01097','106.03116','2023-12-13 07:12:11','2023-12-13','2024-10-06 17:25:23'),
(41,'0','0','0','ARMAN SYATRI','3672081708640001','082112666464',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Palembang. 17-08-1964','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.01103','106.03128',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Produksi dan Distribusi Empek Empek','7083841100102842','ARMAN SYATRI','BANK SYARI\'AH MUAMALAH','id3672081708640001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','NURHAYATI','2','12','2','1','','1','','','','','','<p>Pengembangan usaha</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'26e6d28afb5a0f0dc69f3a6598dba258.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01103','106.03128','2023-12-13 07:13:29','2023-12-13','2024-10-06 17:26:35'),
(42,'0','0','0','DAHLENA','180801660590002','087757166951',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Sukamulya, 26-05-1995','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.01089','106.03084',NULL,'Link. Kubang Sepat RT 01/07, Citangkil dekat lapangan Fasum RT 01','Ikan hias dan Produksi aquarium','7083841100102826','DAHLENA','BANK SYARI\'AH MUAMALAH','id180801660590002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 orang','EKO SUPRANTO','2','11','8','1','','1','','','','','','<p>Pengembangan usaha</p>\r\n','1500000','375000',0,0,'13',10,NULL,NULL,'f3d1a9f837f9d8540a856471769a25ba.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01089','106.03084','2023-12-13 07:13:51','2023-12-13','2024-10-17 15:15:25'),
(43,'0','0','0','MARYATI','3672086106650001','087774839154',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Purworwo, 21-061065','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.01036','106.03080',NULL,'Link. Kubang Sepat RT 01/07, CitangkilDekat Lapangan Futsal RT 01','Warung Kecil','7083841100102830','MARYATI','BANK SYARIAH MUAMALAH','id3672086106650001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','MARYATI','2','12','6','1','','1','','','','','','<p>Pengembangan usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'4af2c1a3d573a5b1c176d3bbe3001495.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01036','106.03080','2023-12-13 07:14:13','2023-12-13','2024-10-17 15:15:55'),
(45,'0','0','0','ANIYATI','3672085008800001','083138804109',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 10=08-1980','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.0110749','106.031607',NULL,'Link. Kubang Sepat RT 01/07, CitangkilJalan Utama','Warung Sembako','70838341100102831','ANIYATI','BANK SYARI\'AH MUAMALAH','id3672085008800001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','SUHEMI','2','11','6','1','','1','','','','','','<p>Pengembangan usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'3ffbe41810ce4688142dae3edf0332c0.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0110749','106.031607','2023-12-13 07:14:47','2023-12-13','2024-10-17 15:17:03'),
(46,'0','0','0','SUMIYATI','3672086810740001','087887316202',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang,28-10-1974','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.011003','106.031759',NULL,'Link. Kubang Sepat RT 01/07, CitangkilJalan Utama','Nasi uduk, gorengan dan Lain Lain','7083841100102819','SUMIYATI','BANK SYARI\'AH MUAMALAH','id3672086810740001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'3 Orang','SUKHERMAN','2','11','5','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','2500000','0',0,0,'13',10,NULL,NULL,'c31202a216666c494915fc08b6d5ce62.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011003','106.031759','2023-12-13 07:15:15','2023-12-13','2024-10-18 09:48:11'),
(47,'0','0','0','HERNANI','3672084210730001','081318835135',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Palembang, 02-10-1973','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.010638','106.031231',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Jasa Maintenance dan Cleaning AC','7083841100102846','HERNANI','BANK SYARI\'AH MUAMALAH','id3672084210730001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'3 orang','LASIMAN','2','11','4','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','3000000','1000000',0,0,'13',10,NULL,NULL,'9c1fa18bd2cc203451e97547781bf6a1.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.010638','106.031231','2023-12-13 07:15:35','2023-12-13','2024-10-16 15:06:57'),
(48,'0','0','0','SUHERI','3672081907730004','085933266237',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 19-07-1973','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.0100777','106.0280231',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Jasa Kebidanan','7083841100102845','SUHERI','BANK SYARI\'AH MUAMALAH','id3672081907730004-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','ETI MASLIYATI','2','12','4','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','2500000','40000',0,0,'13',10,NULL,NULL,'f1885e8dfc9bf2a8fa49523bea9f5024.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0100777','106.0280231','2023-12-13 07:16:06','2023-12-13','2024-10-16 15:07:51'),
(49,'0','0','0','HERLIN JANUARTI','3672084101790002','087796212710',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 01-01-1979','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.010243','106.0283231',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Warung Kecil','7083841100102814','HERLIN JANUARTI','BANK SYARI\'AH MUAMALAH','id3672084101790002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','SAHRONI','2','11','6','1','3','1','<p>1. Penghasilan sampai dengan Rp 1.500.000</p>\r\n\r\n<p>2. Pendapatan Meningkat</p>\r\n\r\n<p>3. Peluang lebih besar mendapatkan pelanggan baru</p>\r\n\r\n<p>4. Orderan meningkat</p>\r\n\r\n<p>5. Makin termotivasi untuk usaha</p>\r\n','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'d1389d5b5947a3a29ac8694cac5d2f2a.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0102377','106.0283231','2023-12-13 07:16:34','2023-12-13','2024-10-17 15:29:19'),
(51,'0','0','0','ARIFULLOH','3672080107820005','081906094700',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 01-07-1982','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.010200','106.030759',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','WARUNG SOSIS BAKAR DLL','7083841100102839','ARIFULLOH','BANK SYARI\'AH MUAMALAH','id3672080107820005-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','HERNA','2','12','6','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','2500000','363000',0,0,'13',10,NULL,NULL,'9b90a3033df8e2db7e653422d7322d9c.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.010200','106.030759','2023-12-13 07:17:29','2023-12-13','2024-10-17 15:29:44'),
(52,'0','0','0','DEDEH HIDAYAT','3604266010800002','085952674664',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Jakarta, 20-10-1980','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.010072','06.030909',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','warung sembako kecil','7083841100102841','DEDEH HIDAYAT','BANK SYARI\'AH MUAMALAH','id3604266010800002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','RICKY NOVIANSYAH ','2','11','6','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','2000000','440000',0,0,'13',10,NULL,NULL,'71d9db21e154f5a382a23b63cc312af4.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.010072','106.030909','2023-12-13 07:17:58','2023-12-13','2024-10-16 15:10:42'),
(53,'0','0','0','TASORI','3672082105720001','081390231962',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Pemalang, 21-05-1972','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.009987','106.030630',NULL,'Keliling Sekitaran Keluarahan Citangkil','Penjual Nasi GorenG Keliling (gerobak)','','','','id3672082105720001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','TARONAH','2','12','5','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.009987','106.030630','2023-12-13 07:18:21','2023-12-13','2024-10-16 15:13:21'),
(55,'0','0','0','DEWI FADILAH','3602106107820004','085939363100',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Lebak, 21-07-1982','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.011161','106.031038',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Warung kecil dan Es','7083841100102843','DEWI FADILAH','BANK SYARI\'AH MUAMALAH','id3602106107820004-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','TEGUH FIRMANSYAH','2','11','6','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'78a58e23f8e891602b64e2de42fa9fe0.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011161','106.031038','2023-12-13 07:19:04','2023-12-13','2024-10-16 15:14:36'),
(57,'0','0','0','RIA HARMONIS','3672034806870003','087772320103',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 08-06-1987','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.011016','106.031982',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Warung Kecil','7083841100102848','RIA HARMONIS','BNAK SYARI\'AH MUAMALAH','id3672034806870003-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','ANTONI','2','11','6','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','1000000','0',0,0,'13',10,NULL,NULL,'b3c87190a096be290ef860d5aba27f98.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011016','106.031982','2023-12-13 07:20:00','2023-12-13','2024-10-16 15:15:51'),
(58,'0','0','0','IDAYANTI','3672084610790002','083142228251',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 06-10-1979','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.0110033','106.0319883',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Warung es dan Snack','7083841100102835','IDAYANTI','BANK SYARI\'AH MUAMALAH','id3672084610790002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'2 Orang','AMAN SAHRUL ISLAM','2','13','6','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','1500000','375000',0,0,'13',10,NULL,NULL,'0626831929f52140e740699de2500c28.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0110033','106.0319883','2023-12-13 07:20:23','2023-12-13','2024-10-16 15:18:35'),
(60,'0','0','0','AMINUDIN','3602210101750012','089606731507',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Lebak, 01-01-1975','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.011630','106.032164',NULL,'KOTA CILEGON','Jual Pakaian Keliling, online dan Ofline','7083841100102855','AMINUDIN','BANK SYARI\'AH MUAMALAH','id3602210101750012-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'8 Orang','TUTI JULIAH','2','12','1','1',NULL,'1',NULL,NULL,NULL,NULL,NULL,'<p>Penambahan Modal usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'d3b3053de48d6228967926f47fad6b9e.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011630','106.032164','2023-12-13 07:21:10','2023-12-13','2025-05-16 20:33:11'),
(61,'0','0','0','MASKURI','3672082703650001','081214680475',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 27-03-1965','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.01206','106.03204',NULL,'Link. Kubang Sepat RT 02/07, Citangkiljalan Utama','Warung Sembako','7083841100102849','MASKURI','BANK SYARI\'AH MUAMALAH','id3672082703650001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','SURANI','2','12','6','1','','1','','','','','','<p>Penambahan Modal usaha</p>\r\n','3000000','0',0,0,'13',10,NULL,NULL,'f416b91ddbbfb8a516d81b7aaa747bbb.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01206','106.03204','2023-12-13 07:21:30','2023-12-13','2024-10-16 15:20:51'),
(62,'0','0','0','HUDARI','3672082111600001','087890300946',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 21-11-1960','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.012260','106.032100',NULL,'Link. Kubang Sepat RT 02/07, Citangkiljalan Utama','Isi Ulang Air Mineral dan Gas','7173667668','HUDARI','BSI','id3672082111600001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'2 Orang','INAS SAYYIDA LATIFA','2','13','4','1','','1','','','','','','<p>Penambahan Modal usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'815015af24a3cf07f44135bd40ac9e93.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.012260','106.032100','2023-12-13 07:21:53','2023-12-13','2024-10-16 15:21:17'),
(64,'0','0','0','ANNISA','3212156506840011','087772453012',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Indramayu, 25-06-1984','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.011016','106.031982',NULL,'Link. Kubang Sepat RT 01/07, Citangkildepan Masjid','Produksi Bouqete\r\nJual Gamis / Pakaian Online\r\nProduksi dan Jual Kue Kue','7083841100102822','ANNISA','BANK SYARI\'AH MUAMALAH','id3212156506840011-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','Agus Fitrianto','2','11','4','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','3000000','750000',0,0,'13',10,NULL,NULL,'76902d24a59b5f0fc1d17a5c8a114b84.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0110087','106.0319803','2023-12-13 09:08:29','2023-12-13','2024-10-16 15:22:35'),
(65,'0','0','0','ACHMAD SUDRAJAT','3672080609750001','087771695272',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 06-09-1975','Link. Kubang Sepat RT 03/07, Citangkil',NULL,'-6.010702','106.032905',NULL,'Link. Kubang Sepat RT 03/07, Citangkildekat SMK YP FATAHILLAH','Warung  dan Kantin Sekolah','7083841100102817','','','id3672080609750001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','a','2','12','6','1','','1','','','','','','<p>Penambahan Modal usaha dan Investasi</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'761e6365b4bfac0a08b83f49f61dcb8d.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.010702','106.032905','2023-12-13 09:08:45','2023-12-13','2024-10-16 15:23:35'),
(66,'0','0','0','ANADINAWATI','3672084603810002','081314076495',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Kediri. 06-03-1981','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.011662','106.032100 ',NULL,'Link. Kubang Sepat RT 02/07, Citangkil','Konveksi / Tukang Jahit','7083841100102853','ANA DINAWATI','BANK SYARI\'AH MUAMALAH','id3672084603810002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 orang','HARMUJI','2','11','1','1',NULL,'1',NULL,NULL,NULL,NULL,NULL,'<p>Pengembangan Usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'99b98c01fb46fa723f08fcbc9895a4fb.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011662','106.032100 ','2023-12-13 09:09:22','2023-12-13','2024-10-06 16:22:35'),
(68,'0','0','0','KANDOENG SUPRIHATIN','3672084901660001','087771711427',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Madiun, 09-01-1966','Link. Kubang Sepat RT 03/07, Citangkil',NULL,'-6.011845','106.032519',NULL,'Sekitaran Kubang Sepat RW 07Link. Kubang Sepat RT 02/07, Citangkil','Tukang Sayur Keliling','7083841100102815','KANDOENG SUPRIHATIN','BANK SYARI\'AH MUAMALAH','id3672084901660001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'3 Orang','HARIYADI','2','11','6','1','','1','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'360645296cf8588b54264ae9fe884911.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011845','106.032519','2023-12-13 09:09:59','2024-01-23','2024-10-16 15:25:24'),
(69,'0','0','0','TUTI HERNAWATI','3672086705780002','087808408328',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 27-05-1978','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.011844','106.032518',NULL,'Link. Kubang Sepat RT 02/07, Citangkil','NASI UDUK PAGI / SUBUH','','','','id3672086705780002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'6 Orang','AHMAD ROYANI','2','11','5','1','','1','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011844','106.032518','2023-12-13 09:11:22','2023-12-13','2025-05-16 20:32:09'),
(70,'0','0','0','ARYATI','3672084504730001','087885596627',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Lampung, 05-04-1973','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.012046','106.032368',NULL,'Link. Kubang Sepat RT 02/07, Citangkil','Warung Kecil','','','','id3672084504730001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','HUSNI APRIADI','2','11','6','1','','1','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.012046','106.032368','2023-12-13 09:11:48','2023-12-13','2024-10-16 15:39:41'),
(71,'0','0','0','AMIR HAMZAH','3672080902620002','085770772964',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 09-02-1962','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.0113667','106.0255444',NULL,'link. Kubang Sepat Inpress RW 10','Penjahit / Permak','7083841100102829','AMIR HAMZAH','BANK SYARI\'AH MUAMALAH','id3672080902620002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'2 Orang','YULINA','2','13','1','1',NULL,'1',NULL,NULL,NULL,NULL,NULL,'<p>Pengembangan Usaha</p>\r\n','3000000','750000',0,0,'13',10,NULL,NULL,'f6316667d86e0e957e0304e9583188e1.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0030976','106.0306944','2023-12-13 09:12:37','2023-12-13','2024-10-06 10:13:30'),
(73,'0','0','0','AJI LUKMAN','3672081510650004','089644390790',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','...','Link. Kubang Sepat RT 03/07, Citangkil',NULL,'-6.011908','106.033141',NULL,'Link. Kubang Sepat RT 03/07, Citangkil','Pengusah makanan / Catering','70034449976','AJI LUKMAN','BSI','id3672081510650004-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'','.','2','12','2','1','','1','<p>UMKM yang bergerak di bidang Industri Kuliner&nbsp;<br />\r\nBerawal melayani pesanan tetangga dengan jumlah porsi terbtas, kemudian meningkat menjadi acara pernikahan dan lain lain<br />\r\nsaat ini melayani di luar wilayah Lingkungan&nbsp;<br />\r\nSaat ini memilik Merk Dagang Dapur Fanisha</p>\r\n','5000000','10000000 - 20000000','Awal Bantuan 3.000.000','8 BULAN','<p>Pengembangan Usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'c982ed745b64bc739fb90bfa334a1788.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011908','106.033141','2023-12-13 09:13:19','2023-12-13','2024-10-16 14:38:34'),
(74,'0','0','0','BANI','3672080405620003','0895365196523',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 04-05-1962','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.0118917','106.0295251',NULL,'Link. Kubang Sepat RT 02/07, Citangkil','Warung Kecil dan Pulsa','7083841100102836','BANI','BANK SYARI\'AH MUAMALAH','id3672080405620003-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','MASTIYAH','2','12','6','1','','1','','','','','','<p>Pengembangan Usaha</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'b55a5edf817974b08a639e031736e184.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0118917','106.0295251','2023-12-13 09:13:50','2023-12-13','2024-10-16 15:58:03'),
(76,'0','0','0','H. MISRONI','3672080405630002','08128489214',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Serang, 04-05-1963','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.011492','106.0293431',NULL,'Link. Kubang Sepat RT 02/07, Citangkil','Maintenance','7083841112851','MISRONI','BANK SYARI\'AH MUAMALAH','id3672080405630002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','Hj. MASTULELI','2','12','4','1','','1','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','500000',5,0,'13',10,NULL,NULL,'ead59ada7becc21e1b852632ce3d00d1.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0114867','106.0293431','2023-12-13 09:14:47','2023-12-13','2024-10-19 06:49:48'),
(78,'0','0','0','SERINAH','3672085208830000','081219694664',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Serang, 12-08-1983','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.011568','106.032263',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Produksi Empek Empek','7083841100102858','SERINAH','BANK SYARI\'AH MUAMALAH','id3672085208830000-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','HIDYATULLOH','2','11','2','1','','1','','','','','','<p>Pengembangan Usaha</p>\r\n','1500000','375000',0,0,'13',10,NULL,NULL,'6b4481c3dfcd7a5183c2830a3add628a.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011568','106.032263','2023-12-13 09:15:48','2023-12-13','2024-10-16 16:00:06'),
(81,'0','0','0','HERLINA','3672084706760001','081213037661',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Palembang, 07-08-1976','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.010255','106.031312',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Produksi Kemplang\r\nproduksi Kerupuk Palembang\r\nproduksi Empek2','7083841100102833','HERLINA','BANK SYARI\'AH MUAMALAH','id3672084706760001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'4 Orang','INDRA','2','11','2','1','','1','<p>1. Penghasilan sampai dengan Rp 1.500.000</p>\r\n\r\n<p>2. Pendapatan Meningkat</p>\r\n\r\n<p>3. Peluang lebih besar mendapatkan pelanggan baru</p>\r\n\r\n<p>4. Orderan meningkat</p>\r\n\r\n<p>5. Makin termotivasi untuk usaha</p>\r\n','','','','','<p>Pengembangan Usaha</p>\r\n','2000000','300000',0,0,'13',10,NULL,NULL,'e1a3a0fe118bbb35a111d1f41906fe17.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.009119','106.023982','2023-12-13 09:16:48','2023-12-13','2024-10-16 16:01:43'),
(82,'0','0','0','IDA','3672086404660003','087809577426',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Palembang, 24-04-1966','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.0114926','106.0305774',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Produksi Empek Empek\r\nKios Empek Empek','7083841100102832','IDA','BANK SYARI\'AH MUAMALAH','id3672086404660003-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'1 Orang','.','2','','5','1','2','1','<p>1. Penghasilan sampai dengan Rp 1.500.000</p>\r\n\r\n<p>2. Pendapatan Meningkat</p>\r\n\r\n<p>3. Peluang lebih besar mendapatkan pelanggan baru</p>\r\n\r\n<p>4. Orderan meningkat</p>\r\n\r\n<p>5. Makin termotivasi untuk usaha</p>\r\n','Rp 0 - Rp 500.000','Rp 1.500.000','Rp 2.000.000','3 Bulan','<p>Pengembangan Usaha</p>\r\n\r\n<p>&nbsp;</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'ee969397789fff83f766447a38d4803f.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0114926','106.0305775','2023-12-13 09:17:08','2023-12-13','2024-10-16 16:03:12'),
(83,'0','0','0','ANITA REFITRI SIREGAR','1221024708800001','085280359471',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Aeknabara Tonga, 16-02-1980','Link. Kubang Sepat RT 02/07, Citangkil',NULL,'-6.0114927','106.0305775',NULL,'Depan SDN Kubang Sepat 1','Warung Kecil','7083841100102840','ANITA REFITRI SIREGAR','BNAK SYARI\'AH MUAMALAH','id1221024708800001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'6 Orang','IKHWAN HARAHAP','2','11','6','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','2000000','515000',0,0,'13',10,NULL,NULL,'688cb2902f0542ad6a3b65590f9e748c.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0110116','106.031991','2023-12-13 09:18:16','2023-12-13','2024-10-16 16:06:14'),
(84,'0','0','0','ARYODILLAH','3672082509610001','087764488168',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Plaju, 25=09-1961','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.0113668','106.0235061',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Warung Nasi','7212234383','','BSI','id3672082509610001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'1 Orang','.ARYODILLAH','2','','5','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','1500000','0',0,0,'13',10,NULL,NULL,'347f3cf57945e39e1710cd52a8414efa.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0113668','106.0235061','2023-12-13 09:18:39','2023-12-13','2024-10-16 16:08:15'),
(85,'0','0','0','IMRON','3672083112670003','081398966026',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Palembang, 31-12-1967','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.0113666','106.0235059',NULL,'Link. Kubang Sepat RT 01/07, Citangkil','Produksi Kerupuk','7083841100102861','IMRON','BANK SYARI\'AH MUAMALAH','id3672083112670003-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'3 Orang','NAFSIAH','2','12','2','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'459edd2d7fc2e20a9472d4a6e7f7065d.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.0113666','106.0235059','2023-12-13 09:19:02','2023-12-13','2024-10-16 16:09:03'),
(87,'0','0','0','RINI','3672084305810006','081324604626',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Kuningan, 03-05-1981','Link. Kubang Sepat RT 01/07, Citangkil',NULL,'-6.003098','106.03069',NULL,'Depan Rel Kubang Sepat RT 01','Warung Kecil','7083841100102825','RINI','BANK SYARI\'AH MUAMALAH','id3672084305810006-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','Eko Purnomo','2','11','2','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'8b00abf421b5c27a312e1cdc72d23f98.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.01011','106.03166','2023-12-13 09:19:44','2023-12-13','2024-10-16 16:10:10'),
(88,'0','0','0','GEOVANI ALTHA FIRMANSYAH','3672080801850005','0895340034703',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','.','Link. Kubang Sepat RT 03/07, Citangkil',NULL,'-6.01207','106.03317',NULL,'Lingkar Selatan Kota Cilegon','Produksi dan jual Pasir Retail','','','','id3672080801850005-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'','.','2','','6','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','750000',0,0,'13',10,NULL,NULL,'',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.049478','106.063814','2023-12-13 09:20:03','2023-12-13','2024-10-16 16:11:23'),
(89,'0','0','0','SUFIYAN ANSHORY','3672081401790003','082110601978',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','.','Link. Kubang Sepat RT 03/07, Citangkil',NULL,'-6.011367','106.025544',NULL,'Link. Kubang Sepat RT 03/07, Citangkil','Warung Kecil','','','','id3672081401790003-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'.','.','2','','2','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','2000000','620000',0,0,'13',10,NULL,NULL,'',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011367','106.025544','2023-12-13 09:20:41','2023-12-13','2024-10-16 16:12:45'),
(90,'0','0','0','LARTO','3312171710820001','085215722220',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','.','Link. Kubang Sepat RT 03/07, Citangkil',NULL,'-6.011366','106.023505',NULL,'Mangka di Alfamart Sambimanis','Jula Sayur Keliling menggunakan Sepeda Motor','7083841100102838','LARTO','BANK SYARI\'AH MUAMALAH','id3312171710820001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'..','','2','12','6','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','500000',0,0,'13',10,NULL,NULL,'f950f9e6423e50765b6efae92cb480be.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011366','106.023505','2023-12-13 09:21:00','2023-12-13','2024-10-16 16:17:07'),
(91,'0','0','0','TUMINI','3672086609790001','081288355253',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','.','Link. Kubang Sepat RT 03/07, Citangkil',NULL,'-6.011367','106.023506',NULL,'Link. Kubang Sepat RT 03/07, Citangkil','Jasa Sablon','7083841100102837','TUMINI','BANK SYARI\'AH MUAMALAH','id3672086609790001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'.','.','2','','4','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','3000000','750000',0,0,'13',10,NULL,NULL,'e90dc8a16436e532fe08ac6b67582a19.jpeg',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011367','106.023506','2023-12-13 09:21:17','2023-12-13','2024-10-16 16:18:25'),
(93,'0','0','0','SUIRAT','3672086808840001','081219449685',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','P. Sibesi, 28-08-1984','Link. Kubang Sepat RT 01/07, kelurahan Citangkil, Kecamatan Citangkil Kota Cilegon 42441',NULL,'-6.011367','106.023506',NULL,'Jl. Sutan Syahrir Link. Kubang Sepat RT 01/07, kelurahan Citangkil, Kecamatan Citangkil Kota Cilegon 42441','Produksi Kue\r\njasa Parut Kelapa','7083841100102852','SUIRAT','BANK SYARI\'AH MUAMALAH','id3672086808840001-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','SAFRUDIN ARMAN','2','11','2','1','','','','','','','','<p>Pengembangan Usaha</p>\r\n','2000000','0',0,0,'13',10,NULL,NULL,'569b68281bf106de56705adb94b48fe3.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-6.011367','106.023506','2023-12-13 09:21:58','2023-12-13','2024-10-16 16:19:35'),
(94,'0','0','0','LULUK RIZKY AMALIAH','3601122409890003','087774686715',NULL,NULL,NULL,NULL,NULL,NULL,'Perempuan','Pandegelang, 18-11-1992','Link. Gerem Raya RT 02/04, Gerem',NULL,'-5.967043','106.000948',NULL,'Jalan Utama Gerem ','Produksi Kue Titipan Warung','8375493910','LULUK RIZKY AMALIAH','BSI','id3601122409890003-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'5 Orang','FARHAN YASYIRLI AMRI','2','11','2','1','','','','','','','','<p>Modal Usaha dan Investasi</p>\r\n','3000000','750000',0,0,'13',10,NULL,NULL,'d68c2990ebfa1e802166f24fe61a80f0.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-5.967043','106.000948','2023-12-13 09:22:17','2023-12-13','2024-10-16 16:20:46'),
(95,'0','0','0','H. MUHAMMAD SOLEH','3672062601520002','081212819154',NULL,NULL,NULL,NULL,NULL,NULL,'Laki-laki','Cianjur, 26-01-1952','Link. Gerem Raya RT 02/04, Gerem',NULL,'-5.972938','105.987814',NULL,'Gerem','Jasa Laundry','7257361114','H. MUHAMMAD SOLEH','BSI','id3672062601520002-M367230004042023872-microfinance-Masjid_Jami_Al_Hidayah_Kubang_Sepat',NULL,'1 Orang','.','2','','1','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'<p>Modal Usaha dan Investasi</p>\r\n','3000000','0',0,0,'13',10,NULL,NULL,'b8164557574efa7979b3dcddfa6fab1c.PNG',NULL,'M367230004042023872','36','3672','367208','3672081007','3672300','-5.972938','105.987814','2023-12-13 09:22:40','2023-12-13','2024-10-06 17:03:37');

CREATE TABLE `bmfi_transaksi_microfinance` (
  `id_bmfi_transaksi_microfinance` int NOT NULL AUTO_INCREMENT,
  `jalur_akses` varchar(100) DEFAULT NULL,
  `hash_blockchain_peserta_bmm` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hash_blockchain_masjid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `nominal_pengembalian` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `infak_peserta` varchar(50) NOT NULL DEFAULT '0',
  `sisa_dana_cicilan_pengembalian` varchar(50) NOT NULL DEFAULT '0',
  `nominal_peminjaman` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `keterangan_transaksi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `lampiran` varchar(100) DEFAULT NULL,
  `id_bmfi_peserta_program` int DEFAULT NULL,
  `id_bmfi_pendamping` int DEFAULT NULL,
  `id_bmfi_jenis_angsuran` int DEFAULT NULL,
  `id_level` int DEFAULT NULL,
  `id_bmm_peserta_jenis_pembayaran` int DEFAULT '1',
  `id_jalur_akses` varchar(191) DEFAULT NULL,
  `nama_masjid` varchar(191) DEFAULT NULL,
  `no_bmm_transaksi_microfinance` varchar(191) DEFAULT NULL,
  `id_provinsi` char(2) DEFAULT NULL,
  `id_kabupaten_kota` char(4) DEFAULT NULL,
  `id_kecamatan` char(7) DEFAULT NULL,
  `id_kelurahan_desa` char(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `kode_org_baznas` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_bmfi_transaksi_microfinance`),
  KEY `Tanggal` (`tanggal`) USING BTREE,
  KEY `Jalur_Akses` (`jalur_akses`),
  KEY `id_jenis_dana` (`id_bmfi_peserta_program`),
  KEY `id_jenis_harta` (`id_bmfi_pendamping`)
) ENGINE=InnoDB AUTO_INCREMENT=11011 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 100 baris contoh transaksi ledger nyata (dari 297 total) khusus masjid
-- 'M367230004042023872', diekstrak persis dari dump asli.
INSERT INTO `bmfi_transaksi_microfinance` VALUES
(146,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Warung Kecil',NULL,103,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 00:33:32',NULL,NULL),
(147,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Berjualan Sayuran, Termasuk Memberikan Tempo Pada Warga Sekita Bagi Yang Memiliki Penghasilan Bulanan / KAryawan','',102,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-11 00:41:22','2024-02-01 15:01:30',NULL),
(148,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Pedagang Sembako Yang Memenuhi Permintaan Warga Sekitar RT 02 Dan 03, Krena Letaknya Yang Berbatasan Dengan Wilayah RT 02 Dan 03',NULL,101,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 00:45:28',NULL,NULL),
(149,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Pedagang Nasi Uduk, Gorengan Yang Menyediakan Bagi Warga Sekitar Khususnya Karyawan Dan Petugas Ronda, Karena Lokasi Usaha Yang Dekat Dengan Pos Ronda / Pos Paguyuban',NULL,98,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 00:48:29',NULL,NULL),
(150,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','P',NULL,97,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 00:49:39',NULL,NULL),
(151,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Usaha Jajanan Kue Tradisional Dan Kue Kue Untuk Kebutuhan Pesanan Pengajian Dan Acara Acara Lain',NULL,96,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 00:53:34',NULL,NULL),
(152,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Usaha Laundry',NULL,95,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 00:58:10',NULL,NULL),
(153,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Usaha Penjualan Titipan Kue Kue Tradisional',NULL,94,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:01:05',NULL,NULL),
(154,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Pengusaha Pembuatan Kue Kue Tradisional Dan Di Jajakan Langsung',NULL,93,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:06:59',NULL,NULL),
(155,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Usaha Sablon, Yang Menerima Pesanan Baik Partai Besar Maupun Satuan',NULL,91,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:09:49',NULL,NULL),
(156,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Penjual Sayur Keliling Yang Berjualan Di Sekitar Komplek Kelurahan Citangkil',NULL,90,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:14:21',NULL,NULL),
(157,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Warung Kecil',NULL,89,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:18:23',NULL,NULL),
(158,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Usaha Yang Di Jalankan Adalah Perbengkelan, Perbaikan Sepeda Motor, Service Dan Ganti Oli Serta Tambal Ban Dll',NULL,105,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:47:06',NULL,NULL),
(159,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','<p>Pengembangan Usaha</p>\r\n','',104,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-11 01:49:56','2024-01-11 08:51:15',NULL),
(160,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Penjualan Pasir Retail Untuk Di Kirim Ke Toko Toko Bangunan Dengan Mengunakan Mobil Pick Up ',NULL,88,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:54:56',NULL,NULL),
(161,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Usaha Warung Kecil Yang Melayani Warga Sekitar Dan Pejalan Kaki Juga Peserta Ronda Karena Lokasinya Yang Strategis Dekat Pos Ronda Dan Jalan Utama ',NULL,87,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 01:59:02',NULL,NULL),
(162,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Usaha Produksi Kerupuk Khas Palembang, Yang Melayani Penjualan Langsung Dan Pesanan',NULL,85,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 03:19:14',NULL,NULL),
(163,'Masjid',NULL,NULL,'2023-12-28','0','0','1500000','1500000','Usaha Warung Makan Ibu Aryodillah, Menerima Pesanan Nasi Kotak Dan Nasi Bungkus Untuk Keperluan Acara Acara Tahlilan Dll',NULL,84,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 03:22:11',NULL,NULL),
(164,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Usaha Warung Kecil Di Depan Sekolah SDN Kubang Sepat 1, Citangkil\r\nDengan Konsumen Anak Anak Sekolah',NULL,83,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-11 03:25:15',NULL,NULL),
(165,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','Produksi Empek Empek Untuk Penjualan Langsung Dan Pesanan',NULL,82,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:06:55',NULL,NULL),
(166,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Produksi Kemplang Produksi Kerupuk Palembang Produksi Empek2',NULL,81,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:10:07',NULL,NULL),
(167,'Masjid',NULL,NULL,'2023-12-28','0','0','1500000','1500000','Produksi Dana Penjualan, Empek Empek, Cisolor Dan Sosis Selimut',NULL,78,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:12:40',NULL,NULL),
(168,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Modal Yang Di Dapatkan Untuk Pembelian Konsumable Dan Alat Alat Safety',NULL,76,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:15:05',NULL,NULL),
(169,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Warung Kecil Untuk Memenuhi Kebutuhan Anaka Anak Sekolah',NULL,74,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:17:12',NULL,NULL),
(170,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Pengusah Makanan / Catering Untuk Acara Hajatan, Tahlilan Dan Pesanan Nasi Kotak',NULL,73,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:20:23',NULL,NULL),
(171,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Penjahit Yang Melayani Perbaikan Celana Atau Baju Robek, Atau Pemasangan Logo Dll',NULL,71,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:38:42',NULL,NULL),
(172,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Warung Kecil Dan Sayur',NULL,70,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:41:38',NULL,NULL),
(173,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Warung Kecil',NULL,69,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:44:06',NULL,NULL),
(174,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Penjual Sayur Keliling (Sayur Gendong)',NULL,68,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:46:33',NULL,NULL),
(175,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Penjahit Dan Konveksi, Menerima Pesanan Pembuatan Seragam Dll',NULL,66,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:48:59',NULL,NULL),
(176,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Warung Kecil Dekat Sekolah STM Fatahillah 2, Melayani Anak Sekolah',NULL,65,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 05:51:40',NULL,NULL),
(177,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Produksi Bouqete, Jual Gamis / Pakaian Online Produksi Dan Jual Kue Kue',NULL,64,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:18:27',NULL,NULL),
(178,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Produksi Empek Empek Dan Tekwan',NULL,63,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:20:42',NULL,NULL),
(179,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Isi Ulang Air Mineral Dan Isi Ulang Gas LPG',NULL,62,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:23:38',NULL,NULL),
(180,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Toko Sembako, Beras, Dll',NULL,61,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:26:19',NULL,NULL),
(181,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Nama Usaha : Jual Pakaian Keliling, Online Dan Ofline\r\n',NULL,60,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:28:48',NULL,NULL),
(182,'Masjid',NULL,NULL,'2023-12-28','0','0','1500000','1500000','Warung Es Dan Snack',NULL,58,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:30:27',NULL,NULL),
(183,'Masjid',NULL,NULL,'2023-12-28','0','0','1000000','1000000','Warung Kecil Dan Online Yang Menerima Pesanan Maupun COD',NULL,57,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:33:55',NULL,NULL),
(184,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Warung Kecil Dan Es',NULL,55,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:39:42',NULL,NULL),
(185,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Pedagang Nasi Goreng Keliling',NULL,53,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:41:48',NULL,NULL),
(186,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','WArung Kecil Dan Jajanan',NULL,52,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:45:43',NULL,NULL),
(187,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','Usaha Warung Kecil Dan Sosis Bakar',NULL,51,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:47:32',NULL,NULL),
(188,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Usaha Warung Kecil Di Sekitaran Krakatu Junction',NULL,49,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:51:04',NULL,NULL),
(189,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','Usaha Kebidanan Dan Klinik Pengobatan',NULL,48,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:52:57',NULL,NULL),
(190,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Maintenance Dan Service AC ',NULL,47,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:55:22',NULL,NULL),
(191,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','Nasi Uduk, Gorengan Dan Lain Lain',NULL,46,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:57:15',NULL,NULL),
(192,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Warung Sembako, Sncak Dan Gas',NULL,45,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 09:59:23',NULL,NULL),
(193,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Warung Kecil,',NULL,43,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 10:01:51',NULL,NULL),
(194,'Masjid',NULL,NULL,'2023-12-28','0','0','1500000','1500000','Ikan Hias Dan Produksi Aquarium',NULL,42,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 10:04:13',NULL,NULL),
(195,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Produksi Dan Distribusi Empek EMpek\r\n',NULL,41,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-13 10:07:54',NULL,NULL),
(196,'Masjid',NULL,NULL,'2023-12-28','0','0','2000000','2000000','Produksi Kerupuk Kemplang Bakar',NULL,38,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:20:22',NULL,NULL),
(197,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','JUal Sayur Keliling Dan Warung Kecil',NULL,37,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:22:48',NULL,NULL),
(198,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Penjual Jamu Dari Pasar Ke Pasar',NULL,36,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:24:55',NULL,NULL),
(199,'Masjid',NULL,NULL,'2023-12-28','0','0','1500000','1500000','Produksi Kemplang Bakar ',NULL,33,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:26:48',NULL,NULL),
(200,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Usaha Jual Nasi Uduk Dengan Gerobak',NULL,32,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:28:51',NULL,NULL),
(201,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','JUal Nasi Uduk / Gorengan Dll',NULL,31,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:30:48',NULL,NULL),
(202,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','Usaha Juala Baju Online Dan Langsung',NULL,28,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:32:38',NULL,NULL),
(203,'Masjid',NULL,NULL,'2023-12-28','0','0','2500000','2500000','Warung Kecil',NULL,27,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-14 22:34:40',NULL,NULL),
(204,'Masjid',NULL,NULL,'2023-12-28','0','0','3000000','3000000','Peternakan Ayam','',26,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-14 22:36:24','2024-02-01 16:45:25',NULL),
(205,'Masjid',NULL,NULL,'2024-01-27','208500','0','2291500','0','CICILAN KE 1 (TRANSFER)\r\n','b6bfa8910aeca651baef05a3508ea113.png',28,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-27 09:36:35','2024-02-01 14:03:34','3672300'),
(206,'Masjid',NULL,NULL,'2024-01-30','250000','0','2750000','0','CICILAN KE 1 (TUNAI)','',76,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 02:18:45','2024-02-01 14:02:51','3672300'),
(207,'Masjid',NULL,NULL,'2024-01-30','200000','0','1800000','0','CICILAN KE 1 (TUNAI)\r\n','',53,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 02:22:38','2024-02-01 13:51:39','3672300'),
(208,'Masjid',NULL,NULL,'2024-01-30','250000','0','2750000','0','CICILAN KE 1 (TRANSFER)','563765a228ad3c47a1105acc8aad4297.jpeg',94,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 03:01:21','2024-02-01 13:50:58','3672300'),
(209,'Masjid',NULL,NULL,'2024-01-30','208500','6500','2291500','0','CICILAN KE-1 (TUNAI) Dan Infaq Rp.6.500','',27,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 03:07:07','2024-02-20 20:05:20','3672300'),
(210,'Masjid',NULL,NULL,'2024-01-30','1000000','10000','2000000','0','CICILAN KE-1 (TUNAI) Dan Infaq Rp.10.000','',61,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 09:03:25','2024-02-20 20:03:59','3672300'),
(211,'Masjid',NULL,NULL,'2024-01-30','250000','0','2750000','0','CICILAN KE-1 (TUNAI)','',63,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 09:15:19','2024-02-01 13:48:46','3672300'),
(212,'Masjid',NULL,NULL,'2024-01-30','210000','0','2290000','0','CICILAN KE-1 (TUNAI)','',37,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 09:15:50','2024-02-01 13:48:38','3672300'),
(213,'Masjid',NULL,NULL,'2024-01-30','250000','10000','2750000','0','CICILAN KE-1 (TUNAI) Dan Infaq Rp.10.000','',64,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 09:25:33','2024-02-20 20:03:29','3672300'),
(214,'Masjid',NULL,NULL,'2024-01-30','250000','10000','2750000','0','CICILAN KE-1 (TRANSFER) Dan Infaq Rp.10.000','9b8a858abb6ad1793ea00911553234fb.jpeg',96,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 09:31:12','2024-02-20 20:03:09','3672300'),
(215,'Masjid',NULL,NULL,'2024-01-30','150000','10000','850000','0','CICILAN KE-1 (TUNAI) Dan Infaq Rp.10.000','',57,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-30 12:30:54','2024-02-20 20:02:52','3672300'),
(216,'Masjid',NULL,NULL,'2024-01-23','250000','0','2750000','0','CICILAN KE-1 (TRANSFER)','',95,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-31 05:32:42','2024-02-01 13:26:37','3672300'),
(217,'Masjid',NULL,NULL,'2024-01-31','170000','0','1830000','0','CICILAN KE-1 (TUNAI)','',81,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-31 09:53:59','2024-02-01 13:25:41','3672300'),
(218,'Masjid',NULL,NULL,'2024-01-31','250000','20000','2750000','0','CICILAN KE-1 (TUNAI) Dan Infaq Rp.20.000','',102,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-01-31 10:36:09','2024-02-20 20:02:36','3672300'),
(227,'Masjid',NULL,NULL,'2024-02-01','250000','10000','2750000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',36,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:46:20','2024-02-20 20:02:22','3672300'),
(228,'Masjid',NULL,NULL,'2024-02-01','200000','20000','1800000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 20.000','',38,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:47:10','2024-02-20 20:02:02','3672300'),
(229,'Masjid',NULL,NULL,'2024-02-01','200000','10000','1800000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',41,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:48:15','2024-02-20 20:01:40','3672300'),
(230,'Masjid',NULL,NULL,'2024-02-01','125000','5000','1375000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 5.000','',42,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:49:17','2024-02-20 20:01:21','3672300'),
(231,'Masjid',NULL,NULL,'2024-02-01','250000','50000','2750000','0','CICILAN KE-1 (TUNAI)\r\nDa Infaq Rp. 50.000','',45,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:50:00','2024-02-20 20:01:00','3672300'),
(232,'Masjid',NULL,NULL,'2024-02-01','210000','0','2290000','0','CICILAN KE-1 (TUNAI)','',48,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:50:52',NULL,'3672300'),
(233,'Masjid',NULL,NULL,'2024-02-01','200000','10000','1800000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',55,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:52:37','2024-02-20 20:00:35','3672300'),
(234,'Masjid',NULL,NULL,'2024-02-01','250000','10000','2750000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp.10.000','',68,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:53:32','2024-02-20 20:00:18','3672300'),
(235,'Masjid',NULL,NULL,'2024-02-01','200000','0','1800000','0','CICILAN KE-1 (TRANSFER)','',82,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:54:14',NULL,'3672300'),
(236,'Masjid',NULL,NULL,'2024-02-01','150000','10000','1350000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp 10.000','',84,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:55:13','2024-02-20 19:59:55','3672300'),
(237,'Masjid',NULL,NULL,'2024-02-01','167000','3000','1833000','0','CICILAN KE-1 (Tunai)\r\nDAn Infaq Rp 3.000','',98,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 10:56:06','2024-02-20 19:59:38','3672300'),
(238,'Masjid',NULL,NULL,'2024-02-01','150000','10000','1350000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',33,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-01 14:09:20','2024-02-20 19:59:17','3672300'),
(239,'Masjid',NULL,NULL,'2024-02-02','210000','10000','2290000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',51,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 01:58:42','2024-02-20 19:58:58','3672300'),
(240,'Masjid',NULL,NULL,'2024-02-02','250000','0','2750000','0','CICILAN KE-1 (TUNAI)','',104,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 02:06:16',NULL,'3672300'),
(241,'Masjid',NULL,NULL,'2024-02-02','200000','20000','1800000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 20.000','',93,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 02:07:57','2024-02-20 19:58:42','3672300'),
(242,'Masjid',NULL,NULL,'2024-02-02','200000','0','1800000','0','CICILAN KE-1 (TUNAI)','',74,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 03:23:35',NULL,'3672300'),
(243,'Masjid',NULL,NULL,'2024-02-02','250000','0','2750000','0','CICILAN KE-1 (TUNAI)','',32,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 06:19:51',NULL,'3672300'),
(244,'Masjid',NULL,NULL,'2024-02-02','250000','10000','2750000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp.10.000','',65,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 06:22:22','2024-02-20 19:58:14','3672300'),
(245,'Masjid',NULL,NULL,'2024-02-02','250000','0','2750000','0','CICILAN KE-1 (TUNAI)','',69,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 06:53:49',NULL,'3672300'),
(246,'Masjid',NULL,NULL,'2024-02-02','250000','10000','2750000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',43,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 11:53:29','2024-02-20 19:57:44','3672300'),
(247,'Masjid',NULL,NULL,'2024-02-02','250000','10000','2750000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',87,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 11:54:10','2024-02-20 19:57:29','3672300'),
(248,'Masjid',NULL,NULL,'2024-02-02','125000','10000','1375000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 10.000','',78,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 11:54:53','2024-02-20 19:57:11','3672300'),
(249,'Masjid',NULL,NULL,'2024-02-02','210000','0','2290000','0','CICILAN KE-1 (TUNAI)','',31,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 12:33:16',NULL,'3672300'),
(250,'Masjid',NULL,NULL,'2024-02-02','250000','0','2750000','0','CICILAN KE-1 (TRANSFER)','',97,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-02 13:13:18',NULL,'3672300'),
(251,'Masjid',NULL,NULL,'2024-02-03','300000','30000','2700000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 30.000','',101,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-03 06:36:30','2024-02-20 19:56:49','3672300'),
(252,'Masjid',NULL,NULL,'2024-02-03','250000','20000','2750000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 20.000','',90,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-03 10:54:04','2024-02-20 19:56:34','3672300'),
(253,'Masjid',NULL,NULL,'2024-02-03','250000','20000','2250000','0','CICILAN KE-1 (TUNAI)\r\nDan Infaq Rp. 20.000','',46,NULL,13,NULL,1,'M367230004042023872',NULL,NULL,'36','3672','367208','3672081007','2024-02-03 12:08:43','2024-02-20 19:56:22','3672300');

CREATE TABLE `bmfi_skoring_asesmen_peserta_bmm` (
  `id_bmfi_skoring_asesmen_peserta_bmm` int NOT NULL,
  `tgl_bmfi_skoring_asesmen_peserta_bmm` date DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `hash_blockchain_peserta_bmm` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hash_blockchain_masjid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_peserta_program_bmfi_bmm` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_masjid_bmfi_bmm` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_user_login_peserta_microfinance_bmm` int NOT NULL,
  `1_experience_in_proposed_business` int NOT NULL,
  `2_growth_potential` int NOT NULL,
  `3_monthly_saving_capacity` int NOT NULL,
  `4_capital_turnover` int NOT NULL,
  `5_payment_technique` int NOT NULL,
  `6_sales_payment_facility` int NOT NULL,
  `7_average_revenue` int NOT NULL,
  `8_consumer_demand_level` int NOT NULL,
  `9_sales_reach` int NOT NULL,
  `10_pendidikan` int NOT NULL,
  `11_usia` int NOT NULL,
  `Total_skor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `Hasil_keterangan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `Rekomendasi_plafon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Tabel ini kosong di dump asli (belum pernah dipakai) — Total_skor,
-- Hasil_keterangan, dan Rekomendasi_plafon diisi MANUAL oleh Admin
-- Masjid di Phase 1 (lihat catatan formula skor yang belum terverifikasi
-- di README rencana implementasi).
