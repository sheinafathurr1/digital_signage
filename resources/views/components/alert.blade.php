@props(['type' => 'success'])

@php
    $palette = [
        'success' => ['border' => 'border-l-success', 'bg' => 'bg-success/5', 'text' => 'text-success'],
        'warning' => ['border' => 'border-l-warning', 'bg' => 'bg-warning/5', 'text' => 'text-warning'],
        'danger' => ['border' => 'border-l-danger', 'bg' => 'bg-danger/5', 'text' => 'text-danger'],
        'info' => ['border' => 'border-l-accent', 'bg' => 'bg-accent/5', 'text' => 'text-accent'],
    ][$type];

    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />',
        'danger' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />',
    ][$type];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 6000)"
    x-transition:leave="transition ease-in duration-base"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-1"
    {{ $attributes->merge(['class' => "flex items-start gap-3 border-l-4 {$palette['border']} {$palette['bg']} {$palette['text']} rounded-md pl-4 pr-3 py-4"]) }}
>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 shrink-0 mt-0.5">
        {!! $icons !!}
    </svg>

    <p class="flex-1 text-sm leading-relaxed">{{ $slot }}</p>

    <button type="button" @click="show = false" aria-label="Tutup notifikasi"
        class="shrink-0 text-muted hover:text-primary transition-colors duration-fast rounded-md p-1 -m-1 focus:outline-none focus-visible:shadow-focus">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </button>
</div>
