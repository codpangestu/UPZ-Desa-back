from sqlalchemy.orm import Session
from typing import Any, Dict, List, Optional
from services.admin_service.queries.admin_queries import create_record, update_record, delete_record, list_pengajuan, sync_masjid_detail_replace
from shared.models.masjid import Masjid
from shared.models.content import (
    Informasi, Kajian, Acara, Cerita, Donasi, Postingan, Publikasi,
    PanduanPengguna, PanduanPengelola, Pengumuman,
    Repository, DokumentasiPengembangan, DokumentasiPengujian,
    DokumentasiSosialisasi, InfoPemberitahuan, Infografis,
    Inspirasi, Infovideo, Infoayat, Infohadits, Infodoa,
)
from shared.models.master import (
    Levels, Labels, JenisDana, JenisHarta, JenisLaporan,
    JenisPerolehanAset, LabelInfoDana, Tema,
    KategoriAcara, KategoriCerita, KategoriDonasi, KategoriPostingan,
    KategoriPanduanPengguna, KategoriPanduanPengelola, KategoriUpzVideo,
)
from shared.models.transaksi import (
    BukukasPenerimaan, BukukasPengeluaran, Kas, Coa,
    BukuAset, BerkasLaporan, SaranDanMasukan,
)
from shared.models.masjid_detail import (
    Fasilitas, Images, Document, Bank, Medsos, Logo,
    MitraKerjasama, Qris, Struktur, Kegiatan, Videos,
)
from shared.models.wilayah import Provinsi, KabupatenKota, Kecamatan, KelurahanDesa
from shared.models.pengajuan import PengajuanMasjid
from shared.models.microfinance import (
    BmfiSejarahDanProfilMicrofinance, BmfiSaldoMicrofinance,
    BmfiPilihanSkemaPinjaman, BmfiPendamping, BmfiPesertaProgram,
    BmfiTransaksiMicrofinance, BmfiSkoringAsesmenPesertaBmm,
    BmfiBmmOperasionalKasPenerimaan, BmfiBmmOperasionalKasPengeluaran,
    BmfiPemutihanPenghapusbukuan,
)
from shared.schemas.base import model_to_dict
from shared.utils.response_helper import ApiResponse

# Batas maksimal nominal pinjaman BMM (dikonfirmasi tertulis di
# "Panduan Aplikasi BAZNAS Microfinance Masjid", section Pilihan Jumlah
# Pinjaman). Berlaku untuk entri BARU saja — tidak mempengaruhi data
# historis yang sudah ada (mis. skema "PINJAMAN VIP" lama).
BMM_MAX_NOMINAL_PINJAMAN = 3_000_000


