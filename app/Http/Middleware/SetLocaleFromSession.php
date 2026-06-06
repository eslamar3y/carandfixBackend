<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromSession
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['en', 'ar'])) {
                App::setLocale($locale);
            }
        }
        return $next($request);
    }
}
