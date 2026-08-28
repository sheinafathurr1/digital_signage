@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-surface border border-border text-ink placeholder-muted rounded-md shadow-none px-4 py-3 focus:border-primary focus:ring-0 focus:shadow-focus transition-colors duration-fast disabled:bg-background disabled:text-muted disabled:cursor-not-allowed']) }}>