class AdminService:
    _CONTENT_MODELS = {
        "informasi": Informasi, "kajian": Kajian, "acara": Acara,
        "cerita": Cerita, "donasi": Donasi, "postingan": Postingan,
        "panduan-pengguna": PanduanPengguna, "panduan-pengelola": PanduanPengelola,
        "pengumuman": Pengumuman, "repository": Repository,
        "dokumentasi-pengembangan": DokumentasiPengembangan,
        "dokumentasi-pengujian": DokumentasiPengujian,
        "dokumentasi-sosialisasi": DokumentasiSosialisasi,
        "info-pemberitahuan": InfoPemberitahuan, "infografis": Infografis,
        "inspirasi": Inspirasi, "infovideo": Infovideo,
        "infoayat": Infoayat, "infohadits": Infohadits, "infodoa": Infodoa,
    }

    _MASTER_MODELS = {
        "levels": Levels, "labels": Labels, "jenis-dana": JenisDana,
        "jenis-harta": JenisHarta, "jenis-laporan": JenisLaporan,
        "jenis-perolehan-aset": JenisPerolehanAset, "label-info-dana": LabelInfoDana,
        "tema": Tema, "kategori-acara": KategoriAcara, "kategori-cerita": KategoriCerita,
        "kategori-donasi": KategoriDonasi, "kategori-postingan": KategoriPostingan,
        "kategori-panduan-pengguna": KategoriPanduanPengguna,
        "kategori-panduan-pengelola": KategoriPanduanPengelola,
        "kategori-upz-video": KategoriUpzVideo,
    }

    _WILAYAH_MODELS = {
        "provinsi": Provinsi, "kabupaten-kota": KabupatenKota,
        "kecamatan": Kecamatan, "kelurahan-desa": KelurahanDesa,
    }

    _DETAIL_MODELS = {
        "fasilitas": Fasilitas, "images": Images, "dokumen": Document,
        "bank": Bank, "medsos": Medsos, "logo": Logo,
        "mitra-kerjasama": MitraKerjasama, "qris": Qris,
        "struktur": Struktur, "kegiatan": Kegiatan, "videos": Videos,
    }

    _TRANSAKSI_MODELS = {
        "bukukas-penerimaan": BukukasPenerimaan,
        "bukukas-pengeluaran": BukukasPengeluaran,
        "kas": Kas, "coa": Coa, "buku-aset": BukuAset,
        "berkas-laporan": BerkasLaporan, "saran-masukan": SaranDanMasukan,
    }

    # --- Masjid CRUD ---
    def create_masjid(self, db: Session, data: dict) -> dict:
        obj = create_record(db, Masjid, data)
        from services.admin_service.queries.admin_queries import _get_pk
        pk = _get_pk(Masjid)
        return {pk: getattr(obj, pk)}

    def update_masjid(self, db: Session, id_masjid: str, data: dict) -> bool:
        obj = update_record(db, Masjid, id_masjid, data)
        return obj is not None

    def delete_masjid(self, db: Session, id_masjid: str) -> bool:
        return delete_record(db, Masjid, id_masjid)

    # --- Content CRUD ---
    def create_content(self, db: Session, tipe: str, data: dict):
        if tipe not in self._CONTENT_MODELS:
            return None, f"Tipe konten '{tipe}' tidak dikenal"
        model = self._CONTENT_MODELS[tipe]
        obj = create_record(db, model, data)
        from services.admin_service.queries.admin_queries import _get_pk
        pk = _get_pk(model)
        return {pk: getattr(obj, pk)}, None

    def update_content(self, db: Session, tipe: str, pk_value: Any, data: dict):
        if tipe not in self._CONTENT_MODELS:
            return None, f"Tipe konten '{tipe}' tidak dikenal"
        model = self._CONTENT_MODELS[tipe]
        obj = update_record(db, model, pk_value, data)
        return obj is not None, None

    def delete_content(self, db: Session, tipe: str, pk_value: Any):
        if tipe not in self._CONTENT_MODELS:
            return False, f"Tipe konten '{tipe}' tidak dikenal"
        model = self._CONTENT_MODELS[tipe]
        return delete_record(db, model, pk_value), None

    # --- Master CRUD ---
    def create_master(self, db: Session, tipe: str, data: dict):
        if tipe not in self._MASTER_MODELS:
            return None, f"Tipe master '{tipe}' tidak dikenal"
        model = self._MASTER_MODELS[tipe]
        obj = create_record(db, model, data)
        from services.admin_service.queries.admin_queries import _get_pk
        pk = _get_pk(model)
        return {pk: getattr(obj, pk)}, None

    def update_master(self, db: Session, tipe: str, pk_value: int, data: dict):
        if tipe not in self._MASTER_MODELS:
            return None, f"Tipe master '{tipe}' tidak dikenal"
        model = self._MASTER_MODELS[tipe]
        obj = update_record(db, model, pk_value, data)
        return obj is not None, None

    def delete_master(self, db: Session, tipe: str, pk_value: int):
        if tipe not in self._MASTER_MODELS:
            return False, f"Tipe master '{tipe}' tidak dikenal"
        model = self._MASTER_MODELS[tipe]
        return delete_record(db, model, pk_value), None

    # --- Wilayah CRUD ---
    def create_wilayah(self, db: Session, tipe: str, data: dict):
        if tipe not in self._WILAYAH_MODELS:
            return None, f"Tipe wilayah '{tipe}' tidak dikenal"
        model = self._WILAYAH_MODELS[tipe]
        obj = create_record(db, model, data)
        from services.admin_service.queries.admin_queries import _get_pk
        pk = _get_pk(model)
        return {pk: getattr(obj, pk)}, None

    def update_wilayah(self, db: Session, tipe: str, pk_value: int, data: dict):
        if tipe not in self._WILAYAH_MODELS:
            return None, f"Tipe wilayah '{tipe}' tidak dikenal"
        model = self._WILAYAH_MODELS[tipe]
        obj = update_record(db, model, pk_value, data)
        return obj is not None, None

    def delete_wilayah(self, db: Session, tipe: str, pk_value: int):
        if tipe not in self._WILAYAH_MODELS:
            return False, f"Tipe wilayah '{tipe}' tidak dikenal"
        model = self._WILAYAH_MODELS[tipe]
        return delete_record(db, model, pk_value), None

    # --- Masjid Detail CRUD ---
    def create_detail(self, db: Session, tipe: str, data: dict):
        if tipe not in self._DETAIL_MODELS:
            return None, f"Tipe detail '{tipe}' tidak dikenal"
        model = self._DETAIL_MODELS[tipe]
        obj = create_record(db, model, data)
        from services.admin_service.queries.admin_queries import _get_pk
        pk = _get_pk(model)
        return {pk: getattr(obj, pk)}, None

    def update_detail(self, db: Session, tipe: str, pk_value: int, data: dict):
        if tipe not in self._DETAIL_MODELS:
            return None, f"Tipe detail '{tipe}' tidak dikenal"
        model = self._DETAIL_MODELS[tipe]
        obj = update_record(db, model, pk_value, data)
        return obj is not None, None

    def delete_detail(self, db: Session, tipe: str, pk_value: int):
        if tipe not in self._DETAIL_MODELS:
            return False, f"Tipe detail '{tipe}' tidak dikenal"
        model = self._DETAIL_MODELS[tipe]
        return delete_record(db, model, pk_value), None

    # --- Transaksi CRUD ---
    def create_transaksi(self, db: Session, tipe: str, data: dict):
        if tipe not in self._TRANSAKSI_MODELS:
            return None, f"Tipe transaksi '{tipe}' tidak dikenal"
        model = self._TRANSAKSI_MODELS[tipe]
        obj = create_record(db, model, data)
        from services.admin_service.queries.admin_queries import _get_pk
        pk = _get_pk(model)
        return {pk: getattr(obj, pk)}, None

    def update_transaksi(self, db: Session, tipe: str, pk_value: int, data: dict):
        if tipe not in self._TRANSAKSI_MODELS:
            return None, f"Tipe transaksi '{tipe}' tidak dikenal"
        model = self._TRANSAKSI_MODELS[tipe]
        obj = update_record(db, model, pk_value, data)
        return obj is not None, None

    def delete_transaksi(self, db: Session, tipe: str, pk_value: int):
        if tipe not in self._TRANSAKSI_MODELS:
            return False, f"Tipe transaksi '{tipe}' tidak dikenal"
        model = self._TRANSAKSI_MODELS[tipe]
        return delete_record(db, model, pk_value), None

    # --- Microfinance (BMM) CRUD ---
    _MICROFINANCE_MODELS = {
        "sejarah-profil": BmfiSejarahDanProfilMicrofinance,
        "saldo": BmfiSaldoMicrofinance,
        "pilihan-skema-pinjaman": BmfiPilihanSkemaPinjaman,
        "pendamping": BmfiPendamping,
        "peserta-program": BmfiPesertaProgram,
        "transaksi": BmfiTransaksiMicrofinance,
        "skoring-asesmen": BmfiSkoringAsesmenPesertaBmm,
        "operasional-kas-penerimaan": BmfiBmmOperasionalKasPenerimaan,
        "operasional-kas-pengeluaran": BmfiBmmOperasionalKasPengeluaran,
        "pemutihan": BmfiPemutihanPenghapusbukuan,
    }

    @staticmethod
    def _validate_microfinance(tipe: str, data: dict) -> Optional[str]:
        """Business rule: nominal pinjaman maks Rp3.000.000 (lihat BMM_MAX_NOMINAL_PINJAMAN)."""
        if tipe == "pilihan-skema-pinjaman":
            nominal = data.get("jumlah_nominal")
            if nominal is not None:
                try:
                    if float(nominal) > BMM_MAX_NOMINAL_PINJAMAN:
                        return f"Jumlah nominal pinjaman maksimal Rp{BMM_MAX_NOMINAL_PINJAMAN:,.0f}".replace(",", ".")
                except (TypeError, ValueError):
                    pass
        if tipe == "transaksi":
            nominal = data.get("nominal_peminjaman")
            if nominal is not None:
                try:
                    if float(nominal) > BMM_MAX_NOMINAL_PINJAMAN:
                        return f"Nominal peminjaman maksimal Rp{BMM_MAX_NOMINAL_PINJAMAN:,.0f}".replace(",", ".")
                except (TypeError, ValueError):
                    pass
        return None

    def _apply_transaksi_side_effects(self, db: Session, data: dict) -> None:
        """
        Saat transaksi pencairan (nominal_peminjaman) atau pengembalian
        (nominal_pengembalian) dicatat, perbarui saldo masjid & sisa dana
        peserta secara otomatis (bukan cuma dicatat sebagai baris ledger).
        `infak_peserta` dicatat terpisah, TIDAK menambah saldo pokok
        (mengikuti tampilan asli "Total Riwayat Penerimaan Infak" yang
        terpisah dari "Total Saldo Keseluruhan").
        """
        def to_num(v) -> float:
            try:
                return float(v) if v not in (None, "") else 0.0
            except (TypeError, ValueError):
                return 0.0

        nominal_pinjam = to_num(data.get("nominal_peminjaman"))
        nominal_kembali = to_num(data.get("nominal_pengembalian"))
        id_peserta = data.get("id_bmfi_peserta_program")
        id_jalur = data.get("id_jalur_akses") or data.get("jalur_akses")

        if id_peserta and (nominal_pinjam or nominal_kembali):
            peserta = db.query(BmfiPesertaProgram).filter(
                BmfiPesertaProgram.id_peserta_program_bmfi_asesmen == id_peserta
            ).first()
            if peserta:
                sisa = to_num(peserta.sisa_dana_pengembalian)
                sisa = sisa + nominal_pinjam - nominal_kembali
                peserta.sisa_dana_pengembalian = str(sisa)
                if nominal_pinjam:
                    peserta.jumlah_pinjaman = str(to_num(peserta.jumlah_pinjaman) + nominal_pinjam)

        if id_jalur and (nominal_pinjam or nominal_kembali):
            saldo = db.query(BmfiSaldoMicrofinance).filter(
                BmfiSaldoMicrofinance.parent_id == id_jalur
            ).order_by(BmfiSaldoMicrofinance.id_bmfi_saldo_microfinance.desc()).first()
            if saldo:
                nominal_saldo = to_num(saldo.nominal_saldo_microfinance)
                nominal_saldo = nominal_saldo - nominal_pinjam + nominal_kembali
                saldo.nominal_saldo_microfinance = str(nominal_saldo)

        db.commit()

    def create_microfinance(self, db: Session, tipe: str, data: dict):
        if tipe not in self._MICROFINANCE_MODELS:
            return None, f"Tipe microfinance '{tipe}' tidak dikenal"
        error = self._validate_microfinance(tipe, data)
        if error:
            return None, error
        model = self._MICROFINANCE_MODELS[tipe]
        if tipe == "skoring-asesmen" and not data.get("id_bmfi_skoring_asesmen_peserta_bmm"):
            # PK tabel ini bukan auto_increment di skema asli.
            max_id = db.query(model.id_bmfi_skoring_asesmen_peserta_bmm).order_by(
                model.id_bmfi_skoring_asesmen_peserta_bmm.desc()
            ).first()
            data["id_bmfi_skoring_asesmen_peserta_bmm"] = (max_id[0] + 1) if max_id else 1
        obj = create_record(db, model, data)
        if tipe == "transaksi":
            self._apply_transaksi_side_effects(db, data)
        from services.admin_service.queries.admin_queries import _get_pk
        pk = _get_pk(model)
        return {pk: getattr(obj, pk)}, None

    def update_microfinance(self, db: Session, tipe: str, pk_value: Any, data: dict):
        if tipe not in self._MICROFINANCE_MODELS:
            return None, f"Tipe microfinance '{tipe}' tidak dikenal"
        error = self._validate_microfinance(tipe, data)
        if error:
            return None, error
        model = self._MICROFINANCE_MODELS[tipe]
        obj = update_record(db, model, pk_value, data)
        if obj is not None and tipe == "transaksi":
            self._apply_transaksi_side_effects(db, data)
        return obj is not None, None

    def delete_microfinance(self, db: Session, tipe: str, pk_value: Any):
        if tipe not in self._MICROFINANCE_MODELS:
            return False, f"Tipe microfinance '{tipe}' tidak dikenal"
        model = self._MICROFINANCE_MODELS[tipe]
        return delete_record(db, model, pk_value), None

    # --- Publikasi (dashboard Menu Publikasi) — tabel unified `publikasi` (Fix #49) ---
    JENIS_PUBLIKASI = (
        "postingan", "materi-khutbah", "media-info", "jadwal-kajian",
        "video-kajian", "cerita", "donasi", "acara",
    )

    @staticmethod
    def _format_publikasi(obj) -> dict:
        """Serialize baris publikasi — id alias + angka Decimal -> float untuk JSON."""
        d = model_to_dict(obj)
        d["id"] = d.get("id_publikasi")
        for f in ("target_dana", "dana_terkumpul"):
            if d.get(f) is not None:
                d[f] = float(d[f])
        return d

    def list_publikasi(self, db: Session, jenis: str, page: int = 1, per_page: int = 10, search: str = None):
        if jenis not in self.JENIS_PUBLIKASI:
            return None, f"Jenis publikasi '{jenis}' tidak dikenal"
        q = db.query(Publikasi).filter(Publikasi.jenis == jenis)
        if search:
            like = f"%{search}%"
            q = q.filter(Publikasi.judul.like(like))
        total = q.count()
        rows = (
            q.order_by(Publikasi.id_publikasi.desc())
            .offset((page - 1) * per_page)
            .limit(per_page)
            .all()
        )
        total_pages = (total + per_page - 1) // per_page if total else 1
        return {
            "status": True,
            "data": [self._format_publikasi(r) for r in rows],
            "pagination": {"page": page, "per_page": per_page, "total": total, "total_pages": total_pages},
        }, None

    def create_publikasi(self, db: Session, jenis: str, data: dict, current_user=None):
        if jenis not in self.JENIS_PUBLIKASI:
            return None, f"Jenis publikasi '{jenis}' tidak dikenal"
        judul = (data.get("judul") or "").strip()
        if not judul:
            return None, "Judul wajib diisi"
        status = data.get("status_aktif", 1)
        if status not in (0, 1, "0", "1"):
            return None, "status_aktif hanya boleh 0 (nonaktif) atau 1 (aktif)"
        obj = Publikasi(
            jenis=jenis,
            judul=judul,
            keterangan=data.get("keterangan") or None,
            gambar=data.get("gambar") or None,
            status_aktif=int(status),
            konten=data.get("konten") or None,
            kategori=data.get("kategori") or None,
            pemateri=data.get("pemateri") or None,
            tanggal=data.get("tanggal") or None,
            url=data.get("url") or None,
            tipe_media=data.get("tipe_media") or None,
            hari=data.get("hari") or None,
            jam=data.get("jam") or None,
            lokasi=data.get("lokasi") or None,
            video_url=data.get("video_url") or None,
            target_dana=data.get("target_dana") or None,
            dana_terkumpul=data.get("dana_terkumpul") or 0,
            tanggal_berakhir=data.get("tanggal_berakhir") or None,
            tanggal_mulai=data.get("tanggal_mulai") or None,
            tanggal_selesai=data.get("tanggal_selesai") or None,
            penulis=data.get("penulis") or None,
            # provenance — dari sesi login (frontend belum kirim, backend siap duluan)
            id_masjid=(current_user.id_jalur_akses if current_user else None) or None,
            id_user=(current_user.id_user if current_user else None),
        )
        obj.slug = self._make_publikasi_slug(db, judul, jenis, obj.id_publikasi)
        db.add(obj)
        db.commit()
        db.refresh(obj)
        return self._format_publikasi(obj), None

    def update_publikasi(self, db: Session, jenis: str, pk_value: int, data: dict):
        if jenis not in self.JENIS_PUBLIKASI:
            return False, f"Jenis publikasi '{jenis}' tidak dikenal"
        obj = db.query(Publikasi).filter(
            Publikasi.id_publikasi == pk_value, Publikasi.jenis == jenis
        ).first()
        if not obj:
            return False, None  # 404 — baris tidak ada / jenis tidak cocok
        status = data.get("status_aktif", obj.status_aktif)
        if status not in (0, 1, "0", "1"):
            return False, "status_aktif hanya boleh 0 (nonaktif) atau 1 (aktif)"
        # kolom yang boleh di-update (judul ikut — kalau kosong dipertahankan)
        editable = [
            "judul", "keterangan", "gambar", "konten", "kategori", "pemateri",
            "tanggal", "url", "tipe_media", "hari", "jam", "lokasi",
            "video_url", "target_dana", "dana_terkumpul", "tanggal_berakhir",
            "tanggal_mulai", "tanggal_selesai", "penulis",
        ]
        for key in editable:
            if key in data and data[key] is not None:
                setattr(obj, key, data[key])
        obj.status_aktif = int(status)
        db.commit()
        db.refresh(obj)
        return True, None

    def delete_publikasi(self, db: Session, jenis: str, pk_value: int):
        if jenis not in self.JENIS_PUBLIKASI:
            return False, f"Jenis publikasi '{jenis}' tidak dikenal"
        obj = db.query(Publikasi).filter(
            Publikasi.id_publikasi == pk_value, Publikasi.jenis == jenis
        ).first()
        if not obj:
            return False, None
        db.delete(obj)
        db.commit()
        return True, None

    @classmethod
    def _make_publikasi_slug(cls, db: Session, judul: str, jenis: str, pk: int | None = None) -> str:
        """Slug unik per jenis: '{judul-slug}-{jenis}-{pk|-random}'."""
        import re, secrets
        base = re.sub(r"[^a-z0-9]+", "-", (judul or jenis).lower()).strip("-") or jenis
        suffix = str(pk) if pk else secrets.token_hex(3).upper()
        slug = f"{base}-{jenis}-{suffix}".lower()
        while db.query(Publikasi).filter(Publikasi.slug == slug).first():
            slug = f"{base}-{jenis}-{secrets.token_hex(3).upper()}".lower()
        return slug

    # --- Pengajuan ---
    def list_pengajuan(self, db: Session, page: int = 1, per_page: int = 10) -> dict:
        data, total = list_pengajuan(db, page, per_page)
        items = []
        for d in data:
            item = model_to_dict(d)
            # Alias `id` (PK id_pengajuan) agar konsisten dengan tipe frontend
            item["id"] = item.get("id_pengajuan")
            items.append(item)
        return {
            "data": items,
            "total": total,
        }

    def create_pengajuan(self, db: Session, data: dict) -> dict:
        """
        Buat pengajuan masjid (langkah 1). Kode masjid & slug diterbitkan LANGSUNG
        (bukan menunggu approval) agar pemohon bisa langsung melanjutkan ke langkah 2
        (daftar admin masjid dengan kode tsb). Record masjid & user admin baru dibuat
        saat Admin BAZNAS menyetujui pengajuan.
        """
        import secrets
        from shared.models.masjid import Masjid

        # Generate kode unik (format: MSJ-XXXXXX)
        kode = None
        for _ in range(10):
            candidate = "MSJ-" + secrets.token_hex(3).upper()
            exists = db.query(Masjid).filter(Masjid.kode_org_baznas == candidate).first()
            if not exists:
                kode = candidate
                break
        if not kode:
            return {"error": "Gagal membuat kode masjid. Silakan coba lagi."}

        data["kode_org_baznas"] = kode
        data["slug_masjid"] = self._make_slug(data.get("nama_masjid"), kode)
        obj = create_record(db, PengajuanMasjid, data)
        return {"id": obj.id_pengajuan, "kode_org_baznas": kode, "slug_masjid": obj.slug_masjid}

    def update_pengajuan(self, db: Session, pk_value: int, data: dict) -> dict | None:
        """
        Update pengajuan (langkah 2): simpan data admin pemohon.
        Jika status='disetujui', otomatis AKTIVASI — membuat record masjid
        (memakai kode yang sudah diterbitkan saat create) + user Admin Masjid
        dari data pemohon, SEKALIGUS dalam sekali konfirmasi.
        Mengembalikan dict pengajuan terbaru, dict {"error": ...} jika ada
        masalah validasi, atau None jika tidak ditemukan.
        """
        status = str(data.get("status_pengajuan") or data.get("status") or "").lower()

        # Guard: aktivasi memerlukan data admin pemohon lengkap (nama + email),
        # agar tidak ada masjid teraktivasi tanpa akun Admin Masjid.
        if status == "disetujui":
            existing = db.query(PengajuanMasjid).filter(
                PengajuanMasjid.id_pengajuan == pk_value
            ).first()
            if existing is None:
                return None
            if not (existing.nama_pemohon and existing.email_pemohon):
                return {"error": "Data admin pemohon belum lengkap. Pemohon harus menyelesaikan pendaftaran admin masjid terlebih dahulu."}

        obj = update_record(db, PengajuanMasjid, pk_value, data)
        if obj is None:
            return None

        if status == "disetujui":
            self._activate_pengajuan(db, obj)
        return model_to_dict(obj)

    def _activate_pengajuan(self, db: Session, pengajuan: PengajuanMasjid) -> None:
        """Aktivasi: buat masjid + user Admin Masjid dari data pengajuan (sekali konfirmasi)."""
        from shared.models.masjid import Masjid
        from services.auth_service.queries.auth_queries import create_user, get_user_by_email
        from shared.utils.jwt_helper import get_password_hash
        from shared.config.constants import (
            DEFAULT_PASSWORD, DEFAULT_AVATAR, JALUR_MASJID, DEFAULT_USER_LEVEL,
        )

        kode = pengajuan.kode_org_baznas
        if not kode:
            return

        # 1) Buat record masjid (id_masjid = kode masjid)
        existing = db.query(Masjid).filter(Masjid.kode_org_baznas == kode).first()
        if not existing:
            masjid = Masjid(
                id_masjid=kode,
                kode_org_baznas=kode,
                nama_masjid=pengajuan.nama_masjid,
                alamat_masjid=pengajuan.alamat_masjid,
                email_masjid=pengajuan.email_pemohon or pengajuan.email_masjid,
                nohp_masjid=pengajuan.nohp_masjid,
                id_provinsi=pengajuan.id_provinsi,
                id_kabupaten_kota=pengajuan.id_kabupaten_kota,
                id_kecamatan=pengajuan.id_kecamatan,
                id_kelurahan_desa=pengajuan.id_kelurahan_desa,
                slug_masjid=pengajuan.slug_masjid or self._make_slug(pengajuan.nama_masjid, kode),
                status_aktif=1,
            )
            db.add(masjid)
            db.commit()
            db.refresh(masjid)

        # 2) Buat user Admin Masjid dari data pemohon (jika belum ada / email unik)
        email = (pengajuan.email_pemohon or "").strip()
        if email and not get_user_by_email(db, email):
            user = create_user(
                db,
                jalur_akses=JALUR_MASJID,
                id_jalur_akses=kode,
                nama=pengajuan.nama_pemohon,
                jk=pengajuan.jk_pemohon,
                nohp=pengajuan.nohp_pemohon,
                email=email,
                alamat=pengajuan.alamat_pemohon,
                kode_org_baznas=kode,
                password=get_password_hash(DEFAULT_PASSWORD),
                id_level=2,  # Admin Masjid
                id_labels=1,  # Admin
                avatar=DEFAULT_AVATAR,
                status_aktif=1,
                catatan="Dibuat via persetujuan pengajuan masjid",
            )
            pengajuan.id_user = user.id_user
            db.commit()
            db.refresh(pengajuan)

    @staticmethod
    def _make_slug(nama: str | None, suffix: str) -> str:
        """Buat slug unik dari nama masjid."""
        import re
        if not nama:
            return suffix.lower()
        base = re.sub(r"[^a-z0-9]+", "-", nama.lower()).strip("-")
        return f"{base}-{suffix.lower()}" if base else suffix.lower()

    def delete_pengajuan(self, db: Session, pk_value: int) -> bool:
        return delete_record(db, PengajuanMasjid, pk_value)

    # --- Bulk Sync Detail ---
    def sync_masjid_detail(self, db: Session, id_masjid: str, data: Dict[str, List[Dict[str, Any]]]) -> dict:
        mapping = {
            "fasilitas": Fasilitas, "images": Images, "dokumen": Document,
            "bank": Bank, "medsos": Medsos, "logo": Logo,
            "mitra_kerjasama": MitraKerjasama, "qris": Qris,
            "struktur": Struktur, "kegiatan": Kegiatan, "videos": Videos,
        }
        results = {}
        for key, model in mapping.items():
            items = data.get(key, [])
            if not items:
                continue
            created = sync_masjid_detail_replace(db, model, id_masjid, items)
            results[key] = created
        db.commit()
        return results
