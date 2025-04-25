<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'course_enrollments'; // Nama tabelnya

    protected $primaryKey = 'id_enroll'; // Primary key custom

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_student',
        'id_teacher',
        'id_course',
        'status'
    ];

}
