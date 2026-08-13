@php
    $brand = \App\Models\Setting::get('brand_name', 'SinonimDesign');
    $categories = \App\Models\Category::published()
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->get();
@endphp

<header
    x-data="{ mobileOpen: false }"
    class="sticky top-0 z-30 bg-[var(--color-paper)]/90 backdrop-blur border-b border-[var(--color-brand-200)]"
>
    <div class="container-wide flex items-center justify-between py-5">
        {{-- Left: mobile menu toggle --}}
        <button
            @click="mobileOpen = !mobileOpen"
            class="lg:hidden p-2 -ml-2"
            aria-label="Meni"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="font-display text-xl md:text-2xl tracking-tight">
            {{ $brand }}
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden lg:flex items-center gap-8 text-sm tracking-wide">
            <a href="{{ url('/') }}" class="link-underline">{{ __('Home') }}</a>
            <a href="{{ route('shop.index') }}" class="link-underline">{{ __('Shop') }}</a>
            <a href="{{ route('collections.index') }}" class="link-underline">{{ __('Collections') }}</a>
            @foreach($categories->take(3) as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="link-underline">{{ $cat->name }}</a>
            @endforeach
            <a href="{{ route('page.about') }}" class="link-underline">{{ __('About') }}</a>
            <a href="{{ route('page.contact') }}" class="link-underline">{{ __('Contact') }}</a>
        </nav>

        {{-- Right: language + wishlist + cart --}}
        <div class="flex items-center gap-4">
            {{-- Language switcher --}}
            <div class="flex items-center gap-1 text-xs tracking-widest uppercase">
                @foreach(['bs', 'en'] as $loc)
                    <a
                        href="{{ route('language.switch', $loc) }}"
                        class="px-1.5 py-1 {{ app()->getLocale() === $loc ? 'font-semibold underline underline-offset-4' : 'opacity-60 hover:opacity-100' }}"
                    >{{ strtoupper($loc) }}</a>
                @endforeach
            </div>

            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}" class="relative p-1" aria-label="{{ __('Wishlist') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
                <span
                    x-show="$store.wishlist.count() > 0"
                    x-text="$store.wishlist.count()"
                    class="absolute -top-1 -right-1 bg-[var(--color-brand-800)] text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center"
                ></span>
            </a>

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="relative p-1" aria-label="{{ __('Cart') }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12A1.125 1.125 0 0119.75 21H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 6.75h12.974c.576 0 1.059.435 1.119 1.007z" />
                </svg>
                <span
                    x-show="$store.cart.count() > 0"
                    x-text="$store.cart.count()"
                    class="absolute -top-1 -right-1 bg-[var(--color-brand-800)] text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center"
                ></span>
            </a>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div
        x-show="mobileOpen"
        x-transition
        @click.outside="mobileOpen = false"
        class="lg:hidden border-t border-[var(--color-brand-200)] bg-[var(--color-paper)]"
        style="display:none"
    >
        <nav class="container-wide py-4 flex flex-col gap-3 text-sm">
            <a href="{{ url('/') }}" class="py-2">{{ __('Home') }}</a>
            <a href="{{ route('shop.index') }}" class="py-2">{{ __('Shop') }}</a>
            <a href="{{ route('collections.index') }}" class="py-2">{{ __('Collections') }}</a>
            @foreach($categories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="py-2">{{ $cat->name }}</a>
            @endforeach
            <a href="{{ route('page.about') }}" class="py-2">{{ __('About') }}</a>
            <a href="{{ route('page.contact') }}" class="py-2">{{ __('Contact') }}</a>
        </nav>
    </div>
</header>
