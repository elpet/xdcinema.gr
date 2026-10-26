import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    // Σχετικά URL για fonts/εικόνες μέσα στο CSS, ώστε το build να δουλεύει και σε υποφάκελο (GitHub Pages)
    experimental: {
        renderBuiltUrl(filename, { hostType }) {
            return hostType === 'css' ? { relative: true } : undefined;
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
