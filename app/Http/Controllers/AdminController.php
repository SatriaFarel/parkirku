<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parkir;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

use function Pest\Laravel\get;
use function Symfony\Component\Clock\now;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMasuk = Parkir::count();
        $sedangParkir = Parkir::where('status', 'Masuk')->count();
        $keluarHariIni = Parkir::whereDate('waktu_keluar', today())->count();
        // Ambil ulang data setelah update status
        $dataMember = Parkir::leftJoin('members', 'parkir.id_member', '=', 'members.id')
            ->leftJoin('users', 'parkir.id_petugas', '=', 'users.id') // join untuk petugas
            ->select(
                'parkir.*',
                'members.nama AS nama_member',  // nama member
                'users.name AS nama_petugas'    // nama petugas
            )
            ->whereNotNull('parkir.id_member')
            ->get();
        $dataNonMember = Parkir::leftJoin('members', 'parkir.id_member', '=', 'members.id')
            ->leftJoin('users', 'parkir.id_petugas', '=', 'users.id') // join untuk petugas
            ->select(
                'parkir.*',
                'members.nama AS nama_member',  // nama member
                'users.name AS nama_petugas'    // nama petugas
            )
            ->whereNull('parkir.id_member')
            ->get();;

        $gabungan = collect($dataMember)
            ->map(function ($m) {
                return [
                    'petugas' => $m->nama_petugas,
                    'plat' => $m->plat_nomor,
                    'status' => $m->status,
                    'masuk' => $m->waktu_masuk,
                    'keluar' => $m->waktu_keluar,
                    'jenis' => 'Member'
                ];
            })
            ->merge(
                collect($dataNonMember)->map(function ($n) {
                    return [
                        'petugas' => $n->nama_petugas,
                        'plat' => $n->plat_nomor,
                        'status' => $n->status,
                        'masuk' => $n->waktu_masuk,
                        'keluar' => $n->waktu_keluar,
                        'jenis' => 'Non Member'
                    ];
                })
            );

        $petugasList = $gabungan->pluck('petugas')->filter(fn($p) => !empty(trim($p)))
            ->unique()->values();

        return view('admin.dashboard', compact('totalMasuk', 'sedangParkir', 'keluarHariIni', 'dataMember', 'dataNonMember', 'gabungan', 'petugasList'));
    }

    public function admin()
    {
        $admin = User::where('role', 'admin')->get();
        $isLog = Auth::id();
        return view('admin.admin', compact('admin', 'isLog'));
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

        $role = "admin";
        $id = User::max("id");
        $id = $id ? $id + 1 : 1;

        User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "role" => $role,
            "password" => $validated["password"],
        ]);

        return redirect()->route('admin')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit(User $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($admin, 'id')
            ],
            'password' => 'nullable'
        ]);

        if (empty($validated['password'])) {
            $admin->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        } else {
            $admin->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
        }



        return redirect()->route('admin')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin')->with('success', 'Data berhasil dihapus');
    }

    public function printPetugas(Request $request)
    {
        $data = json_decode($request->data, true);

        $petugasNames = [];

        foreach ($data as $row) {
            if (!empty($row[1])) {
                $petugasNames[] = $row[1]; // kolom petugas
            }
        }

        // Hapus duplikat
        $petugasNames = array_values(array_unique($petugasNames));

        // ✅ CEK APAKAH ADMIN ATAU PETUGAS
        $isAdminReport = count($petugasNames) > 1;

        if ($isAdminReport) {
            // =====================
            // LAPORAN ADMIN
            // =====================
            $dataParkir = Parkir::leftJoin('members', 'parkir.id_member', '=', 'members.id')
                ->leftJoin('users', 'parkir.id_petugas', '=', 'users.id')
                ->select(
                    'parkir.*',
                    'members.nama AS nama_member',
                    'users.name AS nama_petugas'
                )
                ->get();

            $printedBy = 'Admin';
        } else {
            // =====================
            // LAPORAN PETUGAS
            // =====================
            $petugas = User::where('name', $petugasNames[0])->first();

            $dataParkir = Parkir::leftJoin('members', 'parkir.id_member', '=', 'members.id')
                ->leftJoin('users', 'parkir.id_petugas', '=', 'users.id')
                ->select(
                    'parkir.*',
                    'members.nama AS nama_member',
                    'users.name AS nama_petugas'
                )
                ->where('id_petugas', $petugas->id)
                ->get();

            $printedBy = $petugas->name;
        }

        $tanggal = today()->format('Y-m-d');

        $pdf = Pdf::loadView('admin.print.petugas', [
            'dataParkir' => $dataParkir,
            'printedBy'  => $printedBy,
            'tanggal'    => $tanggal,
            'isAdmin'    => $isAdminReport
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-parkir.pdf');
    }


    public function printMember()
    {
        $data = Parkir::leftJoin('members', 'parkir.id_member', '=', 'members.id')
            ->leftJoin('users', 'parkir.id_petugas', '=', 'users.id') // join untuk petugas
            ->select(
                'parkir.*',
                'members.nama AS nama_member',  // nama member
                'users.name AS nama_petugas'    // nama petugas
            )
            ->whereNotNull('parkir.id_member')
            ->get();

        $adminId = Auth::id();
        $admin = User::find($adminId);
        $admin = $admin->name;
        $tanggal = today()->format("Y-m-d");



        $pdf = Pdf::loadView('admin.print.member', [
            "dataParkir" => $data,
            "admin" => $admin,
            "tanggal" => $tanggal
        ])->setPaper('A4', 'portrait');

        return $pdf->download('laporan-member-' . '.pdf');
    }

    public function printNonMember()
    {
        $data = Parkir::leftJoin('members', 'parkir.id_member', '=', 'members.id')
            ->leftJoin('users', 'parkir.id_petugas', '=', 'users.id') // join untuk petugas
            ->select(
                'parkir.*',
                'members.nama AS nama_member',  // nama member
                'users.name AS nama_petugas'    // nama petugas
            )
            ->whereNull('parkir.id_member')
            ->get();

        $adminId = Auth::id();
        $admin = User::find($adminId);
        $admin = $admin->name;
        $tanggal = today()->format("Y-m-d");

        $pdf = Pdf::loadView('admin.print.nonMember', [
            "dataParkir" => $data,
            "admin" => $admin,
            "tanggal" => $tanggal
        ])->setPaper('A4', 'portrait');

        return $pdf->download('laporan-nonmember-' . '.pdf');
    }
}
