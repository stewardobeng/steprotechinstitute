import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                "primary": "#137fec",
                "background-light": "#f6f7f8",
                "background-dark": "#101922",
                "surface-dark": "#192633",
                "border-dark": "#324d67",
                "text-muted-dark": "#92adc9",
                "success": "#0bda5b",
                "danger": "#fa6238",
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"],
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
        },
    },

    plugins: [forms],
};
