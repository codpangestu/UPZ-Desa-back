from sqlalchemy import Column, String, Integer, DateTime, Text, Date
from shared.config.database import Base
import datetime


class BmfiKategoriPengesahanMicrofinance(Base):
    __tablename__ = "bmfi_kategori_pengesahan_microfinance"
    id_bmfi_kategori_pengesahan_microfinance = Column(Integer, primary_key=True, autoincrement=True)
    nama_bmfi_kategori_pengesahan_microfinance = Column(String(50), nullable=False)
    parent_id = Column(String(100), nullable=False)
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)


class BmfiJenisAngsuran(Base):
    __tablename__ = "bmfi_jenis_angsuran"
    id_bmfi_jenis_angsuran = Column(Integer, primary_key=True, autoincrement=True)
    nama_bmfi_jenis_angsuran = Column(String(25), nullable=True)
    parent_id = Column(String(25), nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, nullable=True)


class BmfiSkemaPinjaman(Base):
    __tablename__ = "bmfi_skema_pinjaman"
    id_bmfi_skema_pinjaman = Column(Integer, primary_key=True, autoincrement=True)
    nama_bmfi_skema_pinjaman = Column(String(40), nullable=True)
    parent_id = Column(String(40), nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, nullable=True)


class BmfiSejarahDanProfilMicrofinance(Base):
    __tablename__ = "bmfi_sejarah_dan_profil_microfinance"
    id_bmfi_sejarah_dan_profil_microfinance = Column(Integer, primary_key=True, autoincrement=True)
    tgl_bmfi_sejarah_dan_profil_microfinance = Column(Date, nullable=True)
    judul = Column(String(191), nullable=True)
    tahun_pengesahan = Column(String(100), nullable=True)
    nomor_kerangka_acuan_kerja = Column(String(100), nullable=True)
    jumlah_peserta_awal_pengesahan = Column(String(100), nullable=True)
    nominal_saldo_awal_pengesahan = Column(String(100), nullable=True)
    keterangan = Column(Text, nullable=True)
    poster = Column(String(191), nullable=True)
    slug_bmfi_sejarah_dan_profil_microfinance = Column(String(191), nullable=True)
    status_aktif = Column(Integer, nullable=True)
    id_bmfi_kategori_pengesahan_microfinance = Column(Integer, nullable=True)
    id_user = Column(Integer, nullable=False)
    id_masjid = Column(String(100), nullable=True)
    video_bmfi_sejarah_dan_profil_microfinance = Column(Text, nullable=True)
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)


class BmfiSaldoMicrofinance(Base):
    __tablename__ = "bmfi_saldo_microfinance"
    id_bmfi_saldo_microfinance = Column(Integer, primary_key=True, autoincrement=True)
    nama_bmfi_saldo_microfinance = Column(String(191), nullable=True)
    nominal_saldo_microfinance = Column(String(100), nullable=True, default="0")
    saldo_awal_penerimaan = Column(String(100), nullable=True, default="0")
    pemberian_saldo_awal = Column(String(100), nullable=True, default="0")
    keterangan = Column(Text, nullable=True)
    berkas_bmfi_saldo_microfinance = Column(String(191), nullable=True, default="masjid-default.jpg")
    parent_id = Column(String(100), nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, nullable=True)


class BmfiPilihanSkemaPinjaman(Base):
    __tablename__ = "bmfi_pilihan_skema_pinjaman"
    id_pilihan_skema_pinjaman = Column(Integer, primary_key=True, autoincrement=True)
    nama_pilihan_skema_pinjaman = Column(String(191), nullable=True)
    jumlah_nominal = Column(String(191), nullable=False, default="0")
    keterangan = Column(Text, nullable=True)
    berkas_pilihan_skema_pinjaman = Column(String(191), nullable=True)
    parent_id = Column(String(100), nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, nullable=True)


class BmfiPendamping(Base):
    __tablename__ = "bmfi_pendamping"
    id_pendamping_bmfi_asesmen = Column(Integer, primary_key=True, autoincrement=True)
    hash_blockchain_peserta_bmm = Column(String(191), nullable=False, default="0")
    hash_blockchain_masjid = Column(String(191), nullable=True)
    nama_pendamping_bmfi_asesmen = Column(String(191), nullable=True)
    jenis_kelamin = Column(String(191), nullable=True)
    no_ktp = Column(String(100), nullable=True)
    no_hp = Column(String(100), nullable=True)
    tempat_tanggal_lahir = Column(String(191), nullable=True)
    alamat = Column(String(191), nullable=True)
    keterangan = Column(Text, nullable=True)
    foto_profil_pendamping_bmfi_asesmen = Column(String(191), nullable=True)
    berkas_riwayat_hidup_pendamping = Column(String(191), nullable=True)
    parent_id = Column(String(100), nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, nullable=True)


