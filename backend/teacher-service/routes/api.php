<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use MLL\GraphQLPlayground\GraphQLPlaygroundController;

Route::get('/teacher', function () {
    return response()->json(['message' => 'API is working']);
});

Route::prefix('v1')->group(function () {
    Route::apiResource('teacher', TeacherController::class);
});

Route::post('/graphql', [Rebing\GraphQL\GraphQLController::class, 'query'])->name('graphql');
Route::get('/playground', [MLL\GraphQLPlayground\GraphQLPlaygroundController::class, 'get']);