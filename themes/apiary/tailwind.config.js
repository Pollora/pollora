/** @type {import('tailwindcss').Config} */
export default {
    corePlugins: {
        preflight: false,
    },
    content: [
        "./../../resources/views/**/*.blade.php",
        "./../../app/Http/Controllers/**/*.php",
        "./../../app/Themes/Apiary/**/*.php",
        "./views/**/*.blade.php",
        "./assets/**/*.{js,css,svg}"
    ],
    theme: {},
    variants: {},
    plugins: [
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ]
}

