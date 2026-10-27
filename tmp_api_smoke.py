"""Temp smoke test: login + endpoint utama tiap service (masjid -> upz_desa)."""
import json
import urllib.request
import urllib.error

KEY = "upzdesa23apikey"
PWD = "upzdesa23"

BASES = {
    "auth": "http://127.0.0.1:8001",
    "upz": "http://127.0.0.1:8002",
    "content": "http://127.0.0.1:8003",
    "trx": "http://127.0.0.1:8004",
    "admin": "http://127.0.0.1:8005",
}


def call(method, base, path, token=None, body=None, api_key=True):
    url = BASES[base] + path
    data = json.dumps(body).encode() if body is not None else None
    req = urllib.request.Request(url, data=data, method=method)
    req.add_header("Content-Type", "application/json")
    if api_key:
        req.add_header("x-api-key", KEY)
    if token:
        req.add_header("Authorization", "Bearer " + token)
    try:
        with urllib.request.urlopen(req, timeout=20) as r:
            return r.status, json.loads(r.read().decode() or "{}")
    except urllib.error.HTTPError as e:
        raw = e.read().decode()
        try:
            return e.code, json.loads(raw or "{}")
        except Exception:
            return e.code, {"raw": raw[:200]}
    except Exception as e:  # noqa: BLE001
        return 0, {"err": str(e)}


def login(role_ep, email):
    st, js = call("POST", "auth", role_ep, body={"email": email, "password": PWD})
    if st != 200:
        return None, None, (st, js)
    return js.get("access_token"), js.get("user"), (st, js)


def keys_of(js):
    d = js.get("data") if isinstance(js, dict) else None
    if isinstance(d, list) and d:
        return sorted(d[0].keys()) if isinstance(d[0], dict) else type(d[0]).__name__
    if isinstance(d, dict):
        return sorted(d.keys())
    return type(d).__name__


def show(label, st, js):
    bad = [k for k in ("masjid",) if k in json.dumps(js).lower()]
    print(f"{label:<46} {st:<4} {str(keys_of(js))[:110]}" + ("  !! mengandung 'masjid'" if bad else ""))


def main():
    token, user, raw = login("/api/v1/login/admin-upz-desa", "pangestuakbar939@gmail.com")
    print("login admin-upz:", raw[0], "user keys:", sorted(user.keys()) if user else raw)
    if not user:
        return
    print("  id_upz_desa:", user.get("id_upz_desa"), "| jalur:", user.get("jalur_akses"), "| level:", user.get("id_level"))
    id_upz = user.get("id_upz_desa")

    show("GET /me", *call("GET", "auth", "/api/v1/me", token))
    show("GET upz-desa/list", *call("GET", "upz", "/api/v1/upz-desa/list?page=1&per_page=2", token))
    show("GET upz-desa/detail/{id}", *call("GET", "upz", f"/api/v1/upz-desa/detail/{id_upz}", token))
    show("GET upz-desa/by-wilayah", *call("GET", "upz", "/api/v1/upz-desa/by-wilayah?id_provinsi=31&per_page=2", token))
    show("GET upz-desa/pengurus/{id}", *call("GET", "upz", f"/api/v1/upz-desa/pengurus/{id_upz}", token))
    show("GET rekap/penerimaan", *call("GET", "upz", "/api/v1/rekap/penerimaan", token))
    show("GET rekap/pengeluaran", *call("GET", "upz", "/api/v1/rekap/pengeluaran", token))
    show("GET rekap/by-upz-desa/{id}", *call("GET", "upz", f"/api/v1/rekap/by-upz-desa/{id_upz}", token))
    show("GET rekap/bulanan/{id}", *call("GET", "upz", f"/api/v1/rekap/bulanan/{id_upz}?tahun=2026", token))
    show("GET provinsi", *call("GET", "upz", "/api/v1/provinsi"))

    show("GET content/home", *call("GET", "content", "/api/v1/home"))
    show("GET content/informasi", *call("GET", "content", "/api/v1/informasi?page=1&per_page=2"))
    show("GET content/upz-desa/{slug}", *call("GET", "content", "/api/v1/upz-desa/upz-8e8aea"))
    show("GET content/upz-desa/search", *call("GET", "content", "/api/v1/upz-desa/search?limit=2"))
    show("GET content/publikasi/kajian", *call("GET", "content", "/api/v1/publikasi/kajian/list?page=1&per_page=2"))

    show("GET bukukas-penerimaan/list", *call("GET", "trx", "/api/v1/bukukas-penerimaan/list?page=1&per_page=2", token))
    show("GET bukukas-penerimaan/cc", *call("GET", "trx", "/api/v1/bukukas-penerimaan/command-center", token))
    show("GET bukukas-pengeluaran/list", *call("GET", "trx", "/api/v1/bukukas-pengeluaran/list?page=1&per_page=2", token))
    show("GET microfinance/peserta/list", *call("GET", "trx", "/api/v1/microfinance/peserta/list?page=1&per_page=2", token))
    show("GET microfinance data-pengurus", *call("GET", "trx", "/api/v1/admin/pengurus/list?page=1&per_page=2", token))

    show("GET admin/pengajuan/list", *call("GET", "admin", "/api/v1/admin/pengajuan/list?page=1&per_page=2", token))
    show("GET admin/users/list", *call("GET", "admin", "/api/v1/users/list?page=1&per_page=2", token))
    show("GET admin/master/levels", *call("GET", "admin", "/api/v1/levels", token))
    show("GET admin/master/jenis-dana", *call("GET", "admin", "/api/v1/jenis-dana", token))
    show("GET admin/wilayah/provinsi", *call("GET", "admin", "/api/v1/provinsi", token))
    show("GET admin/user/detail", *call("GET", "admin", f"/api/v1/users/detail/{user.get('id_user')}", token))

    # Admin BAZNAS (pusat/daerah)
    t2, u2, raw2 = login("/api/v1/login/admin-pusat", "baznas-provinsi@upzdesa.baznas.go.id")
    print("\nlogin admin-pusat:", raw2[0], "user:", (u2 or {}).get("email"), "| level:", (u2 or {}).get("id_level"))
    if t2:
        show("GET upz-desa/list (baznas)", *call("GET", "upz", "/api/v1/upz-desa/list?page=1&per_page=2", t2))
        show("GET rekap/penerimaan (baznas)", *call("GET", "upz", "/api/v1/rekap/penerimaan", t2))
        show("GET admin/pengajuan/list (baznas)", *call("GET", "admin", "/api/v1/admin/pengajuan/list?page=1&per_page=2", t2))
        show("GET admin/upz-desa/create probe", *call("POST", "admin", "/api/v1/admin/upz-desa/create", t2, body={}))


if __name__ == "__main__":
    main()
