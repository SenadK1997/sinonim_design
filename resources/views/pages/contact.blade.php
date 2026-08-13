<x-layouts.app :title="__('Contact')">
    @php
        $email = \App\Models\Setting::get('contact_email');
        $phone = \App\Models\Setting::get('contact_phone');
        $wa = \App\Models\Setting::get('whatsapp_number');
        $viber = \App\Models\Setting::get('viber_number');
        $ig = \App\Models\Setting::get('instagram_handle');
    @endphp

    <section class="container-wide py-16 md:py-24 max-w-3xl">
        <p class="eyebrow mb-3">{{ __('Contact us') }}</p>
        <h1 class="font-display text-4xl md:text-5xl mb-8">{{ __('Contact') }}</h1>

        <div class="grid md:grid-cols-2 gap-12">
            <div class="space-y-4 text-lg">
                @if($email)
                    <p><span class="eyebrow block mb-1">{{ __('Email') }}</span><a href="mailto:{{ $email }}" class="link-underline">{{ $email }}</a></p>
                @endif
                @if($phone)
                    <p><span class="eyebrow block mb-1">{{ __('Phone') }}</span><a href="tel:{{ $phone }}" class="link-underline">{{ $phone }}</a></p>
                @endif
                @if($wa)
                    <p><span class="eyebrow block mb-1">WhatsApp</span><a href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}" class="link-underline" target="_blank" rel="noopener">{{ $wa }}</a></p>
                @endif
                @if($viber)
                    <p><span class="eyebrow block mb-1">Viber</span>{{ $viber }}</p>
                @endif
                @if($ig)
                    <p><span class="eyebrow block mb-1">Instagram</span><a href="https://instagram.com/{{ ltrim($ig, '@') }}" class="link-underline" target="_blank" rel="noopener">@{{ ltrim($ig, '@') }}</a></p>
                @endif
            </div>

            <div class="text-sm opacity-80 leading-relaxed">
                <p>{{ __('For all questions about products, availability and orders, reach out via WhatsApp or Instagram — that\'s where we reply fastest.') }}</p>
                <p class="mt-4">{{ __('Business hours: Monday–Saturday, 09:00–18:00.') }}</p>
            </div>
        </div>
    </section>
</x-layouts.app>
