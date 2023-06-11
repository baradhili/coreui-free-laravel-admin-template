import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/style.scss',
                'resources/js/app.js',
                'resources/js/coreui/media-cropp.js',
                'resources/js/coreui/media.js',
                'resources/js/coreui/menu-create.js',
                'resources/js/coreui/menu-edit.js',
            ],
            refresh: true,
        }),
    ],
});
