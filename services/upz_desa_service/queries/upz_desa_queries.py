from sqlalchemy.orm import Session, joinedload
from sqlalchemy import func
from typing import Optional
from shared.models.upz_desa import UpzDesa
from shared.models.users import Users
from shared.models.transaksi import BukukasPenerimaan, BukukasPengeluaran
from shared.models.master import Levels, Labels


def get_upz_desa_by_id(db: Session, id_upz_desa: str) -> UpzDesa | None:
    return db.query(UpzDesa).filter(UpzDesa.id_upz_desa == id_upz_desa).first()


def get_upz_desa_by_slug(db: Session, slug: str) -> UpzDesa | None:
    return db.query(UpzDesa).filter(UpzDesa.slug_upz_desa == slug).first()


def get_list_upz_desa(
    db: Session,
    page: int = 1,
    per_page: int = 10,
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    keyword_nama: Optional[str] = None,
    keyword_alamat: Optional[str] = None,
    keyword_label: Optional[str] = None,
) -> tuple:
    q = db.query(UpzDesa)
    if kode_org_baznas:
        q = q.filter(UpzDesa.kode_org_baznas == kode_org_baznas)
    if id_provinsi:
        q = q.filter(UpzDesa.id_provinsi == id_provinsi)
    if id_kabupaten_kota:
        q = q.filter(UpzDesa.id_kabupaten_kota == id_kabupaten_kota)
    if id_kecamatan:
        q = q.filter(UpzDesa.id_kecamatan == id_kecamatan)
    if keyword_nama:
        q = q.filter(UpzDesa.nama_upz_desa.like(f"%{keyword_nama}%"))
    if keyword_alamat:
        q = q.filter(UpzDesa.alamat_upz_desa.like(f"%{keyword_alamat}%"))
    if keyword_label:
        q = q.filter(UpzDesa.label_upz.like(f"%{keyword_label}%"))

    total = q.count()
    data = q.order_by(UpzDesa.created_at.desc()).offset((page - 1) * per_page).limit(per_page).all()
    return data, total


def get_upz_desa_by_wilayah(
    db: Session,
    id_provinsi: int,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    keyword_nama_upz_desa: Optional[str] = None,
    keyword_alamat_upz_desa: Optional[str] = None,
    keyword_label_upz_desa: Optional[str] = None,
    page: int = 1,
    per_page: int = 10,
) -> list:
    q = db.query(
        UpzDesa.id_upz_desa, UpzDesa.nama_upz_desa, UpzDesa.kode_org_baznas,
        UpzDesa.jenis_upz_desa, UpzDesa.tipologi, UpzDesa.alamat_upz_desa,
        UpzDesa.daya_tampung, UpzDesa.label_upz, UpzDesa.noregis_simas,
        UpzDesa.slug_upz_desa, UpzDesa.id_provinsi, UpzDesa.id_kabupaten_kota,
        func.coalesce(func.sum(BukukasPenerimaan.nominal_masuk), 0).label("total_penerimaan"),
        func.coalesce(func.sum(BukukasPengeluaran.nominal_keluar), 0).label("total_pengeluaran"),
    ).outerjoin(
        BukukasPenerimaan, UpzDesa.id_upz_desa == BukukasPenerimaan.id_jalur_akses
    ).outerjoin(
        BukukasPengeluaran, UpzDesa.id_upz_desa == BukukasPengeluaran.id_jalur_akses
    ).filter(UpzDesa.id_provinsi == id_provinsi)

    if id_kabupaten_kota:
        q = q.filter(UpzDesa.id_kabupaten_kota == id_kabupaten_kota)
    if id_kecamatan:
        q = q.filter(UpzDesa.id_kecamatan == id_kecamatan)
    if keyword_nama_upz_desa:
        q = q.filter(UpzDesa.nama_upz_desa.like(f"%{keyword_nama_upz_desa}%"))
    if keyword_alamat_upz_desa:
        q = q.filter(UpzDesa.alamat_upz_desa.like(f"%{keyword_alamat_upz_desa}%"))
    if keyword_label_upz_desa:
        q = q.filter(UpzDesa.label_upz.like(f"%{keyword_label_upz_desa}%"))

    q = q.group_by(UpzDesa.id_upz_desa).order_by(UpzDesa.created_at.desc())
    q = q.offset((page - 1) * per_page).limit(per_page)
    return q.all()


def get_pengurus_by_upz_desa(db: Session, id_upz_desa: str) -> list:
    return db.query(Users, Levels, Labels, UpzDesa).join(
        Levels, Users.id_level == Levels.id_level, isouter=True
    ).join(
        Labels, Users.id_labels == Labels.id_labels, isouter=True
    ).join(
        UpzDesa, UpzDesa.id_upz_desa == Users.id_jalur_akses, isouter=True
    ).filter(Users.id_jalur_akses == id_upz_desa).all()


def search_upz_desa_public(
    db: Session,
    nama: Optional[str] = None,
    jenis: Optional[str] = None,
    tipologi: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    limit: int = 50,
) -> list:
    q = db.query(UpzDesa).filter(UpzDesa.status_aktif == 1)
    if nama:
        q = q.filter(UpzDesa.nama_upz_desa.like(f"%{nama}%"))
    if jenis:
        q = q.filter(UpzDesa.jenis_upz_desa == jenis)
    if tipologi:
        q = q.filter(UpzDesa.tipologi == tipologi)
    if id_provinsi:
        q = q.filter(UpzDesa.id_provinsi == id_provinsi)
    if id_kabupaten_kota:
        q = q.filter(UpzDesa.id_kabupaten_kota == id_kabupaten_kota)
    if id_kecamatan:
        q = q.filter(UpzDesa.id_kecamatan == id_kecamatan)
    return q.order_by(UpzDesa.created_at.desc()).limit(limit).all()
