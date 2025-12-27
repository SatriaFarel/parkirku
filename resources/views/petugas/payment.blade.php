<x-app-layout>
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
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 transition-colors">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-600 to-indigo-600">
                    <h3 class="text-lg sm:text-xl font-semibold text-white">💳 Pembayaran Parkir</h3>
                </div>

                {{-- body --}}
                <div class="p-6">
                    {{-- Flash messages --}}
                    @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/40 border border-green-200 dark:border-green-800 p-3 text-sm text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-800 p-3 text-sm text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </div>
                    @endif

                    {{-- Validation errors --}}
                    @if ($errors->any())
                    <div class="mb-4 rounded-md bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 p-3 text-sm text-yellow-800 dark:text-yellow-200">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('parkir.payment') }}" method="POST" id="paymentForm" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            @if (!empty($id_member ?? null))
                                {{-- ID --}}
                            <div>
                                <label for="id_member" class="block text-sm font-medium text-gray-700 dark:text-gray-200">ID Member</label>
                                <input type="text"
                                    id="id_member"
                                    name="id_member"
                                    value="{{ $id_member ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    readonly>
                            </div>
                            @else
                            {{-- KODE TIKET --}}
                            <div>
                                <label for="kode_tiket" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kode Tiket</label>
                                <input type="text"
                                    id="kode_tiket"
                                    name="kode_tiket"
                                    value="{{ $kodeTiket ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    readonly>
                            </div>
                            @endif

                            {{-- NO PLAT --}}
                            <div>
                                <label for="plat_nomor" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nomor Plat</label>
                                <input type="text"
                                    id="plat_nomor"
                                    name="plat_nomor"
                                    value="{{ old('plat_nomor')}}"
                                    placeholder="Contoh: B 1234 XYZ"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    required>
                            </div>

                            {{-- JAM MASUK --}}
                            <div>
                                <label for="waktu_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Waktu Masuk</label>
                                <input type="datetime-local"
                                    id="waktu_masuk"
                                    name="waktu_masuk"
                                    value="{{ isset($masuk) ? \Carbon\Carbon::parse($masuk)->format('Y-m-d\TH:i') : '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    readonly>
                            </div>

                            {{-- JAM KELUAR --}}
                            <div>
                                <label for="waktu_keluar" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Waktu Keluar</label>
                                <input type="datetime-local"
                                    id="waktu_keluar"
                                    name="waktu_keluar"
                                    value="{{ isset($keluar) ? \Carbon\Carbon::parse($keluar)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    readonly>
                            </div>

                            {{-- TARIF --}}
                            <div>
                                <label for="tarif" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tarif Parkir (Rp)</label>
                                <input type="number"
                                    id="tarif"
                                    name="tarif"
                                    value="{{ $tarif ?? old('tarif') ?? 0 }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    readonly required>
                            </div>

                            @if (!empty($id_member ?? null))
                            {{-- BAYAR --}}
                            <div>
                                <label for="bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Uang Bayar (Rp)</label>
                                <input type="text"
                                    id="bayar"
                                    name="bayar" readonly
                                    value="Free"
                                    placeholder="Masukkan nominal"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    required>
                            </div>
                            @else
                            {{-- BAYAR --}}
                            <div>
                                <label for="bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Uang Bayar (Rp)</label>
                                <input type="number"
                                    id="bayar"
                                    name="bayar"
                                    value="{{ old('bayar') ?? '' }}"
                                    placeholder="Masukkan nominal"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    required>
                            </div>
                            @endif

                            {{-- KEMBALIAN --}}
                            <div>
                                <label for="kembalian" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Kembalian (Rp)</label>
                                <input type="number"
                                    id="kembalian"
                                    name="kembalian"
                                    value="{{ old('kembalian') ?? 0 }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    readonly>
                            </div>

                            {{-- PETUGAS --}}
                            <div>
                                <label for="petugas_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Petugas</label>

                                <!-- Input tampilan nama petugas -->
                                <input type="text"
                                    id="petugas_nama"
                                    value="{{ $user->name ?? auth()->user()->name ?? 'Petugas' }}"
                                    class="mt-1 block w-full rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-400"
                                    readonly>

                                <!-- Input tersembunyi berisi ID petugas -->
                                <input type="hidden"
                                    id="id_petugas"
                                    name="id_petugas"
                                    value="{{ $user->id ?? auth()->user()->id ?? '' }}">
                            </div>

                        </div>

                        {{-- notes kecil --}}
                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                            <p><strong>Catatan:</strong> Simpan struk / screenshot sebagai bukti keluar. Kehilangan struk dapat dikenakan biaya tambahan.</p>
                        </div>

                        {{-- aksi --}}
                        <div class="mt-6 flex gap-3">
                            <button type="submit" id="btnBayarCetak"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm">
                                💰 Bayar & Cetak
                            </button>

                            <a href="{{ route('home') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-md">
                                ⬅️ Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Small inline script untuk kalkulasi tarif/kembalian + print --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bayarInput = document.getElementById('bayar');
            const tarifInput = document.getElementById('tarif');
            const kembalianInput = document.getElementById('kembalian');
            const kodeTiketInput = document.getElementById('kode_tiket');
            const btnBayarCetak = document.getElementById('btnBayarCetak');
            const form = document.getElementById('paymentForm');

            bayarInput.addEventListener('input', hitungKembalian);

            function hitungKembalian() {
                const bayar = parseInt(bayarInput.value) || 0;
                const tarif = parseInt(tarifInput.value) || 0;
                const kembali = bayar - tarif;
                kembalianInput.value = kembali > 0 ? kembali : 0;
            }

            btnBayarCetak.addEventListener('click', (e) => {
                e.preventDefault();

                // validasi sederhana
                if (!bayarInput.value) {
                    alert('Masukkan nominal pembayaran terlebih dahulu.');
                    bayarInput.focus();
                    return;
                }

                // submit form dulu
                form.submit();
            });
        });
    </script>

</x-app-layout>