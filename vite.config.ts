import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                // App / admin UI sans. Not above the fold on marketing pages,
                // so it loads via font-display: swap rather than being preloaded.
                bunny('Instrument Sans', {
                    weights: [400, 500, 600, 700],
                    preload: false,
                    display: 'optional',
                }),
                // Marketing display headlines — bold, condensed, all-caps.
                // The hero <h1> (weight 700) is the LCP element on the homepage,
                // so preload only that weight. Preloading every variant of every
                // family (the plugin default) saturates the mobile connection and
                // delays LCP.
                bunny('Oswald', {
                    weights: [500, 600, 700],
                    preload: [{ weight: 700, style: 'normal' }],
                    // `optional` prevents the late font swap from reflowing the
                    // condensed hero headline (the dominant CLS culprit). The
                    // preloaded 700 weight usually lands within the block window.
                    display: 'optional',
                }),
                // Marketing body — refined serif. Below the headline, swap is fine.
                bunny('Source Serif 4', {
                    weights: [400, 600],
                    styles: ['normal', 'italic'],
                    preload: false,
                    display: 'optional',
                }),
            ],
        }),
        inertia(),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
    ],
});
