<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;


Route::get('/check', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/requestbooks', [RequestController::class, 'index']);
Route::post('/requestbooks', [RequestController::class, 'store']);
Route::get('/requestbooks/{id}', [RequestController::class, 'show']);
Route::put('/requestbooks/{id}', [RequestController::class, 'update']);
Route::delete('/requestbooks/{id}', [RequestController::class, 'destroy']);
