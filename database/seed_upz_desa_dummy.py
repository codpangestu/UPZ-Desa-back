"""
Seeder data dummy UPZ Desa.

Menggantikan seluruh data legacy "Menara Masjid" (tabel `upz_desa` dkk. sudah
di-rename lewat migration 007) dengan data dummy UPZ Desa yang konsisten:

- 40 UPZ Desa/Kelurahan tersebar di 7 provinsi (termasuk 2 UPZ yang dipakai
  akun uji Cypress: "Al-istiqomah" & "Jami Al-Hidayah Kubang Sepat")
- akun Admin UPZ Desa (password default `upzdesa23`) + pengurus
- buku kas penerimaan/pengeluaran 12 bulan terakhir
- kegiatan, struktur, fasilitas, bank, medsos, galeri, dokumen, QRIS, mitra
- publikasi: postingan, kajian, acara, cerita, donasi, pengumuman
- buku aset, saran & masukan
- microfinance BMM untuk UPZ "Jami Al-Hidayah Kubang Sepat"
- pengajuan UPZ Desa (pending/disetujui/ditolak)

Data referensi (wilayah BPS, levels/labels, jenis dana/harta, coa) TIDAK
disentuh. Akun Admin BAZNAS/Pusat juga dipertahankan.

Jalankan dari folder `backend`:
    python -m database.seed_upz_desa_dummy
"""

import os
import random
import sys
from datetime import date, datetime, timedelta

import pymysql

sys.path.insert(0, os.path.join(os.path.dirname(__file__), ".."))

from shared.config.environment import env  # noqa: E402
from shared.utils.jwt_helper import get_password_hash  # noqa: E402

SEED = 42
DEFAULT_PASSWORD = "upzdesa23"
JALUR = "UPZ Desa"

# ---------------------------------------------------------------- koneksi DB
conn = pymysql.connect(
    host=env.DB_HOST,
    port=env.DB_PORT,
    user=env.DB_USERNAME,
    password=env.DB_PASSWORD,
    database=env.DB_DATABASE,
    charset="utf8mb4",
    autocommit=False,
)
cur = conn.cursor()


def truncate(tables):
    cur.execute("SET FOREIGN_KEY_CHECKS = 0")
    for t in tables:
        cur.execute(f"TRUNCATE TABLE `{t}`")
    cur.execute("SET FOREIGN_KEY_CHECKS = 1")
    print(f"  - dikosongkan: {', '.join(tables)}")


def insert(table, rows):
    """Insert list-of-dict (semua baris wajib punya key sama)."""
    if not rows:
        return 0
    cols = list(rows[0].keys())
    placeholders = ", ".join(["%s"] * len(cols))
    collist = ", ".join(f"`{c}`" for c in cols)
    sql = f"INSERT INTO `{table}` ({collist}) VALUES ({placeholders})"
    cur.executemany(sql, [tuple(r[c] for c in cols) for r in rows])
    print(f"  + {table}: {len(rows)} baris")
    return cur.rowcount


# ------------------------------------------------------------------ referensi
def load_wilayah():
    """Ambil kandidat desa dari provinsi target + nama kab/kec/desa lengkap."""
    target_prov = ("31", "32", "33", "35", "36", "51", "64")
    cur.execute(
        """
        SELECT d.id AS id_desa, d.nama_kelurahan_desa,
               k.id AS id_kecamatan, k.nama_kecamatan,
               kb.id AS id_kabupaten_kota, kb.nama_kabupaten_kota,
               p.id AS id_provinsi, p.nama_provinsi
        FROM kelurahan_desa d
        JOIN kecamatan k ON k.id = d.id_kecamatan
        JOIN kabupaten_kota kb ON kb.id = k.id_kabupaten_kota
        JOIN provinsi p ON p.id = kb.id_provinsi
        WHERE p.id IN %s AND d.nama_kelurahan_desa <> ''
        """,
        (target_prov,),
    )
    rows = cur.fetchall()
    return [
        dict(
            id_desa=r[0], desa=r[1], id_kecamatan=r[2], kecamatan=r[3],
            id_kabupaten_kota=r[4], kabupaten=r[5], id_provinsi=r[6], provinsi=r[7],
        )
        for r in rows
    ]


def pick_desa(candidates, used, provinsi=None, kabupaten=None, kecamatan=None):
    pool = [
        c for c in candidates
        if (provinsi is None or c["id_provinsi"] == provinsi)
        and (kabupaten is None or c["id_kabupaten_kota"] == kabupaten)
        and (kecamatan is None or c["id_kecamatan"] == kecamatan)
        and c["id_desa"] not in used
    ]
    chosen = random.choice(pool)
    used.add(chosen["id_desa"])
    return chosen


random.seed(SEED)
PWD_HASH = get_password_hash(DEFAULT_PASSWORD)
TODAY = date(2026, 10, 20)
NOW = datetime(2026, 10, 20, 9, 0, 0)

# --------------------------------------------------------------- bersih-bersih
print("1. Mengosongkan data legacy (masjid)...")
truncate([
    "bukukas_penerimaan", "bukukas_pengeluaran", "buku_aset", "kas", "berkas_laporan",
    "kegiatan", "struktur", "fasilitas", "bank", "medsos", "images", "logo",
    "qris", "document", "mitra_kerjasama", "videos",
    "postingan", "kajian", "acara", "cerita", "donasi", "pengumuman", "publikasi",
    "saran_dan_masukan", "pengajuan_upz_desa", "upz_desa",
    "bmfi_sejarah_dan_profil_microfinance", "bmfi_saldo_microfinance",
    "bmfi_skema_pinjaman", "bmfi_pilihan_skema_pinjaman", "bmfi_pendamping",
    "bmfi_peserta_program", "bmfi_transaksi_microfinance",
    "bmfi_skoring_asesmen_peserta_bmm", "bmfi_pemutihan_penghapusbukuan",
    "bmfi_bmm_operasional_kas_penerimaan", "bmfi_bmm_operasional_kas_pengeluaran",
])

