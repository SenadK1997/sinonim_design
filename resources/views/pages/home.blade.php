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
                @foreach($collections as $col)
                    <a href="{{ route('collections.show', $col->slug) }}"
                       class="group product-card block relative overflow-hidden aspect-[3/4] bg-[var(--color-brand-100)]">
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

    {{-- NUMBERED MANIFESTO — magazine feature spread --}}
    <section class="relative bg-[var(--color-ink)] text-[var(--color-brand-100)] overflow-hidden">
        {{-- Decorative rotated brand mark --}}
        <div class="hidden lg:block absolute -left-32 top-1/2 -translate-y-1/2 font-display italic text-[16rem] leading-none opacity-[0.04] pointer-events-none select-none -rotate-90 origin-center">
            {{ $brand }}
        </div>

        <div class="container-wide py-24 md:py-36 relative">
            <div class="max-w-2xl mb-16 md:mb-24">
                <p class="eyebrow text-[var(--color-brand-300)] mb-4">{{ __('The manifesto') }}</p>
                <h2 class="font-display font-light text-4xl md:text-6xl lg:text-7xl leading-[1.05]">
                    {{ __('Three principles.') }}<br>
                    <em class="italic opacity-70">{{ __('Every stitch.') }}</em>
                </h2>
            </div>

            <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[var(--color-brand-800)]/40">
                @php
                    $principles = [
                        ['01', __('Handmade'), __('Every piece leaves our workshop carefully sewn and inspected before delivery.')],
                        ['02', __('Small batches'), __('We don\'t make large quantities of the same piece — you\'re buying something not everyone will be wearing.')],
                        ['03', __('Natural fabrics'), __('We choose fabrics that are comfortable, durable, and gentle on the skin.')],
                    ];
                @endphp
                @foreach($principles as [$n, $title, $body])
                    <div class="py-10 md:py-0 md:px-10 first:md:pl-0 last:md:pr-0 group">
                        <div class="flex items-baseline gap-4 mb-6">
                            <span class="font-display text-6xl md:text-7xl font-light opacity-30 group-hover:opacity-100 transition-opacity duration-500">{{ $n }}</span>
                            <div class="flex-1 h-px bg-[var(--color-brand-800)]/60"></div>
                        </div>
                        <h3 class="font-display text-2xl md:text-3xl mb-4">{{ $title }}</h3>
                        <p class="text-sm md:text-base opacity-70 leading-relaxed max-w-xs">{{ $body }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- BENTO PRODUCT MOSAIC — asymmetric editorial grid --}}
    @if($promoted->count() >= 5)
        <section class="py-24 md:py-32">
            <div class="container-wide">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14">
                    <div>
                        <p class="eyebrow mb-3">{{ __('Selected') }}</p>
                        <h2 class="font-display text-4xl md:text-6xl leading-none max-w-2xl">
                            <em class="italic">{{ __('Look closer.') }}</em>
                        </h2>
                    </div>
                    <a href="{{ route('shop.index') }}" class="text-sm link-underline shrink-0 md:pb-2">{{ __('Explore all') }} →</a>
                </div>

                {{-- 5-tile asymmetric grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 grid-rows-2 gap-3 md:gap-4 h-[500px] md:h-[700px]">
                    @php $mosaic = $promoted->slice(0, 5)->values(); @endphp
                    @foreach($mosaic as $i => $product)
                        <a href="{{ route('products.show', $product->slug) }}"
                           class="group relative overflow-hidden bg-[var(--color-brand-100)]
                                  {{ $i === 0 ? 'col-span-2 row-span-2 md:col-span-2 md:row-span-2' : '' }}
                                  {{ $i === 1 ? 'md:col-start-3' : '' }}">
                            @if($product->primaryImageUrl('large'))
                                <img src="{{ $product->primaryImageUrl('large') }}" alt="{{ $product->name }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                    <span class="font-display text-6xl">✦</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <p class="font-display text-xl md:text-2xl">{{ $product->name }}</p>
                                <p class="text-xs md:text-sm opacity-80 mt-1">{{ \App\Support\Money::format($product->effectivePrice()) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- NEWSLETTER — big centered final invitation --}}
    <section class="bg-[var(--color-brand-50)] py-24 md:py-36 relative overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.04] pointer-events-none select-none">
            <span class="font-display italic text-[28rem] leading-none whitespace-nowrap">{{ $brand }}</span>
        </div>

        <div class="container-wide relative max-w-2xl text-center">
            <p class="eyebrow mb-4">{{ __('Newsletter') }}</p>
            <h2 class="font-display font-light text-4xl md:text-5xl lg:text-6xl leading-tight">
                {{ __('Stay in touch.') }}<br>
                <em class="italic opacity-80">{{ __('Get the next drop first.') }}</em>
            </h2>
            <p class="mt-6 max-w-md mx-auto text-sm opacity-70">{{ __('Occasional emails, only when a new collection or restock arrives. No noise.') }}</p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-10 flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                @csrf
                <input
                    type="email"
                    name="email"
                    required
                    placeholder="{{ __('Your email') }}"
                    class="flex-1 px-5 py-4 bg-[var(--color-paper)] border border-[var(--color-brand-300)] focus:outline-none focus:border-[var(--color-ink)] text-sm transition"
                >
                <button type="submit" class="px-6 py-4 bg-[var(--color-ink)] text-white text-xs tracking-[0.2em] uppercase hover:bg-[var(--color-brand-800)] transition">
                    {{ __('Subscribe') }}
                </button>
            </form>
            @if(session('newsletter_ok'))
                <p class="mt-4 text-xs text-[var(--color-brand-700)]">✓ {{ session('newsletter_ok') }}</p>
            @endif
        </div>
    </section>
</x-layouts.app>
