<x-layouts.app>
    @php
        $heroMode = \App\Models\Setting::get('hero_mode', 'gradient');
        $heroHeadline = \App\Models\Setting::get('hero_headline', 'Ručno rađeno. Za tebe.');
        $heroSub = \App\Models\Setting::get('hero_subheadline', 'Svaki komad je jedinstven — sašiven pažljivo, u malim serijama.');
        $ctaLabel = \App\Models\Setting::get('hero_cta_label', 'Pogledaj kolekciju');
        $ctaUrl = \App\Models\Setting::get('hero_cta_url', '/kolekcije');
        $heroImage = \App\Models\Setting::get('hero_image_path');
        $gradFrom = \App\Models\Setting::get('hero_gradient_from', '#efe7de');
        $gradTo = \App\Models\Setting::get('hero_gradient_to', '#c9a892');
    @endphp

    {{-- HERO --}}
    @if($heroMode !== 'none')
        <section
            class="relative overflow-hidden"
            @if($heroMode === 'image' && $heroImage)
                style="background-image: url('{{ asset('storage/'.$heroImage) }}'); background-size: cover; background-position: center;"
            @elseif($heroMode === 'gradient')
                style="background: linear-gradient(135deg, {{ $gradFrom }} 0%, {{ $gradTo }} 100%);"
            @endif
        >
            @if($heroMode === 'image')
                <div class="absolute inset-0 bg-black/25"></div>
            @endif

            <div class="relative container-wide py-32 md:py-48 lg:py-56 text-center {{ $heroMode === 'image' ? 'text-white' : '' }}">
                <p class="eyebrow mb-6 {{ $heroMode === 'image' ? 'text-white/80' : '' }}">SinonimDesign</p>
                <h1 class="font-display text-5xl md:text-7xl lg:text-8xl leading-[1.05] max-w-4xl mx-auto">
                    {{ $heroHeadline }}
                </h1>
                @if($heroSub)
                    <p class="mt-6 text-base md:text-lg max-w-xl mx-auto opacity-90">{{ $heroSub }}</p>
                @endif
                @if($ctaLabel && $ctaUrl)
                    <a
                        href="{{ $ctaUrl }}"
                        class="mt-10 inline-block px-8 py-4 bg-[var(--color-ink)] text-white text-xs tracking-[0.2em] uppercase hover:bg-[var(--color-brand-800)] transition"
                    >{{ $ctaLabel }}</a>
                @endif
            </div>
        </section>
    @endif

    {{-- Featured collections --}}
    @if($collections->isNotEmpty())
        <section class="container-wide py-20 md:py-28">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <p class="eyebrow mb-2">{{ __('Collections') }}</p>
                    <h2 class="font-display text-3xl md:text-4xl">{{ __('New collection') }}</h2>
                </div>
                <a href="{{ route('collections.index') }}" class="text-sm link-underline hidden md:inline">{{ __('View collection') }} →</a>
            </div>

            <div class="grid gap-6 md:gap-8 grid-cols-1 md:grid-cols-2 {{ $collections->count() >= 3 ? 'lg:grid-cols-3' : '' }}">
                @foreach($collections as $col)
                    <a href="{{ route('collections.show', $col->slug) }}" class="group product-card block relative overflow-hidden aspect-[4/5] bg-[var(--color-brand-100)]">
                        @if($col->coverUrl('large'))
                            <img src="{{ $col->coverUrl('large') }}" alt="{{ $col->name }}" class="product-card-image w-full h-full object-cover" loading="lazy">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <p class="eyebrow text-white/80 mb-1">{{ __('Collection') }}</p>
                            <h3 class="font-display text-2xl md:text-3xl">{{ $col->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Promoted products --}}
    @if($promoted->isNotEmpty())
        <section class="container-wide py-20 md:py-28 border-t border-[var(--color-brand-200)]">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <p class="eyebrow mb-2">{{ __('Featured') }}</p>
                    <h2 class="font-display text-3xl md:text-4xl">{{ __('New arrivals') }}</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-sm link-underline hidden md:inline">{{ __('All products') }} →</a>
            </div>

            <div class="grid gap-6 md:gap-8 grid-cols-2 lg:grid-cols-4">
                @foreach($promoted as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- About blurb --}}
    @php $about = \App\Models\Setting::get('about_text'); @endphp
    @if($about)
        <section class="bg-[var(--color-brand-50)] py-24 md:py-32">
            <div class="container-wide max-w-2xl text-center">
                <p class="eyebrow mb-4">{{ __('About the brand') }}</p>
                <p class="font-display text-2xl md:text-3xl leading-relaxed">{{ $about }}</p>
                <a href="{{ route('page.about') }}" class="mt-8 inline-block text-sm link-underline">{{ __('Learn more') }} →</a>
            </div>
        </section>
    @endif
</x-layouts.app>
