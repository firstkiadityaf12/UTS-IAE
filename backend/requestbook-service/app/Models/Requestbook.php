<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBook extends Model
{
    use HasFactory;

    protected $table = 'book_requests';
    protected $fillable = [
        'id_student',
        'requested_title',
        'notes',
        'status'
    ];
}