print("2. Menghapus akun legacy jalur UPZ Desa/Ormas (akun BAZNAS dipertahankan)...")
cur.execute("DELETE FROM users WHERE jalur_akses IN ('UPZ Desa', 'Ormas')")
print(f"  - {cur.rowcount} akun legacy dihapus")

# ------------------------------------------------------------------- 40 UPZ
print("3. Membuat 40 UPZ Desa dummy...")
candidates = load_wilayah()
used_desa = set()

NAMA_UPZ = [
    "Baitul Hikmah", "Nurul Hidayah", "Al-Hidayah", "Baiturrahman", "Al-Ikhlas",
    "Nurul Iman", "Al-Falah", "Baitul Muttaqin", "Ar-Rahman", "Al-Muhajirin",
    "Nurul Huda", "Baitussalam", "Al-Amin", "Nurul Yaqin", "Al-Barokah",
    "Baitul Makmur", "Al-Fattah", "Nurul Hikmah", "Al-Mubarok", "Baitul Amin",
    "Al-Hikmah", "Nurul Hidayah", "Al-Wahid", "Baiturrohim", "Al-Kautsar",
    "Nurul Anwar", "Al-Mukhlisin", "Baitul Ihsan", "Al-Mujahidin", "Nurul Jannah",
    "Al-Qomar", "Baitul Jannah", "Al-Khoir", "Nurul Falah", "Al-Ma'ruf",
    "Baitul Rahmah", "Al-Huda", "Nurul Burhan", "Al-Azhar", "Baitul Karim",
]
JENIS = ["UPZ Desa", "UPZ Kelurahan"]

upz_rows = []
upz_meta = []  # (id, nama, kode, ids wilayah, admin_email, level)

# UPZ khusus akun uji
special = [
    {
        "id": "UPZ-8E8AEA", "kode": "UPZ-8E8AEA", "nama": "Al-istiqomah",
        "jenis": "UPZ Desa", "email": "pangestuakbar939@gmail.com",
        "kab": "3173", "alamat": "Jl. Tanjung Pura II, Pegadungan, Kalideres, Jakarta Barat",
    },
    {
        "id": "UPZ-HIDAYAH01", "kode": "UPZ-HIDAYAH01", "nama": "Jami Al-Hidayah Kubang Sepat",
        "jenis": "UPZ Desa", "email": "dkmalhidayah0107@gmail.com",
        "kab": "3672", "alamat": "Jl. Sutan Syahrir, Link. Kubang Sepat RT 01/07, Cilegon",
    },
]
for sp in special:
    w = pick_desa(candidates, used_desa, kabupaten=sp["kab"])
    upz_rows.append(dict(
        id_upz_desa=sp["id"], kode_org_baznas=sp["kode"], nama_upz_desa=sp["nama"],
        jenis_upz_desa=sp["jenis"], tipologi=sp["jenis"], alamat_upz_desa=sp["alamat"],
        wilayah=w, admin_email=sp["email"],
    ))

slugs = set()
for i, nama in enumerate(NAMA_UPZ):
    jenis = JENIS[i % len(JENIS)]
    w = pick_desa(candidates, used_desa)
    nama_lengkap = f"{nama} {w['desa']}" if nama != "Al-istiqomah" else nama
    base_slug = nama_lengkap.lower().replace("'", "").replace(" ", "-")
    base_slug = "".join(ch for ch in base_slug if ch.isalnum() or ch == "-")
    slug = base_slug
    n = 2
    while slug in slugs:
        slug = f"{base_slug}-{n}"
        n += 1
    slugs.add(slug)
    upz_rows.append(dict(
        id_upz_desa=f"UPZ-{w['id_desa']}",
        kode_org_baznas=str(w["id_desa"]),
        nama_upz_desa=nama_lengkap,
        jenis_upz_desa=jenis,
        tipologi=jenis,
        alamat_upz_desa=f"Jl. {w['kecamatan']} No. {random.randint(1, 90)}, {w['desa']}, {w['kabupaten']}",
        wilayah=w,
        admin_email=f"admin.upz{i + 1:02d}@upzdesa.test",
    ))

upz_db_rows = []
for idx, u in enumerate(upz_rows):
    w = u["wilayah"]
    slug_base = "".join(
        ch for ch in u["nama_upz_desa"].lower().replace("'", "").replace(" ", "-")
        if ch.isalnum() or ch == "-"
    )
    upz_db_rows.append(dict(
        id_upz_desa=u["id_upz_desa"],
        kode_org_baznas=u["kode_org_baznas"],
        nama_upz_desa=u["nama_upz_desa"],
        jenis_upz_desa=u["jenis_upz_desa"],
        tipologi=u["tipologi"],
        alamat_upz_desa=u["alamat_upz_desa"],
        status_upz_desa="Verified" if idx % 8 != 7 else "Not Verified",
        luas_tanah=random.choice([120, 200, 300, 500]),
        status_tanah="Wakaf",
        luas_bangunan=random.choice([80, 120, 150, 250]),
        daya_tampung=random.choice([100, 150, 200, 300, 500]),
        deskripsi=(
            f"UPZ Desa {u['nama_upz_desa']} melayani penghimpunan dan penyaluran "
            f"ZIS DSKL untuk warga {w['desa']}, {w['kecamatan']}, {w['kabupaten']}."
        ),
        slug_upz_desa=f"{slug_base}-{u['id_upz_desa'].lower()}",
        link_maps="",
        thumbnail="upz-desa-default.jpg",
        status_aktif=1,
        noregis_simas=f"REG-{w['id_desa']}",
        no_sk_upz=f"SK/UPZ/{w['id_desa']}/2025",
        kode_upz=idx + 1,
        nama_upz=u["nama_upz_desa"],
        label_upz=u["jenis_upz_desa"],
        id_provinsi=w["id_provinsi"],
        id_kabupaten_kota=w["id_kabupaten_kota"],
        id_kecamatan=w["id_kecamatan"],
        id_kelurahan_desa=w["id_desa"],
        email_upz_desa=u["admin_email"],
        nohp_upz_desa=f"08{random.randint(1000000000, 9999999999)}",
        web_upz_desa="",
        video_upz_desa="",
        show_saldo=1,
        created_at=NOW - timedelta(days=random.randint(30, 900)),
        updated_at=NOW,
    ))

