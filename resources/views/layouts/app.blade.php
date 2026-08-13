<!doctype html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $brand = \App\Models\Setting::get('brand_name', 'SinonimDesign');
        $tagline = \App\Models\Setting::localized('tagline');
        $pageTitle = $title ?? null;
        $pageDescription = $description ?? \App\Models\Setting::localized('tagline', 'Ručno rađena kolekcija odjeće');
        $ogImage = $ogImage ?? null;
    @endphp

    <title>{{ $pageTitle ? $pageTitle . ' — ' . $brand : $brand . ($tagline ? ' — ' . $tagline : '') }}</title>
    <meta name="description" content="{{ $pageDescription }}">

    <meta property="og:site_name" content="{{ $brand }}">
    <meta property="og:title" content="{{ $pageTitle ?? $brand }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    @php $faviconPath = \App\Models\Setting::get('favicon_path'); @endphp
    @if($faviconPath)
        <link rel="icon" href="{{ asset('storage/'.$faviconPath) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full antialiased">

    @include('partials.announcement-banner')

    @include('partials.header')

    <main class="min-h-[60vh] fade-in">
        {{ $slot }}
    </main>

    @include('partials.footer')

    @include('partials.floating-contact')

    @livewireScripts
</body>
</html>
