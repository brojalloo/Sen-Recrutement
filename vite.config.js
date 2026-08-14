import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { globSync } from 'node:fs';

/*
 * Chaque page ayant des styles propres a son entrée dédiée dans
 * resources/css/pages : plusieurs pages définissent les mêmes sélecteurs
 * (.avatar-circle existe en cinq tailles différentes) et les fusionner dans un
 * bundle unique les ferait se marcher dessus. La page ne charge donc que son
 * fichier, comme le faisait son ancien bloc <style>.
 */
const pageStyles = globSync('resources/css/pages/*.css');

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', ...pageStyles],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
