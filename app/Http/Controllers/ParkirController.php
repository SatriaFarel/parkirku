<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Member;


use function PHPUnit\Framework\isEmpty;

class ParkirController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor' => 'required|alphanum|max:8',
            'jenisKendaraan' => 'required'
        ]);

        $user = Auth::user();
        $id_petugas = $user->id;

        $id = Parkir::max("id");
        $id = $id ? $id + 1 : 1;

        $code = 'TKT' . Str::upper(Str::random(4));

        $parkir = Parkir::create([
            'id'        => $id,
            'id_petugas' => $id_petugas,
            'id_kendaraan' => $request->input('jenisKendaraan'),
            'kode_tiket' => $code,
            'plat_nomor' => $validated['plat_nomor'],
            'waktu_masuk' => now()
        ]);

        return redirect()->route('parkir.ticket', $parkir->kode_tiket);
    }

    public function ticket($kode)
    {
        $parkir = Parkir::where('kode_tiket', $kode)->firstOrFail();
        return view('petugas.tiket', compact('parkir'));
    }

    public function keluar($kodeTiket)
    {
        // Jika angka → kemungkinan ID member
        $isMember = is_numeric($kodeTiket);

        if ($isMember) {
            // Cari data parkir berdasarkan ID member
            $parkir = Parkir::where('id_member', $kodeTiket)->whereNull("waktu_keluar")->first();
        } else {
            // Cari data parkir berdasarkan kode tiket
            $parkir = Parkir::where('kode_tiket', $kodeTiket)->first();
        }
        if (!$parkir) {
            return redirect()->back()->with('error', 'Kode tiket tidak ditemukan!');
        }

        if ($parkir->status == 'keluar') {
            return redirect()->back()->with('error', 'Kendaraan sudah keluar!');
        }

        // Hitung durasi parkir (dalam jam, dibulatkan ke atas)
        $masuk = \Carbon\Carbon::parse($parkir->waktu_masuk);
        $keluar = \Carbon\Carbon::now();
        $durasiJam = ceil($masuk->floatDiffInHours($keluar));

        // Hitung tarif
        $tarif = 0;
        $tarif_awal = 3000;
        $tarif_kelipatan = 2000;
        $tarif = $tarif_awal + max(0, $durasiJam - 1) * $tarif_kelipatan;

        $user = Auth::user(); // ambil user yang login

        if ($isMember) {
            return view("petugas.payment", [
                'id_member' => $kodeTiket,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'tarif' => $tarif,
                'user' => $user
            ]);
        }
        return view("petugas.payment", [
            'kodeTiket' => $kodeTiket,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'tarif' => $tarif,
            'user' => $user
        ]);
    }

    public function payment(Request $request)
    {
        $validated = $request->validate([
            'id_member' => 'numeric',
            'kode_tiket' => 'alpha_num|max:8',
            'id_petugas' => 'required|integer',
            'plat_nomor' => 'required|max:15',
            'waktu_masuk' => 'required|date',
            'waktu_keluar' => 'required|date|after_or_equal:waktu_masuk',
            'tarif' => 'required|numeric|min:0',
            'bayar' => 'required|min:0',
            'kembalian' => 'required|numeric|min:0'
        ]);

        if ($validated["bayar"] < $validated["tarif"]) {
            return redirect()->back()->with('error', 'Bayar kurang dari tarif!');
        }

        if (!empty($validated['id_member'] ?? null)) {
            $parkir = Parkir::where('id_member', $validated['id_member'])
                ->where('status', 'masuk') // cuma yang masih parkir
                ->first();

            if (!$parkir) {
                return redirect()->back()->with('error', 'Member ini belum melakukan parkir atau sudah keluar!');
            }

            $bayar = $validated['tarif'];
            $id = $validated['id_member'];
        } else {
            $parkir = Parkir::where('kode_tiket', $validated['kode_tiket'])
                ->where('status', 'masuk') // cuma yang masih parkir
                ->first();

            if (!$parkir) {
                return redirect()->back()->with('error', 'Tiket ini belum melakukan parkir atau sudah keluar!');
            }

            $bayar = $validated['bayar'];
            $id = null;
        }


        // Update data parkir
        $parkir->update([
            'id_petugas'    => $validated['id_petugas'],
            'plat_nomor'    => $validated['plat_nomor'],
            'waktu_masuk'   => $validated['waktu_masuk'],
            'waktu_keluar'  => $validated['waktu_keluar'],
            'tarif'         => $validated['tarif'],
            'bayar'         => $bayar,
            'kembalian'     => $validated['kembalian'],
            'status'        => 'keluar'
        ]);

        // Redirect ke halaman invoice
        return view('petugas.invoiceParkir', compact('parkir', 'id'));
    }
}
