import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"Fira Code"', ...defaultTheme.fontFamily.mono],
            },

            // Design system colors. `ink`/`muted` correspond to the design
            // spec's "text"/"text-muted" tokens — renamed to avoid the
            // confusing `text-text` utility Tailwind would otherwise emit.
            colors: {
                primary: {
                    DEFAULT: '#E8844A',
                    hover: '#D66E34',
                    active: '#C55820',
                },
                'on-primary': '#FFFFFF',
                background: '#FAFAF8',
                surface: '#FFFFFF',
                border: '#E8E6E1',
                ink: '#2C2420',
                muted: '#7A7370',
                accent: '#F39C12',
                success: '#27AE60',
                warning: '#E67E22',
                danger: '#E74C3C',
            },

            fontSize: {
                display: ['56px', { lineHeight: '1.05', letterSpacing: '-0.03em' }],
                heading: ['32px', { lineHeight: '1.15', letterSpacing: '-0.02em' }],
                body: ['15px', { lineHeight: '1.65', letterSpacing: '-0.01em' }],
            },

            borderRadius: {
                sm: '4px',
                md: '8px',
                lg: '12px',
                xl: '16px',
            },

            boxShadow: {
                card: '0 2px 8px rgba(44, 36, 32, 0.08)',
                elevated: '0 8px 24px rgba(44, 36, 32, 0.12)',
                focus: '0 0 0 3px rgba(232, 132, 74, 0.2)',
            },

            transitionDuration: {
                fast: '150ms',
                base: '250ms',
                slow: '400ms',
            },

            transitionTimingFunction: {
                DEFAULT: 'cubic-bezier(0.4, 0, 0.2, 1)',
            },
        },
    },

    plugins: [forms],
};
