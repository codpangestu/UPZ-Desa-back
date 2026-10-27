from fastapi import APIRouter, Depends, Query, Header
from sqlalchemy.orm import Session
from typing import Optional
from datetime import date
from shared.config.database import get_db
from services.transaction_service.services.bukukas_service import BukuKasService
from shared.utils.route_wrapper import require_api_key
from shared.utils.response_helper import ApiResponse
from shared.middlewares.auth_middleware import get_current_user
from shared.middlewares.rbac_middleware import get_user_scope_columns
from shared.models.users import Users

router = APIRouter()
service = BukuKasService()


def _apply_scope(current_user: Users, filters: dict) -> dict:
    """Timpa filter wilayah yang ADA di `filters` dengan scope milik current_user
    (Admin Daerah/Kab/KUA) — sama seperti pola di upz_desa_routes.py."""
    scope_cols = get_user_scope_columns(current_user)
    if not scope_cols:
        return filters
    filters = dict(filters)
    for col, val in scope_cols.items():
        if col in filters:
            filters[col] = val
    return filters


@router.get("/bukukas-penerimaan/list")
def list_penerimaan(
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    id_jenis_dana: Optional[int] = None,
    id_label_info_dana: Optional[int] = None,
    start_date: Optional[date] = None,
    end_date: Optional[date] = None,
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
    result = service.get_penerimaan(
        db, kode_org_baznas=kode_org_baznas, id_provinsi=scoped["id_provinsi"],
        id_kabupaten_kota=scoped["id_kabupaten_kota"], id_kecamatan=scoped["id_kecamatan"],
        id_jenis_dana=id_jenis_dana,
        id_label_info_dana=id_label_info_dana,
        start_date=start_date, end_date=end_date, page=page, per_page=per_page,
    )
    return ApiResponse(
        data=result["data"],
        total=result["total"],
        page=result["page"],
        per_page=result["per_page"],
    )


@router.get("/bukukas-penerimaan/command-center")
def list_penerimaan_cc(
    kode_org_baznas: Optional[str] = None,
    page: int = Query(1, ge=1),
    per_page: int = Query(10, ge=1, le=100),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    data = service.get_penerimaan_cc(
        db, kode_org_baznas=kode_org_baznas, page=page, per_page=per_page,
    )
    return ApiResponse(data=data)


@router.get("/bukukas-pengeluaran/list")
def list_pengeluaran(
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    id_jenis_dana: Optional[int] = None,
    id_label_info_dana: Optional[int] = None,
    start_date: Optional[date] = None,
    end_date: Optional[date] = None,
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
    result = service.get_pengeluaran(
        db, kode_org_baznas=kode_org_baznas, id_provinsi=scoped["id_provinsi"],
        id_kabupaten_kota=scoped["id_kabupaten_kota"], id_kecamatan=scoped["id_kecamatan"],
        id_jenis_dana=id_jenis_dana,
        id_label_info_dana=id_label_info_dana,
        start_date=start_date, end_date=end_date, page=page, per_page=per_page,
    )
    return ApiResponse(
        data=result["data"],
        total=result["total"],
        page=result["page"],
        per_page=result["per_page"],
    )


@router.get("/bukukas-pengeluaran/command-center")
def list_pengeluaran_cc(
    kode_org_baznas: Optional[str] = None,
    page: int = Query(1, ge=1),
    per_page: int = Query(10, ge=1, le=100),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    data = service.get_pengeluaran_cc(
        db, kode_org_baznas=kode_org_baznas, page=page, per_page=per_page,
    )
    return ApiResponse(data=data)