insert("upz_desa", upz_db_rows)
UPZ = [
    dict(id=u["id_upz_desa"], nama=u["nama_upz_desa"], kode=u["kode_org_baznas"],
         jenis=u["jenis_upz_desa"], prov=u["id_provinsi"], kab=u["id_kabupaten_kota"],
         kec=u["id_kecamatan"], desa=u["id_kelurahan_desa"],
         admin=upz_rows[idx]["admin_email"])
    for idx, u in enumerate(upz_db_rows)
]

# --------------------------------------------------------------- akun & users
print("4. Membuat akun Admin UPZ Desa + pengurus...")
user_rows = []
user_id_by_upz = {}
for idx, u in enumerate(UPZ):
    user_rows.append(dict(
        jalur_akses=JALUR,
        nama=f"Admin {u['nama']}",
        jk=random.choice(["Laki-laki", "Perempuan"]),
        nohp=f"08{random.randint(1000000000, 9999999999)}",
        email=u["admin"],
        password=PWD_HASH,
        alamat=f"Kantor {u['nama']}",
        avatar="user-default.png",
        id_level=2,
        id_labels=1,
        status_aktif=1,
        id_jalur_akses=u["id"],
        id_provinsi=u["prov"],
        id_kabupaten_kota=u["kab"],
        id_kecamatan=u["kec"],
        id_kelurahan_desa=u["desa"],
        catatan="Seed dummy UPZ Desa",
        show_saldo=1,
        created_at=NOW - timedelta(days=random.randint(30, 700)),
        updated_at=NOW,
        kode_org_baznas=u["kode"],
    ))
    user_rows.append(dict(
        jalur_akses=JALUR,
        nama=f"Bendahara {u['nama']}",
        jk=random.choice(["Laki-laki", "Perempuan"]),
        nohp=f"08{random.randint(1000000000, 9999999999)}",
        email=f"bendahara.upz{idx + 1:02d}@upzdesa.test",
        password=PWD_HASH,
        alamat=f"Kantor {u['nama']}",
        avatar="user-default.png",
        id_level=8,
        id_labels=1,
        status_aktif=1,
        id_jalur_akses=u["id"],
        id_provinsi=u["prov"],
        id_kabupaten_kota=u["kab"],
        id_kecamatan=u["kec"],
        id_kelurahan_desa=u["desa"],
        catatan="Seed dummy UPZ Desa",
        show_saldo=1,
        created_at=NOW - timedelta(days=random.randint(30, 700)),
        updated_at=NOW,
        kode_org_baznas=u["kode"],
    ))
insert("users", user_rows)

cur.execute("SELECT id_user, email FROM users WHERE jalur_akses = %s", (JALUR,))
for uid, email in cur.fetchall():
    for idx, u in enumerate(UPZ):
        if email == u["admin"]:
            user_id_by_upz[u["id"]] = uid
        elif email == f"bendahara.upz{idx + 1:02d}@upzdesa.test":
            pass

# Admin Daerah (level 11) untuk akun uji Cypress — email @upzdesa.baznas.go.id
cur.execute("SELECT id_user FROM users WHERE email = 'baznas-provinsi@upzdesa.baznas.go.id'")
row = cur.fetchone()
if row:
    cur.execute(
        """UPDATE users SET nama='Admin Daerah (Jawa Tengah)', jalur_akses='Pusat',
           password=%s, id_level=11, id_labels=1, status_aktif=1,
           id_provinsi=33, id_kabupaten_kota=NULL, id_kecamatan=NULL
           WHERE id_user=%s""",
        (PWD_HASH, row[0]),
    )
else:
    cur.execute(
        """INSERT INTO users (jalur_akses, nama, jk, nohp, email, password, alamat,
           id_level, id_labels, status_aktif, id_provinsi, catatan, kode_org_baznas)
           VALUES ('Pusat','Admin Daerah (Jawa Tengah)','Laki-laki','081200000011',
           'baznas-provinsi@upzdesa.baznas.go.id',%s,'Kantor BAZNAS Jawa Tengah',
           11,1,1,33,'Seed dummy UPZ Desa','')""",
        (PWD_HASH,),
    )
print("  + akun Admin Daerah baznas-provinsi@upzdesa.baznas.go.id")

