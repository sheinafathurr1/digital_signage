<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 min-h-[44px] bg-transparent border border-primary rounded-md font-semibold text-sm text-primary hover:bg-background hover:border-primary-hover active:bg-accent/10 focus:outline-none focus-visible:shadow-focus transition-colors duration-fast ease-in-out disabled:opacity-60 disabled:cursor-not-allowed disabled:border-border disabled:text-muted']) }}>
    {{ $slot }}
</button>
