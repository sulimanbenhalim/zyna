import { defineConfig } from 'vite';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
    plugins: [
        legacy({
            targets: ['defaults', 'not IE 11']
        })
    ],
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        manifest: true,
        lib: {
            entry: 'resources/js/core.js',
            name: 'Zyna',
            fileName: (format) => `core.${format}.js`,
            formats: ['es', 'umd']
        },
        rollupOptions: {
            external: ['flowbite'],
            output: {
                globals: {
                    flowbite: 'Flowbite'
                }
            }
        }
    },
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    }
});