# ------------------------------------------------------------------- buku kas
print("5. Membuat buku kas 12 bulan terakhir...")
KETERANGAN_MASUK = [
    ("Zakat Mal dari muzakki", 11), ("Zakat Fitrah", 12),
    ("Infak Jumat", 13), ("Sedekah warga", 13), ("DSKL kegiatan sosial", 14),
    ("Kotak amal harian", 13), ("Donasi pembangunan", 13), ("Zakat penghasilan", 11),
]
KETERANGAN_KELUAR = [
    ("Santunan yatim & dhuafa", 13), ("Bantuan sembako", 14),
    ("Biaya operasional kantor", 13), ("Beasiswa anak asuh", 14),
    ("Bantuan renovasi rumah", 14), ("Kegiatan pengajian", 13),
]
penerimaan_rows, pengeluaran_rows = [], []
for u in UPZ:
    for m in range(12):
        bln = TODAY - timedelta(days=30 * m)
        for _ in range(random.randint(2, 3)):
            ket, dana = random.choice(KETERANGAN_MASUK)
            tgl = bln.replace(day=random.randint(1, 26))
            penerimaan_rows.append(dict(
                jalur_akses=JALUR, tanggal=tgl,
                nominal_masuk=str(random.randint(15, 400) * 10000),
                jumlah_donatur=random.randint(1, 40),
                keterangan=f"{ket} — {u['nama']}",
                lampiran="", id_jenis_dana=dana, id_jenis_harta=11,
                id_label_info_dana=random.choice([1, 2]), id_level=0,
                id_jalur_akses=u["id"], id_provinsi=str(u["prov"]),
                id_kabupaten_kota=str(u["kab"]), id_kecamatan=str(u["kec"]),
                id_kelurahan_desa=str(u["desa"]), created_at=NOW, updated_at=NOW,
                kode_org_baznas=u["kode"],
            ))
        for _ in range(random.randint(1, 2)):
            ket, dana = random.choice(KETERANGAN_KELUAR)
            tgl = bln.replace(day=random.randint(1, 26))
            pengeluaran_rows.append(dict(
                jalur_akses=JALUR, tanggal=tgl,
                nominal_keluar=str(random.randint(5, 150) * 10000),
                jumlah_penerima_manfaat=random.randint(1, 80),
                keterangan=f"{ket} — {u['nama']}",
                lampiran="", id_jenis_dana=dana, id_jenis_harta=11,
                id_label_info_dana=random.choice([1, 2]), id_level=0,
                id_jalur_akses=u["id"], nama_upz_desa=u["nama"],
                id_provinsi=str(u["prov"]), id_kabupaten_kota=str(u["kab"]),
                id_kecamatan=str(u["kec"]), id_kelurahan_desa=str(u["desa"]),
                created_at=NOW, updated_at=NOW, kode_org_baznas=u["kode"],
            ))

# UPZ "Al-istiqomah" wajib punya transaksi Zakat (uji Cypress Buku Kas)
for dana, nominal in ((11, "2500000"), (12, "1750000"), (13, "900000")):
    penerimaan_rows.append(dict(
        jalur_akses=JALUR, tanggal=TODAY - timedelta(days=5),
        nominal_masuk=nominal, jumlah_donatur=12,
        keterangan="Zakat Mal penghimpunan bulanan warga",
        lampiran="", id_jenis_dana=dana, id_jenis_harta=11,
        id_label_info_dana=1, id_level=0,
        id_jalur_akses=UPZ[0]["id"], id_provinsi=str(UPZ[0]["prov"]),
        id_kabupaten_kota=str(UPZ[0]["kab"]), id_kecamatan=str(UPZ[0]["kec"]),
        id_kelurahan_desa=str(UPZ[0]["desa"]), created_at=NOW, updated_at=NOW,
        kode_org_baznas=UPZ[0]["kode"],
    ))
insert("bukukas_penerimaan", penerimaan_rows)
insert("bukukas_pengeluaran", pengeluaran_rows)

# ------------------------------------------------------- detail & profil UPZ
print("6. Membuat detail UPZ (struktur, fasilitas, bank, medsos, galeri)...")
struktur_rows, fasilitas_rows, bank_rows, medsos_rows, images_rows = [], [], [], [], []
kegiatan_rows = []
NAMA_PENGURUS = ["H. Ahmad Fauzi", "Hj. Siti Aminah", "Muhammad Rizki", "Abdul Rahman",
                 "Nurhasanah", "Ust. Fahmi Idris"]
for i, u in enumerate(UPZ):
    for jabatan in ("Ketua", "Sekretaris", "Bendahara"):
        struktur_rows.append(dict(
            nama_struktur=random.choice(NAMA_PENGURUS),
            keterangan=f"{jabatan} {u['nama']}",
            jenis_struktur=jabatan, foto_struktur="user-default.png",
            parent_id=u["id"], created_at=NOW, updated_at=NOW,
        ))
    for fas in ("Aula Serbaguna", "Gudang Logistik", "Kantor UPZ", "Musholla"):
        fasilitas_rows.append(dict(
            parent_id=u["id"], nama_fasilitas=fas,
            keterangan=f"{fas} {u['nama']}", foto_fasilitas="",
            created_at=NOW, updated_at=NOW,
        ))
    bank_rows.append(dict(
        parent_id=u["id"], nama_bank="Bank Syariah Indonesia",
        norek=f"70{random.randint(10000000, 99999999)}",
        atas_nama=u["nama"], logo_bank="bsi.png",
        created_at=NOW, updated_at=NOW,
    ))
    for sos, url in (("Instagram", f"https://instagram.com/{u['id'].lower()}"),
                     ("Facebook", f"https://facebook.com/{u['id'].lower()}")):
        medsos_rows.append(dict(
            parent_id=u["id"], icon_medsos=f"{sos.lower()}.svg", name_medsos=sos,
            url_medsos=url, created_at=NOW, updated_at=NOW,
        ))
    for n in range(3):
        images_rows.append(dict(
            parent_id=u["id"], name=f"Kegiatan {n + 1}",
            src=f"https://picsum.photos/seed/u{i}{n}/600/400",
            description=f"Dokumentasi kegiatan {u['nama']}",
        ))
    for nama_keg, jenis_keg in (
        ("Santunan Anak Yatim", "Sosial"),
        ("Pengajian Rutin Mingguan", "Keagamaan"),
        ("Kerja Bakti Bersih Desa", "Sosial"),
    ):
        kegiatan_rows.append(dict(
            nama_kegiatan=f"{nama_keg} {u['nama']}", keterangan=f"{nama_keg} bersama warga {u['nama']}",
            jenis_kegiatan=jenis_keg, foto_kegiatan="", parent_id=u["id"],
            created_at=NOW, updated_at=NOW,
        ))
