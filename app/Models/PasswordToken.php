<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordToken extends Model
{
     protected $table = 'password_reset_tokens'; // kasih tau nama tabel yang bener
    protected $fillable = [
        'email',
        'token',
    ];
}
