<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Parkir</title>
    <style>
        body {
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .invoice-container {
            background: #1e293b;
            width: 420px;
            padding: 28px;
            border-radius: 14px;
            box-shadow: 0 0 25px rgba(37, 99, 235, 0.5);
            border: 1px solid #334155;
            text-align: center;
        }

        h2 {
            color: #60a5fa;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .invoice-header {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 15px;
        }

        hr {
            border: 0;
            border-top: 1px solid #334155;
            margin: 12px 0 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table td {
            padding: 8px 0;
            font-size: 14px;
        }

        .invoice-table td:nth-child(1) {
            text-align: left;
            color: #cbd5e1;
        }

        .invoice-table td:nth-child(2) {
            text-align: right;
            color: #93c5fd;
        }

        .invoice-summary {
            border-top: 1px solid #334155;
            margin-top: 15px;
            padding-top: 10px;
        }

        .invoice-summary td {
            font-weight: bold;
            font-size: 15px;
        }

        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #94a3b8;
        }

        .buttons {
            margin-top: 30px;
        }

        button {
            padding: 9px 18px;
            margin: 6px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .print-btn {
            background-color: #2563eb;
            color: white;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.5);
        }

        .print-btn:hover {
            background-color: #1e40af;
        }

        .back-btn {
            background-color: #475569;
            color: white;
        }

        .back-btn:hover {
            background-color: #334155;
        }

        @media print {
            body {
                background: white;
                color: black;
                font-family: 'Times New Roman', serif;
            }

            .invoice-container {
                width: 90%;
                background: white;
                border: none;
                box-shadow: none;
                text-align: left;
            }

            h2 {
                color: black;
                text-align: center;
                border-bottom: 2px solid black;
                padding-bottom: 5px;
                margin-bottom: 20px;
            }

            .invoice-header {
                text-align: center;
                color: gray;
                margin-bottom: 15px;
            }

            .invoice-table td:nth-child(1) {
                color: black;
            }

            .invoice-table td:nth-child(2) {
                color: black;
            }

            .buttons {
                display: none;
            }

            .footer {
                color: gray;
                text-align: center;
                font-style: italic;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <h2>🧾 Invoice Parkir</h2>
        <div class="invoice-header">
            <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
        </div>
        <hr>

        <table class="invoice-table">
            <tr>
                <td>Kode Tiket</td>
                <td><strong>{{ isset($id) ? $id : $parkir->kode_tiket }}</strong></td>
            </tr>
            <tr>
                <td>Plat Nomor</td>
                <td>{{ strtoupper($parkir->plat_nomor) }}</td>
            </tr>
            <tr>
                <td>Waktu Masuk</td>
                <td>{{ \Carbon\Carbon::parse($parkir->waktu_masuk)->translatedFormat('d F Y, H:i') }}</td>
            </tr>
            <tr>
                <td>Waktu Keluar</td>
                <td>{{ \Carbon\Carbon::parse($parkir->waktu_keluar)->translatedFormat('d F Y, H:i') }}</td>
            </tr>
        </table>

        <div class="invoice-summary">
            <table class="invoice-table">
                <tr>
                    <td>Tarif</td>
                    <td>Rp {{ number_format($parkir->tarif, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td> {{
                     isset($id) ? "Free" : "Rp" . number_format($parkir->bayar, 0, ',', '.') 
                     }}</td>
                </tr>
               
                <tr>
                    <td>Kembalian</td>
                    <td>Rp {{ number_format($parkir->kembalian, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima kasih telah menggunakan layanan parkir kami 🚗</p>
            <p><strong>Parkir Aman & Nyaman — {{ config('app.name', 'SmartPark') }}</strong></p>
        </div>

        <div class="buttons">
            <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>
            <button class="back-btn" onclick="window.location.href='{{ route('home') }}'">🏠 Kembali ke Home</button>
        </div>
    </div>
</body>
</html>
