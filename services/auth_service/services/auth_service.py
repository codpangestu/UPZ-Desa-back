from fastapi import HTTPException, status
from sqlalchemy.orm import Session
from services.auth_service.queries.auth_queries import get_user_by_email, get_user_by_id, create_user, update_last_login
from shared.utils.jwt_helper import verify_password, get_password_hash, create_access_token
from shared.config.constants import (
    DEFAULT_PASSWORD, DEFAULT_AVATAR, DEFAULT_USER_LEVEL, JALUR_MASJID,
    LEVEL_ADMIN_KUA,
)
from shared.models.users import Users
from shared.models.masjid import Masjid


class AuthService:
    def login(self, db: Session, email: str, password: str):
        user = get_user_by_email(db, email)
        if not user:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Email tidak terdaftar",
            )
        if not verify_password(password, user.password):
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Password salah",
            )
        if user.status_aktif != 1:
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail="Status akun tidak aktif",
            )

        update_last_login(db, user)
        token = create_access_token(data={"sub": str(user.id_user)})
        return {
            "access_token": token,
            "user": {
                "id_user": user.id_user,
                "nama": user.nama,
                "email": user.email,
                "jalur_akses": user.jalur_akses,
                "id_jalur_akses": user.id_jalur_akses,
                "avatar": user.avatar,
                "id_level": user.id_level,
                "id_labels": user.id_labels,
                "nohp": user.nohp,
            }
        }

    def login_admin_masjid(self, db: Session, email: str, password: str):
        user = get_user_by_email(db, email)
        if not user or not verify_password(password, user.password):
            raise HTTPException(status_code=401, detail="Email atau password salah")
        if user.jalur_akses != JALUR_MASJID:
            raise HTTPException(status_code=403, detail="Akses ditolak. Bukan akun Masjid")
        if user.status_aktif != 1:
            raise HTTPException(status_code=403, detail="Akun tidak aktif")

        update_last_login(db, user)
        token = create_access_token(data={"sub": str(user.id_user)})
        return {
            "access_token": token,
            "user": {
                "id_user": user.id_user,
                "nama": user.nama,
                "email": user.email,
                "jalur_akses": user.jalur_akses,
                "id_jalur_akses": user.id_jalur_akses,
                "avatar": user.avatar,
                "id_level": user.id_level,
            }
        }

    def login_admin_pusat(self, db: Session, email: str, password: str):
        user = get_user_by_email(db, email)
        if not user or not verify_password(password, user.password):
            raise HTTPException(status_code=401, detail="Email atau password salah")
        if user.jalur_akses != "Pusat":
            raise HTTPException(status_code=403, detail="Akses ditolak")
        # Admin KUA (id_level 13) dipisahkan dari opsi "Admin BAZNAS" — punya
        # opsi & endpoint login sendiri (login_admin_kua di bawah).
        if user.id_level == LEVEL_ADMIN_KUA:
            raise HTTPException(
                status_code=403,
                detail=(
                    "Akun ini terdaftar sebagai Admin KUA. "
                    "Silakan pilih opsi 'Admin KUA' saat login."
                ),
            )
        if user.status_aktif != 1:
            raise HTTPException(status_code=403, detail="Akun tidak aktif")

        token = create_access_token(data={"sub": str(user.id_user)})
        return {
            "access_token": token,
            "user": {
                "id_user": user.id_user,
                "nama": user.nama,
                "email": user.email,
                "jalur_akses": user.jalur_akses,
                "avatar": user.avatar,
                "id_level": user.id_level,
                "id_provinsi": user.id_provinsi,
                "id_kabupaten_kota": user.id_kabupaten_kota,
                "id_kecamatan": user.id_kecamatan,
            }
        }

    def login_admin_kua(self, db: Session, email: str, password: str):
        """Login khusus Admin KUA (id_level 13, scope kecamatan).

        Opsi "Admin KUA" di dropdown login TERPISAH dari "Admin BAZNAS":
        endpoint ini hanya menerima akun level 13, dan sebaliknya
        login_admin_pusat menolak akun level 13 (silang).
        """
        user = get_user_by_email(db, email)
        if not user or not verify_password(password, user.password):
            raise HTTPException(status_code=401, detail="Email atau password salah")
        if user.jalur_akses != "Pusat":
            raise HTTPException(status_code=403, detail="Akses ditolak. Bukan akun BAZNAS")
        if user.id_level != LEVEL_ADMIN_KUA:
            raise HTTPException(
                status_code=403,
                detail=(
                    "Akun ini bukan Admin KUA. "
                    "Silakan pilih opsi login yang sesuai."
                ),
            )
        if user.status_aktif != 1:
            raise HTTPException(status_code=403, detail="Akun tidak aktif")

        token = create_access_token(data={"sub": str(user.id_user)})
        return {
            "access_token": token,
            "user": {
                "id_user": user.id_user,
                "nama": user.nama,
                "email": user.email,
                "jalur_akses": user.jalur_akses,
                "avatar": user.avatar,
                "id_level": user.id_level,
                "id_provinsi": user.id_provinsi,
                "id_kabupaten_kota": user.id_kabupaten_kota,
                "id_kecamatan": user.id_kecamatan,
            }
        }

    def register_user(self, db: Session, data: dict):
        """
        Register User / Admin Masjid.
        Jika `kode_org_baznas` diisi:
        - Validasi kode terhadap tabel masjid (harus masjid aktif):
          * valid -> user dibuat sebagai Admin Masjid (id_level=2, jalur_akses=Masjid)
        Jika `kode_org_baznas` kosong:
        - User dibuat sebagai Jamaah/Pengguna biasa (id_level=3, jalur_akses=Umum, id_labels=3).
        """
        existing = get_user_by_email(db, data["email"])
        if existing:
            return {"status_code": "403", "status": "Email sudah terdaftar"}

        kode = (data.get("kode_org_baznas") or "").strip()
        if kode:
            # Validasi: masjid dengan kode tsb harus sudah terdaftar & aktif
            masjid = db.query(Masjid).filter(Masjid.kode_org_baznas == kode).first()
            if not masjid or masjid.status_aktif != 1:
                return {
                    "status_code": "404",
                    "status": "Kode masjid tidak valid. Pastikan pengajuan masjid Anda sudah disetujui Admin BAZNAS.",
                }
            create_user(
                db,
                jalur_akses=JALUR_MASJID,
                id_jalur_akses=masjid.id_masjid,
                nama=data.get("nama"),
                jk=data.get("jk"),
                alamat=data.get("alamat"),
                email=data.get("email"),
                nohp=data.get("nohp", ""),
                kode_org_baznas=kode,
                password=get_password_hash(DEFAULT_PASSWORD),
                id_level=2,  # Admin Masjid
                id_labels=1 if data.get("id_labels") is None else data.get("id_labels"),  # 1 = Admin
                avatar=DEFAULT_AVATAR,
                status_aktif=1,
                catatan=data.get("catatan", ""),
            )
        else:
            # User biasa / Jamaah (tanpa kode masjid)
            create_user(
                db,
                jalur_akses="Umum",
                id_jalur_akses=data.get("id_jalur_akses", "") or "",
                nama=data.get("nama"),
                jk=data.get("jk"),
                alamat=data.get("alamat"),
                email=data.get("email"),
                nohp=data.get("nohp", ""),
                kode_org_baznas="",
                password=get_password_hash(DEFAULT_PASSWORD),
                id_level=3,  # Jamaah / Pengguna
                id_labels=3 if data.get("id_labels") is None else data.get("id_labels"),  # 3 = Jamaah
                avatar=DEFAULT_AVATAR,
                status_aktif=1,
                catatan=data.get("catatan", ""),
            )
        return {"status_code": "000", "status": "Sukses"}

    def update_profile(self, db: Session, current_user: Users, data: dict):
        """Update data personal user yang sedang login (FR-3.1).

        Email divalidasi unik terhadap user lain. Field kosong/None diabaikan.
        """
        email = (data.get("email") or "").strip()
        if email and email != current_user.email:
            existing = get_user_by_email(db, email)
            if existing and existing.id_user != current_user.id_user:
                raise HTTPException(status_code=409, detail="Email sudah dipakai akun lain")
            current_user.email = email

        for field in ("nama", "jk", "alamat", "nohp", "avatar"):
            value = data.get(field)
            if value is not None:
                setattr(current_user, field, value)

        db.commit()
        db.refresh(current_user)
        return self.get_me(current_user)

    def change_password(self, db: Session, current_user: Users, password_lama: str, password_baru: str):
        """Ubah password akun yang sedang login (FR-3.2).

        password_lama wajib cocok; password_baru min 8 karakter dan harus beda
        dari password lama. Disimpan sebagai hash bcrypt.
        """
        if not verify_password(password_lama, current_user.password):
            raise HTTPException(status_code=400, detail="Password lama tidak sesuai")
        if len(password_baru) < 8:
            raise HTTPException(status_code=400, detail="Password baru minimal 8 karakter")
        if password_baru == password_lama:
            raise HTTPException(status_code=400, detail="Password baru harus berbeda dari password lama")

        current_user.password = get_password_hash(password_baru)
        db.commit()
        return {"status_code": "000", "status": "Password berhasil diubah"}

    def get_me(self, current_user: Users):
        return {
            "id_user": current_user.id_user,
            "nama": current_user.nama,
            "email": current_user.email,
            "jalur_akses": current_user.jalur_akses,
            "id_jalur_akses": current_user.id_jalur_akses,
            "avatar": current_user.avatar,
            "id_level": current_user.id_level,
            "id_labels": current_user.id_labels,
            "nohp": current_user.nohp,
            "jk": current_user.jk,
            "alamat": current_user.alamat,
            "status_aktif": current_user.status_aktif,
            "id_provinsi": current_user.id_provinsi,
            "id_kabupaten_kota": current_user.id_kabupaten_kota,
            "id_kecamatan": current_user.id_kecamatan,
        }

    def logout(self):
        return {"status_code": "000", "status": "Berhasil logout"}
