<x-app-layout>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-500 text-white rounded-lg mx-3 mt-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert sukses --}}
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-500 text-white rounded-lg mx-3 mt-4">
            {{ session('error') }}
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

    <div class="flex gap-3 mb-6 mx-3 my-5">
        <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
            + Tambah Member
        </button>
    </div>

    <!-- Tabel Data Member -->
    <div class="bg-gray-800 rounded-lg p-4 mx-3 shadow overflow-x-auto">
        <h3 class="text-white text-lg font-semibold mb-3">Data Member Terbaru</h3>

        <table class="min-w-full text-sm text-left text-gray-400">
            <thead class="text-gray-300 uppercase bg-gray-700">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($member as $data)
                    <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>

                        <td class="px-4 py-2 font-semibold text-white">
                            {{ $data->nama }}
                        </td>

                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-md text-white
                                                {{ $data->status === 'lunas' ? 'bg-green-600' : 'bg-red-600' }}">
                                {{ ucfirst($data->status) }}
                            </span>
                        </td>

                        <td class="px-4 py-2 flex flex-wrap justify-center items-center gap-1">

                            {{-- Detail --}}
                            <a href="{{ route('member.view', $data->id) }}"
                                class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md">
                                Detail
                            </a>

                            {{-- Print --}}
                            <a href="{{ route('member.print', $data->id) }}"
                                class="px-2 py-1 bg-indigo-500 hover:bg-indigo-600 text-white text-xs rounded-md">
                                🖨️ Print
                            </a>

                            {{-- Edit --}}
                            <button onclick="openEditModal({{ $data->id }}, '{{ $data->nama }}', '{{ $data->status }}')"
                                class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md">
                                Edit
                            </button>

                            {{-- Pelunasan (hanya jika belum lunas) --}}
                            @if ($data->status === 'belum lunas')
                                <button onclick="openPelunasanModal({{ $data->id }}, '{{ $data->nama }}')"
                                    class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded-md">
                                    Pelunasan
                                </button>
                            @endif

                            {{-- Hapus --}}
                            <form action="{{ route('member.destroy', $data->id) }}" method="POST"
                                onsubmit="return confirm('Yakin mau hapus member ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Belum ada data member
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Modal Tambah Member --}}
    <div id="createModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold text-white mb-4">Tambah Member</h2>
            <form action="{{ route('member.store') }}" method="POST">
                @csrf

                <!-- Nama -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Nama</label>
                    <input type="text" name="nama" required class="w-full p-2 rounded bg-gray-800 text-gray-100 border border-blue-800/40 
                   focus:outline-none focus:border-blue-500 shadow-inner shadow-blue-900/20">
                </div>

                <!-- Tarif (readonly) -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Tarif</label>

                    <input type="text" value="150000" disabled class="w-full p-2 rounded bg-gray-800 text-gray-300 border border-blue-800/40 
                   focus:outline-none shadow-inner shadow-blue-900/20">

                    <input type="hidden" name="tarif" value="150000">
                </div>

                <!-- Bayar -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Bayar</label>
                    <input type="text" name="bayar" id="bayar" required class="w-full p-2 rounded bg-gray-800 text-gray-100 border border-blue-800/40 
                   focus:outline-none focus:border-blue-500 shadow-inner shadow-blue-900/20">
                </div>

                <!-- Kembalian -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Kembalian</label>

                    <input type="text" id="kembali" disabled class="w-full p-2 rounded bg-gray-800 text-gray-300 border border-blue-800/40 
                   shadow-inner shadow-blue-900/20">

                    <input type="hidden" name="kembali" id="kembali_value">

                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow shadow-blue-900/50">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Modal Edit Member --}}
    <div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold text-white mb-4">Update Member</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editId" name="id">
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Nama</label>
                    <input type="text" id="editNama" name="nama" required
                        class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Status</label>
                    <input type="text" id="editStatus" disabled
                        class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600"
                        placeholder="Status Member">
                    <input type="hidden" id="statusHidden" name="status">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Pelunasan --}}
    <div id="pelunasanModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold text-white mb-4">Form Pelunasan</h2>
            <form id="pelunasanForm" method="POST" action="{{ route('member.payment') }}">
                @csrf
                @method('POST')
                <input type="hidden" id="id_member" name="id_member">
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Nama Member</label>
                    <input type="text" id="pelunasanNama" readonly name="nama"
                        class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600">
                </div>
                @php
                    $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
                    $firstDay = $now->format('Y') . '-' . str_pad($now->format('m'), 2, '0', STR_PAD_LEFT) . '-01';
                @endphp

                <!-- Tanggal Tagihan -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Tanggal Tagihan</label>
                    <input type="date" id="editTanggalTagihan" disabled
                        class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600"
                        value="{{ $firstDay }}">
                    <!-- Hidden input biar tetap dikirim ke server -->
                    <input type="hidden" id="tanggalTagihanHidden" name="tanggal_bayar" value="{{ $firstDay }}">
                </div>


                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Tarif</label>
                    <input type="number" id="tarif" value="150000" disabled
                        class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600"
                        placeholder="Masukkan tarif (Rp)">
                    <input type="hidden" name="tarif" value="150000">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Bayar</label>
                    <input type="number" id="bayar" name="bayar" required
                        class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500"
                        placeholder="Masukkan jumlah bayar (Rp)">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1">Kembali</label>
                    <input type="number" id="kembali" name="kembali" readonly
                        class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600"
                        placeholder="Otomatis dihitung">
                        <!-- optional hidden -->
                    <input type="hidden" id="kembali_value" name="kembali_hidden">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closePelunasanModal()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md">Lunaskan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(id, nama, status) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editId').value = id;
            document.getElementById('editNama').value = nama;
            document.getElementById('editStatus').value = status;
            document.getElementById('statusHidden').value = status;
            document.getElementById('editForm').action = '/member/' + id;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openPelunasanModal(id, nama) {
            const modal = document.getElementById('pelunasanModal');
            modal.classList.remove('hidden');

            // Set nama dan action form
            document.getElementById('pelunasanNama').value = nama;
            document.getElementById('id_member').value = id;

            // Set tanggal otomatis ke tanggal 1 bulan ini
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            // Format YYYY-MM-DD manual
            const year = firstDay.getFullYear();
            const month = String(firstDay.getMonth() + 1).padStart(2, '0');
            const day = String(firstDay.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;

            // Set ke input visible dan hidden
            document.getElementById('editTanggalTagihan').value = formattedDate;
            document.getElementById('tanggalTagihanHidden').value = formattedDate;

            // Reset bayar & kembali
            document.getElementById('bayar').value = '';
            document.getElementById('kembali').value = '';
        }

        function closePelunasanModal() {
            document.getElementById('pelunasanModal').classList.add('hidden');
        }

        // Fungsi hitung otomatis kembalian
        function hitungKembali() {
            const tarif = parseInt(document.querySelector('input[name="tarif"]').value) || 0;
            const bayar = parseInt(document.getElementById('bayar').value) || 0;
            const kembali = bayar - tarif;

            document.getElementById('kembali').value = kembali >= 0 ? kembali : 0;
            document.getElementById('kembali_value').value = kembali >= 0 ? kembali : 0;
        }

        // Event listener
        document.getElementById('bayar').addEventListener('input', hitungKembali);
    </script>



</x-app-layout>