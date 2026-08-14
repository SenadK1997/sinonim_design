@props(['product'])

@php
    $img = $product->primaryImageUrl('card');
    $isOnSale = $product->isOnSale();
    $inStock = $product->isInStock();
@endphp

<article class="product-card group">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        <div class="relative overflow-hidden aspect-[4/5] bg-[var(--color-brand-100)] rounded-2xl">
            @if($img)
                <img src="{{ $img }}" alt="{{ $product->name }}" class="product-card-image w-full h-full object-cover" loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center text-[var(--color-brand-400)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-16 h-16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            @endif

            {{-- Sold out overlay — covers image with soft white tint --}}
            @if(!$inStock)
                <div class="absolute inset-0 bg-white/50 flex items-center justify-center">
                    <span class="bg-[var(--color-ink)] text-white text-[10px] tracking-[0.3em] uppercase px-4 py-2">{{ __('Sold out') }}</span>
                </div>
            @endif

            {{-- Refined labels — top-left, minimal --}}
            <div class="absolute top-3 left-3 flex flex-col items-start gap-1.5 pointer-events-none">
                @if($isOnSale)
                    @php
                        $percentOff = $product->base_price > 0
                            ? (int) round((($product->base_price - $product->sale_price) / $product->base_price) * 100)
                            : 0;
                    @endphp
                    <span class="inline-flex items-center gap-1 bg-white/95 backdrop-blur text-[10px] tracking-[0.15em] uppercase text-[var(--color-ink)] px-2 py-1 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                        <span class="w-1 h-1 rounded-full bg-red-500"></span>
                        @if($percentOff > 0) −{{ $percentOff }}% @else {{ __('Sale') }} @endif
                    </span>
                @endif
                @if($product->is_made_to_order)
                    <span class="inline-flex items-center gap-1.5 bg-[var(--color-brand-50)]/95 backdrop-blur text-[10px] tracking-[0.15em] uppercase text-[var(--color-brand-800)] px-2 py-1 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-2.5 h-2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        {{ __('Made to order') }}
                    </span>
                @endif
            </div>

            {{-- Wishlist toggle --}}
            <button
                type="button"
                @click.prevent.stop="$store.wishlist.toggle({{ $product->id }})"
                :class="$store.wishlist.has({{ $product->id }}) ? 'bg-[var(--color-ink)] text-white' : 'bg-white/90 text-[var(--color-ink)]'"
                class="absolute top-3 right-3 p-2 rounded-full backdrop-blur hover:scale-110 transition"
                aria-label="{{ __('Add to wishlist') }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </button>
        </div>

        <div class="pt-4">
            <h3 class="text-sm md:text-base font-medium tracking-wide">{{ $product->name }}</h3>
            @if($product->category)
                <p class="text-xs opacity-60 mt-0.5">{{ $product->category->name }}</p>
            @endif
            <div class="mt-2 flex items-baseline gap-2">
                @if($isOnSale)
                    <span class="text-sm font-semibold text-[var(--color-ink)]">{{ \App\Support\Money::format($product->sale_price) }}</span>
                    <span class="text-xs line-through opacity-50">{{ \App\Support\Money::format($product->base_price) }}</span>
                @else
                    <span class="text-sm font-semibold">{{ \App\Support\Money::format($product->base_price) }}</span>
                @endif
            </div>
        </div>
    </a>
</article>
