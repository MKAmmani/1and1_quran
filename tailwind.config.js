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
                display: ["Inter", "sans-serif","Poppins"],
                arabic: ["Amiri", "Noto Sans Arabic", "Tajawal", "serif"],
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
                teal: { 600: '#0d9488', 500: '#14b8a6' },

                primary: "#009688", // Teal color from the active nav item
                        "primary-hover": "#54cdbfff",
                        "background-light": "#F8F9FA",
                        "background-dark": "#121212",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1E1E1E",
                        "text-light": "#333333",
                        "text-dark": "#E0E0E0",
                        "muted-light": "#757575",
                        "muted-dark": "#A0A0A0",
                        "border-light": "#E0E0E0",
                        "border-dark": "#333333",
            },
            borderRadius: {
                DEFAULT: "0.5rem",
                'xl': '1rem',
                '2xl': '1.5rem',
            },
            spacing: {
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
            }
        },
    },

    plugins: [forms],
};
