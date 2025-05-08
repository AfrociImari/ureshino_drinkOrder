import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({ autoImport: true }),
    ],
    server: {
        host: '0.0.0.0', // Allows access from the local network
        port: 5173, // Ensure this port is open
        cors: true, // Enables CORS
        hmr: {
            host: '192.168.0.105',
        },
        watch: {
            usePolling: true,
        },
    },
});
