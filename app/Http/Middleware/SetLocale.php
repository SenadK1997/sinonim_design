<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $available = config('app.available_locales', ['bs', 'en']);
        $default = config('app.locale', 'bs');

        $locale = $request->session()->get('locale', $default);

        if (! in_array($locale, $available, true)) {
            $locale = $default;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
