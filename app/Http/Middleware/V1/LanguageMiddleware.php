<?php

namespace App\Http\Middleware\V1;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $request->headers->remove('X-Powered-By');
        $request->headers->remove('Server');

        $lang = $request->header('Accept-Language') ?? $request->query('lang');

        if ($lang && in_array($lang, ['en', 'nl'])) {
            App::setLocale($lang);
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}