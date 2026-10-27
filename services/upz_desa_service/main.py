"""
UPZ Desa Service — UPZ Desa API
Handles: UPZ Desa CRUD, wilayah, rekap, master data (tema, kategori)
Port: 8002
"""
import sys
from pathlib import Path
sys.path.insert(0, str(Path(__file__).parent.parent.parent))

import uvicorn
from fastapi import FastAPI
from shared.config.environment import env
from shared.middlewares.error_middleware import global_exception_handler
from shared.middlewares.logging_middleware import logging_middleware
from shared.middlewares.cors import setup_cors

from services.upz_desa_service.routes.upz_desa_routes import router as upz_desa_router
from services.upz_desa_service.routes.wilayah_routes import router as wilayah_router

app = FastAPI(
    title=f"{env.APP_NAME} - UPZ Desa Service",
    version=env.APP_VERSION,
    docs_url="/docs" if env.DEBUG else None,
    redoc_url="/redoc" if env.DEBUG else None,
)

app.middleware("http")(logging_middleware)
app.add_exception_handler(Exception, global_exception_handler)
setup_cors(app)

app.include_router(upz_desa_router, prefix="/api/v1", tags=["UPZ Desa"])
app.include_router(wilayah_router, prefix="/api/v1", tags=["Wilayah"])


@app.get("/health")
def health():
    return {"service": "upz-desa", "status": "ok"}


if __name__ == "__main__":
    uvicorn.run("services.upz_desa_service.main:app", host="0.0.0.0", port=8002, reload=env.DEBUG)
