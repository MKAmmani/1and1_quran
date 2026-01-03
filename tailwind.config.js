import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    darkMode: "class",
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#009688", // Teal/Green from the screenshot
                secondary: "#E0F2F1",
                "background-light": "#FAFAFA",
                "background-dark": "#121212",
                "surface-light": "#FFFFFF",
                "surface-dark": "#1E1E1E",
                "text-primary-light": "#1F2937",
                "text-primary-dark": "#F3F4F6",
                "text-secondary-light": "#6B7280",
                "text-secondary-dark": "#9CA3AF",
                "border-light": "#E5E7EB",
                "border-dark": "#374151",
                "danger": "#EF4444",
                teal: { 600: '#0d9488', 500: '#14b8a6' }
            },
            borderRadius: {
                DEFAULT: "0.5rem",
                'xl': '1rem',
                '2xl': '1.5rem',
            },
        },
    },

    plugins: [forms],
};
