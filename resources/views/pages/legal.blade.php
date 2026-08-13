<x-layouts.app :title="$title">
    <section class="container-wide py-16 md:py-24 max-w-3xl">
        <h1 class="font-display text-4xl md:text-5xl mb-8">{{ $title }}</h1>
        <div class="prose max-w-none opacity-90 leading-relaxed space-y-6">
            {!! $content !!}
        </div>
    </section>
</x-layouts.app>
