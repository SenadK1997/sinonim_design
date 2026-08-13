<x-layouts.app :title="__('Shop')">
    <section class="container-wide py-12 md:py-16">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <p class="eyebrow mb-2">{{ __('Shop') }}</p>
                <h1 class="font-display text-4xl md:text-5xl">
                    {{ $category ? $category->name : __('All products') }}
                </h1>
                @if($category && $category->description)
                    <p class="mt-3 max-w-xl opacity-80">{{ $category->description }}</p>
                @endif
            </div>

            <form method="GET" class="flex items-center gap-2">
                <label class="text-xs eyebrow">{{ __('Sort by') }}:</label>
                <select name="sort" onchange="this.form.submit()" class="border border-[var(--color-brand-300)] bg-transparent px-3 py-2 text-sm focus:outline-none">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ __('Newest') }}</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>{{ __('Price: low to high') }}</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ __('Price: high to low') }}</option>
                </select>
            </form>
        </div>

        {{-- Category chips --}}
        @if($categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-10">
                <a href="{{ route('shop.index') }}" class="px-4 py-1.5 text-xs tracking-wider uppercase border {{ ! $category ? 'bg-[var(--color-ink)] text-white border-[var(--color-ink)]' : 'border-[var(--color-brand-300)] hover:border-[var(--color-ink)]' }} transition">{{ __('All products') }}</a>
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="px-4 py-1.5 text-xs tracking-wider uppercase border {{ $category && $category->id === $cat->id ? 'bg-[var(--color-ink)] text-white border-[var(--color-ink)]' : 'border-[var(--color-brand-300)] hover:border-[var(--color-ink)]' }} transition">{{ $cat->name }}</a>
                @endforeach
            </div>
        @endif

        @if($products->isEmpty())
            <p class="text-center py-24 opacity-60">{{ __('No products in this category yet.') }}</p>
        @else
            <div class="grid gap-6 md:gap-8 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </section>
</x-layouts.app>
