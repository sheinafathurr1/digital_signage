<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 min-h-[44px] bg-primary border border-transparent rounded-md font-semibold text-sm text-on-primary hover:bg-primary-hover hover:shadow-card active:bg-primary-active focus:outline-none focus-visible:shadow-focus transition-colors duration-fast ease-in-out disabled:opacity-60 disabled:cursor-not-allowed disabled:bg-border disabled:text-muted disabled:hover:bg-border']) }}>
    {{ $slot }}
</button>
