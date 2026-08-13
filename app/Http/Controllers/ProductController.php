<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::published()
            ->with(['category', 'variants', 'media', 'collections'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::published()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->with(['category', 'media'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
