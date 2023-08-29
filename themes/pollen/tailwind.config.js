/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./themes/pollen/**/*.{blade.php,js,vue,ts}",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography'),
    ],
}

