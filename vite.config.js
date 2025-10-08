import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/metronic.scss', 'resources/js/metronic.js'],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/metronic/theme/assets/media',
                    dest: 'assets',
                },
                {
                    src: 'resources/metronic/theme/assets/plugins/global/fonts',
                    dest: 'assets/plugins/global',
                },
            ],
        }),
    ],
});
