<?php
use App\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;
Route::middleware('api')->group(function () {
    Route::get('/check', function () {
        return response()->json(['message' => 'API is working']);
    });

    Route::get('/enrollments', [EnrollmentController::class, 'index']);
    Route::post('/enrollments', [EnrollmentController::class, 'store']);
    Route::get('/enrollments/{id}', [EnrollmentController::class, 'show']);
    Route::put('/enrollments/{id}', [EnrollmentController::class, 'update']);
    Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy']);
});


