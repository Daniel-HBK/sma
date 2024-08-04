<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\V1\LanguageMiddleware;

Route::fallback(function () {
    return response()->json([
        'status'    => false,
        'message'   => __('app.notFound')
    ], 404);
})->middleware(LanguageMiddleware::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum', LanguageMiddleware::class);

// Version 1 of the API
Route::prefix('v1')->group(base_path('routes/api_v1.php'));