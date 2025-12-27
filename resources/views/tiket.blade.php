<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tiket Parkir | {{ $code }}</title>
    <style>
        body {
            font-family: "Courier New", monospace;
            background: #f5f5f5;
            padding: 30px 0;
            display: flex;
            justify-content: center;
        }

        .ticket {
            background: #fff;
            width: 270px;
            border: 1px dashed #333;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #333;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header small {
            display: block;
            font-size: 11px;
            color: #555;
        }

        .qr {
            text-align: center;
            margin: 10px 0;
        }

        #qrcode {
            display: inline-block;
            margin-top: 5px;
        }

        .info {
            font-size: 13px;
            line-height: 1.4;
            border-top: 1px dashed #333;
            border-bottom: 1px dashed #333;
            padding: 6px 0;
            margin: 6px 0;
        }

        .info p {
            margin: 2px 0;
        }

        .note {
            font-size: 11px;
            color: #666;
            text-align: center;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
        }

        .footer strong {
            display: block;
            margin-bottom: 3px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .ticket {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="ticket">
        <div class="header">
            <h2>PARKIR CENTER</h2>
            <small>Jl. Merdeka No. 45 - Jakarta</small>
        </div>

        <div class="qr">
            <div id="qrcode"></div>
        </div>

        <div class="info">
            <p><strong>KODE PARKIR:</strong> {{ $code }}</p>
            <p><strong>Tanggal Masuk:</strong> {{ date('d M Y') }}</p>
            <p><strong>Waktu:</strong> {{ date('H:i:s') }}</p>
            <p><strong>Status:</strong> Non-Member</p>
        </div>

        <div class="note">
            Simpan struk ini dengan baik. Kehilangan struk akan dikenakan denda 🚫
            Tunjukkan QR ke petugas saat keluar.
        </div>

        <div class="footer">
            <strong>Terima kasih 🙏</strong>
            <span>Gunakan layanan kami kembali!</span>
        </div>
    </div>

    <!-- QR Code generator (tanpa install apapun) -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Generate QR
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $code }}",
                width: 128,
                height: 128,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            // Tunggu 1 detik biar QR sempat muncul, lalu print
            setTimeout(() => {
                window.print();

                // Kalau user selesai print (atau cancel), kasih waktu 2 detik lalu redirect
                setTimeout(() => {
                    window.location.href = "{{ route('masuk') }}";
                }, 1000);
            }, 800);
        });
    </script>


</body>

</html>