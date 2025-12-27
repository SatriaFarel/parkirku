<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Produk
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('home') }}" 
               class="inline-block mb-4 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow transition">
               Kembali
            </a>

            {{-- Alert sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
                    {{ session('success') }}
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

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form action="{{ route('petugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- Nama Produk --}}
                    <div>
                        <label for="name" class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Nama Produk</label>
                        <input type="text" id="name" name="name" 
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                   focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan nama produk" value="{{ old('name') }}" required>
                    </div>

                    {{-- Deskripsi Produk --}}
                    <div>
                        <label for="deskripsi" class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                   focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan deskripsi (opsional)">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label for="price" class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Harga</label>
                        <input type="number" id="price" name="price" min="0"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                   focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan harga" value="{{ old('price') }}" required>
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label for="pic_product" class="block text-gray-700 dark:text-gray-300 mb-1 font-medium">Foto</label>
                        <input type="file" id="pic_product" name="pic_product" min="0"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                   focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Masukkan harga" value="{{ old('pic_product') }}" required>
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">
                        Simpan
                    </button>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>


