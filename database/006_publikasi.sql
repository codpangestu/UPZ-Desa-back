-- ============================================================
-- 006_PUBLIKASI — Tabel unified "publikasi" untuk CRUD dashboard
-- Menu Publikasi (frontend features/menu-publikasi).
--
-- Kontrak frontend (types.ts PublikasiItem, 8 jenis):
--   postingan | materi-khutbah | media-info | jadwal-kajian |
--   video-kajian | cerita | donasi | acara
--
-- CATATAN DESAIN (Fix #49):
--   - Tabel legacy (kajian/acara/cerita/donasi/informasi/postingan/videos)
--     TIDAK disentuh — dipakai halaman publik untuk data historis.
--   - Konten BARU yang dibuat admin lewat dashboard masuk ke tabel ini
--     dengan shape yang persis sama dengan form dashboard.
--   - Halaman publik menggabungkan kedua sumber (legacy + tabel ini).
-- ============================================================

CREATE TABLE IF NOT EXISTS publikasi (
    id_publikasi INT AUTO_INCREMENT PRIMARY KEY,
    jenis VARCHAR(30) NOT NULL,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NULL,
    keterangan TEXT NULL,
    gambar VARCHAR(500) NULL,
    status_aktif INT DEFAULT 1,

    -- postingan / materi-khutbah
    konten TEXT NULL,
    kategori VARCHAR(100) NULL,

    -- materi-khutbah / jadwal-kajian / video-kajian
    pemateri VARCHAR(255) NULL,
    tanggal DATE NULL,

    -- media-info
    url VARCHAR(500) NULL,
    tipe_media VARCHAR(50) NULL,

    -- jadwal-kajian
    hari VARCHAR(20) NULL,
    jam VARCHAR(10) NULL,
    lokasi VARCHAR(255) NULL,

    -- video-kajian
    video_url VARCHAR(500) NULL,

    -- donasi
    target_dana DECIMAL(15,2) NULL,
    dana_terkumpul DECIMAL(15,2) DEFAULT 0,
    tanggal_berakhir DATE NULL,

    -- acara
    tanggal_mulai DATE NULL,
    tanggal_selesai DATE NULL,

    -- cerita
    penulis VARCHAR(255) NULL,

    -- opsional provenance (belum dikirim frontend dashboard — lihat Fix #49)
    id_masjid VARCHAR(50) NULL,
    id_user INT NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_publikasi_jenis (jenis),
    INDEX idx_publikasi_status (status_aktif),
    INDEX idx_publikasi_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
