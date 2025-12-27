<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Parkir;
use App\Models\User;
use App\Models\Member;
use Carbon\Carbon;
use App\Mpdels\RiwayatPembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\Mime\Email;

class PetugasController extends Controller
{
    public function home()
    {
        $user = Auth::user(); // ambil user yang login
        $id = $user->id;
        $parkirs = Parkir::whereNull('id_petugas')
            ->orWhere('id_petugas', $id)
            ->whereDate('created_at', today())
            ->get();

        $totalMasuk = Parkir::whereDate('created_at', today())->count();
        $sedangParkir = Parkir::where('status', 'Masuk')->count();
        $keluarHariIni = Parkir::whereDate('waktu_keluar', today())->count();
        return view('petugas.home', compact('parkirs', 'totalMasuk', 'sedangParkir', 'keluarHariIni'));
    }

    public function member()
    {
        // Ambil waktu sekarang
        $now = Carbon::now();

        // Subquery: ambil tanggal pembayaran terakhir (MAX) untuk setiap id_member
        $paymentsSub = DB::table('riwayat_pembayaran')
            ->select('id_member', DB::raw('MAX(tanggal_bayar) as last_payment'))
            ->groupBy('id_member');

        // Join member dengan subquery pembayaran terakhir sehingga setiap member muncul sekali
        $member = Member::leftJoinSub($paymentsSub, 'last_pay', function ($join) {
            $join->on('members.id', '=', 'last_pay.id_member');
        })
            // agar nama kolom tetap sesuai yang dulu (kalau view mengharapkan 'tanggal_bayar')
            ->select('members.*', DB::raw('last_pay.last_payment as tanggal_bayar'))
            ->get();

        // Loop untuk set status berdasarkan tanggal pembayaran terakhir
        foreach ($member as $m) {
            // jika ada tanggal pembayaran terakhir
            if (!is_null($m->tanggal_bayar)) {
                $tanggal = Carbon::parse($m->tanggal_bayar);
                $pembayaranBulanIni = ($tanggal->month == $now->month) && ($tanggal->year == $now->year);
            } else {
                $pembayaranBulanIni = false;
            }

            if ($pembayaranBulanIni) {
                // Set status jadi "lunas"
                Member::where('id', $m->id)->update(['status' => 'lunas']);
            } else {
                // Set status jadi "belum lunas"
                Member::where('id', $m->id)->update(['status' => 'belum lunas']);
            }
        }

        // Ambil ulang data setelah update status (gunakan lagi subquery agar tetap unik)
        $paymentsSub = DB::table('riwayat_pembayaran')
            ->select('id_member', DB::raw('MAX(tanggal_bayar) as last_payment'))
            ->groupBy('id_member');

        $member = Member::leftJoinSub($paymentsSub, 'last_pay', function ($join) {
            $join->on('members.id', '=', 'last_pay.id_member');
        })
            ->select('members.*', DB::raw('last_pay.last_payment as tanggal_bayar'))
            ->get();

        return view('petugas.member', compact('member'));
    }


    public function masuk()
    {
        $user = Auth::user(); // ambil user yang login
        return view('petugas.create', compact('user'));
    }

    public function petugas()
    {
        $petugas = User::where('role', 'petugas')->get();
        return view('admin.petugas', compact('petugas'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required',
        ]);

        $role = "petugas";

        $cek = User::where('email', $validated['email'])->exists();
        if ($cek) {
            return redirect()->route('petugas')->with('success', 'Email sudah terdaftar');
        }

        $id = User::max('id');
        $id = $id ? $id + 1 : 1;

        User::create([
            "id" => $id,
            "name" => $validated["name"],
            "email" => $validated["email"],
            "role" => $role,
            "password" => $validated["password"],
        ]);

        return redirect()->route('petugas')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit(User $petugas)
    {
        return view('admin.edit', compact('petugas'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable',
        ]);

        $cek = User::where('email', $validated['email'])
            ->whereKeyNot($validated['id'])
            ->exists();;

        if ($cek) {
            return redirect()->route('petugas')->with('error', 'Email sudah terdaftar');
        }

        $user = User::where('id', $validated['id'])->first();

        if (empty($validated['password'])) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],

            ]);
        } else {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
        }

        return redirect()->route('petugas')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('petugas')->with('success', 'Data berhasil dihapus');
    }



    public function downloadLaporan()
    {
        $tanggal = today();

        $laporan = Parkir::whereDate('waktu_masuk', $tanggal)->get();

        $nama = Auth::user();

        $pdf = Pdf::loadView('petugas.printLaporan', [
            'tanggal' => $tanggal,
            'nama_petugas' => $nama["name"],
            'laporan' => $laporan
        ])->setPaper('A4', 'portrait');

        return $pdf->download('laporan-parkir-' . $tanggal . '.pdf');
    }
}
