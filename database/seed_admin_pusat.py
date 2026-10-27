"""
Seeder: Admin Pusat account for UPZ Desa
Jalankan: python -m database.seed_admin_pusat
"""

import sys
import os

sys.path.insert(0, os.path.join(os.path.dirname(__file__), ".."))

from shared.config.database import SessionLocal, engine
from shared.config.constants import DEFAULT_PASSWORD
from shared.utils.jwt_helper import get_password_hash
from sqlalchemy import text


LEVELS = [
    (1, "Admin Pusat"),
    (2, "Admin UPZ Desa"),
    (3, "Petugas"),
    # NOTE: id 4/5/6 SUDAH dipakai data legacy (Anggota/Admin UPZ Desa/Admin
    # Monitor UPZ Desa) — JANGAN dipakai ulang. DB legacy juga sudah punya baris
    # 10=Admin BAZNAS Pusat, 11=Admin BAZNAS Provinsi, 12=Admin BAZNAS
    # Kabupaten Kota — persis konsep Admin Daerah/Kab di bawah, jadi id-nya
    # disamakan. 13 sebelumnya placeholder "Level Baru" tanpa user, dipakai
    # untuk Admin KUA (kecamatan, belum ada di skema legacy).
    (11, "Admin Daerah"),
    (12, "Admin Kab"),
    (13, "Admin KUA"),
]

LABELS = [
    (1, "Admin", ""),
    (2, "Petugas", ""),
    (3, "Jamaah", ""),
]

ADMIN_PUSAT = {
    "kode_org_baznas": "BAZNAS",
    "jalur_akses": "Pusat",
    "nama": "Admin Pusat BAZNAS",
    "jk": "Laki-laki",
    "nohp": "081234567890",
    "email": "admin_pusat@baznas.go.id",
    "password": DEFAULT_PASSWORD,
    "alamat": "Jl. Lapangan Banteng Timur 3-4, Jakarta Pusat",
    "id_level": 1,
    "id_labels": 1,
    "status_aktif": 1,
}

# Akun demo untuk 3 role "anak" Admin BAZNAS yang scope-nya dipersempit ke
# wilayah tertentu (lihat CONTEXT.md section 12). Wilayah dipilih yang punya
# data UPZ Desa nyata di DB lokal supaya hasil scoping langsung terlihat saat
# login: Jawa Tengah (33) > Kab. Banjarnegara (3304) > Kec. Wanayasa (330417).
# jalur_akses tetap 'Pusat' (anak Admin BAZNAS) — login lewat opsi dropdown
# "Admin BAZNAS", role sebenarnya di-infer dari id_level di frontend.
SCOPED_ADMINS = [
    {
        "nama": "Admin Daerah Demo (Jawa Tengah)",
        "email": "admin.daerah@upzdesa.baznas.go.id",
        "id_level": 11,
        "id_provinsi": 33,
        "id_kabupaten_kota": None,
        "id_kecamatan": None,
    },
    {
        "nama": "Admin Kab Demo (Banjarnegara)",
        "email": "admin.kab@upzdesa.baznas.go.id",
        "id_level": 12,
        "id_provinsi": 33,
        "id_kabupaten_kota": 3304,
        "id_kecamatan": None,
    },
    {
        "nama": "Admin KUA Demo (Wanayasa)",
        "email": "admin.kua@upzdesa.baznas.go.id",
        "id_level": 13,
        "id_provinsi": 33,
        "id_kabupaten_kota": 3304,
        "id_kecamatan": 330417,
    },
]


def seed_levels(db):
    print("Seeding levels...")
    for id_level, nama_level in LEVELS:
        exists = db.execute(
            text("SELECT id_level FROM levels WHERE id_level = :id"),
            {"id": id_level},
        ).fetchone()
        if not exists:
            db.execute(
                text("INSERT INTO levels (id_level, nama_level) VALUES (:id, :nama)"),
                {"id": id_level, "nama": nama_level},
            )
            print(f"  + Level: {nama_level} (id={id_level})")
        else:
            print(f"  = Level '{nama_level}' sudah ada (id={id_level})")
    db.commit()


def seed_labels(db):
    print("Seeding labels...")
    for id_labels, nama_labels, parent_id in LABELS:
        exists = db.execute(
            text("SELECT id_labels FROM labels WHERE id_labels = :id"),
            {"id": id_labels},
        ).fetchone()
        if not exists:
            db.execute(
                text("INSERT INTO labels (id_labels, nama_labels, parent_id) VALUES (:id, :nama, :pid)"),
                {"id": id_labels, "nama": nama_labels, "pid": parent_id},
            )
            print(f"  + Label: {nama_labels} (id={id_labels})")
        else:
            print(f"  = Label '{nama_labels}' sudah ada (id={id_labels})")
    db.commit()


