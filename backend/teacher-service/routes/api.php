<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/teacher', function () {
    return response()->json(['message' => 'API is working']);
});

Route::apiResource('teacher', TeacherController::class);
