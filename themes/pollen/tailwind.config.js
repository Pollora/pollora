/** @type {import('tailwindcss').Config} */
module.exports = {
    content: {
        relative: true,
        files: [
            "./../../resources/views/**/*.blade.php",
            "./views/**/*.blade.php",
            "./assets/**/*.{js,css}",
            "./assets/**/*.{js,css}",
        ],
    },
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography'),
    ],
}

