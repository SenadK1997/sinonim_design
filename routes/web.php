<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderLookupController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Language
Route::get('/jezik/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Shop
Route::get('/prodavnica', [ShopController::class, 'index'])->name('shop.index');
Route::get('/kategorija/{slug}', [ShopController::class, 'index'])->name('category.show');

// Collections
Route::get('/kolekcije', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/kolekcije/{slug}', [CollectionController::class, 'show'])->name('collections.show');

// Product
Route::get('/proizvod/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart & wishlist (client-side stores, server just renders shells)
Route::get('/korpa', [CartController::class, 'index'])->name('cart.index');
Route::get('/lista-zelja', [WishlistController::class, 'index'])->name('wishlist.index');

// Checkout (COD)
Route::get('/kasa', [CheckoutController::class, 'show'])->name('checkout.index');
Route::post('/kasa', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/narudzba/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Order lookup
Route::get('/pratite-narudzbu', [OrderLookupController::class, 'show'])->name('order.lookup');

// Newsletter
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Static / legal pages
Route::get('/o-nama', [PageController::class, 'about'])->name('page.about');
Route::get('/kontakt', [PageController::class, 'contact'])->name('page.contact');
Route::get('/dostava', [PageController::class, 'shipping'])->name('page.shipping');
Route::get('/reklamacije', [PageController::class, 'returns'])->name('page.returns');
Route::get('/politika-privatnosti', [PageController::class, 'privacy'])->name('page.privacy');
Route::get('/uslovi-koristenja', [PageController::class, 'terms'])->name('page.terms');