insert("struktur", struktur_rows)
insert("fasilitas", fasilitas_rows)
insert("bank", bank_rows)
insert("medsos", medsos_rows)
insert("images", images_rows)
insert("kegiatan", kegiatan_rows)

# logo / qris / dokumen / mitra — hanya untuk 10 UPZ pertama
logo_rows, qris_rows, dok_rows, mitra_rows = [], [], [], []
for u in UPZ[:10]:
    logo_rows.append(dict(
        nama_logo=f"Logo {u['nama']}", keterangan="Logo resmi UPZ Desa",
        jenis_logo="Utama", gambar_logo="logo-upzDesa2.webp", parent_id=u["id"],
        created_at=NOW, updated_at=NOW,
    ))
    qris_rows.append(dict(
        nama_qris=f"QRIS {u['nama']}", keterangan="QRIS donasi ZIS",
        gambar_qris="qris.png", parent_id=u["id"], created_at=NOW, updated_at=NOW,
    ))
    dok_rows.append(dict(
        parent_id=u["id"], document_name="SK UPZ Desa", document_src="sk-upz.pdf",
        document_type="Umum", document_number=f"SK/{u['kode']}/2025",
        document_category="Publik", document_date=TODAY - timedelta(days=200),
        is_active=1,
    ))
    mitra_rows.append(dict(
        nama_mitra_kerjasama="BAZNAS Kabupaten/Kota", keterangan="Mitra penyaluran ZIS",
        gambar_mitra_kerjasama="baznas.png", parent_id=u["id"],
        created_at=NOW, updated_at=NOW,
    ))
insert("logo", logo_rows)
insert("qris", qris_rows)
insert("document", dok_rows)
insert("mitra_kerjasama", mitra_rows)

# --------------------------------------------------------------- publikasi
print("7. Membuat konten publikasi UPZ...")
postingan_rows, kajian_rows, acara_rows, cerita_rows, donasi_rows, pengumuman_rows = [], [], [], [], [], []
for i, u in enumerate(UPZ):
    uid = user_id_by_upz.get(u["id"])
    judul_post = f"Laporan Penghimpunan ZIS {u['nama']}"
    postingan_rows.append(dict(
        tgl_postingan=TODAY - timedelta(days=random.randint(5, 90)), judul=judul_post,
        penulis="Admin UPZ Desa",
        keterangan=f"<p>{judul_post} periode bulan ini. Alhamdulillah dana ZIS tersalurkan tepat sasaran.</p>",
        poster="", slug_postingan=f"{u['id'].lower()}-laporan-penghimpunan",
        status_aktif=1, id_kategori_postingan=1, id_user=uid or 0,
        id_upz_desa=u["id"], video_postingan=None, created_at=NOW, updated_at=NOW,
    ))
    kajian_rows.append(dict(
        tgl_kajian=TODAY - timedelta(days=random.randint(3, 60)),
        judul=f"Kajian Fiqih Zakat — {u['nama']}", pemateri="Ust. Fahmi Idris",
        keterangan="<p>Kajian rutin membahas fiqih zakat, infak, dan sedekah.</p>",
        poster="", slug_kajian=f"{u['id'].lower()}-kajian-fiqih-zakat",
        status_aktif=1, id_tema=1 if i % 2 == 0 else 2, id_user=uid or 0,
        id_upz_desa=u["id"], nama_upz_desa=u["nama"], video_kajian=None,
        created_at=NOW, updated_at=NOW,
    ))
    acara_rows.append(dict(
        tgl_acara=TODAY + timedelta(days=random.randint(3, 45)),
        judul=f"Bakti Sosial {u['nama']}", penulis="Admin UPZ Desa",
        keterangan="<p>Penyaluran sembako untuk warga dhuafa.</p>",
        poster="", slug_acara=f"{u['id'].lower()}-bakti-sosial",
        status_aktif=1, id_kategori_acara=1, id_user=uid or 0,
        id_upz_desa=u["id"], nama_upz_desa=u["nama"], video_acara=None,
        created_at=NOW, updated_at=NOW,
    ))
    cerita_rows.append(dict(
        tgl_cerita=TODAY - timedelta(days=random.randint(5, 80)),
        judul=f"Kisah Mustahik Binaan {u['nama']}", penulis="Admin UPZ Desa",
        keterangan="<p>Usaha kecil mustahik bangkit setelah menerima bantuan modal ZIS.</p>",
        poster="", slug_cerita=f"{u['id'].lower()}-kisah-mustahik",
        status_aktif=1, id_kategori_cerita=1, id_user=uid or 0,
        id_upz_desa=u["id"], video_cerita=None, created_at=NOW, updated_at=NOW,
    ))
    target = random.randint(20, 100) * 1000000
    donasi_rows.append(dict(
        tgl_donasi=TODAY - timedelta(days=random.randint(5, 50)),
        judul=f"Donasi Renovasi Rumah Dhuafa — {u['nama']}", penulis="Admin UPZ Desa",
        keterangan="<p>Mari bersama membantu renovasi rumah dhuafa di lingkungan UPZ Desa.</p>",
        jumlah_dana_terkumpul=str(int(target * random.uniform(0.2, 0.9))),
        target_pengumpulan_dana=str(target),
        batas_tgl_pengumpulan=(TODAY + timedelta(days=60)).isoformat(),
        kontak_informasi="081200000000", progres_status=1, poster="",
        rekap_data_pengumpulan="", slug_donasi=f"{u['id'].lower()}-donasi-renovasi",
        status_aktif=1, id_kategori_donasi=1, id_user=uid or 0,
        id_upz_desa=u["id"], video_donasi=None, created_at=NOW, updated_at=NOW,
    ))
    pengumuman_rows.append(dict(
        tgl_pengumuman=TODAY - timedelta(days=random.randint(1, 30)),
        judul=f"Pengumuman Jadwal Penyaluran ZIS {u['nama']}", penulis="Admin UPZ Desa",
        keterangan="<p>Penyaluran ZIS dilaksanakan setiap Jumat di kantor UPZ Desa.</p>",
        poster="", slug_pengumuman=f"{u['id'].lower()}-jadwal-penyaluran",
        status_aktif=1, id_kategori_pengumuman=1, id_user=uid or 0,
        id_upz_desa=u["id"], video_pengumuman=None, created_at=NOW, updated_at=NOW,
    ))
