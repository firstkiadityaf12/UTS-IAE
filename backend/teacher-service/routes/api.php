<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/teacher', function () {
    return response()->json(['message' => 'API is working']);
});

Route::prefix('v1')->group(function () {
    Route::apiResource('teacher', TeacherController::class);
});