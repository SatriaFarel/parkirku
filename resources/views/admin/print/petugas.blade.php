<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Parkir</title>

    <style>
        body {
            font-family: "Arial", sans-serif;
            margin: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 20px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table thead tr {
            background: #e3e3e3;
        }

        table th,
        table td {
            border: 1px solid #555;
            padding: 8px 10px;
            font-size: 14px;
        }

        table tbody tr:nth-child(even) {
            background: #f8f8f8;
        }

        @media print {
            body {
                margin: 0;
            }

            table th,
            table td {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <h2>Laporan Parkir</h2>

    <div class="info">
        <p><strong>Tanggal:</strong> {{ $tanggal }}</p>

        <p>
            <strong>Role:</strong>
            {{ $printedBy }}
        </p>

        @if($isAdmin)
            <p><em>Laporan keseluruhan (Admin)</em></p>
        @endif
    </div>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Name/Kode Tiket</th>
                @if ($isAdmin) <th>Petugas</th> @endif
                <th>Role</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Nomor Plat</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($dataParkir as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->id_member ? $row->nama_member : $row->kode_tiket }}</td>
                    @if ($isAdmin)
                        <td>{{ $row->nama_petugas ?? '-' }}</td>
                    @endif
                    <td>{{ $row->id_member ? 'member' : 'Non Member' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->waktu_masuk)->format('H:i') }}</td>
                    <td>
                        {{ $row->waktu_keluar ? \Carbon\Carbon::parse($row->waktu_keluar)->format('H:i') : '-' }}
                    </td>
                    <td>{{ $row->plat_nomor }}</td>
                    <td>{{ $row->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>