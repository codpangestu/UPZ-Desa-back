import datetime

from sqlalchemy.orm import Session
from sqlalchemy import func
from typing import Optional
from shared.models.transaksi import BukukasPenerimaan, BukukasPengeluaran


def _to_float(val) -> float:
    """Konversi Decimal/Numeric/None dari agregasi SQL ke float aman JSON."""
    return float(val) if val is not None else 0.0


def get_rekap_penerimaan(
    db: Session,
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
):
    q = db.query(
        func.count(func.distinct(BukukasPenerimaan.id_jalur_akses)).label("jumlah_upz_desa"),
        func.coalesce(func.sum(BukukasPenerimaan.nominal_masuk), 0).label("total_penerimaan"),
        func.coalesce(func.sum(BukukasPenerimaan.jumlah_donatur), 0).label("total_donatur"),
    )

    if id_provinsi:
        q = q.filter(BukukasPenerimaan.id_provinsi == id_provinsi)
    if id_kabupaten_kota:
        q = q.filter(BukukasPenerimaan.id_kabupaten_kota == id_kabupaten_kota)
    if id_kecamatan:
        q = q.filter(BukukasPenerimaan.id_kecamatan == id_kecamatan)
    if not id_provinsi and not id_kabupaten_kota and not id_kecamatan and kode_org_baznas:
        q = q.filter(BukukasPenerimaan.kode_org_baznas == kode_org_baznas)

    if start_date:
        q = q.filter(BukukasPenerimaan.tanggal >= start_date)
    if end_date:
        q = q.filter(BukukasPenerimaan.tanggal <= end_date)

    return q.first()


def get_rekap_pengeluaran(
    db: Session,
    kode_org_baznas: Optional[str] = None,
    id_provinsi: Optional[int] = None,
    id_kabupaten_kota: Optional[int] = None,
    id_kecamatan: Optional[int] = None,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
):
    q = db.query(
        func.count(func.distinct(BukukasPengeluaran.id_jalur_akses)).label("jumlah_upz_desa"),
        func.coalesce(func.sum(BukukasPengeluaran.nominal_keluar), 0).label("total_pengeluaran"),
        func.coalesce(func.sum(BukukasPengeluaran.jumlah_penerima_manfaat), 0).label("total_penerima_manfaat"),
    )

    if id_provinsi:
        q = q.filter(BukukasPengeluaran.id_provinsi == id_provinsi)
    if id_kabupaten_kota:
        q = q.filter(BukukasPengeluaran.id_kabupaten_kota == id_kabupaten_kota)
    if id_kecamatan:
        q = q.filter(BukukasPengeluaran.id_kecamatan == id_kecamatan)
    if not id_provinsi and not id_kabupaten_kota and not id_kecamatan and kode_org_baznas:
        q = q.filter(BukukasPengeluaran.kode_org_baznas == kode_org_baznas)

    if start_date:
        q = q.filter(BukukasPengeluaran.tanggal >= start_date)
    if end_date:
        q = q.filter(BukukasPengeluaran.tanggal <= end_date)

    return q.first()


def get_rekap_by_upz_desa(
    db: Session,
    id_upz_desa: str,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
) -> dict:
    p_query = db.query(
        func.coalesce(func.sum(BukukasPenerimaan.nominal_masuk), 0).label("total_penerimaan"),
        func.coalesce(func.sum(BukukasPenerimaan.jumlah_donatur), 0).label("total_donatur"),
    ).filter(BukukasPenerimaan.id_jalur_akses == id_upz_desa)

    pe_query = db.query(
        func.coalesce(func.sum(BukukasPengeluaran.nominal_keluar), 0).label("total_pengeluaran"),
        func.coalesce(func.sum(BukukasPengeluaran.jumlah_penerima_manfaat), 0).label("total_penerima_manfaat"),
    ).filter(BukukasPengeluaran.id_jalur_akses == id_upz_desa)

    if start_date:
        p_query = p_query.filter(BukukasPenerimaan.tanggal >= start_date)
        pe_query = pe_query.filter(BukukasPengeluaran.tanggal >= start_date)
    if end_date:
        p_query = p_query.filter(BukukasPenerimaan.tanggal <= end_date)
        pe_query = pe_query.filter(BukukasPengeluaran.tanggal <= end_date)

    p = p_query.first()
    pe = pe_query.first()

    result = {"id_upz_desa": id_upz_desa}
    if p:
        result.update(dict(p._mapping))
    if pe:
        result.update(dict(pe._mapping))
    return result


