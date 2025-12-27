<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaran extends Model
{
    protected $table = 'riwayat_pembayaran'; // kasih tau nama tabel yang bener
    protected $fillable = [
        'id',
        'id_member',
        'tanggal_bayar'
    ];

    public function member(){
        $this->belongsTo(Member::class, 'id_member', 'id');
    }
}
