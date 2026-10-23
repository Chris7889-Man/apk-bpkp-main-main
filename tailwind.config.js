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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                '2xs': ['0.70rem', { lineHeight: '1.25' }],
                xs: ['0.78rem', { lineHeight: '1.5' }],
                sm: ['0.91rem', { lineHeight: '1.5' }],
                'text-xs': ['0.875rem', { lineHeight: '1.5rem' }],
                'text-sm': ['1rem', { lineHeight: '1.5rem' }],
            },
        },
    },

    plugins: [forms],
};