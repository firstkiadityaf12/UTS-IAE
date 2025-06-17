<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

Route::prefix('/')->group(function () {
    // Endpoint tambahan untuk buku berdasarkan student_id
    Route::get('buku/by-student', [BukuController::class, 'listByStudent']);

    // Resource bawaan: index, show, store, update, destroy
    Route::apiResource('buku', BukuController::class);
});
