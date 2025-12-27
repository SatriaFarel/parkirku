<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\RiwayatPembayaran;
use App\Models\Parkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf;

class MemberController extends Controller
{
    public function masuk(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
        ]);

        $id = $validated['id'];

        $maxId = Parkir::max('id');
        $idNew = $maxId ? $maxId + 1 : 1; // kalau tabel kosong, mulai dari 1

        $member = Member::find($id);
        if (!$member) {
            return back()->withErrors(['id' => 'Member tidak ditemukan'])->withInput();
        }

        if ($member->status == "belum lunas") {
            return back()->withErrors(['id' => 'Member belum lunas'])->withInput();
        }

        $parkirHariIni = Parkir::where('id_member', $id)
            ->whereDate('waktu_masuk', now()->toDateString())
            ->whereNull('waktu_keluar')
            ->first();
        if ($parkirHariIni) {
            return back()->withErrors(['id' => 'Member hari ini sudah tercatat masuk'])->withInput();
        }

        Parkir::create([
            'id'          => $idNew,
            'id_member'   => $id,
            'waktu_masuk' => now(),
            'status'      => 'masuk',
        ]);

        return back()->with('success', 'Kendaraan berhasil tercatat masuk hari ini.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'bayar' => 'required|numeric|min:150000',
            'kembali' => 'required'
        ]);

        if ($request->bayar < $request->tarif) {
            return back()
                ->withErrors(['bayar' => 'Bayar tidak boleh kurang dari tarif!'])
                ->withInput();
        }

        $idM = Member::max('id');
        $idM = $idM ? $idM + 1 : 1;

        $idR = RiwayatPembayaran::max('id');
        $idR = $idR ? $idR + 1 : 1;


        $member = Member::create([
            "id"   => $idM,
            "nama" => $validated["nama"],
        ]);

        // Buat data riwayat pembayaran (contoh otomatis satu kali bayar)
        $riwayat = RiwayatPembayaran::create([
            'id'        => $idR,
            'id_member' => $member->id,
            'tanggal_bayar' => now(),
        ]);

        return view('petugas.invoiceMember', ['member' => $member, 'riwayat' => $riwayat, 'bayar' => $validated['bayar'], 'kembalian' => $validated['kembali']]);
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'id' => 'required',
            'nama' => 'required',
        ]);

        $member->update([
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('member')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $member = Member::where('id', $id)->where('status', 'lunas')->first();
        if($member){
              return redirect()->route('member')->with('error', 'Masih Langganan');
        }

        $member->delete();
        return redirect()->route('member')->with('success', 'Data berhasil dihapus');
    }

    public function print($id)
    {
        $member = Member::findOrFail($id);
        $pdf = SnappyPdf::loadView('petugas.kartu', compact('member'))

            ->setOption('page-width', '85mm')
            ->setOption('page-height', '54mm')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('lowquality', true)
            ->setOption('disable-smart-shrinking', true);

        return $pdf->download('kartu.pdf');
    }



    public function payment(Request $request)
    {
        $validated = $request->validate([
            'id_member' => 'required',
            'nama' => 'required',
            'tanggal_bayar' => 'required',
            'tarif' => 'required',
            'bayar' => 'required',
            'kembali' => '',
        ]);

        if ($validated['bayar'] < $validated['tarif']) {
            return redirect()->back()->with('error', 'Jumlah bayar kurang dari tarif!');
        }

        $member = Member::findOrFail($validated['id_member']);
        $member->update([
            'status' => 'lunas'
        ]);
        $riwayat = RiwayatPembayaran::create([
            'id_member' => $member->id,
            'tanggal_bayar' => $validated['tanggal_bayar'],

        ]);

        return view('petugas.invoiceMember', ['member' => $member, 'riwayat' => $riwayat, 'bayar' => $validated['bayar'], 'kembalian' => $validated['kembali']]);
    }

    public function view($id)
    {
        $member = Member::find($id);

        return view('petugas.view', [
            'member' => $member,
            'riwayat' => RiwayatPembayaran::where('id_member', $member->id)->get()
        ]);
    }
}
