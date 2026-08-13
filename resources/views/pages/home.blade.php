<x-layouts.app>
    @php
        $heroMode = \App\Models\Setting::get('hero_mode', 'gradient');
        $heroHeadline = \App\Models\Setting::localized('hero_headline', __('Handmade. For you.'));
        $heroSub = \App\Models\Setting::localized('hero_subheadline', __('Every piece is unique — sewn with care, in small batches.'));
        $ctaLabel = \App\Models\Setting::localized('hero_cta_label', __('Shop the collection'));
        $ctaUrl = \App\Models\Setting::get('hero_cta_url', '/kolekcije');
        $heroImage = \App\Models\Setting::get('hero_image_path');
        $gradFrom = \App\Models\Setting::get('hero_gradient_from', '#efe7de');
        $gradTo = \App\Models\Setting::get('hero_gradient_to', '#c9a892');
        $brand = \App\Models\Setting::get('brand_name', 'SinonimDesign');
        $about = \App\Models\Setting::localized('about_text');
    @endphp

    {{-- HERO --}}
    @if($heroMode !== 'none')
        <section
            class="relative overflow-hidden min-h-[85vh] flex items-center"
            @if($heroMode === 'image' && $heroImage)
                style="background-image: url('{{ asset('storage/'.$heroImage) }}'); background-size: cover; background-position: center;"
            @elseif($heroMode === 'gradient')
                style="background: linear-gradient(135deg, {{ $gradFrom }} 0%, {{ $gradTo }} 100%);"
            @endif
        >
            @if($heroMode === 'image')
                <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/20 to-black/40"></div>
            @endif

            {{-- Big brand watermark --}}
            @if($heroMode === 'gradient')
                <div class="hidden md:block absolute -bottom-20 -right-20 font-display text-[20rem] leading-none opacity-[0.06] pointer-events-none select-none">
                    {{ substr($brand, 0, 2) }}
                </div>
            @endif

            <div class="relative container-wide py-24 md:py-32 w-full {{ $heroMode === 'image' ? 'text-white' : '' }}">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-px bg-current opacity-40"></div>
                        <p class="eyebrow {{ $heroMode === 'image' ? 'text-white/90' : '' }}">{{ $brand }}</p>
                    </div>

                    <h1 class="font-display font-light text-5xl md:text-7xl lg:text-8xl leading-[0.95] tracking-tight">
                        @foreach(preg_split('/\s+/', trim($heroHeadline), -1, PREG_SPLIT_NO_EMPTY) as $i => $word)
                            <span class="inline-block fade-in" style="animation-delay: {{ $i * 80 }}ms">{{ $word }}&nbsp;</span>
                        @endforeach
                    </h1>

                    @if($heroSub)
                        <p class="mt-8 text-base md:text-lg max-w-lg opacity-90 leading-relaxed fade-in" style="animation-delay: 500ms">{{ $heroSub }}</p>
                    @endif

                    @if($ctaLabel && $ctaUrl)
                        <div class="mt-10 fade-in flex flex-wrap gap-4" style="animation-delay: 700ms">
                            <a
                                href="{{ $ctaUrl }}"
                                class="group inline-flex items-center gap-3 px-8 py-4 bg-[var(--color-ink)] text-white text-xs tracking-[0.25em] uppercase hover:bg-[var(--color-brand-800)] transition-all"
                            >
                                {{ $ctaLabel }}
                                <span class="inline-block transition-transform group-hover:translate-x-1">→</span>
                            </a>
                            <a
                                href="{{ route('collections.index') }}"
                                class="inline-flex items-center gap-2 px-6 py-4 text-xs tracking-[0.25em] uppercase link-underline {{ $heroMode === 'image' ? 'text-white' : '' }}"
                            >
                                {{ __('Collections') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Scroll indicator --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-60 {{ $heroMode === 'image' ? 'text-white' : '' }}">
                <span class="text-[10px] tracking-[0.3em] uppercase">Scroll</span>
                <div class="w-px h-8 bg-current animate-pulse"></div>
            </div>
        </section>
    @endif

    {{-- Marquee brand strip --}}
    <section class="border-y border-[var(--color-brand-200)] py-4 overflow-hidden bg-[var(--color-brand-50)]">
        <div class="marquee">
            <div class="marquee-track">
                @for($i = 0; $i < 2; $i++)
                    <span class="font-display italic text-2xl md:text-3xl px-8 whitespace-nowrap opacity-70">{{ $brand }}</span>
                    <span class="px-4 opacity-40">✦</span>
                    <span class="eyebrow px-8 whitespace-nowrap">{{ __('Handmade') }}</span>
                    <span class="px-4 opacity-40">✦</span>
                    <span class="font-display italic text-2xl md:text-3xl px-8 whitespace-nowrap opacity-70">{{ __('Small batches') }}</span>
                    <span class="px-4 opacity-40">✦</span>
                    <span class="eyebrow px-8 whitespace-nowrap">Sarajevo</span>
                    <span class="px-4 opacity-40">✦</span>
                    <span class="font-display italic text-2xl md:text-3xl px-8 whitespace-nowrap opacity-70">{{ __('Handmade with love') }}</span>
                    <span class="px-4 opacity-40">✦</span>
                @endfor
            </div>
        </div>
    </section>

    {{-- Featured collections — big editorial cards --}}
    @if($collections->isNotEmpty())
        <section class="container-wide py-24 md:py-32">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14">
                <div>
                    <p class="eyebrow mb-3">{{ __('Collections') }}</p>
                    <h2 class="font-display text-4xl md:text-6xl leading-none max-w-2xl">
                        {{ __('Curated pieces, made in small batches.') }}
                    </h2>
                </div>
                <a href="{{ route('collections.index') }}" class="text-sm link-underline shrink-0 md:pb-2">{{ __('All collections') }} →</a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 {{ $collections->count() >= 3 ? 'lg:grid-cols-3' : '' }}">
                @foreach($collections as $i => $col)
                    <a href="{{ route('collections.show', $col->slug) }}"
                       class="group product-card block relative overflow-hidden aspect-[3/4] bg-[var(--color-brand-100)] {{ $i === 0 && $collections->count() === 2 ? '' : '' }}">
                        @if($col->coverUrl('large'))
                            <img src="{{ $col->coverUrl('large') }}" alt="{{ $col->name }}" class="product-card-image w-full h-full object-cover" loading="lazy">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-24 h-24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-8 text-white">
                            <p class="eyebrow text-white/70 mb-2">{{ __('Collection') }}</p>
                            <h3 class="font-display text-3xl md:text-4xl leading-tight">{{ $col->name }}</h3>
                            @if($col->description)
                                <p class="mt-3 text-sm opacity-90 line-clamp-2 max-w-md">{{ $col->description }}</p>
                            @endif
                            <div class="mt-4 inline-flex items-center gap-2 text-xs tracking-[0.2em] uppercase transition-transform group-hover:translate-x-1">
                                {{ __('View collection') }} <span>→</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Editorial split section — brand statement --}}
    <section class="bg-[var(--color-brand-50)] py-24 md:py-32">
        <div class="container-wide grid md:grid-cols-2 gap-12 md:gap-24 items-center">
            <div class="relative aspect-[4/5] overflow-hidden bg-[var(--color-brand-200)]">
                @if($promoted->isNotEmpty() && $promoted->first()->primaryImageUrl('large'))
                    <img src="{{ $promoted->first()->primaryImageUrl('large') }}" alt="" class="w-full h-full object-cover">
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="font-display text-9xl opacity-30">{{ substr($brand, 0, 1) }}</span>
                    </div>
                @endif
                <div class="absolute -bottom-3 -right-3 bg-[var(--color-paper)] px-6 py-3 font-display italic text-lg">
                    est. {{ date('Y') }}
                </div>
            </div>

            <div>
                <p class="eyebrow mb-4">{{ __('The brand') }}</p>
                <p class="font-display text-3xl md:text-4xl lg:text-5xl leading-tight">
                    “{{ $about ?: __('Each piece is a small love letter — to natural fabrics, to slow craft, to wearing something not everyone will have.') }}”
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <div class="w-12 h-px bg-[var(--color-brand-400)]"></div>
                    <p class="text-sm opacity-70">{{ $brand }}</p>
                </div>
                <a href="{{ route('page.about') }}" class="mt-8 inline-block text-sm link-underline">{{ __('Read more') }} →</a>
            </div>
        </div>
    </section>

    {{-- Promoted products --}}
    @if($promoted->isNotEmpty())
        <section class="container-wide py-24 md:py-32">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14">
                <div>
                    <p class="eyebrow mb-3">{{ __('Featured') }}</p>
                    <h2 class="font-display text-4xl md:text-6xl leading-none">{{ __('New arrivals') }}</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-sm link-underline shrink-0 md:pb-2">{{ __('View all') }} →</a>
            </div>

            <div class="grid gap-6 md:gap-8 grid-cols-2 lg:grid-cols-4">
                @foreach($promoted as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Big brand statement --}}
    <section class="border-y border-[var(--color-brand-200)] py-16 md:py-24 bg-[var(--color-paper)]">
        <div class="container-wide text-center">
            <p class="eyebrow mb-6">{{ __('Our promise') }}</p>
            <p class="font-display font-light text-4xl md:text-6xl lg:text-7xl leading-[1.1] max-w-4xl mx-auto">
                {{ __('Not fast, not many.') }}<br>
                <em class="italic opacity-80">{{ __('Just made with care.') }}</em>
            </p>
        </div>
    </section>

    {{-- Value cards --}}
    <section class="container-wide py-24 md:py-32">
        <div class="grid md:grid-cols-3 gap-12 md:gap-16">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center justify-center w-14 h-14 border border-[var(--color-brand-300)] mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <h3 class="font-display text-2xl mb-3">{{ __('Handmade') }}</h3>
                <p class="text-sm opacity-70 leading-relaxed">{{ __('Every piece leaves our workshop carefully sewn and inspected before delivery.') }}</p>
            </div>
            <div class="text-center md:text-left">
                <div class="inline-flex items-center justify-center w-14 h-14 border border-[var(--color-brand-300)] mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.197V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                </div>
                <h3 class="font-display text-2xl mb-3">{{ __('Small batches') }}</h3>
                <p class="text-sm opacity-70 leading-relaxed">{{ __('We don\'t make large quantities of the same piece — you\'re buying something not everyone will be wearing.') }}</p>
            </div>
            <div class="text-center md:text-left">
                <div class="inline-flex items-center justify-center w-14 h-14 border border-[var(--color-brand-300)] mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <h3 class="font-display text-2xl mb-3">{{ __('Natural fabrics') }}</h3>
                <p class="text-sm opacity-70 leading-relaxed">{{ __('We choose fabrics that are comfortable, durable, and gentle on the skin.') }}</p>
            </div>
        </div>
    </section>
</x-layouts.app>
