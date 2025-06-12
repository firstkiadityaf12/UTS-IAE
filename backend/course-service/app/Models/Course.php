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

    /**
     * Get the duration of the course in days
     */
    public function getDurationDaysAttribute()
    {
        if ($this->tgl_mulai && $this->tgl_selesai) {
            return Carbon::parse($this->tgl_mulai)->diffInDays($this->tgl_selesai) + 1;
        }
        return 0;
    }

    /**
     * Check if the course is currently active
     */
    public function getIsActiveAttribute()
    {
        $now = Carbon::now();
        return $this->tgl_mulai && $this->tgl_selesai && 
               $now->between($this->tgl_mulai, $this->tgl_selesai);
    }

    /**
     * Check if the course is upcoming (hasn't started yet)
     */
    public function getIsUpcomingAttribute()
    {
        $now = Carbon::now();
        return $this->tgl_mulai && $now->lt($this->tgl_mulai);
    }

    /**
     * Check if the course has ended
     */
    public function getIsEndedAttribute()
    {
        $now = Carbon::now();
        return $this->tgl_selesai && $now->gt($this->tgl_selesai);
    }
} 