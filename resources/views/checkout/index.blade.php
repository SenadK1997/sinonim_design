<x-layouts.app :title="__('Checkout')">
    <section
        class="container-wide py-12 md:py-16 max-w-6xl"
        x-data="{
            items: JSON.parse(localStorage.getItem('sd_cart') || '[]'),
            shippingFlatRate: {{ $shippingFlatRate }},
            shippingFreeOver: {{ $shippingFreeOver !== null ? $shippingFreeOver : 'null' }},
            get subtotal() { return this.items.reduce((s, i) => s + (i.price * i.qty), 0); },
            get shippingCost() {
                if (this.items.length === 0) return 0;
                if (this.shippingFreeOver !== null && this.subtotal >= this.shippingFreeOver) return 0;
                return this.shippingFlatRate;
            },
            get total() { return this.subtotal + this.shippingCost; },
            format(n) { return n.toFixed(2).replace('.', ',') + ' KM'; },
        }"
    >
        <div class="mb-10">
            <p class="eyebrow mb-2">{{ __('Checkout') }}</p>
            <h1 class="font-display text-4xl md:text-5xl">{{ __('Almost done.') }}</h1>
        </div>

        {{-- Empty cart guard --}}
        <template x-if="items.length === 0">
            <div class="text-center py-24 border border-[var(--color-brand-200)]">
                <p class="opacity-70 mb-6">{{ __('Your cart is empty') }}.</p>
                <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3 border border-[var(--color-ink)] text-xs tracking-widest uppercase hover:bg-[var(--color-ink)] hover:text-white transition">{{ __('Continue shopping') }}</a>
            </div>
        </template>

        <template x-if="items.length > 0">
            <form
                action="{{ route('checkout.store') }}"
                method="POST"
                class="grid lg:grid-cols-[1fr_400px] gap-10 lg:gap-16"
                @submit="$refs.cartField.value = JSON.stringify(items.map(i => ({ product_id: i.product_id, variant_id: i.variant_id, qty: i.qty, size: i.size, color: i.color })))"
            >
                @csrf
                <input type="hidden" name="cart_items" value="[]" x-ref="cartField">

                {{-- LEFT COLUMN: Form fields --}}
                <div class="space-y-8">
                    <div>
                        <p class="eyebrow mb-4">{{ __('Contact') }}</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs tracking-widest uppercase mb-2">{{ __('Name') }} <span class="text-red-600">*</span></label>
                                <input type="text" name="customer_name" required value="{{ old('customer_name') }}" class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)] transition">
                                @error('customer_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs tracking-widest uppercase mb-2">{{ __('Phone') }} <span class="text-red-600">*</span></label>
                                <input type="tel" name="customer_phone" required value="{{ old('customer_phone') }}" placeholder="+387 61 000 000" class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)] transition">
                                @error('customer_phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs tracking-widest uppercase mb-2">{{ __('Email') }}</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)] transition">
                                @error('customer_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="eyebrow mb-4">{{ __('Shipping address') }}</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs tracking-widest uppercase mb-2">{{ __('Address') }} <span class="text-red-600">*</span></label>
                                <input type="text" name="shipping_address" required value="{{ old('shipping_address') }}" placeholder="Ulica i broj" class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)] transition">
                                @error('shipping_address')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs tracking-widest uppercase mb-2">{{ __('City') }} <span class="text-red-600">*</span></label>
                                <input type="text" name="shipping_city" required value="{{ old('shipping_city') }}" class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)] transition">
                                @error('shipping_city')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs tracking-widest uppercase mb-2">{{ __('Postal code') }}</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)] transition">
                            </div>
                            <input type="hidden" name="shipping_country" value="BA">
                        </div>
                    </div>

                    <div>
                        <p class="eyebrow mb-4">{{ __('Order notes') }}</p>
                        <textarea name="notes" rows="3" placeholder="{{ __('Anything else we should know? (optional)') }}" class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)] transition">{{ old('notes') }}</textarea>
                    </div>

                    @if($errors->has('cart_items'))
                        <div class="p-4 border border-red-300 bg-red-50 text-sm text-red-700">
                            {{ $errors->first('cart_items') }}
                        </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit" class="w-full lg:w-auto px-12 py-4 bg-[var(--color-ink)] text-white text-xs tracking-[0.25em] uppercase hover:bg-[var(--color-brand-800)] transition">
                            {{ __('Place order') }} →
                        </button>
                        <p class="mt-3 text-xs opacity-70">{{ __('Cash on delivery') }} · {{ __('You pay when the package arrives.') }}</p>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Cart summary --}}
                <aside class="lg:sticky lg:top-24 self-start">
                    <div class="border border-[var(--color-brand-200)] p-6">
                        <p class="eyebrow mb-5">{{ __('Your order') }}</p>

                        <div class="divide-y divide-[var(--color-brand-200)]">
                            <template x-for="item in items" :key="item.key">
                                <div class="py-3 flex gap-3">
                                    <div class="w-16 h-20 bg-[var(--color-brand-100)] flex-shrink-0 overflow-hidden">
                                        <img :src="item.image" x-show="item.image" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                        <p class="text-xs opacity-60 mt-0.5">
                                            <span x-show="item.size" x-text="item.size"></span>
                                            <span x-show="item.color" x-text="' · ' + item.color"></span>
                                            <span x-text="' · × ' + item.qty"></span>
                                        </p>
                                        <p class="text-sm font-semibold mt-1" x-text="format(item.price * item.qty)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-5 pt-5 border-t border-[var(--color-brand-200)] space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>{{ __('Subtotal') }}</span>
                                <span x-text="format(subtotal)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('Shipping') }}</span>
                                <span>
                                    <template x-if="shippingCost > 0"><span x-text="format(shippingCost)"></span></template>
                                    <template x-if="shippingCost === 0"><span class="text-[var(--color-brand-700)] font-medium">{{ __('Free') }}</span></template>
                                </span>
                            </div>
                            @if($shippingFreeOver)
                                <template x-if="subtotal < shippingFreeOver">
                                    <p class="text-xs opacity-60 pt-1">
                                        {{ __('Add :amount more for free shipping', ['amount' => '']) }}
                                        <span x-text="format(shippingFreeOver - subtotal)"></span>
                                    </p>
                                </template>
                            @endif
                            <div class="flex justify-between pt-3 border-t border-[var(--color-brand-200)] font-semibold text-base">
                                <span>{{ __('Total') }}</span>
                                <span x-text="format(total)"></span>
                            </div>
                        </div>

                        @if($shippingNote)
                            <p class="mt-4 text-xs opacity-70">{{ $shippingNote }}</p>
                        @endif
                    </div>
                </aside>
            </form>
        </template>
    </section>
</x-layouts.app>
