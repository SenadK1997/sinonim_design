@php
    $brand = \App\Models\Setting::get('brand_name', 'SinonimDesign');
    $email = \App\Models\Setting::get('contact_email');
    $phone = \App\Models\Setting::get('contact_phone');
    $ig = \App\Models\Setting::get('instagram_handle', 'sinonim_design');
    $fb = \App\Models\Setting::get('facebook_url');
    $tk = \App\Models\Setting::get('tiktok_url');
@endphp

<footer class="mt-24 border-t border-[var(--color-brand-200)] bg-[var(--color-brand-50)]">
    <div class="container-wide py-16 grid gap-12 md:grid-cols-4">
        <div class="md:col-span-2">
            <p class="font-display text-2xl">{{ $brand }}</p>
            <p class="mt-3 text-sm max-w-md opacity-80">{{ __('Handmade with love') }} — {{ \App\Models\Setting::localized('tagline', 'Ručno rađena kolekcija odjeće') }}</p>

            {{-- Newsletter --}}
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-6 flex gap-2 max-w-sm">
                @csrf
                <input
                    type="email"
                    name="email"
                    required
                    placeholder="{{ __('Email') }}"
                    class="flex-1 px-4 py-2.5 bg-transparent border border-[var(--color-brand-300)] focus:outline-none focus:border-[var(--color-brand-600)] text-sm"
                >
                <button type="submit" class="px-5 py-2.5 bg-[var(--color-ink)] text-white text-xs tracking-widest uppercase hover:bg-[var(--color-brand-800)] transition">
                    {{ __('Subscribe') }}
                </button>
            </form>
            @if(session('newsletter_ok'))
                <p class="mt-2 text-xs text-[var(--color-brand-700)]">✓ {{ session('newsletter_ok') }}</p>
            @endif
        </div>

        <div>
            <p class="eyebrow mb-4">{{ __('Contact') }}</p>
            <ul class="space-y-2 text-sm">
                @if($email)<li><a href="mailto:{{ $email }}" class="link-underline">{{ $email }}</a></li>@endif
                @if($phone)<li><a href="tel:{{ $phone }}" class="link-underline">{{ $phone }}</a></li>@endif
            </ul>

            <p class="eyebrow mt-6 mb-3">{{ __('Follow us') }}</p>
            <ul class="flex gap-4">
                @if($ig)
                    <li><a href="https://instagram.com/{{ ltrim($ig, '@') }}" target="_blank" rel="noopener" class="link-underline">Instagram</a></li>
                @endif
                @if($fb)<li><a href="{{ $fb }}" target="_blank" rel="noopener" class="link-underline">Facebook</a></li>@endif
                @if($tk)<li><a href="{{ $tk }}" target="_blank" rel="noopener" class="link-underline">TikTok</a></li>@endif
            </ul>
        </div>

        <div>
            <p class="eyebrow mb-4">{{ __('Details') }}</p>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('page.about') }}" class="link-underline">{{ __('About the brand') }}</a></li>
                <li><a href="{{ route('page.shipping') }}" class="link-underline">{{ __('Shipping information') }}</a></li>
                <li><a href="{{ route('page.returns') }}" class="link-underline">{{ __('Returns and refunds') }}</a></li>
                <li><a href="{{ route('page.privacy') }}" class="link-underline">{{ __('Privacy policy') }}</a></li>
                <li><a href="{{ route('page.terms') }}" class="link-underline">{{ __('Terms of service') }}</a></li>
                <li><a href="{{ route('order.lookup') }}" class="link-underline">{{ __('Track your order') }}</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-[var(--color-brand-200)]">
        <div class="container-wide py-6 flex flex-col md:flex-row justify-between gap-4 text-xs opacity-70">
            <p>© {{ date('Y') }} {{ $brand }}. {{ __('All rights reserved') }}.</p>
            <p>{{ __('Cash on delivery') }} · {{ __('Handmade with love') }}</p>
        </div>
    </div>
</footer>
