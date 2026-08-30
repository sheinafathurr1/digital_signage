@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-[3px] border-primary text-sm font-semibold leading-5 text-primary focus:outline-none transition-colors duration-fast ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-[3px] border-transparent text-sm font-medium leading-5 text-muted hover:text-ink hover:border-border focus:outline-none focus:text-ink transition-colors duration-fast ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
