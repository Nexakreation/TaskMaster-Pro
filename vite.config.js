import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    },
    build: {
        // Generate source maps for better debugging
        sourcemap: true,
        rollupOptions: {
            output: {
                // Ensure proper code splitting
                manualChunks: {
                    vendor: ['alpinejs']
                }
            }
        }
    }
});