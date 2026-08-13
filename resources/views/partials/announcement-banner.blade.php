@php
    $enabled = (bool) \App\Models\Setting::get('banner_enabled', false);
    $text = \App\Models\Setting::get('banner_text');
    $url = \App\Models\Setting::get('banner_url');
    $bg = \App\Models\Setting::get('banner_bg', '#1a1a1a');
    $fg = \App\Models\Setting::get('banner_fg', '#ffffff');
@endphp

@if($enabled && $text)
<div class="text-center py-2 px-4 text-sm tracking-wide" style="background:{{ $bg }}; color:{{ $fg }};">
    @if($url)
        <a href="{{ $url }}" class="link-underline">{{ $text }}</a>
    @else
        <span>{{ $text }}</span>
    @endif
</div>
@endif
