<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    protected $casts = [
        'tgl_mulai' => 'datetime',
        'tgl_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'duration_days',
        'is_active',
        'is_upcoming',
        'is_ended',
    ];

    public function getDurationDaysAttribute()
    {
        if ($this->tgl_mulai && $this->tgl_selesai) {
            return Carbon::parse($this->tgl_mulai)->diffInDays($this->tgl_selesai) + 1;
        }
        return 0;
    }

    public function getIsActiveAttribute()
    {
        $now = Carbon::now();
        return $this->tgl_mulai && $this->tgl_selesai && 
               $now->between($this->tgl_mulai, $this->tgl_selesai);
    }

    public function getIsUpcomingAttribute()
    {
        $now = Carbon::now();
        return $this->tgl_mulai && $now->lt($this->tgl_mulai);
    }

    public function getIsEndedAttribute()
    {
        $now = Carbon::now();
        return $this->tgl_selesai && $now->gt($this->tgl_selesai);
    }
}
