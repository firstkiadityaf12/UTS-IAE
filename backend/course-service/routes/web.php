<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
Route::put('/courses/{id}', [CourseController::class, 'update']);
<<<<<<< HEAD
Route::delete('/courses/{id}', [CourseController::class, 'destroy']); 
=======
Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
 
>>>>>>> 9fc4609e06e017f0435b64a25a862f65ef09c9f4
