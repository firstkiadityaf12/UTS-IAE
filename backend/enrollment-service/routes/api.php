<?php

use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;


Route::apiResource('enrollments', EnrollmentController::class);
