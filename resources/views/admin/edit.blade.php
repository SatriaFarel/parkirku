@if (Request::is("kendaraan/*/edit"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Kendaraan
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('kendaraan') }}"
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
                <form action="{{ route('kendaraan.update', $kendaraan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama produk --}}
                    <!-- <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Nama Produk
                        </label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ old('name', $kendaraan->name) }}" required>
                    </div> -->

                    {{-- Tarif Awal --}}
                    <div class="mb-4">
                        <label for="tarifAwal" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Tarif Awal
                        </label>
                        <input id="tarifAwal" name="tarifAwal" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" value="{{ old('tarif_awal', $kendaraan->tarif_awal) }}"></input>
                    </div>

                    {{-- Tarif Per Jam --}}
                    <div class="mb-4">
                        <label for="tarifPerJam" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Tarif Per Jam
                        </label>
                        <input id="tarifPerJam" name="tarifPerJam" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" value="{{ old('tarif_per_jam', $kendaraan->tarif_per_jam) }}"> </input>
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition">
                        Update Kendaraan
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
@endif

@if (Request::is("petugas/*/edit"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Petugas
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('petugas') }}"
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
                <form action="{{ route('petugas.update', $petugas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="id" value="{{ old('name', $petugas->id) }}">

                    {{-- Nama  --}}
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Nama
                        </label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ old('name', $petugas->name) }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Email
                        </label>
                        <input id="email" name="email" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" value="{{ old('email', $petugas->email) }}"></input>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Password
                        </label>
                        <input id="password" name="password" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" value="{{ old('password') }}"> </input>
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition">
                        Update Petugas
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
@endif

@if (Request::is("admin/*/edit"))
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Admin
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('admin') }}"
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
                <form action="{{ route('admin.update', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" value="{{ old('id', $admin->id) }}">

                    {{-- Nama  --}}
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Nama
                        </label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ old('name', $admin->name) }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Email
                        </label>
                        <input id="email" name="email" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" value="{{ old('email', $admin->email) }}"></input>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 dark:text-gray-300 mb-2 font-medium">
                            Password
                        </label>
                        <input id="password" name="password" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" value="{{ old('password') }}"> </input>
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition">
                        Update Admin
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
@endif
