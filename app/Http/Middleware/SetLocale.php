<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public const LOCALES = ['en', 'bn'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale', config('app.locale'));
        if (! in_array($locale, self::LOCALES, true)) {
            $locale = 'en';
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
