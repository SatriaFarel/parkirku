<x-app-layout>

    <!-- Tabs Member / Non Member / Petugas -->
    <div class="mt-6 mx-3">
        <div class="flex gap-2 mb-4">
            <input type="radio" id="tabMember" name="tab" class="hidden peer/member" checked>
            <input type="radio" id="tabNonMember" name="tab" class="hidden peer/nonmember">
            <input type="radio" id="tabPetugas" name="tab" class="hidden peer/petugas">

            <label for="tabMember" class="px-4 py-2 rounded-md cursor-pointer
               bg-gray-700 text-white
               peer-checked/member:bg-blue-600
               peer-not-checked/member:bg-gray-700">
                Member
            </label>

            <label for="tabNonMember" class="px-4 py-2 rounded-md cursor-pointer
               bg-gray-700 text-gray-300
               peer-checked/nonmember:bg-blue-600
               peer-checked/nonmember:text-white">
                Non Member
            </label>

            <label for="tabPetugas" class="px-4 py-2 rounded-md cursor-pointer
               bg-gray-700 text-gray-300
               peer-checked/petugas:bg-blue-600
               peer-checked/petugas:text-white">
                Petugas
            </label>
        </div>

        <!-- Filter + Search + Print -->
        <div class="flex flex-wrap gap-3 mb-4">
            <input id="searchInput" type="text" class="px-3 py-2 bg-gray-800 text-white rounded-md w-48"
                placeholder="Cari nomor plat...">

            <select id="filterStatus" class="px-3 py-2 bg-gray-800 text-white rounded-md w-40">
                <option value="">Semua Status</option>
                <option value="masuk">Masuk</option>
                <option value="keluar">Keluar</option>
            </select>

            <button onclick="printTable()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                Print
            </button>

        </div>

        {{-- Tabel Member --}}
        <div id="tableMember">
            <!-- Contoh Tabel Member -->
            <table class="min-w-full border border-gray-700 text-white border-collapse">
                <thead class="bg-gray-900 text-gray-300">
                    <tr>
                        <th class="border border-gray-700 p-2 text-left">No</th>
                        <th class="border border-gray-700 p-2 text-left">Petugas</th>
                        <th class="border border-gray-700 p-2 text-left">Nama</th>
                        <th class="border border-gray-700 p-2 text-left">Plat</th>
                        <th class="border border-gray-700 p-2 text-left">Status</th>
                        <th class="border border-gray-700 p-2 text-left">Masuk</th>
                        <th class="border border-gray-700 p-2 text-left">Keluar</th>
                    </tr>
                </thead>
                <tbody id="bodyMember">
                    @forelse ($dataMember as $m)
                        <tr>
                            <td class="border border-gray-700 p-2">{{ $loop->iteration }}</td>
                            <td class="border border-gray-700 p-2">{{ $m->nama_petugas ?? '-' }}</td>
                            <td class="border border-gray-700 p-2">{{ $m->nama_member }}</td>
                            <td class="border border-gray-700 p-2">{{ $m->plat_nomor ?? '-' }}</td>
                            <td class="border border-gray-700 p-2">{{ $m->status }}</td>
                            <td class="border border-gray-700 p-2">{{ $m->waktu_masuk }}</td>
                            <td class="border border-gray-700 p-2">{{ $m->waktu_keluar ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-gray-700 text-center py-4 text-gray-500">Belum ada data
                                parkir</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- Tabel Non Member --}}
        <div id="tableNonMember" class="hidden">
            <table class="min-w-full border border-collapse border-gray-700 text-white">
                <thead>
                    <tr class="bg-gray-900 text-gray-300">
                        <th class="border border-gray-700 p-2 text-left">No</th>
                        <th class="border border-gray-700 p-2 text-left">Petugas</th>
                        <th class="border border-gray-700 p-2 text-left">Plat</th>
                        <th class="border border-gray-700 p-2 text-left">Status</th>
                        <th class="border border-gray-700 p-2 text-left">Masuk</th>
                        <th class="border border-gray-700 p-2 text-left">Keluar</th>
                    </tr>
                </thead>
                <tbody id="bodyNonMember">
                    @forelse ($dataNonMember as $n)
                        <tr class="border-t border-gray-700">
                            <td class="border border-gray-700 p-2 text-left">{{ $loop->iteration }}</td>
                            <td class="border border-gray-700 p-2 text-left">{{ $n->nama_petugas ?? '-' }}</td>
                            <td class="border border-gray-700 p-2 text-left">{{ $n->plat_nomor ?? '-' }}</td>
                            <td class="border border-gray-700 p-2 text-left">{{ $n->status }}</td>
                            <td class="border border-gray-700 p-2 text-left">{{ $n->waktu_masuk }}</td>
                            <td class="border border-gray-700 p-2 text-left">{{ $n->waktu_keluar ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr id="emptyNonMember">
                            <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data parkir</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel Petugas --}}
        <div id="tablePetugas" class="hidden">
            <div class="mb-3">
                <select id="filterPetugas" class="px-3 py-2 bg-gray-800 text-white rounded-md w-48">
                    <option value="">Semua Petugas</option>
                    @foreach ($petugasList as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <table class="min-w-full border border-gray-700 text-white border-collapse">
                <thead class="bg-gray-900 text-gray-300">
                    <tr>
                        <th class="border border-gray-700 p-2 text-left">No</th>
                        <th class="border border-gray-700 p-2 text-left">Petugas</th>
                        <th class="border border-gray-700 p-2 text-left">Jenis</th>
                        <th class="border border-gray-700 p-2 text-left">Plat</th>
                        <th class="border border-gray-700 p-2 text-left">Status</th>
                        <th class="border border-gray-700 p-2 text-left">Masuk</th>
                        <th class="border border-gray-700 p-2 text-left">Keluar</th>
                    </tr>
                </thead>
                <tbody id="bodyPetugas">
                    @forelse ($gabungan as $g)
                        <tr class="border-t border-gray-700">
                            <td class="border border-gray-700 p-2">{{ $loop->iteration }}</td>
                            <td class="border border-gray-700 p-2">{{ $g['petugas'] ?? '-' }}</td>
                            <td class="border border-gray-700 p-2">{{ $g['jenis'] }}</td>
                            <td class="border border-gray-700 p-2">{{ $g['plat'] ?? '-' }}</td>
                            <td class="border border-gray-700 p-2">{{ $g['status'] }}</td>
                            <td class="border border-gray-700 p-2">{{ $g['masuk'] }}</td>
                            <td class="border border-gray-700 p-2">{{ $g['keluar'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr id="emptyPetugas">
                            <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data parkir</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>

    {{-- Form Print --}}
    <form id="printForm" action="/print/petugas" method="POST" target="_blank" class="hidden">
        @csrf
        <input type="hidden" name="data" id="dataPrintPetugas">
    </form>

    {{-- SCRIPT --}}
    <script>
        // Tabs
        const tabMember = document.getElementById('tabMember');
        const tabNonMember = document.getElementById('tabNonMember');
        const tabPetugas = document.getElementById('tabPetugas');
        const tableMember = document.getElementById('tableMember');
        const tableNonMember = document.getElementById('tableNonMember');
        const tablePetugas = document.getElementById('tablePetugas');

        const searchInput = document.getElementById('searchInput');
        const filterStatus = document.getElementById('filterStatus');
        const filterPetugas = document.getElementById('filterPetugas');

        let currentTable = 'member';

        function resetTabStyles() {
            tabMember.nextElementSibling.classList.remove('bg-blue-600', 'text-white');
            tabNonMember.nextElementSibling.classList.remove('bg-blue-600', 'text-white');
        }

        tabMember.onclick = () => {
            currentTable = 'member';
            tableMember.classList.remove('hidden');
            tableNonMember.classList.add('hidden');
            tablePetugas.classList.add('hidden');
            resetTabStyles();
            tabMember.nextElementSibling.classList.add('bg-blue-600', 'text-white');
        };

        tabNonMember.onclick = () => {
            currentTable = 'nonmember';
            tableNonMember.classList.remove('hidden');
            tableMember.classList.add('hidden');
            tablePetugas.classList.add('hidden');
            resetTabStyles();
            tabNonMember.nextElementSibling.classList.add('bg-blue-600', 'text-white');
        };

        tabPetugas.onclick = () => {
            currentTable = 'petugas';
            tablePetugas.classList.remove('hidden');
            tableMember.classList.add('hidden');
            tableNonMember.classList.add('hidden');
            resetTabStyles();
        };

        // Filter Member / Nonmember
        function filterTable() {
            const keyword = searchInput.value.toLowerCase();
            const status = filterStatus.value;

            const tableMap = {
                member: 'bodyMember',
                nonmember: 'bodyNonMember',
                petugas: 'bodyPetugas'
            };
            const emptyMap = {
                member: 'emptyMember',
                nonmember: 'emptyNonMember',
                petugas: 'emptyPetugas'
            };
            const statusIndex = {
                member: 4,
                nonmember: 3,
                petugas: 4
            };

            const tableId = tableMap[currentTable];
            const emptyId = emptyMap[currentTable];
            const rows = document.querySelectorAll(`#${tableId} tr`);
            let visibleCount = 0;

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();
                const rowStatus = row.children[statusIndex[currentTable]]?.innerText.trim() ?? '';
                const show = rowText.includes(keyword) && (status === '' || rowStatus === status);
                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });

            const emptyRow = document.getElementById(emptyId);
            if (emptyRow) emptyRow.classList.toggle('hidden', visibleCount > 0);
        }

        searchInput.onkeyup = filterTable;
        filterStatus.onchange = filterTable;

        // Filter Petugas dropdown
        filterPetugas.onchange = () => {
            const petugas = filterPetugas.value.toLowerCase();
            const rows = document.querySelectorAll('#bodyPetugas tr');
            rows.forEach(row => {
                const rowPetugas = row.children[1]?.innerText.toLowerCase() ?? '';
                row.style.display = (petugas === '' || rowPetugas === petugas) ? '' : 'none';
            });
        };

        // Print Table
        function printTable() {
            if (currentTable === 'petugas') {
                const rows = document.querySelectorAll('#bodyPetugas tr');
                const visibleRows = [];
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cols = [...row.children].map(c => c.innerText.trim());
                        visibleRows.push(cols);
                    }
                });
                document.getElementById("dataPrintPetugas").value = JSON.stringify(visibleRows);
                document.getElementById("printForm").submit();
                return;
            }

            if (currentTable === "member") window.location.href = "/print/member";
            if (currentTable === "nonmember") window.location.href = "/print/nonmember";
        }
    </script>

</x-app-layout>