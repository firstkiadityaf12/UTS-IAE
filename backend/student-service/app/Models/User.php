<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'nama',
        'gmail',
        'no_telp',
        'alamat',
        'username',
        'password',
    ];
}