class BmfiPesertaProgram(Base):
    __tablename__ = "bmfi_peserta_program"
    id_peserta_program_bmfi_asesmen = Column(Integer, primary_key=True, autoincrement=True)
    kode_id_unik_peserta_bmm = Column(String(50), nullable=False, default="0")
    hash_blockchain_peserta_bmm = Column(String(191), nullable=True)
    hash_blockchain_masjid = Column(String(191), nullable=True)
    nama_peserta_program_bmfi_asesmen = Column(String(191), nullable=True)
    no_ktp = Column(String(100), nullable=True)
    no_hp = Column(String(100), nullable=True)
    password = Column(String(100), nullable=True)
    id_level = Column(Integer, nullable=True)
    id_labels = Column(Integer, nullable=True)
    status_aktif = Column(Integer, nullable=True)
    jalur_akses = Column(String(50), nullable=True)
    last_login = Column(DateTime, nullable=True)
    jenis_kelamin = Column(String(100), nullable=True)
    tempat_tanggal_lahir = Column(String(150), nullable=True)
    alamat_rumah = Column(String(191), nullable=True)
    foto_rumah = Column(String(191), nullable=True)
    kordinat_latitude_rumah = Column(String(191), nullable=True)
    kordinat_longitude_rumah = Column(String(191), nullable=True)
    foto_lokasi_usaha = Column(String(191), nullable=True)
    lokasi_usaha = Column(String(191), nullable=True)
    nama_usaha = Column(String(191), nullable=True)
    no_rekening = Column(String(191), nullable=True)
    atas_nama_rekening = Column(String(191), nullable=True)
    nama_bank = Column(String(191), nullable=True)
    slug_peserta_program = Column(String(191), nullable=True)
    no_kode_peserta_program = Column(String(191), nullable=True)
    jumlah_anggota_keluarga = Column(String(191), nullable=True)
    nama_penjamin = Column(String(191), nullable=True)
    id_jenis_asnaf = Column(String(191), nullable=True, default="2")
    id_status_hubungan_penjamin = Column(String(191), nullable=True)
    id_sektor_usaha_peserta = Column(String(191), nullable=True)
    id_kategori_kualitas_angsuran_peserta = Column(String(191), nullable=True)
    id_rating_dampak_kemajuan_peserta = Column(String(191), nullable=True)
    id_jenis_program_pendampingan_peserta = Column(String(191), nullable=True)
    hasil_dampak_kemajuan = Column(Text, nullable=True)
    pendapatan_sebelum = Column(String(191), nullable=True)
    pendapatan_sesudah = Column(String(191), nullable=True)
    nilai_jumlah_intervensi_bantuan = Column(String(191), nullable=True)
    durasi_pendampingan_peserta = Column(String(191), nullable=True)
    keterangan = Column(Text, nullable=True)
    jumlah_pinjaman = Column(String(191), nullable=False, default="0")
    sisa_dana_pengembalian = Column(String(100), nullable=False, default="0")
    perjanjian_tanggal_pengembalian_peserta = Column(Integer, nullable=False, default=0)
    nomor_urut_peserta = Column(Integer, nullable=False, default=0)
    id_jenis_angsuran = Column(String(100), nullable=True)
    id_anggaran_microfinance = Column(Integer, nullable=True)
    lampiran_pelengkap = Column(String(191), nullable=True)
    foto_dampak_program = Column(String(191), nullable=True, default="-")
    foto_profil_peserta_program_bmfi_asesmen = Column(String(191), nullable=True)
    id_pendamping_bmfi_asesmen = Column(Integer, nullable=True)
    parent_id = Column(String(100), nullable=True)
    id_provinsi = Column(String(100), nullable=True)
    id_kabupaten_kota = Column(String(100), nullable=True)
    id_kecamatan = Column(String(100), nullable=True)
    id_kelurahan_desa = Column(String(100), nullable=True)
    kode_org_baznas = Column(String(100), nullable=True)
    kordinat_latitude_lokasi_usaha = Column(String(100), nullable=True)
    kordinat_longitude_lokasi_usaha = Column(String(100), nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    tgl_registrasi_peserta = Column(Date, nullable=True)
    updated_at = Column(DateTime, nullable=True)


class BmfiTransaksiMicrofinance(Base):
    __tablename__ = "bmfi_transaksi_microfinance"
    id_bmfi_transaksi_microfinance = Column(Integer, primary_key=True, autoincrement=True)
    jalur_akses = Column(String(100), nullable=True)
    hash_blockchain_peserta_bmm = Column(String(191), nullable=True)
    hash_blockchain_masjid = Column(String(191), nullable=True)
    tanggal = Column(Date, nullable=True)
    nominal_pengembalian = Column(String(50), nullable=False, default="0")
    infak_peserta = Column(String(50), nullable=False, default="0")
    sisa_dana_cicilan_pengembalian = Column(String(50), nullable=False, default="0")
    nominal_peminjaman = Column(String(50), nullable=False, default="0")
    keterangan_transaksi = Column(Text, nullable=True)
    lampiran = Column(String(100), nullable=True)
    id_bmfi_peserta_program = Column(Integer, nullable=True)
    id_bmfi_pendamping = Column(Integer, nullable=True)
    id_bmfi_jenis_angsuran = Column(Integer, nullable=True)
    id_level = Column(Integer, nullable=True)
    id_bmm_peserta_jenis_pembayaran = Column(Integer, nullable=True, default=1)
    id_jalur_akses = Column(String(191), nullable=True)
    nama_masjid = Column(String(191), nullable=True)
    no_bmm_transaksi_microfinance = Column(String(191), nullable=True)
    id_provinsi = Column(String(2), nullable=True)
    id_kabupaten_kota = Column(String(4), nullable=True)
    id_kecamatan = Column(String(7), nullable=True)
    id_kelurahan_desa = Column(String(10), nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, nullable=True)
    kode_org_baznas = Column(String(100), nullable=True)


class BmmLabelInfoDanaKasOperasional(Base):
    """Master: sumber dana masuk kas operasional BMM."""
    __tablename__ = "bmm_label_info_dana_kas_operasional"
    id_bmm_label_info_dana_kas_operasional = Column(Integer, primary_key=True, autoincrement=True)
    nama_bmm_label_info_dana_kas_operasional = Column(String(100), nullable=True)
    parent_id = Column(String(25), nullable=True, default="Masjid")
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)


