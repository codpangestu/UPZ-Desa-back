from fastapi import Depends, HTTPException, status
from sqlalchemy.orm import Session
from typing import Dict, List
from shared.middlewares.auth_middleware import get_current_user
from shared.models.users import Users


# Level id -> level scope (11=Admin Daerah/provinsi, 12=Admin Kab/kabupaten-kota,
# 13=Admin KUA/kecamatan — id ini mengikuti baris `levels` legacy yang SUDAH ADA
# di DB: 10=Admin BAZNAS Pusat, 11=Admin BAZNAS Provinsi, 12=Admin BAZNAS
# Kabupaten Kota; 13 sebelumnya placeholder tanpa user, dipakai utk Admin KUA.
# JANGAN pakai id 4/5/6 — sudah dipakai data legacy lain (Anggota/Admin
# UpzDesa/Admin Monitor UPZ Desa) dengan user asli). Level di luar daftar ini
# (1=Admin Pusat, 2=Admin UPZ Desa, 3=Petugas, dst.) tidak di-scope.
# Akun Kab/KUA disimpan dengan HIERARKI LENGKAP (id_provinsi + id_kabupaten_kota
# untuk Kab; ketiganya untuk KUA) — bukan cuma kolom paling spesifik — supaya
# endpoint yang mensyaratkan id_provinsi (mis. /upz-desa/by-wilayah) tetap benar.
SCOPED_LEVELS = (11, 12, 13)


def get_user_scope_columns(current_user: Users) -> Dict[str, int]:
    """Kolom wilayah (+nilai) yang wajib dipaksakan untuk current_user.
    Dict kosong jika user tidak di-scope (Admin Pusat/UpzDesa/Petugas).

    Data legacy menyimpan kolom wilayah yang belum diisi sebagai string kosong
    ('') bukan NULL — harus diperlakukan sama seperti "belum di-set", kalau
    tidak Admin Daerah (yang hanya punya id_provinsi terisi) akan salah
    ter-filter id_kabupaten_kota='' juga, sehingga tidak ada upz_desa yang cocok.
    """
    if current_user.id_level not in SCOPED_LEVELS:
        return {}
    result = {}
    for column in ("id_provinsi", "id_kabupaten_kota", "id_kecamatan"):
        value = getattr(current_user, column, None)
        if value not in (None, ""):
            result[column] = value
    return result


def get_current_active_user(current_user=Depends(get_current_user)):
    """Get current active user (status_aktif == 1)."""
    if current_user.status_aktif != 1:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Inactive user account",
        )
    return current_user


def require_roles(allowed_roles: List[str]):
    """Dependency factory for role-based access control."""
    def role_checker(current_user=Depends(get_current_active_user)):
        if current_user.jalur_akses not in allowed_roles:
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail=f"Access denied. Required roles: {', '.join(allowed_roles)}",
            )
        return current_user
    return role_checker
