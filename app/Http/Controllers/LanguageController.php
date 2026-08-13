<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $available = config('app.available_locales', ['bs', 'en']);

        if (in_array($locale, $available, true)) {
            $request->session()->put('locale', $locale);
        }

        return back();
    }
}
