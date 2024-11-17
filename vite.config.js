import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: '0.0.0.0', // Ensure it binds to 0.0.0.0
        port: 3000, // You can change this to the port Render expects, typically 3000
    },
});
