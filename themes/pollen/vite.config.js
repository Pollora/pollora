import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { readFileSync } from 'node:fs';
import path from 'path';

let publicPath = path.resolve(__dirname) + '/../../public';

export default defineConfig({
    plugins: [
        laravel({
            publicDirectory: '/../../public',
            input: [
                'css/app.css',
                'js/app.js'
            ],
            hotFile: publicPath + '/hot',
            buildDirectory: "build/pollen",
            refresh: true,
        }),
        {
            name: "blade",
            handleHotUpdate({ file, server }) {
                if (file.endsWith(".blade.php")) {
                    server.ws.send({
                        type: "full-reload",
                        path: "*",
                    });
                }
            },
        },
    ],
    server: {
        cors: true,
        strictPort: true,
        port: 5173,
        host: '0.0.0.0',
        open: false,
        hmr: {
            port: 5173,
            clientPort: 5173,
        },
        https: {
            key: readFileSync('/etc/certs/local-key.pem'),
            cert: readFileSync('/etc/certs/local-cert.pem'),
        }
    },
});
