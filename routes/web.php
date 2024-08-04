<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\V1\LanguageMiddleware;


Route::middleware(LanguageMiddleware::class)->any('{url?}/{sub_url?}', function () {
    return response()->json([
        'status'    => false,
        'message'   => __('app.notFound')
    ], 404);
});