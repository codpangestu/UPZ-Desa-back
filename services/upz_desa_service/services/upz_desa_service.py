from fastapi import HTTPException
from sqlalchemy.orm import Session
from typing import Optional
from services.upz_desa_service.queries.upz_desa_queries import (
    get_upz_desa_by_id, get_upz_desa_by_slug, get_list_upz_desa,
    get_upz_desa_by_wilayah, get_pengurus_by_upz_desa, search_upz_desa_public,
)
from services.upz_desa_service.queries.rekap_queries import (
    get_rekap_penerimaan, get_rekap_pengeluaran, get_rekap_by_upz_desa,
    get_rekap_bulanan_by_upz_desa,
)
from shared.schemas.base import model_to_dict


class UpzDesaService:
    def get_detail(self, db: Session, id_upz_desa: str):
        upz_desa = get_upz_desa_by_id(db, id_upz_desa)
        if not upz_desa:
            raise HTTPException(status_code=404, detail="UPZ Desa tidak ditemukan")
        return model_to_dict(upz_desa)

    def get_list(self, db: Session, page: int = 1, per_page: int = 10,
                 kode_org_baznas: Optional[str] = None,
                 id_provinsi: Optional[int] = None,
                 id_kabupaten_kota: Optional[int] = None,
                 id_kecamatan: Optional[int] = None,
                 keyword_nama: Optional[str] = None,
                 keyword_alamat: Optional[str] = None,
                 keyword_label: Optional[str] = None) -> dict:
        data, total = get_list_upz_desa(
            db, page, per_page, kode_org_baznas, id_provinsi,
            id_kabupaten_kota, id_kecamatan, keyword_nama, keyword_alamat, keyword_label,
        )
        items = [
            {
                "id_upz_desa": m.id_upz_desa,
                "nama_upz_desa": m.nama_upz_desa,
                "kode_org_baznas": m.kode_org_baznas,
                "jenis_upz_desa": m.jenis_upz_desa,
                "tipologi": m.tipologi,
                "alamat_upz_desa": m.alamat_upz_desa,
                "slug_upz_desa": m.slug_upz_desa,
                "daya_tampung": m.daya_tampung,
                "label_upz": m.label_upz,
                "id_provinsi": m.id_provinsi,
                "id_kabupaten_kota": m.id_kabupaten_kota,
            }
            for m in data
        ]
        return {"data": items, "total": total, "page": page, "per_page": per_page}

    def get_by_wilayah(self, db: Session, id_provinsi: int,
                       id_kabupaten_kota: Optional[int] = None,
                       id_kecamatan: Optional[int] = None,
                       keyword_nama_upz_desa: Optional[str] = None,
                       keyword_alamat_upz_desa: Optional[str] = None,
                       keyword_label_upz_desa: Optional[str] = None,
                       page: int = 1, per_page: int = 10) -> list:
        return get_upz_desa_by_wilayah(
            db, id_provinsi, id_kabupaten_kota, id_kecamatan, keyword_nama_upz_desa,
            keyword_alamat_upz_desa, keyword_label_upz_desa, page, per_page,
        )

    def get_pengurus(self, db: Session, id_upz_desa: str) -> list:
        results = get_pengurus_by_upz_desa(db, id_upz_desa)
        data = []
        for u, lv, lb, m in results:
            data.append({
                "id_user": u.id_user,
                "nama": u.nama,
                "email": u.email,
                "nama_level": lv.nama_level if lv else None,
                "nama_labels": lb.nama_labels if lb else None,
                "id_jalur_akses": u.id_jalur_akses,
                "nama_upz_desa": m.nama_upz_desa if m else None,
            })
        return data

    def search_public(self, db: Session, nama: Optional[str] = None,
                      jenis: Optional[str] = None,
                      tipologi: Optional[str] = None,
                      id_provinsi: Optional[int] = None,
                      id_kabupaten_kota: Optional[int] = None,
                      id_kecamatan: Optional[int] = None) -> list:
        return search_upz_desa_public(
            db, nama, jenis, tipologi, id_provinsi, id_kabupaten_kota, id_kecamatan,
        )

    def get_rekap_penerimaan(self, db: Session, **kwargs):
        return get_rekap_penerimaan(db, **kwargs)

    def get_rekap_pengeluaran(self, db: Session, **kwargs):
        return get_rekap_pengeluaran(db, **kwargs)

    def get_rekap_by_upz_desa(self, db: Session, id_upz_desa: str, **kwargs):
        return get_rekap_by_upz_desa(db, id_upz_desa, **kwargs)

    def get_rekap_bulanan_by_upz_desa(self, db: Session, id_upz_desa: str, **kwargs):
        return get_rekap_bulanan_by_upz_desa(db, id_upz_desa, **kwargs)