insert("postingan", postingan_rows)
insert("kajian", kajian_rows)
insert("acara", acara_rows)
insert("cerita", cerita_rows)
insert("donasi", donasi_rows)
insert("pengumuman", pengumuman_rows)

# publikasi unified (dashboard Menu Publikasi) — 5 baris contoh
publikasi_rows = []
JENIS_PUBLIKASI = ["postingan", "materi-khutbah", "media-info", "jadwal-kajian", "video-kajian"]
for i, jenis in enumerate(JENIS_PUBLIKASI):
    u = UPZ[i + 1]
    publikasi_rows.append(dict(
        jenis=jenis, judul=f"Contoh {jenis.replace('-', ' ').title()} — {u['nama']}",
        slug=f"contoh-{jenis}-{u['id'].lower()}", keterangan="Contoh konten menu publikasi",
        gambar="", status_aktif=1, konten="<p>Konten contoh menu publikasi UPZ Desa.</p>",
        kategori="Umum", pemateri="", tanggal=TODAY - timedelta(days=10),
        url="", tipe_media="", hari="", jam="", lokasi="", video_url="",
        target_dana=None, dana_terkumpul=0, tanggal_berakhir=None,
        tanggal_mulai=None, tanggal_selesai=None, penulis="Admin UPZ Desa",
        id_upz_desa=u["id"], id_user=user_id_by_upz.get(u["id"]),
        created_at=NOW, updated_at=NOW,
    ))
insert("publikasi", publikasi_rows)

# --------------------------------------------------------------- buku aset
print("8. Membuat buku aset + saran & masukan...")
aset_rows = []
for u in UPZ[:15]:
    for nama, harga in (("Tenda Kegiatan", "4500000"), ("Sound System", "7500000"),
                        ("Laptop Operasional", "8500000")):
        aset_rows.append(dict(
            jalur_akses=JALUR, tanggal=TODAY - timedelta(days=random.randint(30, 500)),
            harga_perolehan=harga, nama_barang=f"{nama} {u['nama']}", jumlah=1,
            lokasi=f"Kantor {u['nama']}", tahun_perolehan=2025, lampiran="",
            id_jenis_perolehan_aset=11, id_level=0, id_jalur_akses=u["id"],
            id_provinsi=str(u["prov"]), id_kabupaten_kota=str(u["kab"]),
            id_kecamatan=str(u["kec"]), id_kelurahan_desa=str(u["desa"]),
            catatan="", created_at=NOW, updated_at=NOW,
        ))
insert("buku_aset", aset_rows)

saran_rows = []
for u in UPZ[:4]:
    saran_rows.append(dict(
        jalur_akses=JALUR, judul_saran_dan_masukan="Masukan tampilan dashboard",
        deskripsi_saran_dan_masukan=f"Mohon tambahkan filter wilayah pada dashboard {u['nama']}.",
        balasan_saran_dan_masukan="Terima kasih, sedang kami kembangkan.",
        lampiran="", id_level=0, id_jalur_akses=u["id"],
        id_provinsi=str(u["prov"]), id_kabupaten_kota=str(u["kab"]),
        id_kecamatan=str(u["kec"]), id_kelurahan_desa=str(u["desa"]),
        created_at=NOW, updated_at=NOW, kode_org_baznas=u["kode"],
    ))
insert("saran_dan_masukan", saran_rows)

# ------------------------------------------------------------- microfinance
print("9. Membuat data microfinance BMM (UPZ Jami Al-Hidayah Kubang Sepat)...")
bmm = next(u for u in UPZ if u["id"] == "UPZ-HIDAYAH01")
bmm_user = user_id_by_upz.get(bmm["id"], 0)

cur.execute(
    """INSERT INTO bmfi_sejarah_dan_profil_microfinance
       (tgl_bmfi_sejarah_dan_profil_microfinance, judul, tahun_pengesahan,
        nomor_kerangka_acuan_kerja, jumlah_peserta_awal_pengesahan,
        nominal_saldo_awal_pengesahan, keterangan, poster,
        slug_bmfi_sejarah_dan_profil_microfinance, status_aktif,
        id_bmfi_kategori_pengesahan_microfinance, id_user, id_upz_desa,
        updated_at, created_at)
       VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)""",
    (TODAY - timedelta(days=300), f"Microfinance {bmm['nama']}", "2025",
     "B/DPBZ-DPDG/020/KAK/2025", "50", "150000000",
     "<p>Program BMM untuk pemberdayaan usaha mustahik.</p>", "",
     f"{bmm['id'].lower()}-sejarah-profil", 1, 1, bmm_user, bmm["id"], NOW, NOW),
)
cur.execute(
    """INSERT INTO bmfi_saldo_microfinance
       (nama_bmfi_saldo_microfinance, nominal_saldo_microfinance, saldo_awal_penerimaan,
        pemberian_saldo_awal, keterangan, berkas_bmfi_saldo_microfinance, parent_id,
        created_at, updated_at)
       VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)""",
    ("Saldo BAZNAS RI", "58091000", "58125000", "0",
     "Saldo microfinance per Oktober 2026", "upz-desa-default.jpg", bmm["id"], NOW, NOW),
)
skema_rows = [
    dict(nama_bmfi_skema_pinjaman="Pekanan", parent_id=JALUR, created_at=NOW),
    dict(nama_bmfi_skema_pinjaman="Dua Pekanan", parent_id=JALUR, created_at=NOW),
    dict(nama_bmfi_skema_pinjaman="Bulanan", parent_id=JALUR, created_at=NOW),
]
insert("bmfi_skema_pinjaman", skema_rows)

