# Context Backend UPZ Desa

## Deskripsi
Backend service untuk aplikasi UPZ Desa (Unit Pengumpul Zakat desa) - bagian dari ekosistem BAZNAS.

## Services
| Service | Port | Deskripsi |
|---------|------|-----------|
| Auth Service | 8001 | Autentikasi, login, logout, token management |
| UPZ Desa Service | 8002 | Data UPZ Desa, detail, pengurus, rekap |
| Content Service | 8003 | Konten, cerita, postingan, detail UPZ Desa publik |
| Transaction Service | 8004 | Buku kas, microfinance |
| Admin Service | 8005 | Pengajuan, master data, admin management |

## Configuration
- **Database**: MySQL/MariaDB, database name: `upzdesa_revamp`
- **API Key**: `upzdesa23apikey` (header x-api-key)
- **Secret Key**: `upzdesa-secret-key-change-in-production`
- **Default Password**: `upzdesa23`

## Struktur
```
backend/
├── services/
│   ├── auth_service/         # Auth routes & business logic
│   ├── admin_service/        # Admin, pengajuan & master data CRUD
│   ├── upz_desa_service/     # Data UPZ Desa & wilayah
│   ├── content_service/      # Content & publication
│   └── transaction_service/  # Buku kas & microfinance
├── shared/
│   ├── models/           # SQLAlchemy models
│   ├── config/           # Environment & constants
│   └── middlewares/      # Logging & auth middleware
├── database/             # Schema files, migration & seeds
└── scriptmicrofinance/   # Microfinance module (legacy)
```

## Migration Notes
- Namespace/API key telah diganti dari `menara` ke `upzdesa`.
- Model `KategoriMenaraVideo` → `KategoriUpzVideo`; tabel `kategori_menara_video` → `kategori_upz_video`.
- Email seed admin: `@upzdesa.baznas.go.id`.
- **Rename penuh "Masjid" → "UPZ Desa" SUDAH SELESAI (2026-10-20)**:
  - Skema DB: tabel `masjid` → `upz_desa`, `pengajuan_masjid` → `pengajuan_upz_desa`; seluruh kolom `*_masjid` → `*_upz_desa` (termasuk `bukukas_pengeluaran.nama_upz_desa`, `postingan.id_upz_desa`, `bmfi_*`), index ikut di-rename. Migration: `database/007_rename_masjid_to_upz_desa.sql`.
  - Nilai data: `users.jalur_akses = 'UPZ Desa'`, `jenis_upz_desa` enum `('UPZ Desa','UPZ Kelurahan','UPZ Kecamatan')`, `parent_id` master data (`jenis_dana`, `jenis_harta`, `label_info_dana`, BMM) = `'UPZ Desa'`, nama level 5/6 = Admin (Monitor) UPZ Desa.
  - Kode: `services/masjid_service` → `services/upz_desa_service`; model `shared/models/upz_desa.py` + `upz_desa_detail.py`; class `Masjid` → `UpzDesa`, `PengajuanMasjid` → `PengajuanUpzDesa`.
  - Route: `/api/v1/upz-desa/{detail,list,by-wilayah,pengurus}`, `/api/v1/upz-desa/{slug}` + `/api/v1/upz-desa/search` (Content 8003), `/api/v1/admin/upz-desa/*`, `/api/v1/rekap/by-upz-desa/{id}`, `/api/v1/login/admin-upz-desa`. Path lama `/api/v1/masjid/...` sudah 404.
  - Frontend tidak lagi memakai adapter `toAppShape`/`toApiShape` (dihapus dari `frontend/src/services/api.ts`); kontrak backend dan frontend sama-sama `*_upz_desa`.
- **Data dummy**: `python -m database.seed_upz_desa_dummy` mengisi 42 UPZ Desa/Kelurahan + akun admin/pengurus, buku kas 12 bulan, kegiatan/struktur/fasilitas/bank/medsos/galeri, publikasi, buku aset, microfinance BMM, dan pengajuan. Data referensi wilayah BPS, levels/labels, jenis dana/harta, serta akun Admin BAZNAS/Pusat dipertahankan.
- Akun uji: `pangestuakbar939@gmail.com` (UPZ Al-istiqomah), `dkmalhidayah0107@gmail.com` (UPZ Jami Al-Hidayah Kubang Sepat + BMM), `baznas-provinsi@upzdesa.baznas.go.id` (Admin Daerah, level 11), `admin.kua@upzdesa.baznas.go.id` (level 13), `admin.upz01..40@upzdesa.test` (admin UPZ lain). Password semua: `upzdesa23`.
