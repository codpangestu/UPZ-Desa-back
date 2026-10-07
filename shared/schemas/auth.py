from pydantic import BaseModel
from typing import Optional, Any


class LoginRequest(BaseModel):
    email: str
    password: str


class TokenResponse(BaseModel):
    access_token: str
    token_type: str = "bearer"
    user: Any = None


class RegisterUserRequest(BaseModel):
    id_jalur_akses: Optional[str] = ""
    nama: str
    jk: str
    alamat: str
    email: str
    nohp: Optional[str] = ""
    kode_org_baznas: Optional[str] = ""
    id_labels: Optional[int] = 3
    catatan: Optional[str] = ""


class UpdateProfileRequest(BaseModel):
    """Body `PUT /api/v1/profile` — semua field opsional (partial update)."""
    nama: Optional[str] = None
    email: Optional[str] = None
    jk: Optional[str] = None
    alamat: Optional[str] = None
    nohp: Optional[str] = None
    avatar: Optional[str] = None


class ChangePasswordRequest(BaseModel):
    """Body `PUT /api/v1/change-password`."""
    password_lama: str
    password_baru: str
