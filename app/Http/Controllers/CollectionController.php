<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::published()
            ->orderByDesc('published_at')
            ->get();

        return view('collections.index', compact('collections'));
    }

    public function show(string $slug)
    {
        $collection = Collection::published()->where('slug', $slug)->firstOrFail();

        $products = $collection->products()
            ->published()
            ->with(['category', 'media'])
            ->get();

        return view('collections.show', compact('collection', 'products'));
    }
}
