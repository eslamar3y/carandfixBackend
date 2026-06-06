<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->header('Accept-Language', 'en');
        if (in_array($lang, ['en', 'ar'])) {
            App::setLocale($lang);
        }
        return $next($request);
    }
}
