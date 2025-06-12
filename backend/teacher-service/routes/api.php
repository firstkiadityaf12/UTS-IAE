<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use Nuwave\Lighthouse\Support\Facades\GraphQL;

Route::post('/graphql', [\Nuwave\Lighthouse\Support\Http\Controllers\GraphQLController::class, 'query']);

Route::get('/teacher', function () {
    return response()->json(['message' => 'API is working']);
});

Route::prefix('v1')->group(function () {
    Route::apiResource('teacher', TeacherController::class);
});