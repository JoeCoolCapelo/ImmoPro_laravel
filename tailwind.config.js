import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                indigo: {
                    50: colors.slate[50],
                    100: colors.slate[100],
                    200: colors.slate[200],
                    300: colors.slate[300],
                    400: colors.slate[400],
                    500: colors.slate[800],
                    600: colors.slate[900],
                    700: colors.slate[950],
                    800: '#000000',
                    900: '#000000',
                    950: '#000000',
                },
                violet: {
                    50: colors.slate[50],
                    100: colors.slate[100],
                    200: colors.slate[200],
                    300: colors.slate[300],
                    400: colors.slate[400],
                    500: colors.slate[800],
                    600: colors.slate[900],
                    700: colors.slate[950],
                    800: '#000000',
                    900: '#000000',
                    950: '#000000',
                },
                sky: {
                    50: colors.slate[50],
                    100: colors.slate[100],
                    200: colors.slate[200],
                    300: colors.slate[300],
                    400: colors.slate[400],
                    500: colors.slate[800],
                    600: colors.slate[900],
                    700: colors.slate[950],
                    800: '#000000',
                    900: '#000000',
                    950: '#000000',
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
