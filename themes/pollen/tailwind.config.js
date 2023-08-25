/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./../../storage/framework/views/*.php",
        "./../../resources/views/**/*.blade.php",
        "./**/*.{blade.php,js,vue,ts}",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
}

