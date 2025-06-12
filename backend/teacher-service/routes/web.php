<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
// use Nuwave\Lighthouse\Support\Http\Controllers\GraphQLController;

// Route::view('/graphql', 'graphql-playground'); // UI tampilan
// Route::post('/graphql', [GraphQLController::class, 'query']); // untuk query dari playground


Route::get('/teachers', [TeacherController::class, 'index']);
Route::post('/teachers', [TeacherController::class, 'store']);
Route::get('/teachers/{id}', [TeacherController::class, 'show']);
Route::put('/teachers/{id}', [TeacherController::class, 'update']);
Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);