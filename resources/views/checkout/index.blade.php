<x-layouts.app :title="__('Checkout')">
    <section class="container-wide py-16 md:py-24 max-w-2xl text-center">
        <h1 class="font-display text-4xl md:text-5xl mb-6">{{ __('Checkout') }}</h1>
        <p class="opacity-80">{{ __('Checkout is being prepared. Coming soon.') }}</p>
        <a href="{{ route('cart.index') }}" class="mt-8 inline-block px-8 py-3 border border-[var(--color-ink)] text-xs tracking-widest uppercase hover:bg-[var(--color-ink)] hover:text-white transition">← {{ __('Cart') }}</a>
    </section>
</x-layouts.app>
