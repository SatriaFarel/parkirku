<x-app-layout>

    <!-- Actions -->
    <div class="flex gap-3 mb-6 mx-3 my-5">
        <a href="{{ route('petugas.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
            + Petugas
        </a>
    </div>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    {{-- Alert error --}}
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
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
        <h3 class="text-white text-lg font-semibold mb-3">Data Petugas Terbaru</h3>

        <table class="min-w-full text-sm text-left text-gray-400">
            <thead class="text-gray-300 uppercase bg-gray-700">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($petugas as $data)
                    <tr class="border-b border-gray-700">
                        <td class="px-4 py-2">{{ $data->id }}</td>
                        <td class="px-4 py-2">{{ $data->name}}</td>
                        <td class="px-4 py-2">{{ $data->email }}</td>
                        <td class="px-4 py-2 gap-2 flex">
                            <a href="{{ route('petugas.edit', $data) }}"
                                class="border bg-green-500 text-white rounded-md p-1">update</a>
                            <form action="{{ route('petugas.destroy', $data->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button onclick="Confirm('Apakah kamu yakin ingin menghapus data ini>')"
                                    class="border bg-red-500 text-white rounded-md p-1">delete</button>
                            </form>
                        </td>
                    </tr>
                    @if ($data === null)
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data parkir</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

    </div>

</x-app-layout>