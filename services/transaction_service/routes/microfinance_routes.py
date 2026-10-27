from fastapi import APIRouter, Depends, Query, Header
from sqlalchemy.orm import Session
from typing import Optional
from shared.config.database import get_db
from shared.utils.route_wrapper import require_api_key
from shared.utils.response_helper import ApiResponse
from shared.schemas.base import model_to_dict
from services.transaction_service.queries import microfinance_queries as q

router = APIRouter()


@router.get("/microfinance/{tipe}/list")
def list_microfinance(
    tipe: str,
    id_upz_desa: Optional[str] = None,
    page: int = Query(1, ge=1),
    per_page: int = Query(10, ge=1, le=100),
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    data, total, error = q.list_by_tipe(db, tipe, id_upz_desa=id_upz_desa, page=page, per_page=per_page)
    if error:
        return ApiResponse(status_code="400", status=error)
    return ApiResponse(
        data=[model_to_dict(d) for d in data],
        total=total,
        page=page,
        per_page=per_page,
    )


@router.get("/microfinance/mutasi/summary")
def mutasi_summary(
    id_upz_desa: str,
    x_api_key: Optional[str] = Header(None),
    db: Session = Depends(get_db),
):
    err = require_api_key(x_api_key)
    if err:
        return err
    data = q.summary_saldo(db, id_upz_desa)
    return ApiResponse(data=data)
