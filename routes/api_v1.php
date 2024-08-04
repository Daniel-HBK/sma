<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\MessageController;
use App\Http\Controllers\V1\RecipientController;
use App\Http\Middleware\V1\LanguageMiddleware;



Route::middleware('throttle:60,1', LanguageMiddleware::class)->group(function () {

    Route::fallback(function () {
        return response()->json([
            'status'    => false,
            'message'   => __('app.notFound')
        ], 404);
    });

    Route::prefix('messages')->group(function () {

        Route::post('/', [MessageController::class, 'store']);
        Route::get('/{identifier}', [MessageController::class, 'show']);
    });

    Route::prefix('recipients')->group(function () {
        Route::get('/{identifier}', [RecipientController::class, 'show']);
    });
});