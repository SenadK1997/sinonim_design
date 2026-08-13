<x-layouts.app :title="__('Cart')">
    <section class="container-wide py-12 md:py-16 max-w-4xl" x-data>
        <h1 class="font-display text-4xl md:text-5xl mb-10">{{ __('Cart') }}</h1>

        <template x-if="$store.cart.items.length === 0">
            <div class="text-center py-24">
                <p class="opacity-70 mb-6">{{ __('Your cart is empty') }}.</p>
                <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3 border border-[var(--color-ink)] text-xs tracking-widest uppercase hover:bg-[var(--color-ink)] hover:text-white transition">{{ __('Continue shopping') }}</a>
            </div>
        </template>

        <template x-if="$store.cart.items.length > 0">
            <div>
                <div class="divide-y divide-[var(--color-brand-200)]">
                    <template x-for="item in $store.cart.items" :key="item.key">
                        <div class="py-4 flex gap-4">
                            <a :href="item.url" class="w-24 h-32 bg-[var(--color-brand-100)] flex-shrink-0 overflow-hidden">
                                <img :src="item.image" :alt="item.name" x-show="item.image" class="w-full h-full object-cover">
                            </a>
                            <div class="flex-1 flex flex-col">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a :href="item.url" class="font-medium link-underline" x-text="item.name"></a>
                                        <p class="text-xs opacity-60 mt-1">
                                            <span x-show="item.size" x-text="'{{ __('Size') }}: ' + item.size"></span>
                                            <span x-show="item.color" x-text="' · {{ __('Color') }}: ' + item.color"></span>
                                        </p>
                                    </div>
                                    <button @click="$store.cart.remove(item.key)" class="text-xs opacity-60 hover:opacity-100 hover:underline">{{ __('Remove') }}</button>
                                </div>
                                <div class="mt-auto flex items-center justify-between">
                                    <div class="flex items-center border border-[var(--color-brand-300)]">
                                        <button @click="$store.cart.update(item.key, item.qty - 1)" class="px-3 py-1.5">−</button>
                                        <span x-text="item.qty" class="w-8 text-center text-sm"></span>
                                        <button @click="$store.cart.update(item.key, item.qty + 1)" class="px-3 py-1.5">+</button>
                                    </div>
                                    <p class="font-semibold" x-text="(item.price * item.qty).toFixed(2).replace('.', ',') + ' KM'"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mt-10 border-t border-[var(--color-brand-200)] pt-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span>{{ __('Subtotal') }}</span>
                        <span x-text="$store.cart.subtotal().toFixed(2).replace('.', ',') + ' KM'"></span>
                    </div>
                    <div class="flex justify-between text-sm opacity-70">
                        <span>{{ __('Shipping') }}</span>
                        <span>{{ __('Calculated at checkout') }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="mt-6 block text-center bg-[var(--color-ink)] text-white text-xs tracking-[0.2em] uppercase py-4 hover:bg-[var(--color-brand-800)] transition">{{ __('Checkout') }}</a>
                </div>
            </div>
        </template>
    </section>
</x-layouts.app>
