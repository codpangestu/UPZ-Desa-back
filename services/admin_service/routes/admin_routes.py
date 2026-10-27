from fastapi import APIRouter, Depends, Query, Header, Body
from sqlalchemy.orm import Session
from typing import Optional, Any, Dict, List
from shared.config.database import get_db
from services.admin_service.services.admin_service import AdminService
from shared.utils.route_wrapper import verify_api_key
from shared.utils.response_helper import ApiResponse
from shared.middlewares.auth_middleware import get_current_user
from shared.models.users import Users

router = APIRouter()
service = AdminService()


# ======================================================================
# UPZ_DESA CRUD
# ======================================================================

@router.post("/admin/upz-desa/create")
def create_upz_desa(
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result = service.create_upz_desa(db, data)
    return ApiResponse(data=result)


@router.put("/admin/upz-desa/update/{id_upz_desa}")
def update_upz_desa(
    id_upz_desa: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    if not service.update_upz_desa(db, id_upz_desa, data):
        return ApiResponse(status_code="404", status="UPZ Desa tidak ditemukan")
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/upz-desa/delete/{id_upz_desa}")
def delete_upz_desa(
    id_upz_desa: str,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    if not service.delete_upz_desa(db, id_upz_desa):
        return ApiResponse(status_code="404", status="UPZ Desa tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# CONTENT CRUD
# ======================================================================

@router.post("/admin/content/{tipe}/create")
def create_content(
    tipe: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result, error = service.create_content(db, tipe, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    return ApiResponse(data=result)


@router.put("/admin/content/{tipe}/update/{pk_value}")
def update_content(
    tipe: str,
    pk_value: Any,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.update_content(db, tipe, pk_value, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/content/{tipe}/delete/{pk_value}")
def delete_content(
    tipe: str,
    pk_value: Any,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.delete_content(db, tipe, pk_value)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# MASTER DATA CRUD
# ======================================================================

@router.post("/admin/master/{tipe}/create")
def create_master(
    tipe: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result, error = service.create_master(db, tipe, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    return ApiResponse(data=result)


@router.put("/admin/master/{tipe}/update/{pk_value}")
def update_master(
    tipe: str,
    pk_value: int,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.update_master(db, tipe, pk_value, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/master/{tipe}/delete/{pk_value}")
def delete_master(
    tipe: str,
    pk_value: int,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.delete_master(db, tipe, pk_value)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# WILAYAH CRUD
# ======================================================================

@router.post("/admin/wilayah/{tipe}/create")
def create_wilayah(
    tipe: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result, error = service.create_wilayah(db, tipe, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    return ApiResponse(data=result)


@router.put("/admin/wilayah/{tipe}/update/{pk_value}")
def update_wilayah(
    tipe: str,
    pk_value: int,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.update_wilayah(db, tipe, pk_value, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/wilayah/{tipe}/delete/{pk_value}")
def delete_wilayah(
    tipe: str,
    pk_value: int,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.delete_wilayah(db, tipe, pk_value)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# UPZ_DESA DETAIL CRUD
# ======================================================================

@router.post("/admin/detail/{tipe}/create")
def create_detail(
    tipe: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result, error = service.create_detail(db, tipe, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    return ApiResponse(data=result)


@router.put("/admin/detail/{tipe}/update/{pk_value}")
def update_detail(
    tipe: str,
    pk_value: int,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.update_detail(db, tipe, pk_value, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/detail/{tipe}/delete/{pk_value}")
def delete_detail(
    tipe: str,
    pk_value: int,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.delete_detail(db, tipe, pk_value)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# TRANSAKSI CRUD
# ======================================================================

@router.post("/admin/transaksi/{tipe}/create")
def create_transaksi(
    tipe: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result, error = service.create_transaksi(db, tipe, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    return ApiResponse(data=result)


@router.put("/admin/transaksi/{tipe}/update/{pk_value}")
def update_transaksi(
    tipe: str,
    pk_value: int,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.update_transaksi(db, tipe, pk_value, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/transaksi/{tipe}/delete/{pk_value}")
def delete_transaksi(
    tipe: str,
    pk_value: int,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.delete_transaksi(db, tipe, pk_value)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# MICROFINANCE (BMM) CRUD
# ======================================================================

@router.post("/admin/microfinance/{tipe}/create")
def create_microfinance(
    tipe: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result, error = service.create_microfinance(db, tipe, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    return ApiResponse(data=result)


@router.put("/admin/microfinance/{tipe}/update/{pk_value}")
def update_microfinance(
    tipe: str,
    pk_value: int,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.update_microfinance(db, tipe, pk_value, data)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/microfinance/{tipe}/delete/{pk_value}")
def delete_microfinance(
    tipe: str,
    pk_value: int,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    ok, error = service.delete_microfinance(db, tipe, pk_value)
    if error:
        return ApiResponse(status_code="400", status=error)
    if not ok:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# PENGAJUAN CRUD
# ======================================================================

@router.get("/admin/pengajuan/list")
def list_pengajuan(
    page: int = Query(1, ge=1),
    per_page: int = Query(10, ge=1),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result = service.list_pengajuan(db, page, per_page)
    return ApiResponse(data=result["data"], total=result["total"])


@router.post("/admin/pengajuan/create")
def create_pengajuan(
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    result = service.create_pengajuan(db, data)
    if "error" in result:
        return ApiResponse(status_code="500", status=result["error"])
    # Response berisi kode UPZ Desa + slug yang diterbitkan langsung, agar pemohon
    # bisa lanjut ke langkah 2 (daftar admin UPZ Desa) dengan kode tsb.
    return ApiResponse(status_code="000", status="Sukses", data=result)


@router.put("/admin/pengajuan/update/{pk_value}")
def update_pengajuan(
    pk_value: int,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    status_req = str((data or {}).get("status_pengajuan") or (data or {}).get("status") or "").lower()
    result = service.update_pengajuan(db, pk_value, data)
    if not result:
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    if "error" in result:
        return ApiResponse(status_code="400", status=result["error"])
    # Saat disetujui, response menyertakan kode UPZ Desa (aktivasi upz_desa + admin)
    if status_req == "disetujui":
        kode = result.get("kode_org_baznas")
        return ApiResponse(status_code="000", status="Sukses", data={"kode_org_baznas": kode})
    return ApiResponse(status_code="000", status="Sukses")


@router.delete("/admin/pengajuan/delete/{pk_value}")
def delete_pengajuan(
    pk_value: int,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    if not service.delete_pengajuan(db, pk_value):
        return ApiResponse(status_code="404", status="Data tidak ditemukan")
    return ApiResponse(message="Data deleted successfully !")


# ======================================================================
# BULK SYNC DETAIL
# ======================================================================

@router.post("/admin/upz-desa/{id_upz_desa}/sync-detail")
def sync_upz_desa_detail(
    id_upz_desa: str,
    data: Dict[str, List[Dict[str, Any]]] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return ApiResponse(status_code="101", status="API KEY Invalid !")
    results = service.sync_upz_desa_detail(db, id_upz_desa, data)
    return ApiResponse(data=results)


# ======================================================================
# PUBLIKASI — dashboard Menu Publikasi (Fix #49)
# Kontrak frontend features/menu-publikasi/api.ts:
#   list   = GET  /api/v1/publikasi/{jenis}/list           (TANPA /admin)
#   create = POST /api/v1/admin/publikasi/{jenis}/create
#   update = PUT  /api/v1/admin/publikasi/{jenis}/update/{id}
#   delete = DEL  /api/v1/admin/publikasi/{jenis}/delete/{id}
# Response format kustom frontend: { status: bool, data, pagination, message }
# ======================================================================

@router.get("/publikasi/{jenis}/list")
def list_publikasi(
    jenis: str,
    page: int = Query(1, ge=1),
    per_page: int = Query(10, ge=1, le=100),
    search: Optional[str] = None,
    db: Session = Depends(get_db),
):
    result, error = service.list_publikasi(db, jenis, page, per_page, search)
    if error:
        return {"status": False, "message": error, "data": [], "pagination": {"page": page, "per_page": per_page, "total": 0, "total_pages": 1}}
    return result


@router.post("/admin/publikasi/{jenis}/create")
def create_publikasi(
    jenis: str,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    current_user: Users = Depends(get_current_user),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return {"status": False, "message": "API KEY Invalid !"}
    result, error = service.create_publikasi(db, jenis, data, current_user)
    if error:
        return {"status": False, "message": error}
    return {"status": True, "data": result, "message": "Publikasi berhasil ditambahkan"}


@router.put("/admin/publikasi/{jenis}/update/{pk_value}")
def update_publikasi(
    jenis: str,
    pk_value: int,
    data: Dict[str, Any] = Body(...),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return {"status": False, "message": "API KEY Invalid !"}
    ok, error = service.update_publikasi(db, jenis, pk_value, data)
    if error:
        return {"status": False, "message": error}
    if not ok:
        return {"status": False, "message": "Data tidak ditemukan"}
    return {"status": True, "message": "Publikasi berhasil diperbarui"}


@router.delete("/admin/publikasi/{jenis}/delete/{pk_value}")
def delete_publikasi(
    jenis: str,
    pk_value: int,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    if not verify_api_key(x_api_key):
        return {"status": False, "message": "API KEY Invalid !"}
    ok, error = service.delete_publikasi(db, jenis, pk_value)
    if error:
        return {"status": False, "message": error}
    if not ok:
        return {"status": False, "message": "Data tidak ditemukan"}
    return {"status": True, "message": "Publikasi berhasil dihapus"}
