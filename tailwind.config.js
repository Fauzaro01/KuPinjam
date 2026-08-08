/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#2563EB',
                    dark:    '#1D4ED8',
                },
                sidebar: {
                    DEFAULT: '#1E293B',
                    dark:    '#0F172A',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui'],
            },
        },
    },
    plugins: [],
}
