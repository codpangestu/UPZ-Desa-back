from sqlalchemy.orm import Session
from typing import Optional
from datetime import date
from shared.models.transaksi import BukukasPenerimaan, BukukasPengeluaran
from shared.models.master import JenisDana, JenisHarta, LabelInfoDana
from shared.models.masjid import Masjid


def list_penerimaan(
    db: Session,
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    id_jenis_dana: Optional[int] = None,
    id_label_info_dana: Optional[int] = None,
    start_date: Optional[date] = None,
    end_date: Optional[date] = None,
    page: int = 1,
    per_page: int = 10,
) -> tuple:
    q = db.query(
        BukukasPenerimaan, JenisDana.nama_jenis_dana,
        JenisHarta.nama_jenis_harta, LabelInfoDana.nama_label_info_dana,
        Masjid.nama_masjid, Masjid.alamat_masjid, Masjid.id_masjid,
    ).outerjoin(JenisDana, BukukasPenerimaan.id_jenis_dana == JenisDana.id_jenis_dana
    ).outerjoin(JenisHarta, BukukasPenerimaan.id_jenis_harta == JenisHarta.id_jenis_harta
    ).outerjoin(LabelInfoDana, BukukasPenerimaan.id_label_info_dana == LabelInfoDana.id_label_info_dana
    ).outerjoin(Masjid, BukukasPenerimaan.id_jalur_akses == Masjid.id_masjid)

    if kode_org_baznas:
        q = q.filter(BukukasPenerimaan.kode_org_baznas == kode_org_baznas)
    if id_provinsi:
        q = q.filter(Masjid.id_provinsi == id_provinsi)
    if id_kabupaten_kota:
        q = q.filter(Masjid.id_kabupaten_kota == id_kabupaten_kota)
    if id_kecamatan:
        q = q.filter(Masjid.id_kecamatan == id_kecamatan)
    if id_jenis_dana:
        q = q.filter(BukukasPenerimaan.id_jenis_dana == id_jenis_dana)
    if id_label_info_dana:
        q = q.filter(BukukasPenerimaan.id_label_info_dana == id_label_info_dana)
    if start_date:
        q = q.filter(BukukasPenerimaan.tanggal >= start_date)
    if end_date:
        q = q.filter(BukukasPenerimaan.tanggal <= end_date)

    total = q.count()
    data = q.order_by(BukukasPenerimaan.created_at.desc()
    ).offset((page - 1) * per_page).limit(per_page).all()
    return data, total


def list_penerimaan_cc(
    db: Session,
    kode_org_baznas: Optional[str] = None,
    page: int = 1,
    per_page: int = 10,
) -> list:
    q = db.query(BukukasPenerimaan).outerjoin(
        Masjid, BukukasPenerimaan.id_jalur_akses == Masjid.id_masjid
    )
    if kode_org_baznas:
        q = q.filter(BukukasPenerimaan.kode_org_baznas == kode_org_baznas)
    return q.order_by(BukukasPenerimaan.created_at.desc()
    ).offset((page - 1) * per_page).limit(per_page).all()


def list_pengeluaran(
    db: Session,
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    id_jenis_dana: Optional[int] = None,
    id_label_info_dana: Optional[int] = None,
    start_date: Optional[date] = None,
    end_date: Optional[date] = None,
    page: int = 1,
    per_page: int = 10,
) -> tuple:
    q = db.query(
        BukukasPengeluaran, JenisDana.nama_jenis_dana,
        JenisHarta.nama_jenis_harta, LabelInfoDana.nama_label_info_dana,
        Masjid.nama_masjid, Masjid.alamat_masjid, Masjid.id_masjid,
    ).outerjoin(JenisDana, BukukasPengeluaran.id_jenis_dana == JenisDana.id_jenis_dana
    ).outerjoin(JenisHarta, BukukasPengeluaran.id_jenis_harta == JenisHarta.id_jenis_harta
    ).outerjoin(LabelInfoDana, BukukasPengeluaran.id_label_info_dana == LabelInfoDana.id_label_info_dana
    ).outerjoin(Masjid, BukukasPengeluaran.id_jalur_akses == Masjid.id_masjid)

    if kode_org_baznas:
        q = q.filter(BukukasPengeluaran.kode_org_baznas == kode_org_baznas)
    if id_provinsi:
        q = q.filter(Masjid.id_provinsi == id_provinsi)
    if id_kabupaten_kota:
        q = q.filter(Masjid.id_kabupaten_kota == id_kabupaten_kota)
    if id_kecamatan:
        q = q.filter(Masjid.id_kecamatan == id_kecamatan)
    if id_jenis_dana:
        q = q.filter(BukukasPengeluaran.id_jenis_dana == id_jenis_dana)
    if id_label_info_dana:
        q = q.filter(BukukasPengeluaran.id_label_info_dana == id_label_info_dana)
    if start_date:
        q = q.filter(BukukasPengeluaran.tanggal >= start_date)
    if end_date:
        q = q.filter(BukukasPengeluaran.tanggal <= end_date)

    total = q.count()
    data = q.order_by(BukukasPengeluaran.created_at.desc()
    ).offset((page - 1) * per_page).limit(per_page).all()
    return data, total


def list_pengeluaran_cc(
    db: Session,
    kode_org_baznas: Optional[str] = None,
    page: int = 1,
    per_page: int = 10,
) -> list:
    q = db.query(BukukasPengeluaran).outerjoin(
        Masjid, BukukasPengeluaran.id_jalur_akses == Masjid.id_masjid
    )
    if kode_org_baznas:
        q = q.filter(BukukasPengeluaran.kode_org_baznas == kode_org_baznas)
    return q.order_by(BukukasPengeluaran.created_at.desc()
    ).offset((page - 1) * per_page).limit(per_page).all()
