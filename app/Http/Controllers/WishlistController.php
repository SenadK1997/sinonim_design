<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        // Load all published products; view filters client-side by localStorage.
        $products = Product::published()->with(['category', 'media'])->get();

        return view('wishlist.index', compact('products'));
    }
}
