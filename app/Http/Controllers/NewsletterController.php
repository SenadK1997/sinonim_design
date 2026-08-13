<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower($data['email'])],
            ['locale' => app()->getLocale()]
        );

        return back()->with('newsletter_ok', 'Hvala na prijavi!');
    }
}
