<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem Masuk Parkir - Dark Mode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
        }

        .card {
            background-color: #1e1e1e !important;
            color: #fff !important;
            border: 1px solid #333 !important;
        }

        input,
        .form-control {
            background-color: #242424 !important;
            color: #fff !important;
            border-color: #444 !important;
        }

        input::placeholder {
            color: #aaa !important;
        }

        .modal-content {
            background-color: #1f1f1f !important;
            color: #fff !important;
        }

        .btn-primary {
            background-color: #0066ff !important;
            border-color: #005ce6 !important;
        }

        .btn-success {
            background-color: #00aa55 !important;
            border-color: #00994d !important;
        }

        .btn-secondary {
            background-color: #444 !important;
            border-color: #555 !important;
        }

        hr {
            border-color: #333 !important;
        }

        #reader {
            border: 2px solid #444 !important;
        }

        .text-muted {
            color: #bbbbbb !important;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container py-4">
        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-500 text-white rounded-lg mx-3 mt-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-500 text-white rounded-lg mx-3 mt-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card shadow-lg border-0 mx-auto" style="max-width: 950px;">
            <div class="card-body">
                <h3 class="text-center fw-bold mb-4 text-primary">🚗 Sistem Masuk Parkir</h3>

                <div class="row g-4">
                    <!-- Kolom Kiri -->
                    <div class="col-md-5 d-flex flex-column align-items-center justify-content-center text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/1995/1995574.png" alt="Parkir"
                            class="img-fluid mb-3" style="max-width: 120px;">
                        <p class="text-muted small">
                            Scan tiket pakai scanner fisik atau kamera.
                        </p>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="fw-semibold mb-3">🔢 Input Scanner / Manual</h5>
                                <form id="formScan" method="POST" action="{{ route('member.masuk') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="id" class="form-label">Masukkan / Scan Barcode:</label>
                                        <input type="text" id="id" name="id" class="form-control form-control-lg"
                                            placeholder="Arahkan scanner fisik atau ketik manual">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 py-2">Proses Masuk</button>
                                </form>

                                <hr class="my-4">

                                <button id="btnOpenCamera" class="btn btn-primary w-100 mb-2 py-2">
                                    📷 Scan via Kamera
                                </button>

                                <button id="btnCetakNonMember" class="btn btn-secondary w-100 py-2">
                                    🧾 Cetak Struk Non Member
                                </button>

                                <div id="result" class="text-center fw-semibold mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Kamera -->
    <div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">📷 Scan Barcode via Kamera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="reader" style="width:100%; height:350px;" class="border rounded"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const resultDiv = document.getElementById("result");
            const barcodeInput = document.getElementById("id");
            const formScan = document.getElementById("formScan");
            let html5QrCode;
            let modal = new bootstrap.Modal(document.getElementById("cameraModal"));

            // === 1. Auto-submit untuk scanner fisik ===
            barcodeInput.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    formScan.submit();
                }
            });

            // === 2. Buka Kamera ===
            document.getElementById("btnOpenCamera").addEventListener("click", async () => {
                modal.show();
                html5QrCode = new Html5Qrcode("reader");
                try {
                    const devices = await Html5Qrcode.getCameras();
                    if (devices.length) {
                        await html5QrCode.start(
                            devices[0].id,
                            { fps: 10, qrbox: 250 },
                            (message) => {
                                barcodeInput.value = message; // Masukkan hasil ke input
                                formScan.submit(); // Auto-submit form
                                modal.hide();
                                if (html5QrCode) html5QrCode.stop();
                            }
                        );
                    }
                } catch (err) {
                    resultDiv.textContent = "Tidak dapat mengakses kamera.";
                    console.error("Camera error:", err);
                }
            });

            // === 3. Cetak Non Member ===
            document.getElementById("btnCetakNonMember").addEventListener("click", () => {
                window.open("{{ route('tiket.index') }}", "_blank");
            });

            // Tutup kamera
            document.getElementById("cameraModal").addEventListener("hidden.bs.modal", () => {
                if (html5QrCode) html5QrCode.stop();
            });
        });
    </script>

</body>

</html>