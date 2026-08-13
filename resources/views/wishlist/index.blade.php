<x-layouts.app :title="__('Wishlist')">
    <section class="container-wide py-12 md:py-16" x-data="{
        products: @js($products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'image' => $p->primaryImageUrl('card'),
            'price' => $p->effectivePrice(),
            'url' => route('products.show', $p->slug),
            'category' => $p->category?->name,
        ])),
        visible() { return this.products.filter(p => $store.wishlist.has(p.id)); }
    }">
        <h1 class="font-display text-4xl md:text-5xl mb-10">{{ __('Wishlist') }}</h1>

        <template x-if="visible().length === 0">
            <div class="text-center py-24">
                <p class="opacity-70 mb-6">{{ __('Your wishlist is empty') }}.</p>
                <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3 border border-[var(--color-ink)] text-xs tracking-widest uppercase hover:bg-[var(--color-ink)] hover:text-white transition">{{ __('Continue shopping') }}</a>
            </div>
        </template>

        <template x-if="visible().length > 0">
            <div class="grid gap-6 md:gap-8 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template x-for="p in visible()" :key="p.id">
                    <div class="group">
                        <a :href="p.url" class="block relative overflow-hidden aspect-[4/5] bg-[var(--color-brand-100)]">
                            <img :src="p.image" :alt="p.name" x-show="p.image" class="w-full h-full object-cover">
                            <button @click.prevent="$store.wishlist.toggle(p.id)" class="absolute top-3 right-3 p-2 bg-[var(--color-ink)] text-white rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path d="M12 21s-8-4.78-8-12A6 6 0 0 1 12 5a6 6 0 0 1 8 4c0 7.22-8 12-8 12z"/></svg>
                            </button>
                        </a>
                        <div class="pt-4">
                            <a :href="p.url" class="text-sm font-medium link-underline" x-text="p.name"></a>
                            <p class="text-xs opacity-60 mt-0.5" x-text="p.category"></p>
                            <p class="text-sm font-semibold mt-2" x-text="p.price.toFixed(2).replace('.', ',') + ' KM'"></p>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </section>
</x-layouts.app>
