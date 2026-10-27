from sqlalchemy.orm import Session
from typing import Optional
from shared.models.microfinance import (
    BmfiSejarahDanProfilMicrofinance, BmfiSaldoMicrofinance,
    BmfiPilihanSkemaPinjaman, BmfiPendamping, BmfiPesertaProgram,
    BmfiTransaksiMicrofinance, BmfiSkoringAsesmenPesertaBmm,
    BmfiBmmOperasionalKasPenerimaan, BmfiBmmOperasionalKasPengeluaran,
    BmfiPemutihanPenghapusbukuan,
)

_MODELS = {
    "sejarah-profil": (BmfiSejarahDanProfilMicrofinance, "id_upz_desa"),
    "saldo": (BmfiSaldoMicrofinance, "parent_id"),
    "pilihan-skema-pinjaman": (BmfiPilihanSkemaPinjaman, "parent_id"),
    "pendamping": (BmfiPendamping, "parent_id"),
    "peserta-program": (BmfiPesertaProgram, "parent_id"),
    "transaksi": (BmfiTransaksiMicrofinance, "id_jalur_akses"),
    "skoring-asesmen": (BmfiSkoringAsesmenPesertaBmm, "id_upz_desa_bmfi_bmm"),
    "operasional-kas-penerimaan": (BmfiBmmOperasionalKasPenerimaan, "id_jalur_akses"),
    "operasional-kas-pengeluaran": (BmfiBmmOperasionalKasPengeluaran, "id_jalur_akses"),
    "pemutihan": (BmfiPemutihanPenghapusbukuan, "id_upz_desa"),
}


def list_by_tipe(
    db: Session,
    tipe: str,
    id_upz_desa: Optional[str] = None,
    page: int = 1,
    per_page: int = 10,
):
    if tipe not in _MODELS:
        return None, None, f"Tipe microfinance '{tipe}' tidak dikenal"
    model, upz_desa_field = _MODELS[tipe]
    q = db.query(model)
    if id_upz_desa:
        q = q.filter(getattr(model, upz_desa_field) == id_upz_desa)
    pk_col = list(model.__table__.primary_key.columns)[0]
    total = q.count()
    data = q.order_by(pk_col.desc()).offset((page - 1) * per_page).limit(per_page).all()
    return data, total, None


def summary_saldo(db: Session, id_upz_desa: str) -> dict:
    """
    Ringkasan mutasi microfinance per upz_desa (mirror tampilan "Mutasi
    Transaksi Microfinance" di live site): saldo tersedia, dana masih
    di peserta (outstanding), total saldo keseluruhan, dan total infak.
    """
    def to_num(v) -> float:
        try:
            return float(v) if v not in (None, "") else 0.0
        except (TypeError, ValueError):
            return 0.0

    saldo_row = db.query(BmfiSaldoMicrofinance).filter(
        BmfiSaldoMicrofinance.parent_id == id_upz_desa
    ).order_by(BmfiSaldoMicrofinance.id_bmfi_saldo_microfinance.desc()).first()
    saldo_tersedia = to_num(saldo_row.nominal_saldo_microfinance) if saldo_row else 0.0

    peserta_rows = db.query(BmfiPesertaProgram).filter(
        BmfiPesertaProgram.parent_id == id_upz_desa
    ).all()
    outstanding = sum(to_num(p.sisa_dana_pengembalian) for p in peserta_rows)

    transaksi_rows = db.query(BmfiTransaksiMicrofinance).filter(
        BmfiTransaksiMicrofinance.id_jalur_akses == id_upz_desa
    ).all()
    total_infak = sum(to_num(t.infak_peserta) for t in transaksi_rows)

    return {
        "saldo_tersedia": saldo_tersedia,
        "dana_di_peserta": outstanding,
        "total_saldo_keseluruhan": saldo_tersedia + outstanding,
        "total_infak_peserta": total_infak,
    }
