<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Struk Parkir - {{ $parkir->kode_tiket }}</title>

<style>
@page {
    size: 80mm 200mm;
    margin: 0;
}

body {
    margin: 0;
    padding: 0;
    font-family: 'Courier New', monospace;
    font-size: 11px;
    background: #fff;
    display: flex;
    justify-content: center;
}

/* 80mm kira kira 302px */
.receipt {
    width: 302px;
    padding: 10px 12px;
    text-align: left;
    box-sizing: border-box;
}

.center { text-align: center; }
.brand { font-weight: 700; font-size: 14px; }
.muted { font-size: 10px; color: #444; }

.hr {
    border-top: 1px dashed #000;
    margin: 8px 0;
}

.line {
    display: flex;
    justify-content: space-between;
    margin: 4px 0;
    font-size: 11px;
}

.big {
    font-size: 13px;
    font-weight: 700;
}

.barcode-wrap {
    text-align: center;
    margin-top: 8px;
}

.note {
    margin-top: 6px;
    font-size: 10px;
}

@media print {
    body {
        display: flex;
        justify-content: center;
    }

    .receipt {
        margin: auto;
    }
}
</style>
</head>

<body onload="triggerPrint()">

<div class="receipt">
    <div class="center brand">PARKIRKU</div>
    <div class="center muted">Jl. Contoh No.1 - Kota</div>

    <div class="hr"></div>

    <div class="line"><div>ID</div><div class="big">{{ $parkir->kode_tiket }}</div></div>
    <div class="line"><div>Tanggal</div><div>{{ \Carbon\Carbon::parse($parkir->waktu_masuk)->format('d/m/Y') }}</div></div>
    <div class="line"><div>Masuk</div><div>{{ \Carbon\Carbon::parse($parkir->waktu_masuk)->format('H:i:s') }}</div></div>
    <div class="line"><div>Plat</div><div>{{ $parkir->plat_nomor }}</div></div>
    <div class="line"><div>Jenis</div><div>{{ $parkir->kendaraan->jenis_kendaraan ?? '-' }}</div></div>
    <div class="line"><div>Tarif</div><div>{{ $parkir->tarif ?? '-' }}</div></div>

    <div class="hr"></div>

    <div class="barcode-wrap"><svg id="barcode"></svg></div>
    <div class="center muted">Kode: {{ $parkir->kode_tiket }}</div>

    <div class="hr"></div>

    <div class="note">* Simpan struk ini untuk keluar.</div>
    <div class="note">* Tunjukkan kode ke petugas.</div>

    <div class="center muted" style="margin-top:6px;">Terima kasih!</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
function triggerPrint() {
    JsBarcode("#barcode", "{{ $parkir->kode_tiket }}", {
        format: "CODE128",
        displayValue: false,
        width: 1.2,
        height: 35,
        margin: 0
    });

    setTimeout(() => {
        window.print();
        window.location.href = "{{ route('home') }}";
    }, 300);
}
</script>
</body>
</html>
