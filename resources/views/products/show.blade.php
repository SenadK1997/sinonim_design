<x-layouts.app :title="$product->name" :description="$product->meta_description ?? $product->description" :ogImage="$product->primaryImageUrl('large')">
    @php
        $images = $product->getMedia('gallery');
        $variants = $product->variants;
        $sizes = $variants->pluck('size')->filter()->unique()->values();
        $colors = $variants->pluck('color')->filter()->unique()->values();
        $isOnSale = $product->isOnSale();
        $inStock = $product->isInStock();
    @endphp

    <section class="container-wide py-8 md:py-12">
        <nav class="text-xs opacity-60 mb-6 flex gap-2">
            <a href="{{ url('/') }}" class="link-underline">{{ __('Home') }}</a> /
            <a href="{{ route('shop.index') }}" class="link-underline">{{ __('Shop') }}</a>
            @if($product->category)
                / <a href="{{ route('category.show', $product->category->slug) }}" class="link-underline">{{ $product->category->name }}</a>
            @endif
            <span>/ {{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-8 md:gap-16" x-data="{ activeImage: 0 }">
            {{-- Gallery --}}
            <div>
                @if($images->count() > 0)
                    <div class="aspect-[4/5] bg-[var(--color-brand-100)] overflow-hidden rounded-2xl md:rounded-3xl">
                        @foreach($images as $i => $img)
                            <img
                                src="{{ $img->getUrl('large') }}"
                                alt="{{ $product->name }}"
                                x-show="activeImage === {{ $i }}"
                                class="w-full h-full object-cover"
                                @if($i > 0) style="display:none" @endif
                            >
                        @endforeach
                    </div>
                    @if($images->count() > 1)
                        <div class="grid grid-cols-5 gap-2 mt-3">
                            @foreach($images as $i => $img)
                                <button @click="activeImage = {{ $i }}" class="aspect-square overflow-hidden rounded-xl" :class="activeImage === {{ $i }} ? 'ring-2 ring-[var(--color-ink)]' : ''">
                                    <img src="{{ $img->getUrl('thumb') }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-[4/5] bg-[var(--color-brand-100)] rounded-2xl md:rounded-3xl"></div>
                @endif
            </div>

            {{-- Info --}}
            <div class="lg:sticky lg:top-24 self-start">
                @if($product->category)
                    <p class="eyebrow mb-3">{{ $product->category->name }}</p>
                @endif
                <h1 class="font-display text-3xl md:text-4xl lg:text-5xl">{{ $product->name }}</h1>

                <div class="mt-4 flex items-baseline gap-3">
                    @if($isOnSale)
                        <span class="text-2xl font-semibold text-[var(--color-ink)]">{{ \App\Support\Money::format($product->sale_price) }}</span>
                        <span class="text-lg line-through opacity-50">{{ \App\Support\Money::format($product->base_price) }}</span>
                        <span class="text-xs uppercase tracking-widest bg-[var(--color-ink)] text-white px-2 py-1">{{ __('Sale') }}</span>
                    @else
                        <span class="text-2xl font-semibold">{{ \App\Support\Money::format($product->base_price) }}</span>
                    @endif
                </div>

                @if($product->is_made_to_order)
                    <p class="mt-3 text-xs tracking-widest uppercase text-[var(--color-brand-700)]">{{ __('Made to order') }}</p>
                @endif

                @if($product->description)
                    <div class="mt-6 prose prose-sm opacity-90 leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @endif

                {{-- Variant picker + add to cart --}}
                <form
                    x-data="{
                        selectedSize: {{ $sizes->count() ? 'null' : "''" }},
                        selectedColor: {{ $colors->count() ? 'null' : "''" }},
                        qty: 1,
                        variants: @js($variants->map(fn($v) => ['id' => $v->id, 'size' => $v->size, 'color' => $v->color, 'stock' => $v->stock, 'price' => $v->price()])->toArray()),
                        get matched() {
                            return this.variants.find(v => (this.selectedSize === null || v.size === this.selectedSize) && (this.selectedColor === null || v.color === this.selectedColor));
                        },
                        addToCart() {
                            const v = this.matched;
                            $store.cart.add({
                                product_id: {{ $product->id }},
                                variant_id: v?.id ?? null,
                                name: '{{ addslashes($product->name) }}',
                                image: '{{ $product->primaryImageUrl('thumb') }}',
                                size: this.selectedSize,
                                color: this.selectedColor,
                                price: v?.price ?? {{ $product->effectivePrice() }},
                                qty: this.qty,
                                url: '{{ route('products.show', $product->slug) }}',
                            });
                            $dispatch('cart-added');
                        }
                    }"
                    class="mt-8"
                >
                    @if($sizes->count() > 0)
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs tracking-widest uppercase">{{ __('Size') }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                    <button
                                        type="button"
                                        @click="selectedSize = '{{ $size }}'"
                                        :class="selectedSize === '{{ $size }}' ? 'border-[var(--color-ink)] bg-[var(--color-ink)] text-white' : 'border-[var(--color-brand-300)]'"
                                        class="px-4 py-2 border text-sm uppercase tracking-wider"
                                    >{{ $size }}</button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($colors->count() > 0)
                        <div class="mb-6">
                            <p class="text-xs tracking-widest uppercase mb-2">{{ __('Color') }}: <span x-text="selectedColor" class="normal-case tracking-normal opacity-70"></span></p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                    <button
                                        type="button"
                                        @click="selectedColor = '{{ $color }}'"
                                        :class="selectedColor === '{{ $color }}' ? 'border-[var(--color-ink)] bg-[var(--color-ink)] text-white' : 'border-[var(--color-brand-300)]'"
                                        class="px-4 py-2 border text-sm capitalize"
                                    >{{ $color }}</button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-3 mb-6">
                        <div class="flex items-center border border-[var(--color-brand-300)]">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" class="px-3 py-3">−</button>
                            <input type="number" x-model.number="qty" min="1" class="w-12 text-center bg-transparent border-0 focus:outline-none">
                            <button type="button" @click="qty++" class="px-3 py-3">+</button>
                        </div>

                        <button
                            type="button"
                            @click="addToCart()"
                            :disabled="{{ $variants->count() ? 'matched === undefined' : 'false' }}"
                            class="flex-1 bg-[var(--color-ink)] text-white text-xs tracking-[0.2em] uppercase py-4 hover:bg-[var(--color-brand-800)] transition disabled:opacity-40 disabled:cursor-not-allowed"
                        >{{ $inStock ? __('Add to cart') : __('Out of stock') }}</button>

                        <button
                            type="button"
                            @click="$store.wishlist.toggle({{ $product->id }})"
                            :class="$store.wishlist.has({{ $product->id }}) ? 'bg-[var(--color-ink)] text-white border-[var(--color-ink)]' : ''"
                            class="p-4 border border-[var(--color-brand-300)] hover:border-[var(--color-ink)] transition"
                            aria-label="{{ __('Add to wishlist') }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                    </div>

                    <p class="text-xs opacity-70">{{ __('Cash on delivery') }} · {{ __('Free shipping over :amount', ['amount' => \App\Support\Money::format(\App\Models\Setting::get('shipping_free_over', 100))]) }}</p>
                </form>

                @if($product->care_instructions)
                    <details class="mt-8 border-t border-[var(--color-brand-200)] pt-6">
                        <summary class="cursor-pointer text-sm tracking-wide font-medium">{{ __('Care instructions') }}</summary>
                        <div class="mt-3 text-sm opacity-80 leading-relaxed">{!! nl2br(e($product->care_instructions)) !!}</div>
                    </details>
                @endif
            </div>
        </div>

        {{-- Related products --}}
        @if($related->isNotEmpty())
            <section class="mt-24 pt-16 border-t border-[var(--color-brand-200)]">
                <h2 class="font-display text-2xl md:text-3xl mb-8">{{ __('You may also like') }}</h2>
                <div class="grid gap-6 grid-cols-2 lg:grid-cols-4">
                    @foreach($related as $rel)
                        <x-product-card :product="$rel" />
                    @endforeach
                </div>
            </section>
        @endif
    </section>
</x-layouts.app>
