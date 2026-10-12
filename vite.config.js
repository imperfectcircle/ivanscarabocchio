import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { local } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

/**
 * Builds the explicit variant list for a self-hosted font in resources/fonts.
 *
 * @param {string} filePrefix
 * @param {Array<{ weight: number, style?: 'normal' | 'italic', suffix: string }>} variants
 */
const fontVariants = (filePrefix, variants) =>
    variants.map(({ weight, style = 'normal', suffix }) => ({
        src: `resources/fonts/${filePrefix}-${suffix}.woff2`,
        weight,
        style,
    }));

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                local('Caveat', {
                    variants: fontVariants('caveat-v23-latin_latin-ext', [
                        { weight: 400, suffix: 'regular' },
                        { weight: 500, suffix: '500' },
                        { weight: 600, suffix: '600' },
                        { weight: 700, suffix: '700' },
                    ]),
                    preload: [{ weight: 400 }],
                    fallbacks: ['cursive'],
                    optimizedFallbacks: false,
                }),
                local('Literata', {
                    variants: fontVariants('literata-v40-latin_latin-ext', [
                        { weight: 400, suffix: 'regular' },
                        { weight: 400, style: 'italic', suffix: 'italic' },
                        { weight: 500, suffix: '500' },
                        { weight: 500, style: 'italic', suffix: '500italic' },
                        { weight: 600, suffix: '600' },
                        { weight: 600, style: 'italic', suffix: '600italic' },
                        { weight: 700, suffix: '700' },
                        { weight: 700, style: 'italic', suffix: '700italic' },
                    ]),
                    preload: [{ weight: 400 }],
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