pilihan_rows = [
    dict(nama_pilihan_skema_pinjaman="Pinjaman Rp1.000.000", jumlah_nominal="1000000",
         keterangan="Skema 10 periode", parent_id=bmm["id"], created_at=NOW, updated_at=NOW),
    dict(nama_pilihan_skema_pinjaman="Pinjaman Rp1.500.000", jumlah_nominal="1500000",
         keterangan="Skema 10 periode", parent_id=bmm["id"], created_at=NOW, updated_at=NOW),
    dict(nama_pilihan_skema_pinjaman="Pinjaman Rp2.000.000", jumlah_nominal="2000000",
         keterangan="Skema 10 periode", parent_id=bmm["id"], created_at=NOW, updated_at=NOW),
    dict(nama_pilihan_skema_pinjaman="Pinjaman Rp3.000.000", jumlah_nominal="3000000",
         keterangan="Skema 10 periode", parent_id=bmm["id"], created_at=NOW, updated_at=NOW),
]
insert("bmfi_pilihan_skema_pinjaman", pilihan_rows)

pendamping_rows = []
for i, nama in enumerate(("Ahmad Subkhi", "Nurul Hidayati", "Rizal Fahmi")):
    pendamping_rows.append(dict(
        hash_blockchain_peserta_bmm="0", hash_blockchain_upz_desa=bmm["id"],
        nama_pendamping_bmfi_asesmen=nama, jenis_kelamin="Laki-laki" if i != 1 else "Perempuan",
        no_ktp=f"367208{random.randint(1000000000, 9999999999)}",
        no_hp=f"08{random.randint(1000000000, 9999999999)}",
        tempat_tanggal_lahir="Cilegon, 01-01-1985",
        alamat="Link. Kubang Sepat, Citangkil, Cilegon",
        keterangan="Pendamping BMM", foto_profil_pendamping_bmfi_asesmen="",
        berkas_riwayat_hidup_pendamping="", parent_id=bmm["id"],
        created_at=NOW, updated_at=NOW,
    ))
insert("bmfi_pendamping", pendamping_rows)
cur.execute("SELECT id_pendamping_bmfi_asesmen FROM bmfi_pendamping")
pendamping_ids = [r[0] for r in cur.fetchall()]

NAMA_PESERTA = [
    "H. MUHAMMAD SOLEH", "SITI KOMARIAH", "NENENG SULHIYATI", "TUTI", "MUHASIM",
    "RUSLIA M ARIF", "SAMSUDIN", "SUMARDI", "NASIAH", "ARMAN SYATRI",
]
peserta_rows = []
for i in range(50):
    nama = NAMA_PESERTA[i % len(NAMA_PESERTA)]
    if i % 10 == 5:
        nama = f"PESERTA BMM {i + 1:02d}"
    pinjaman = random.choice([1000000, 1500000, 2000000, 2500000, 3000000])
    peserta_rows.append(dict(
        kode_id_unik_peserta_bmm=f"PBM{ i + 1:04d}",
        hash_blockchain_peserta_bmm="0", hash_blockchain_upz_desa=bmm["id"],
        nama_peserta_program_bmfi_asesmen=nama,
        no_ktp=f"367208{random.randint(1000000000, 9999999999)}",
        no_hp=f"08{random.randint(1000000000, 9999999999)}",
        status_aktif=1, jalur_akses=JALUR,
        jenis_kelamin="Laki-laki" if i % 2 == 0 else "Perempuan",
        tempat_tanggal_lahir="Serang, 05-03-1975",
        alamat_rumah="Link. Kubang Sepat RT 01/07, Citangkil, Cilegon",
        nama_usaha=random.choice(["Warung Sembako", "Penjahit", "Katering", "Ternak Ayam"]),
        lokasi_usaha="Link. Kubang Sepat", jumlah_anggota_keluarga=str(random.randint(2, 6)),
        nama_penjamin="Pendamping BMM", hasil_dampak_kemajuan="Pendapatan meningkat",
        pendapatan_sebelum="Rp 500.000 - Rp 1.000.000",
        pendapatan_sesudah="Rp 1.000.000 - Rp 2.000.000",
        keterangan="Pengembangan usaha", jumlah_pinjaman=str(pinjaman),
        sisa_dana_pengembalian=str(int(pinjaman * random.uniform(0.2, 0.9))),
        perjanjian_tanggal_pengembalian_peserta=10, nomor_urut_peserta=i + 1,
        id_jenis_angsuran=str(13), foto_dampak_program="",
        id_pendamping_bmfi_asesmen=pendamping_ids[i % len(pendamping_ids)],
        parent_id=bmm["id"], id_provinsi=str(bmm["prov"]),
        id_kabupaten_kota=str(bmm["kab"]), id_kecamatan=str(bmm["kec"]),
        id_kelurahan_desa=str(bmm["desa"]), kode_org_baznas=bmm["kode"],
        created_at=NOW, tgl_registrasi_peserta=TODAY - timedelta(days=200 - i),
        updated_at=NOW,
    ))
# pastikan "H. MUHAMMAD SOLEH" ada di halaman pertama (order by PK desc)
peserta_rows[-1]["nama_peserta_program_bmfi_asesmen"] = "H. MUHAMMAD SOLEH"
insert("bmfi_peserta_program", peserta_rows)
cur.execute("SELECT id_peserta_program_bmfi_asesmen FROM bmfi_peserta_program")
peserta_ids = [r[0] for r in cur.fetchall()]

