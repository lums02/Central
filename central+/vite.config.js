import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'public/build',
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
                home: 'resources/js/home.js',
                dashboard: 'resources/js/dashboard.js',
                styles: 'resources/css/dashboard.css' // ajout CSS direct
            }
        }
    },
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    }
});

