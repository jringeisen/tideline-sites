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
                // App / admin UI sans.
                bunny('Instrument Sans', {
                    weights: [400, 500, 600, 700],
                }),
                // Marketing display headlines — bold, condensed, all-caps.
                bunny('Oswald', {
                    weights: [500, 600, 700],
                }),
                // Marketing body — refined serif.
                bunny('Source Serif 4', {
                    weights: [400, 600],
                    styles: ['normal', 'italic'],
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
