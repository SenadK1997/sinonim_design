<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        $collections = Collection::published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $promoted = Product::published()
            ->promoted()
            ->with(['category', 'media'])
            ->latest('published_at')
            ->take(8)
            ->get();

        return view('pages.home', compact('collections', 'promoted'));
    }
}