def get_rekap_bulanan_by_upz_desa(
    db: Session,
    id_upz_desa: str,
    tahun: Optional[int] = None,
    start_date: Optional[str] = None,
    end_date: Optional[str] = None,
) -> dict:
    """Rekap penerimaan vs pengeluaran per bulan untuk 1 upz_desa (FR-2.5).

    Dua mode (FR-2.6):
    - ``tahun`` saja  → 12 entri Jan–Des tahun tsb (default: tahun berjalan).
    - ``start_date``/``end_date`` → entri per bulan dalam rentang tsb.

    Bulan tanpa transaksi tetap dikembalikan dengan nilai 0 supaya frontend
    tinggal render grafik tanpa perlu fill gap.
    """
    today = datetime.date.today()

    # Tentukan rentang bulan (list 'YYYY-MM')
    if start_date and end_date:
        try:
            d_start = datetime.date.fromisoformat(start_date).replace(day=1)
            d_end = datetime.date.fromisoformat(end_date).replace(day=1)
        except ValueError:
            raise ValueError("Format tanggal harus YYYY-MM-DD")
        if d_end < d_start:
            d_start, d_end = d_end, d_start
    else:
        year = int(tahun) if tahun else today.year
        d_start = datetime.date(year, 1, 1)
        d_end = datetime.date(year, 12, 1)

    periode_list: list[str] = []
    cursor = d_start
    while cursor <= d_end:
        periode_list.append(cursor.strftime("%Y-%m"))
        # naik 1 bulan
        cursor = datetime.date(cursor.year + (1 if cursor.month == 12 else 0),
                               1 if cursor.month == 12 else cursor.month + 1, 1)

    base_filter_p = [BukukasPenerimaan.id_jalur_akses == id_upz_desa]
    base_filter_pe = [BukukasPengeluaran.id_jalur_akses == id_upz_desa]
    if start_date and end_date:
        base_filter_p.append(BukukasPenerimaan.tanggal >= start_date)
        base_filter_p.append(BukukasPenerimaan.tanggal <= end_date)
        base_filter_pe.append(BukukasPengeluaran.tanggal >= start_date)
        base_filter_pe.append(BukukasPengeluaran.tanggal <= end_date)
    else:
        base_filter_p.append(func.year(BukukasPenerimaan.tanggal) == d_start.year)
        base_filter_pe.append(func.year(BukukasPengeluaran.tanggal) == d_start.year)

    p_rows = (
        db.query(
            func.date_format(BukukasPenerimaan.tanggal, "%Y-%m").label("periode"),
            func.coalesce(func.sum(BukukasPenerimaan.nominal_masuk), 0).label("total"),
        )
        .filter(*base_filter_p)
        .group_by("periode")
        .all()
    )
    pe_rows = (
        db.query(
            func.date_format(BukukasPengeluaran.tanggal, "%Y-%m").label("periode"),
            func.coalesce(func.sum(BukukasPengeluaran.nominal_keluar), 0).label("total"),
        )
        .filter(*base_filter_pe)
        .group_by("periode")
        .all()
    )

    p_map = {r.periode: _to_float(r.total) for r in p_rows}
    pe_map = {r.periode: _to_float(r.total) for r in pe_rows}

    bulanan = [
        {
            "periode": periode,
            "penerimaan": p_map.get(periode, 0.0),
            "pengeluaran": pe_map.get(periode, 0.0),
        }
        for periode in periode_list
    ]
    total_penerimaan = sum(b["penerimaan"] for b in bulanan)
    total_pengeluaran = sum(b["pengeluaran"] for b in bulanan)

    return {
        "id_upz_desa": id_upz_desa,
        "start_date": d_start.isoformat(),
        "end_date": d_end.isoformat(),
        "bulanan": bulanan,
        "total_penerimaan": total_penerimaan,
        "total_pengeluaran": total_pengeluaran,
        "saldo": total_penerimaan - total_pengeluaran,
    }
