<x-app-layout>
    <!-- Info Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 my-6 mx-3">

        <div class="p-4 rounded-lg bg-gray-800 shadow">
            <h3 class="text-gray-300 text-sm">Total Kendaraan Masuk</h3>
            <p class="text-2xl font-bold text-white">{{ $totalMasuk ?? 0 }}</p>
        </div>

        <div class="p-4 rounded-lg bg-gray-800 shadow">
            <h3 class="text-gray-300 text-sm">Sedang Parkir</h3>
            <p class="text-2xl font-bold text-white">{{ $sedangParkir ?? 0 }}</p>
        </div>

        <div class="p-4 rounded-lg bg-gray-800 shadow">
            <h3 class="text-gray-300 text-sm">Kendaraan Keluar Hari Ini</h3>
            <p class="text-2xl font-bold text-white">{{ $keluarHariIni ?? 0 }}</p>
        </div>

    </div>

    <!-- Actions -->
    <div class="flex gap-3 mb-6 mx-3">

        <button onclick="openScanCamModal()" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md">
            Scan Masuk (Camera)
        </button>

        <input id="scannerInput" type="text" placeholder="Scan Barcode Masuk (Scanner Device)"
            class="px-4 py-2 bg-gray-900 border border-gray-700 text-white rounded-md"
            oninput="handleScannerInput(this.value)">

    </div>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert error --}}
    @if (session('error'))
        <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!-- Tabel Data -->
    <div class="bg-gray-800 rounded-lg p-4 mx-3 shadow overflow-x-auto">
        <h3 class="text-white text-lg font-semibold mb-3">Data Parkir Terbaru</h3>
        <h3 class="text-white text-lg font-semibold mb-3">Tanggal : {{ now()->format('d-m-Y')}}</h3>

        <table class="min-w-full text-sm text-left text-gray-400 mb-4">
            <thead class="text-gray-300 uppercase bg-gray-700">
                <tr>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Plat Nomor</th>
                    <th class="px-4 py-2">Masuk</th>
                    <th class="px-4 py-2">Keluar</th>
                    <th class="px-4 py-2">Tarif</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($parkirs as $data)
                    <tr class="border-b border-gray-700">
                        <td class="px-4 py-2">{{ $data->id_member ? 'Member' : 'Non Member' }}</td>
                        <td class="px-4 py-2">{{ $data->plat_nomor ?? '-' }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($data->waktu_masuk)->translatedFormat('H:i') }}
                        </td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($data->waktu_keluar)->translatedFormat('H:i') ?? '-' }}
                        </td>
                        <td class="px-4 py-2">{{ $data->tarif ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded 
                                                        {{ $data->status == 'Masuk' ? 'bg-yellow-600' : 'bg-green-600' }}">
                                {{ $data->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data parkir</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @php
            $disabled = $parkirs->isEmpty();
        @endphp

        <a href="{{ $disabled ? '#' : route('petugas.laporan') }}" class="px-4 py-2 rounded-md font-semibold text-white 
        {{ $disabled
    ? 'bg-gray-400 cursor-not-allowed pointer-events-none'
    : 'bg-blue-600 hover:bg-blue-700'
        }}">
            Print Laporan
        </a>

    </div>

    <!-- Modal Scan Kamera -->
    <div id="scanCamModal"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden justify-center items-center transition-opacity duration-300">

        <div
            class="bg-gray-900 rounded-2xl shadow-2xl w-[95%] max-w-md p-6 text-white relative animate-[fadeIn_0.25s_ease]">

            <!-- Close button -->
            <button onclick="closeScanCamModal()"
                class="absolute top-3 right-3 text-gray-400 hover:text-white transition">
                ✕
            </button>

            <div class="flex flex-col items-center gap-3">
                <h2 class="text-xl font-bold tracking-wide">Scan Barcode Kendaraan</h2>
                <p class="text-gray-400 text-sm text-center">Arahkan barcode ke kamera untuk memindai</p>

                <div class="rounded-xl overflow-hidden bg-black border border-gray-700 shadow-inner">
                    <div id="scanner" class="w-[300px] h-[200px]"></div>
                </div>

                <div class="mt-4 w-full text-center">
                    <button onclick="closeScanCamModal()"
                        class="w-full py-2 bg-red-600 hover:bg-red-700 transition rounded-lg font-semibold">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
        function openScanCamModal() {
            document.getElementById('scanCamModal').classList.remove('hidden');

            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner')
                },
                decoder: {
                    readers: ["code128_reader", "ean_reader"]
                }
            }, err => {
                if (err) return console.error(err);
                Quagga.start();
            });

            Quagga.onDetected(data => {
                let code = data.codeResult.code;
                Quagga.stop();
                window.location.href = "/parkir/scan-masuk?barcode=" + code;
            });
        }

        function closeScanCamModal() {
            document.getElementById('scanCamModal').classList.add('hidden');
            Quagga.stop();
        }

        function handleScannerInput(val) {
            if (val.length >= 7) {
                kirimKode(val);
            }
        }

        // Fungsi kirim biar bisa dipanggil dari dua tempat
        function kirimKode(val) {
            let url = "{{ route('parkir.keluar', ':kode') }}".replace(':kode', val);
            window.location.href = url;

            document.getElementById('scannerInput').value = "";
        }

        // Trigger kalau pencet Enter
        document.getElementById('scannerInput').addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault(); // biar form nggak auto submit
                let val = this.value.trim();

                if (val !== "") {
                    kirimKode(val);
                }
            }
        });

    </script>

</x-app-layout>