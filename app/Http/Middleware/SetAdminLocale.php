<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('admin_locale')) {
            $locale = session('admin_locale');
            if (in_array($locale, ['ar', 'en', 'fr'])) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
