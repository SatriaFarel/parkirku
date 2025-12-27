<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Member</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Custom Theme (Dark + Blue) --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkbg: '#0f172a',      // slate-900
                        darkcard: '#1e293b',    // slate-800
                        darkborder: '#334155',  // slate-700
                        blueaccent: '#3b82f6',  // blue-500
                        bluedark: '#1e40af',    // blue-800
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-darkbg text-gray-200 min-h-screen p-6">

    <div class="max-w-3xl mx-auto bg-darkcard p-6 rounded-xl shadow-2xl border border-darkborder">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-blueaccent">Detail Member</h2>

            <a href="{{ route('member') }}"
                class="px-4 py-2 bg-blueaccent hover:bg-bluedark transition rounded-lg text-white shadow-md">
                Kembali
            </a>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Info Card --}}
            <div class="bg-darkbg p-5 rounded-lg border border-darkborder shadow-md">
                <h3 class="text-xl font-semibold text-blueaccent mb-4">Informasi Member</h3>

                <p class="mb-4">
                    <span class="text-gray-400">ID Member:</span><br>
                    <span class="text-blueaccent text-2xl font-semibold">{{ $member->id }}</span>
                </p>

                <p class="mb-4">
                    <span class="text-gray-400">Nama:</span><br>
                    <span class="text-white text-lg">{{ $member->nama }}</span>
                </p>

                <p>
                    <span class="text-gray-400">Status:</span><br>

                    @if ($member->status === 'lunas')
                        <span class="px-3 py-1 bg-green-600 rounded-md text-white text-sm font-semibold">
                            Lunas
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-600 rounded-md text-white text-sm font-semibold">
                            Belum Lunas
                        </span>
                    @endif
                </p>

            </div>

            {{-- Barcode --}}
            <div class="bg-darkbg p-5 rounded-lg border border-darkborder shadow-md flex flex-col items-center">
                <h3 class="text-xl font-semibold text-blueaccent mb-4">Barcode Member</h3>

                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $member->id }}"
                    alt="QR Member {{ $member->id }}" class="rounded-lg shadow-lg">

                <p class="text-gray-400 mt-3 text-sm">Scan untuk lihat data</p>
            </div>

        </div>

        {{-- Riwayat Pembayaran --}}
        <div class="bg-darkbg mt-6 p-5 rounded-lg border border-darkborder shadow-md">
            <h3 class="text-xl font-semibold text-blueaccent mb-4">Riwayat Pembayaran</h3>

            <table class="w-full text-left text-gray-300">
                <thead class="border-b border-darkborder text-gray-400">
                    <tr>
                        <th class="py-2">Tanggal</th>
                        <th class="py-2">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($riwayat as $r)
                        <tr class="border-b border-darkborder hover:bg-darkcard/50 transition">
                            <td class="py-2">{{ $r->tanggal_bayar }}</td>
                            <td class="py-2 font-semibold text-blueaccent">Rp150.000</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>