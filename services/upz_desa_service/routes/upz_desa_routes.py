from fastapi import APIRouter, Depends, Query, Header, HTTPException
from sqlalchemy.orm import Session
from typing import Optional
from shared.config.database import get_db
from services.upz_desa_service.services.upz_desa_service import UpzDesaService
from shared.utils.route_wrapper import require_api_key
from shared.utils.response_helper import ApiResponse
from shared.middlewares.auth_middleware import get_current_user
from shared.middlewares.rbac_middleware import get_user_scope_columns
from shared.models.users import Users

router = APIRouter()
service = UpzDesaService()


def _apply_scope(current_user: Users, filters: dict) -> dict:
    """Timpa filter wilayah yang ADA di `filters` dengan scope milik current_user
    (Admin Daerah/Kab/KUA) — mengabaikan apa pun yang diminta client untuk kolom
    tsb. Kolom yang tidak di-scope (lebih spesifik dari role user) tetap bebas
    diisi client; hasilnya tetap aman karena semua filter di-AND-kan."""
    scope_cols = get_user_scope_columns(current_user)
    if not scope_cols:
        return filters
    filters = dict(filters)
    for col, val in scope_cols.items():
        if col in filters:
            filters[col] = val
    return filters


def _check_upz_desa_scope(db: Session, current_user: Users, id_upz_desa: str) -> None:
    """403 jika UPZ Desa target di luar wilayah current_user (Admin Daerah/Kab/KUA)."""
    scope_cols = get_user_scope_columns(current_user)
    if not scope_cols:
        return
    from services.upz_desa_service.queries.upz_desa_queries import get_upz_desa_by_id
    upz_desa = get_upz_desa_by_id(db, id_upz_desa)
    if not upz_desa:
        raise HTTPException(status_code=404, detail="UPZ Desa tidak ditemukan")
    for col, val in scope_cols.items():
        if getattr(upz_desa, col, None) != val:
            raise HTTPException(status_code=403, detail="UPZ Desa di luar wilayah Anda")


@router.get("/upz-desa/detail/{id_upz_desa}")
def get_detail_upz_desa(id_upz_desa: str, db: Session = Depends(get_db)):
    data = service.get_detail(db, id_upz_desa)
    return ApiResponse(data=data)


@router.get("/upz-desa/list")
def get_list_upz_desa(
    page: int = Query(1, ge=1),
    per_page: int = Query(10, ge=1, le=100),
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    keyword_nama: Optional[str] = None,
    keyword_alamat: Optional[str] = None,
    keyword_label: Optional[str] = None,
    x_api_key: Optional[str] = Header(None),
    current_user: Users = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    scoped = _apply_scope(current_user, {
        "id_provinsi": id_provinsi,
        "id_kabupaten_kota": id_kabupaten_kota,
        "id_kecamatan": id_kecamatan,
    })
    result = service.get_list(
        db, page, per_page, kode_org_baznas, scoped["id_provinsi"],
        scoped["id_kabupaten_kota"], scoped["id_kecamatan"],
        keyword_nama, keyword_alamat, keyword_label,
    )
    return ApiResponse(
        data=result["data"],
        links=f"?page={page}&per_page={per_page}",
    )


@router.get("/upz-desa/by-wilayah")
def get_upz_desa_by_wilayah(
    id_provinsi: int,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    keyword_nama_upz_desa: Optional[str] = None,
    keyword_alamat_upz_desa: Optional[str] = None,
    keyword_label_upz_desa: Optional[str] = None,
    page: int = Query(1, ge=1),
    per_page: int = Query(10, ge=1, le=100),
    x_api_key: Optional[str] = Header(None),
    current_user: Users = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    scoped = _apply_scope(current_user, {
        "id_provinsi": id_provinsi,
        "id_kabupaten_kota": id_kabupaten_kota,
        "id_kecamatan": id_kecamatan,
    })
    results = service.get_by_wilayah(
        db, scoped["id_provinsi"], scoped["id_kabupaten_kota"], scoped["id_kecamatan"],
        keyword_nama_upz_desa, keyword_alamat_upz_desa, keyword_label_upz_desa, page, per_page,
    )
    return ApiResponse(data=[dict(r._mapping) for r in results])


@router.get("/upz-desa/pengurus/{id_upz_desa}")
def get_pengurus_upz_desa(
    id_upz_desa: str,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    data = service.get_pengurus(db, id_upz_desa)
    return ApiResponse(data=data)


# ===== REKAP ENDPOINTS =====

@router.get("/rekap/penerimaan")
def get_rekap_penerimaan(
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
    x_api_key: Optional[str] = Header(None),
    current_user: Users = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    scoped = _apply_scope(current_user, {
        "id_provinsi": id_provinsi,
        "id_kabupaten_kota": id_kabupaten_kota,
        "id_kecamatan": id_kecamatan,
    })
    result = service.get_rekap_penerimaan(
        db, kode_org_baznas=kode_org_baznas, id_provinsi=scoped["id_provinsi"],
        id_kabupaten_kota=scoped["id_kabupaten_kota"], id_kecamatan=scoped["id_kecamatan"],
        start_date=start_date, end_date=end_date,
    )
    return ApiResponse(data=dict(result._mapping) if result else {})


@router.get("/rekap/pengeluaran")
def get_rekap_pengeluaran(
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
    x_api_key: Optional[str] = Header(None),
    current_user: Users = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    scoped = _apply_scope(current_user, {
        "id_provinsi": id_provinsi,
        "id_kabupaten_kota": id_kabupaten_kota,
        "id_kecamatan": id_kecamatan,
    })
    result = service.get_rekap_pengeluaran(
        db, kode_org_baznas=kode_org_baznas, id_provinsi=scoped["id_provinsi"],
        id_kabupaten_kota=scoped["id_kabupaten_kota"], id_kecamatan=scoped["id_kecamatan"],
        start_date=start_date, end_date=end_date,
    )
    return ApiResponse(data=dict(result._mapping) if result else {})


@router.get("/rekap/by-upz-desa/{id_upz_desa}")
def get_rekap_by_upz_desa(
    id_upz_desa: str,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
    x_api_key: Optional[str] = Header(None),
    current_user: Users = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    _check_upz_desa_scope(db, current_user, id_upz_desa)
    result = service.get_rekap_by_upz_desa(db, id_upz_desa, start_date=start_date, end_date=end_date)
    return ApiResponse(data=result)


@router.get("/rekap/bulanan/{id_upz_desa}")
def get_rekap_bulanan_by_upz_desa(
    id_upz_desa: str,
    tahun: Optional[int] = None,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
    x_api_key: Optional[str] = Header(None),
    current_user: Users = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    """Rekap penerimaan vs pengeluaran per bulan (FR-2.5).

    Mode: ?tahun=2026 (12 bulan Jan–Des, default tahun berjalan)
    atau ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD (rentang custom, FR-2.6).
    """
    err = require_api_key(x_api_key)
    if err:
        return err
    _check_upz_desa_scope(db, current_user, id_upz_desa)
    try:
        result = service.get_rekap_bulanan_by_upz_desa(
            db, id_upz_desa, tahun=tahun, start_date=start_date, end_date=end_date,
        )
    except ValueError as e:
        raise HTTPException(status_code=422, detail=str(e))
    return ApiResponse(data=result)
