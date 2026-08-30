@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary text-start text-base font-semibold text-primary bg-background focus:outline-none focus:text-primary-hover focus:bg-background focus:border-primary-hover transition-colors duration-fast ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-muted hover:text-ink hover:bg-background hover:border-border focus:outline-none focus:text-ink focus:bg-background focus:border-border transition-colors duration-fast ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
