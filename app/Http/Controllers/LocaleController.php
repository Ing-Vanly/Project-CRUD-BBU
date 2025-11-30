<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    /**
     * Persist the requested locale in the session and redirect back.
     */
    public function switch(Request $request): RedirectResponse
    {
        $available = array_keys(config('app.supported_locales', []));

        if (empty($available)) {
            $available = config('app.available_locales', [config('app.locale')]);
        }

        $validated = $request->validate([
            'locale' => ['required', Rule::in($available)],
        ]);

        $locale = $validated['locale'];

        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
