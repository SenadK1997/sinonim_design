<x-layouts.app :title="__('Order confirmed')">
    @if($justPlaced)
        {{-- Clear the cart on first render after checkout --}}
        <script>
            try { localStorage.removeItem('sd_cart'); } catch (e) {}
        </script>
    @endif

    <section class="container-wide py-16 md:py-24 max-w-2xl">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[var(--color-brand-100)] mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-[var(--color-brand-700)]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            <p class="eyebrow mb-3">{{ __('Order confirmed') }}</p>
            <h1 class="font-display text-4xl md:text-6xl mb-4">{{ __('Thank you.') }}</h1>
            <p class="text-base md:text-lg opacity-80 max-w-md mx-auto">
                {{ __('We\'ve received your order. We\'ll contact you within 24 hours to confirm.') }}
            </p>

            <div class="mt-10 inline-block border border-[var(--color-brand-300)] px-8 py-4">
                <p class="eyebrow mb-1">{{ __('Order number') }}</p>
                <p class="font-display text-2xl">{{ $order->order_number }}</p>
            </div>
        </div>

        <div class="mt-16 border-t border-[var(--color-brand-200)] pt-10">
            <p class="eyebrow mb-5">{{ __('Order summary') }}</p>

            <div class="divide-y divide-[var(--color-brand-200)]">
                @foreach($order->items as $item)
                    <div class="py-3 flex justify-between items-start gap-4">
                        <div>
                            <p class="font-medium">{{ $item->product_name }}</p>
                            <p class="text-xs opacity-60 mt-0.5">
                                @if($item->size) {{ $item->size }} @endif
                                @if($item->color) · {{ $item->color }} @endif
                                · × {{ $item->quantity }}
                            </p>
                        </div>
                        <p class="text-sm font-medium whitespace-nowrap">{{ \App\Support\Money::format($item->line_total) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 pt-6 border-t border-[var(--color-brand-200)] space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>{{ __('Subtotal') }}</span>
                    <span>{{ \App\Support\Money::format($order->subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>{{ __('Shipping') }}</span>
                    <span>{{ $order->shipping_cost > 0 ? \App\Support\Money::format($order->shipping_cost) : __('Free') }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-[var(--color-brand-200)] font-semibold text-base">
                    <span>{{ __('Total') }}</span>
                    <span>{{ \App\Support\Money::format($order->total) }}</span>
                </div>
            </div>

            <div class="mt-8 grid md:grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="eyebrow mb-2">{{ __('Shipping to') }}</p>
                    <p>{{ $order->customer_name }}</p>
                    <p class="opacity-80">{{ $order->shipping_address }}</p>
                    <p class="opacity-80">{{ $order->shipping_city }}@if($order->shipping_postal_code), {{ $order->shipping_postal_code }}@endif</p>
                </div>
                <div>
                    <p class="eyebrow mb-2">{{ __('Payment') }}</p>
                    <p>{{ __('Cash on delivery') }}</p>
                    <p class="opacity-80 text-xs mt-1">{{ __('You pay when the package arrives.') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center space-y-4">
            <p class="text-sm opacity-70">
                {{ __('Save your order number to track your order later:') }}
                <a href="{{ route('order.lookup') }}?order_number={{ $order->order_number }}&phone={{ $order->customer_phone }}" class="link-underline">{{ __('Track your order') }} →</a>
            </p>
            <a href="{{ route('shop.index') }}" class="inline-block px-8 py-3 border border-[var(--color-ink)] text-xs tracking-widest uppercase hover:bg-[var(--color-ink)] hover:text-white transition">
                {{ __('Continue shopping') }}
            </a>
        </div>
    </section>
</x-layouts.app>
