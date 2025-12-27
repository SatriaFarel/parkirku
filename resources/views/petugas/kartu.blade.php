<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kartu Member</title>

<style>
@page {
    size: 85mm 54mm; /* ukuran kartu */
    margin: 0;
}

body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: #0f172a;
}

/* Kartu */
.card {
    width: 85mm;
    height: 54mm;
    border-radius: 12px;
    color: #fff;
    padding: 10px;
    box-sizing: border-box;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    margin: 20px auto;
    position: relative;
}

/* FRONT */
.card-front {
    background: linear-gradient(135deg, #1e3a8a, #2563eb, #0f172a);
}

.header {
    text-align: center;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 6px;
}

.info {
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
    padding: 6px 8px;
    font-size: 11px;
}

.info p {
    margin: 2px 0;
}

.footer {
    position: absolute;
    bottom: 6px;
    right: 8px;
    font-size: 9px;
    opacity: 0.8;
}

/* BACK */
.card-back {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.card-back .title {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}

/* QR wrapper (SUPER PENTING) */
.qr-box {
    background: #ffffff;  /* wajib putih */
    padding: 8px;         /* quiet zone */
    border-radius: 6px;
}

.card-back .desc {
    font-size: 10px;
    text-align: center;
    margin-top: 6px;
    line-height: 1.2;
    color: #e5e7eb;
}

/* PRINT */
@media print {
    body {
        background: white;
    }
    .card {
        box-shadow: none;
        margin: 0 auto;
        page-break-after: always;
    }
}
</style>
</head>

<body>

<!-- FRONT -->
<div class="card card-front">
    <div class="header">KARTU MEMBER RESMI</div>
    <div class="info">
        <p><strong>ID Member:</strong> {{ $member->id }}</p>
        <p><strong>Nama:</strong> {{ $member->nama }}</p>
        <p><strong>Status:</strong> {{ ucfirst($member->status) }}</p>
        <p><strong>Bergabung:</strong> {{ $member->created_at->format('d M Y') }}</p>
    </div>
    <div class="footer">© 2025 ParkirKu</div>
</div>

<!-- BACK -->
<div class="card card-back">
    <div class="title">Scan untuk detail member</div>

    <div class="qr-box">
        <div id="qrcode-back"></div>
    </div>

    <div class="desc">
        {{ $member->nama }}<br>
        Status: {{ ucfirst($member->status) }}
    </div>
</div>

<!-- QR CODE JS -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
const memberID = "{{ $member->id }}";

new QRCode(document.getElementById("qrcode-back"), {
    text: memberID,              // bisa ganti URL/member code
    width: 120,
    height: 120,
    colorDark: "#000000",        // QR hitam
    colorLight: "#ffffff",       // background putih
    correctLevel: QRCode.CorrectLevel.H
});
</script>

</body>
</html>