class BmmLabelPenggunaanKasOperasional(Base):
    """Master: kategori penggunaan dana kas operasional BMM."""
    __tablename__ = "bmm_label_penggunaan_kas_operasional"
    id_bmm_label_penggunaan_kas_operasional = Column(Integer, primary_key=True, autoincrement=True)
    nama_bmm_label_penggunaan_kas_operasional = Column(String(100), nullable=True)
    parent_id = Column(String(25), nullable=True, default="Masjid")
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)


class BmfiBmmOperasionalKasPenerimaan(Base):
    __tablename__ = "bmfi_bmm_operasional_kas_penerimaan"
    id_bmfi_bmm_operasional_kas_penerimaan = Column(Integer, primary_key=True, autoincrement=True)
    jalur_akses = Column(String(100), nullable=True)
    tanggal = Column(Date, nullable=True)
    nominal_masuk = Column(String(30), nullable=True)
    jumlah_donatur = Column(String(8), nullable=True)
    keterangan = Column(Text, nullable=True)
    lampiran = Column(String(100), nullable=True)
    id_bmm_label_info_dana_kas_operasional = Column(Integer, nullable=True, default=21)
    id_level = Column(Integer, nullable=True)
    id_jalur_akses = Column(String(191), nullable=True)
    id_provinsi = Column(String(2), nullable=True)
    id_kabupaten_kota = Column(String(4), nullable=True)
    id_kecamatan = Column(String(7), nullable=True)
    id_kelurahan_desa = Column(String(10), nullable=True)
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)
    kode_org_baznas = Column(String(100), nullable=True)
    slug_bmfi_bmm_operasional_kas_penerimaan = Column(String(191), nullable=True)
    no_transaksi_bmfi_bmm_operasional_kas_penerimaan = Column(String(191), nullable=True)
    no_bmm_transaksi_microfinance_pengembalian_dan_infak_peserta = Column(String(191), nullable=True)
    id_status_validasi_bukukas = Column(String(100), nullable=True, default="0")
    terbilang_nominal = Column(String(191), nullable=True)


class BmfiBmmOperasionalKasPengeluaran(Base):
    __tablename__ = "bmfi_bmm_operasional_kas_pengeluaran"
    id_bmfi_bmm_operasional_kas_pengeluaran = Column(Integer, primary_key=True, autoincrement=True)
    jalur_akses = Column(String(100), nullable=True)
    tanggal = Column(Date, nullable=True)
    nominal_keluar = Column(String(30), nullable=True, default="0")
    jumlah_penerima_manfaat = Column(String(10), nullable=True, default="0")
    keterangan = Column(Text, nullable=True)
    lampiran = Column(String(100), nullable=True)
    id_bmm_label_penggunaan_kas_operasional = Column(Integer, nullable=True, default=21)
    id_level = Column(Integer, nullable=True)
    id_jalur_akses = Column(String(191), nullable=True)
    id_provinsi = Column(String(2), nullable=True)
    id_kabupaten_kota = Column(String(4), nullable=True)
    id_kecamatan = Column(String(7), nullable=True)
    id_kelurahan_desa = Column(String(10), nullable=True)
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)
    kode_org_baznas = Column(String(100), nullable=True)
    slug_bmfi_bmm_operasional_kas_pengeluaran = Column(String(191), nullable=True)
    no_transaksi_bmfi_bmm_operasional_kas_pengeluaran = Column(String(191), nullable=True)
    no_bmm_transaksi_microfinance = Column(String(191), nullable=True)
    id_status_validasi_bukukas = Column(String(100), nullable=True, default="0")
    terbilang_nominal = Column(String(191), nullable=True)


