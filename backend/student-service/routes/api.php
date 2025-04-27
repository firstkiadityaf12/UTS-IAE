<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


Route::get('/check', function () {
    return response()->json(['message' => 'API is working']);
});

Route::prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class);
});
