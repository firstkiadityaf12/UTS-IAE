<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'id_course';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nama_course',
        'deskripsi',
        'tgl_mulai',
        'tgl_selesai',
        'kat_bidang',
        'kapasitas'
    ];

    protected $dates = [
        'tgl_mulai',
        'tgl_selesai',
        'created_at',
        'updated_at'
    ];
} 