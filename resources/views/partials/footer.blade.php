@php
    $brand = \App\Models\Setting::get('brand_name', 'SinonimDesign');
    $tagline = \App\Models\Setting::localized('tagline', 'Ručno rađena kolekcija odjeće');
    $footerLogo = \App\Models\Setting::get('site_logo_dark_path') ?: \App\Models\Setting::get('site_logo_path');
    $email = \App\Models\Setting::get('contact_email');
    $phone = \App\Models\Setting::get('contact_phone');
    $wa = \App\Models\Setting::get('whatsapp_number');
    $ig = \App\Models\Setting::get('instagram_handle', 'sinonim_design');
    $fb = \App\Models\Setting::get('facebook_url');
    $tk = \App\Models\Setting::get('tiktok_url');
@endphp

<footer class="mt-24 bg-[var(--color-ink)] text-[var(--color-brand-100)] relative overflow-hidden">
    {{-- Top strip: brand statement --}}
    <div class="border-b border-[var(--color-brand-800)]/40">
        <div class="container-wide py-16 md:py-20 grid gap-12 md:grid-cols-12 items-start">
            <div class="md:col-span-5">
                @if($footerLogo)
                    <img src="{{ asset('storage/'.$footerLogo) }}" alt="{{ $brand }}" class="h-12 md:h-16 w-auto object-contain mb-5">
                @else
                    <p class="eyebrow text-[var(--color-brand-400)] mb-3">{{ $brand }}</p>
                @endif
                <p class="font-display font-light text-3xl md:text-4xl leading-tight">
                    {{ __('Handmade with love') }},<br>
                    <em class="italic opacity-80">{{ $tagline }}</em>
                </p>
            </div>

            {{-- Contact --}}
            <div class="md:col-span-3">
                <p class="eyebrow text-[var(--color-brand-400)] mb-5">{{ __('Contact') }}</p>
                <ul class="space-y-3 text-sm">
                    @if($email)<li><a href="mailto:{{ $email }}" class="link-underline">{{ $email }}</a></li>@endif
                    @if($phone)<li><a href="tel:{{ $phone }}" class="link-underline">{{ $phone }}</a></li>@endif
                    @if($wa)<li><a href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}" target="_blank" rel="noopener" class="link-underline">WhatsApp</a></li>@endif
                </ul>

                <p class="eyebrow text-[var(--color-brand-400)] mt-8 mb-3">{{ __('Follow us') }}</p>
                <ul class="flex flex-wrap gap-x-5 gap-y-2 text-sm">
                    @if($ig)<li><a href="https://instagram.com/{{ ltrim($ig, '@') }}" target="_blank" rel="noopener" class="link-underline">Instagram</a></li>@endif
                    @if($fb)<li><a href="{{ $fb }}" target="_blank" rel="noopener" class="link-underline">Facebook</a></li>@endif
                    @if($tk)<li><a href="{{ $tk }}" target="_blank" rel="noopener" class="link-underline">TikTok</a></li>@endif
                </ul>
            </div>

            {{-- Shop --}}
            <div class="md:col-span-2">
                <p class="eyebrow text-[var(--color-brand-400)] mb-5">{{ __('Shop') }}</p>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('shop.index') }}" class="link-underline">{{ __('All products') }}</a></li>
                    <li><a href="{{ route('collections.index') }}" class="link-underline">{{ __('Collections') }}</a></li>
                    <li><a href="{{ route('wishlist.index') }}" class="link-underline">{{ __('Wishlist') }}</a></li>
                    <li><a href="{{ route('order.lookup') }}" class="link-underline">{{ __('Track your order') }}</a></li>
                </ul>
            </div>

            {{-- Info --}}
            <div class="md:col-span-2">
                <p class="eyebrow text-[var(--color-brand-400)] mb-5">{{ __('Info') }}</p>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('page.about') }}" class="link-underline">{{ __('About') }}</a></li>
                    <li><a href="{{ route('page.shipping') }}" class="link-underline">{{ __('Shipping') }}</a></li>
                    <li><a href="{{ route('page.returns') }}" class="link-underline">{{ __('Returns and refunds') }}</a></li>
                    <li><a href="{{ route('page.privacy') }}" class="link-underline">{{ __('Privacy policy') }}</a></li>
                    <li><a href="{{ route('page.terms') }}" class="link-underline">{{ __('Terms of service') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Giant brand signature / logo --}}
    <div class="container-wide pt-16 md:pt-20 pb-8 md:pb-10 relative flex items-center justify-center">
        @if($footerLogo)
            <img
                src="{{ asset('storage/'.$footerLogo) }}"
                alt="{{ $brand }}"
                class="max-h-40 md:max-h-56 w-auto object-contain opacity-60 hover:opacity-100 transition-opacity duration-700"
                style="filter: drop-shadow(0 0 40px rgba(255,255,255,0.05));"
            >
        @else
            <p class="font-display font-light text-[18vw] md:text-[16vw] leading-[0.85] tracking-tight text-center whitespace-nowrap overflow-hidden">
                <em class="italic bg-gradient-to-b from-[var(--color-brand-100)] via-[var(--color-brand-100)]/40 to-transparent bg-clip-text text-transparent">{{ $brand }}</em>
            </p>
        @endif
    </div>

    {{-- Bottom strip --}}
    <div class="border-t border-[var(--color-brand-800)]/40">
        <div class="container-wide py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-xs opacity-60">
            <p>© {{ date('Y') }} {{ $brand }}. {{ __('All rights reserved') }}.</p>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                    {{ __('Cash on delivery') }}
                </span>
                <span class="opacity-40">·</span>
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9-1.5V15A2.25 2.25 0 016.75 12.75h10.5A2.25 2.25 0 0119.5 15v2.25M4.5 15L6 12M18 15l-1.5-3M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3.375 3.375 0 00-3.285-4.5H2.25M3 6h12L14.25 12M3 6l.75 3"/></svg>
                    Sarajevo, BiH
                </span>
            </div>
        </div>
    </div>
</footer>