transaksi_rows = []
for i in range(100):
    tgl = TODAY - timedelta(days=random.randint(5, 330))
    peserta_id = random.choice(peserta_ids)
    pinjam = random.choice([0, 1000000, 1500000, 2000000, 2500000])
    kembali = random.choice([0, 100000, 200000, 300000, 500000]) if not pinjam else 0
    transaksi_rows.append(dict(
        jalur_akses=JALUR, hash_blockchain_peserta_bmm="0",
        hash_blockchain_upz_desa=bmm["id"], tanggal=tgl,
        nominal_pengembalian=str(kembali), infak_peserta=str(random.choice([0, 5000, 10000])),
        sisa_dana_cicilan_pengembalian="0", nominal_peminjaman=str(pinjam),
        keterangan_transaksi="Pencairan pinjaman" if pinjam else "Angsuran pinjaman",
        lampiran="", id_bmfi_peserta_program=peserta_id,
        id_bmfi_pendamping=pendamping_ids[i % len(pendamping_ids)],
        id_bmfi_jenis_angsuran=13, id_level=0, id_jalur_akses=bmm["id"],
        nama_upz_desa=bmm["nama"],
        no_bmm_transaksi_microfinance=f"TRX-{tgl.strftime('%Y%m%d')}-{i + 1:03d}",
        id_provinsi=str(bmm["prov"]), id_kabupaten_kota=str(bmm["kab"]),
        id_kecamatan=str(bmm["kec"]), id_kelurahan_desa=str(bmm["desa"]),
        created_at=NOW, updated_at=NOW, kode_org_baznas=bmm["kode"],
    ))
insert("bmfi_transaksi_microfinance", transaksi_rows)

# ---------------------------------------------------------------- pengajuan
print("10. Membuat pengajuan UPZ Desa (pending/disetujui/ditolak)...")
pengajuan_rows = []
for i, (nama, status) in enumerate((
    ("Nurul Iman Sukamaju", "pending"),
    ("Al-Barokah Cibeber", "pending"),
    ("Baitul Makmur Wanayasa", "pending"),
    ("Al-Hikmah Pegadungan", "disetujui"),
    ("Nurul Falah Cilegon", "disetujui"),
    ("Al-Ma'ruf Karangtengah", "ditolak"),
)):
    w = pick_desa(candidates, used_desa)
    pengajuan_rows.append(dict(
        nama_upz_desa=nama, alamat_upz_desa=f"Jl. {w['kecamatan']} No. {i + 2}, {w['desa']}",
        email_upz_desa=f"pengajuan{i + 1}@upzdesa.test",
        nohp_upz_desa=f"08{random.randint(1000000000, 9999999999)}",
        id_provinsi=w["id_provinsi"], id_kabupaten_kota=w["id_kabupaten_kota"],
        id_kecamatan=w["id_kecamatan"], id_kelurahan_desa=w["id_desa"],
        status_pengajuan=status, kode_org_baznas=f"UPZ-P{i + 1:04d}",
        slug_upz_desa=nama.lower().replace(" ", "-").replace("'", ""),
        nama_pemohon=random.choice(NAMA_PENGURUS),
        email_pemohon=f"pemohon{i + 1}@upzdesa.test",
        jk_pemohon="Laki-laki", nohp_pemohon=f"08{random.randint(1000000000, 9999999999)}",
        alamat_pemohon=f"Jl. {w['kecamatan']}, {w['desa']}",
        id_user=None, created_at=NOW - timedelta(days=random.randint(1, 60)),
        updated_at=NOW,
    ))
insert("pengajuan_upz_desa", pengajuan_rows)

# --------------------------------------------------------------- finalisasi
print("11. Finalisasi (enum jenis, coa, teks legacy)...")
cur.execute(
    "ALTER TABLE `upz_desa` MODIFY `jenis_upz_desa` "
    "ENUM('UPZ Desa','UPZ Kelurahan','UPZ Kecamatan') NULL"
)
cur.execute("UPDATE `coa` SET `nama`='Operasional UPZ Desa' WHERE `nama`='Operasional Masjid'")
cur.execute("UPDATE `coa` SET `nama`='Kegiatan Jumat' WHERE `nama`='Kegiatan Sholat Jumat'")
cur.execute("UPDATE `coa` SET `nama`='Gaji Amil' WHERE `nama`='Gaji Marbot'")

conn.commit()

print("\n" + "=" * 60)
print("  Seed dummy UPZ Desa selesai")
print("=" * 60)
cur.execute("SELECT COUNT(*) FROM upz_desa")
print(f"  UPZ Desa          : {cur.fetchone()[0]}")
cur.execute("SELECT COUNT(*) FROM users WHERE jalur_akses='UPZ Desa'")
print(f"  Akun UPZ Desa     : {cur.fetchone()[0]}")
cur.execute("SELECT COUNT(*) FROM bukukas_penerimaan")
print(f"  Bukukas masuk     : {cur.fetchone()[0]}")
cur.execute("SELECT COUNT(*) FROM bukukas_pengeluaran")
print(f"  Bukukas keluar    : {cur.fetchone()[0]}")
cur.execute("SELECT COUNT(*) FROM postingan")
print(f"  Postingan         : {cur.fetchone()[0]}")
cur.execute("SELECT COUNT(*) FROM bmfi_peserta_program")
print(f"  Peserta BMM       : {cur.fetchone()[0]}")
print("\n  Akun uji (password: upzdesa23):")
print("   - Admin UPZ Desa  : pangestuakbar939@gmail.com (Al-istiqomah)")
print("   - Admin UPZ BMM   : dkmalhidayah0107@gmail.com (Jami Al-Hidayah Kubang Sepat)")
print("   - Admin Daerah    : baznas-provinsi@upzdesa.baznas.go.id")
print("   - Admin UPZ lain  : admin.upz01..40@upzdesa.test")
print("=" * 60)

cur.close()
conn.close()
