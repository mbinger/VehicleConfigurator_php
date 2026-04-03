import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/sass/app.scss'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        allowedHosts: ['setup'],
        port: 5173,
        hmr: {
            host: 'localhost',
            clientPort: 5173
        }
    }
});
