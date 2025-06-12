<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use MLL\GraphQLPlayground\GraphQLPlaygroundController;

Route::get('/teachers', [TeacherController::class, 'index']);
Route::post('/teachers', [TeacherController::class, 'store']);
Route::get('/teachers/{id}', [TeacherController::class, 'show']);
Route::put('/teachers/{id}', [TeacherController::class, 'update']);
Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);

Route::get('/playground', [GraphQLPlaygroundController::class, 'get']);

