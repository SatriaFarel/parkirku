<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members'; // kasih tau nama tabel yang bener
    protected $fillable = [
        'id',
        'nama',
        'status',
        'riwayat_pembayaran'
    ];
}
