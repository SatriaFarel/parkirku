<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Kendaraan;

class Parkir extends Model
{
    protected $table = 'parkir'; // kasih tau nama tabel yang bener
    protected $fillable = [
        'id',
        'kode_tiket',
        'id_petugas',
        'id_member',
        'plat_nomor',
        'waktu_masuk',
        'waktu_keluar',
        'tarif',
        'bayar',
        'kembalian',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class,'id_petugas', 'id');
    }

    public function member(){
        return $this->belongsTo(Member::class, 'id_member', 'id');
    }
}
