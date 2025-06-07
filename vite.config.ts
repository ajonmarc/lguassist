import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
      server: {
        host: '0.0.0.0', // Allow external connections
        port: 5173,
        hmr: {
            host: 'localhost', // or your actual domain
        },
        cors: {
            origin: [
                'http://localhost',
                'http://localhost:8000',
                'http://lguassist.com',
                'https://lguassist.com'
            ],
            credentials: true
        }
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});