class BmfiKategoriPemutihanPenghapusbukuan(Base):
    """Master: kategori pemutihan/penghapusbukuan (Meninggal, Diputihkan, ...)."""
    __tablename__ = "bmfi_kategori_pemutihan_penghapusbukuan"
    id_bmfi_kategori_pemutihan_penghapusbukuan = Column(Integer, primary_key=True, autoincrement=True)
    nama_bmfi_kategori_pemutihan_penghapusbukuan = Column(String(50), nullable=False)
    parent_id = Column(String(50), nullable=False, default="0")
    created_at = Column(DateTime, nullable=True)
    updated_at = Column(DateTime, nullable=True)


class BmfiPemutihanPenghapusbukuan(Base):
    __tablename__ = "bmfi_pemutihan_penghapusbukuan"
    id_bmfi_pemutihan_penghapusbukuan = Column(Integer, primary_key=True, autoincrement=True)
    tgl_bmfi_pemutihan_penghapusbukuan = Column(Date, nullable=True)
    judul = Column(String(191), nullable=True)
    jumlah_nominal_pemutihan_penghapusbukuan = Column(Integer, nullable=True)
    keterangan = Column(Text, nullable=True)
    gambar_foto = Column(String(191), nullable=True)
    slug_bmfi_pemutihan_penghapusbukuan = Column(String(191), nullable=True)
    status_bmfi_pemutihan_penghapusbukuan = Column(String(100), nullable=True)
    id_bmfi_kategori_pemutihan_penghapusbukuan = Column(Integer, nullable=True)
    peserta_program_bmfi_pemutihan_penghapusbukuan = Column(String(191), nullable=True)
    pendamping_bmfi_pemutihan_penghapusbukuan = Column(String(191), nullable=True)
    no_bmm_transaksi_microfinance = Column(String(191), nullable=True)
    id_user = Column(Integer, nullable=False)
    id_masjid = Column(String(100), nullable=False)
    video_bmfi_pemutihan_penghapusbukuan = Column(Text, nullable=True)
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)
    id_provinsi = Column(String(100), nullable=True)
    id_kabupaten_kota = Column(String(100), nullable=True)
    id_kecamatan = Column(String(100), nullable=True)
    id_kelurahan_desa = Column(String(100), nullable=True)
    kode_org_baznas = Column(String(100), nullable=True)


class BmfiSkoringAsesmenPesertaBmm(Base):
    __tablename__ = "bmfi_skoring_asesmen_peserta_bmm"
    id_bmfi_skoring_asesmen_peserta_bmm = Column(Integer, primary_key=True)
    tgl_bmfi_skoring_asesmen_peserta_bmm = Column(Date, nullable=True)
    keterangan = Column(Text, nullable=True)
    hash_blockchain_peserta_bmm = Column(String(191), nullable=True)
    hash_blockchain_masjid = Column(String(191), nullable=True)
    id_peserta_program_bmfi_bmm = Column(String(191), nullable=True)
    id_masjid_bmfi_bmm = Column(String(100), nullable=False)
    id_user_login_peserta_microfinance_bmm = Column(Integer, nullable=False, default=0)
    experience_in_proposed_business = Column("1_experience_in_proposed_business", Integer, nullable=False, default=0)
    growth_potential = Column("2_growth_potential", Integer, nullable=False, default=0)
    monthly_saving_capacity = Column("3_monthly_saving_capacity", Integer, nullable=False, default=0)
    capital_turnover = Column("4_capital_turnover", Integer, nullable=False, default=0)
    payment_technique = Column("5_payment_technique", Integer, nullable=False, default=0)
    sales_payment_facility = Column("6_sales_payment_facility", Integer, nullable=False, default=0)
    average_revenue = Column("7_average_revenue", Integer, nullable=False, default=0)
    consumer_demand_level = Column("8_consumer_demand_level", Integer, nullable=False, default=0)
    sales_reach = Column("9_sales_reach", Integer, nullable=False, default=0)
    pendidikan = Column("10_pendidikan", Integer, nullable=False, default=0)
    usia = Column("11_usia", Integer, nullable=False, default=0)
    Total_skor = Column(String(50), nullable=False, default="0")
    Hasil_keterangan = Column(String(100), nullable=False, default="0")
    Rekomendasi_plafon = Column(String(100), nullable=False, default="0")
    updated_at = Column(DateTime, nullable=True)
    created_at = Column(DateTime, nullable=True)
