<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 min-h-[44px] bg-danger border border-transparent rounded-md font-semibold text-sm text-on-primary hover:bg-danger/90 hover:shadow-card active:bg-danger/80 focus:outline-none focus-visible:shadow-focus transition-colors duration-fast ease-in-out disabled:opacity-60 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
