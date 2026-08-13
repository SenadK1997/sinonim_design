<x-layouts.app :title="__('Track your order')">
    <section class="container-wide py-16 md:py-24 max-w-xl">
        <h1 class="font-display text-4xl md:text-5xl mb-8">{{ __('Track your order') }}</h1>

        <form method="GET" class="space-y-4">
            <div>
                <label class="eyebrow block mb-1">{{ __('Order number') }}</label>
                <input type="text" name="order_number" value="{{ request('order_number') }}" required class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)]">
            </div>
            <div>
                <label class="eyebrow block mb-1">{{ __('Phone') }}</label>
                <input type="text" name="phone" value="{{ request('phone') }}" required class="w-full px-4 py-3 border border-[var(--color-brand-300)] bg-transparent focus:outline-none focus:border-[var(--color-ink)]">
            </div>
            <button class="bg-[var(--color-ink)] text-white px-8 py-3 text-xs tracking-widest uppercase">{{ __('Track your order') }}</button>
        </form>

        @if(request()->has('order_number') && ! $order)
            <p class="mt-8 text-sm text-red-700">{{ __('Order not found. Check the order number and phone.') }}</p>
        @endif

        @if($order)
            <div class="mt-10 border border-[var(--color-brand-200)] p-6">
                <p class="eyebrow mb-1">{{ __('Order number') }}</p>
                <p class="font-display text-2xl">{{ $order->order_number }}</p>
                <p class="mt-4 text-sm"><strong>Status:</strong> {{ \App\Models\Order::statuses()[$order->status] ?? $order->status }}</p>
                <p class="text-sm"><strong>{{ __('Total') }}:</strong> {{ \App\Support\Money::format($order->total) }}</p>
                <p class="text-sm mt-2 opacity-70">Naručeno {{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
        @endif
    </section>
</x-layouts.app>
