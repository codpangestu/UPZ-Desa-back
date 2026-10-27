from sqlalchemy import Column, String, Integer, BigInteger, DateTime, Text
from shared.config.database import Base
import datetime


class PengajuanUpzDesa(Base):
    __tablename__ = "pengajuan_upz_desa"
    id_pengajuan = Column(Integer, primary_key=True, autoincrement=True)
    nama_upz_desa = Column(String(255), nullable=True)
    alamat_upz_desa = Column(Text, nullable=True)
    email_upz_desa = Column(String(255), nullable=True)
    nohp_upz_desa = Column(String(50), nullable=True)
    id_provinsi = Column(BigInteger, nullable=True)
    id_kabupaten_kota = Column(BigInteger, nullable=True)
    id_kecamatan = Column(BigInteger, nullable=True)
    id_kelurahan_desa = Column(BigInteger, nullable=True)
    status_pengajuan = Column(String(50), nullable=True, default="Menunggu")
    # Kode UPZ Desa (kode_org_baznas) — diterbitkan LANGSUNG saat pengajuan dibuat
    # (tidak menunggu approval), dipakai Admin BAZNAS untuk aktivasi upz_desa + admin.
    kode_org_baznas = Column(String(50), nullable=True)
    # Slug upz_desa — dibuat langsung bersama kode saat pengajuan dibuat.
    slug_upz_desa = Column(String(255), nullable=True)
    # Data admin pemohon (daftar upz_desa sekaligus admin-nya)
    nama_pemohon = Column(String(255), nullable=True)
    email_pemohon = Column(String(255), nullable=True)
    jk_pemohon = Column(String(10), nullable=True)
    nohp_pemohon = Column(String(50), nullable=True)
    alamat_pemohon = Column(Text, nullable=True)
    id_user = Column(Integer, nullable=True)
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.datetime.utcnow, onupdate=datetime.datetime.utcnow)


class PengajuanUserUpzDesa(Base):
    __tablename__ = "pengajuan_user_upz_desa"
    id_pengajuan = Column(Integer, primary_key=True, autoincrement=True)
    id_upz_desa = Column(String(50), nullable=True)
    nama = Column(String(255), nullable=True)
    email = Column(String(255), nullable=True)
    nohp = Column(String(50), nullable=True)
    status_pengajuan = Column(String(50), nullable=True, default="Menunggu")
    created_at = Column(DateTime, default=datetime.datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.datetime.utcnow, onupdate=datetime.datetime.utcnow)
