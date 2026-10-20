# Application-wide constants

# Response status codes
STATUS_SUCCESS = "000"
STATUS_API_KEY_INVALID = "101"
STATUS_UNAUTHORIZED = "401"
STATUS_FORBIDDEN = "403"
STATUS_NOT_FOUND = "404"
STATUS_INTERNAL_ERROR = "500"

# User access paths
JALUR_MASJID = "Masjid"
JALUR_PUSAT = "Pusat"

# Default values
DEFAULT_PASSWORD = "upzdesa23"
DEFAULT_AVATAR = "user-default.png"
DEFAULT_MASJID_THUMBNAIL = "masjid-default.jpg"

# Pagination
DEFAULT_PAGE = 1
DEFAULT_PER_PAGE = 10
MAX_PER_PAGE = 100

# Default user level
DEFAULT_USER_LEVEL = 3

# Level Admin KUA (kolom users.id_level) — mengikuti baris tabel `levels` legacy
# di DB (10=Admin BAZNAS Pusat, 11=Admin BAZNAS Provinsi/Admin Daerah,
# 12=Admin BAZNAS Kabupaten Kota/Admin Kab, 13=Admin KUA). JANGAN pakai 4/5/6 —
# id itu sudah dipakai data legacy lain (Anggota/Admin Masjid/Admin Monitor).
# Dipakai untuk memisahkan opsi login "Admin KUA" dari "Admin BAZNAS".
LEVEL_ADMIN_KUA = 13

# Upload directories
UPLOAD_DIRS = [
    "uploads",
    "uploads/masjid",
    "uploads/foto",
    "uploads/dokumen",
    "uploads/profil",
    "uploads/kajian",
    "uploads/postingan",
]