def seed_admin_pusat(db):
    print("Seeding admin pusat...")
    email = ADMIN_PUSAT["email"]
    exists = db.execute(
        text("SELECT id_user FROM users WHERE email = :email"),
        {"email": email},
    ).fetchone()
    if exists:
        print(f"  = User '{email}' sudah ada (id={exists[0]})")
        return

    hashed = get_password_hash(ADMIN_PUSAT["password"])

    db.execute(
        text(
            """
            INSERT INTO users
                (kode_org_baznas, jalur_akses, nama, jk, nohp, email, password,
                 alamat, id_level, id_labels, status_aktif)
            VALUES
                (:kode_org_baznas, :jalur_akses, :nama, :jk, :nohp, :email, :password,
                 :alamat, :id_level, :id_labels, :status_aktif)
            """
        ),
        {
            "kode_org_baznas": ADMIN_PUSAT["kode_org_baznas"],
            "jalur_akses": ADMIN_PUSAT["jalur_akses"],
            "nama": ADMIN_PUSAT["nama"],
            "jk": ADMIN_PUSAT["jk"],
            "nohp": ADMIN_PUSAT["nohp"],
            "email": ADMIN_PUSAT["email"],
            "password": hashed,
            "alamat": ADMIN_PUSAT["alamat"],
            "id_level": ADMIN_PUSAT["id_level"],
            "id_labels": ADMIN_PUSAT["id_labels"],
            "status_aktif": ADMIN_PUSAT["status_aktif"],
        },
    )
    db.commit()
    print(f"  + Admin pusat '{email}' berhasil dibuat!")


def seed_scoped_admins(db):
    print("Seeding akun demo Admin Daerah/Kab/KUA...")
    for admin in SCOPED_ADMINS:
        email = admin["email"]
        existing = db.execute(
            text("SELECT id_user FROM users WHERE email = :email"),
            {"email": email},
        ).fetchone()
        hashed = get_password_hash(DEFAULT_PASSWORD)

        if existing:
            db.execute(
                text(
                    """
                    UPDATE users
                    SET nama = :nama, jalur_akses = 'Pusat', password = :password,
                        id_level = :id_level, id_labels = 1, status_aktif = 1,
                        id_provinsi = :id_provinsi, id_kabupaten_kota = :id_kabupaten_kota,
                        id_kecamatan = :id_kecamatan
                    WHERE email = :email
                    """
                ),
                {
                    "nama": admin["nama"],
                    "password": hashed,
                    "id_level": admin["id_level"],
                    "id_provinsi": admin["id_provinsi"],
                    "id_kabupaten_kota": admin["id_kabupaten_kota"],
                    "id_kecamatan": admin["id_kecamatan"],
                    "email": email,
                },
            )
            print(f"  = Akun '{email}' sudah ada — diperbarui (level={admin['id_level']})")
        else:
            db.execute(
                text(
                    """
                    INSERT INTO users
                        (jalur_akses, nama, jk, nohp, email, password, alamat,
                         id_level, id_labels, status_aktif,
                         id_provinsi, id_kabupaten_kota, id_kecamatan)
                    VALUES
                        ('Pusat', :nama, 'Laki-laki', '080000000000', :email, :password, 'Demo',
                         :id_level, 1, 1,
                         :id_provinsi, :id_kabupaten_kota, :id_kecamatan)
                    """
                ),
                {
                    "nama": admin["nama"],
                    "email": email,
                    "password": hashed,
                    "id_level": admin["id_level"],
                    "id_provinsi": admin["id_provinsi"],
                    "id_kabupaten_kota": admin["id_kabupaten_kota"],
                    "id_kecamatan": admin["id_kecamatan"],
                },
            )
            print(f"  + Akun '{email}' berhasil dibuat! (level={admin['id_level']})")
    db.commit()


def main():
    print("=" * 50)
    print("  UPZ Desa - Seed Admin Pusat")
    print("=" * 50)
    print()

    db = SessionLocal()
    try:
        seed_levels(db)
        print()
        seed_labels(db)
        print()
        seed_admin_pusat(db)
        print()
        seed_scoped_admins(db)

        print()
        print("=" * 50)
        print("  Credential untuk login (semua login lewat opsi dropdown")
        print("  'Admin BAZNAS' — role sebenarnya otomatis terdeteksi):")
        print("=" * 50)
        print(f"  Admin BAZNAS : {ADMIN_PUSAT['email']} / {ADMIN_PUSAT['password']}")
        for admin in SCOPED_ADMINS:
            print(f"  {admin['nama']:<28}: {admin['email']} / {DEFAULT_PASSWORD}")
        print("=" * 50)
    except Exception as e:
        db.rollback()
        print(f"\nERROR: {e}")
        sys.exit(1)
    finally:
        db.close()


if __name__ == "__main__":
    main()
