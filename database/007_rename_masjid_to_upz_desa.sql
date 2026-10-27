-- 007: Rename penuh skema masjid -> upz_desa (UPZ Desa)
-- Jalankan pada DB lama yang masih memakai nama kolom/tabel *_masjid.

SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `masjid` RENAME TO `upz_desa`;
ALTER TABLE `pengajuan_masjid` RENAME TO `pengajuan_upz_desa`;

ALTER TABLE `acara` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `acara` RENAME COLUMN `nama_masjid` TO `nama_upz_desa`;
ALTER TABLE `bmfi_pemutihan_penghapusbukuan` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `bmfi_pendamping` RENAME COLUMN `hash_blockchain_masjid` TO `hash_blockchain_upz_desa`;
ALTER TABLE `bmfi_peserta_program` RENAME COLUMN `hash_blockchain_masjid` TO `hash_blockchain_upz_desa`;
ALTER TABLE `bmfi_sejarah_dan_profil_microfinance` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `bmfi_skoring_asesmen_peserta_bmm` RENAME COLUMN `hash_blockchain_masjid` TO `hash_blockchain_upz_desa`;
ALTER TABLE `bmfi_skoring_asesmen_peserta_bmm` RENAME COLUMN `id_masjid_bmfi_bmm` TO `id_upz_desa_bmfi_bmm`;
ALTER TABLE `bmfi_transaksi_microfinance` RENAME COLUMN `hash_blockchain_masjid` TO `hash_blockchain_upz_desa`;
ALTER TABLE `bmfi_transaksi_microfinance` RENAME COLUMN `nama_masjid` TO `nama_upz_desa`;
ALTER TABLE `bukukas_pengeluaran` RENAME COLUMN `nama_masjid` TO `nama_upz_desa`;
ALTER TABLE `cerita` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `donasi` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `kajian` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `kajian` RENAME COLUMN `nama_masjid` TO `nama_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `nama_masjid` TO `nama_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `jenis_masjid` TO `jenis_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `alamat_masjid` TO `alamat_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `status_masjid` TO `status_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `slug_masjid` TO `slug_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `nohp_masjid` TO `nohp_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `email_masjid` TO `email_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `web_masjid` TO `web_upz_desa`;
ALTER TABLE `upz_desa` RENAME COLUMN `video_masjid` TO `video_upz_desa`;
ALTER TABLE `pengajuan_upz_desa` RENAME COLUMN `nama_masjid` TO `nama_upz_desa`;
ALTER TABLE `pengajuan_upz_desa` RENAME COLUMN `alamat_masjid` TO `alamat_upz_desa`;
ALTER TABLE `pengajuan_upz_desa` RENAME COLUMN `email_masjid` TO `email_upz_desa`;
ALTER TABLE `pengajuan_upz_desa` RENAME COLUMN `nohp_masjid` TO `nohp_upz_desa`;
ALTER TABLE `pengajuan_upz_desa` RENAME COLUMN `slug_masjid` TO `slug_upz_desa`;
ALTER TABLE `pengumuman` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `postingan` RENAME COLUMN `id_masjid` TO `id_upz_desa`;
ALTER TABLE `publikasi` RENAME COLUMN `id_masjid` TO `id_upz_desa`;


-- Index lama masih bernama id_masjid / nama_masjid
ALTER TABLE `bmfi_pemutihan_penghapusbukuan` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `bmfi_pendamping` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `bmfi_peserta_program` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `bmfi_pilihan_skema_pinjaman` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `bmfi_saldo_microfinance` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `bmfi_sejarah_dan_profil_microfinance` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `cerita` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `document` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `donasi` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `fasilitas` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `images` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `kajian` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `kegiatan` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `logo` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `medsos` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `mitra_kerjasama` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `pengumuman` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `postingan` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `qris` RENAME INDEX `id_masjid` TO `id_upz_desa`;
ALTER TABLE `upz_desa` RENAME INDEX `nama_masjid` TO `nama_upz_desa`;

-- Nilai enum jenis UPZ Desa (sebelumnya Masjid/Musholla)
ALTER TABLE `upz_desa` MODIFY `jenis_upz_desa` ENUM('UPZ Desa','UPZ Kelurahan','UPZ Kecamatan') NULL;

-- Nilai data lama
UPDATE `users` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `jenis_dana` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `jenis_harta` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `label_info_dana` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `jenis_laporan` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `jenis_perolehan_aset` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `bmfi_jenis_angsuran` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `bmm_label_info_dana_kas_operasional` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `bmm_label_penggunaan_kas_operasional` SET `parent_id` = 'UPZ Desa' WHERE `parent_id` = 'Masjid';
UPDATE `bukukas_penerimaan` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `bukukas_pengeluaran` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `buku_aset` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `berkas_laporan` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `bmfi_peserta_program` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `bmfi_transaksi_microfinance` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `bmfi_bmm_operasional_kas_penerimaan` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `bmfi_bmm_operasional_kas_pengeluaran` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';
UPDATE `saran_dan_masukan` SET `jalur_akses` = 'UPZ Desa' WHERE `jalur_akses` = 'Masjid';

-- Nama level legacy
UPDATE `levels` SET `nama_level` = 'Admin UPZ Desa' WHERE `id_level` = 5;
UPDATE `levels` SET `nama_level` = 'Admin Monitor UPZ Desa' WHERE `id_level` = 6;

SET FOREIGN_KEY_CHECKS = 1;
