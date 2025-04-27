<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnrollmentController;


Route::get('/check', function () {
    return response()->json(['message' => 'API is working']);
});

Route::apiResource('enrollments', EnrollmentController::class);

