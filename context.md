# Context Backend UPZ Desa

## Deskripsi
Backend service untuk aplikasi UPZ Desa (Unit Pengumpul Zakat desa) - bagian dari ekosistem BAZNAS.

## Services
| Service | Port | Deskripsi |
|---------|------|-----------|
| Auth Service | 8001 | Autentikasi, login, logout, token management |
| Masjid/UPZ Desa Service | 8002 | Data UPZ Desa (masjid), detail, rekap |
| Content Service | 8003 | Konten, cerita, postingan |
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
│   ├── masjid_service/       # Data UPZ Desa (masjid) & wilayah
│   ├── content_service/      # Content & publication
│   └── transaction_service/  # Buku kas & microfinance
├── shared/
│   ├── models/           # SQLAlchemy models
│   ├── config/           # Environment & constants
│   └── middlewares/      # Logging & auth middleware
├── database/             # Schema files & seeds
└── scriptmicrofinance/   # Microfinance module (legacy)
```

## Migration Notes
- Namespace/API key telah diganti dari `menara` ke `upzdesa`
- Model `KategoriMenaraVideo` → `KategoriUpzVideo`
- Tabel `kategori_menara_video` → `kategori_upz_video`
- Email seed admin: `@upzdesa.baznas.go.id`
- **Nama masjid → UPZ Desa BELUM di-rename di backend**: kolom tabel & response masih `*_masjid` (`nama_masjid`, `id_masjid`, `alamat_masjid`, `slug_masjid`) dan path API masih `/api/v1/masjid/...`, `/api/v1/admin/masjid/...`. Frontend memakai nama `*_upz_desa` dan menerjemahkannya lewat adapter di `frontend/src/services/api.ts` (`toAppShape`/`toApiShape`). Jika backend di-rename, adapter itulah yang harus disesuaikan/dihapus.
- Perlu re-seed akun uji Admin BAZNAS/Daerah (`baznas-provinsi@upzdesa.baznas.go.id`) — login saat ini ditolak 401.
