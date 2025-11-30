<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Bootstrap the requested locale before handling the request.
     */
    public function handle(Request $request, Closure $next)
    {
        $availableLocales = $this->availableLocales();
        $locale = $request->hasSession() ? $request->session()->get('locale') : null;

        if (! $locale || ! in_array($locale, $availableLocales, true)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }

    /**
     * Resolve the configured list of supported locales.
     */
    protected function availableLocales(): array
    {
        $supported = config('app.supported_locales', []);

        if (! empty($supported)) {
            return array_keys($supported);
        }

        return config('app.available_locales', [config('app.locale')]);
    }
}
