import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { local } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                local('Caveat', {
                    src: 'resources/fonts/caveat-v23-latin_latin-ext-regular.woff2',
                    fallbacks: ['cursive'],
                    optimizedFallbacks: false,
                }),
                local('Literata', {
                    src: 'resources/fonts/literata-v40-latin_latin-ext-regular.woff2',
                    fallbacks: ['Georgia', 'serif'],
                    optimizedFallbacks: false,
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
