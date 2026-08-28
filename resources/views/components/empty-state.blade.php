@props(['icon' => 'inbox', 'title', 'description' => null, 'actionLabel' => null, 'actionHref' => null])

@php
    $icons = [
        'inbox' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 0 1-1.183 1.98l-6.478 3.488M2.25 9v.906a2.25 2.25 0 0 0 1.183 1.98l6.478 3.488m8.839 2.51-4.66-2.51m0 0-1.023-.55a2.25 2.25 0 0 0-2.134 0l-1.022.55m0 0-4.661 2.51m16.5 1.615a2.25 2.25 0 0 1-1.183 1.98l-7.5 4.04a2.25 2.25 0 0 1-2.134 0l-7.5-4.04A2.25 2.25 0 0 1 2.25 15.5V6.75A2.25 2.25 0 0 1 3.435 4.77l7.5-4.04a2.25 2.25 0 0 1 2.13 0l7.5 4.04A2.25 2.25 0 0 1 21.75 6.75v9Z" />',
        'playlist' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 6h11M9 11.25h11M9 16.5h11M4.5 6h.008v.008H4.5V6Zm.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm-.75 5.25h.008v.008H4.5v-.008Zm.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm-.75 5.25h.008v.008H4.5V16.5Zm.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />',
        'tv' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h16.5v11.5a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V3.75ZM8.25 20.25h7.5M12 16.75v3.5" />',
    ][$icon] ?? '';
@endphp

<div class="flex flex-col items-center justify-center text-center py-16 px-6">
    <div class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
            {!! $icons !!}
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-ink mb-1">{{ $title }}</h3>
    @if ($description)
        <p class="text-sm text-muted max-w-sm mb-5">{{ $description }}</p>
    @endif
    @if ($actionLabel && $actionHref)
        <a href="{{ $actionHref }}"
            class="inline-flex items-center justify-center px-6 py-3 min-h-[44px] bg-primary rounded-md font-semibold text-sm text-on-primary hover:bg-primary-hover hover:shadow-card transition-colors duration-fast">
            {{ $actionLabel }}
        </a>
    @endif
</div>
