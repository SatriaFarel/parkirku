<?php

namespace App\Http\Controllers;

use App\Models\Parkir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    public function masuk(){
        return view("masuk");
    }
    public function index(){

        $code = 'TKT' . Str::upper(Str::random(4));

        $id = Parkir::max('id');
        $id = $id ? $id+1 : 1;

        $parkir = Parkir::create([
            'id'    => $id,
            'kode_tiket' => $code,
            'waktu_masuk' => now()
        ]);

        return view('tiket', ['code' => $code]);
    }
    public function proses(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = $request->barcode;

        // Cek apakah kode tiket valid
        $parkir = Parkir::where('kode_tiket', $barcode)->first();

        if (!$parkir) {
            return response()->json([
                'success' => false,
                'message' => 'Kode tiket tidak ditemukan.'
            ]);
        }

        // Cek apakah sudah masuk sebelumnya
        if ($parkir->status == 'masuk') {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan sudah tercatat masuk.'
            ]);
        }

        // Update status masuk
        $parkir->update([
            'waktu_masuk' => now(),
            'status' => 'masuk'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kendaraan berhasil tercatat masuk.'
        ]);
    }
}
