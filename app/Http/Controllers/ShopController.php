<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, ?string $categorySlug = null)
    {
        $categories = Category::published()->whereNull('parent_id')->orderBy('sort_order')->get();

        $category = $categorySlug
            ? Category::published()->where('slug', $categorySlug)->firstOrFail()
            : null;

        $query = Product::published()->with(['category', 'media']);

        if ($category) {
            $query->where('category_id', $category->id);
        }

        $query = match ($request->get('sort')) {
            'price_asc' => $query->orderBy('base_price'),
            'price_desc' => $query->orderByDesc('base_price'),
            default => $query->latest('published_at'),
        };

        $products = $query->paginate(24);

        return view('shop.index', compact('products', 'categories', 'category'));
    }
}
