import { defineConfig } from "vite";
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import { wordpressThemeJson } from '@roots/vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';

const isDocker = process.env.IS_DOCKER || process.env.DOCKER_ENV || process.env.DDEV_PRIMARY_URL;
const port = 5173;
const publicDirectory = "../../public";
const themeName = path.basename(__dirname);

const getBaseUrl = () => {
    return process.env.APP_URL || process.env.DDEV_PRIMARY_URL || 'http://localhost';
};

const isHttps = getBaseUrl().startsWith('https');

const getDevServerConfig = () => {
    const commonConfig = {
        server: {
            port,
            strictPort: true,
            cors: {
                origin: '*',
                methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                credentials: true
            },
        }
    };

    if (isDocker) {
        return {
            server: {
                ...commonConfig.server,
                host: '0.0.0.0',
                origin: `${getBaseUrl()}:${port}`,
                hmr: {
                    protocol: isHttps ? 'wss' : 'ws',
                    host: new URL(getBaseUrl()).hostname,
                }
            },
        };
    }

    return {
        server: {
            ...commonConfig.server,
            https: isHttps,
            host: isHttps ? new URL(getBaseUrl()).hostname : 'localhost',
            hmr: {
                protocol: isHttps ? 'wss' : 'ws',
                host: new URL(getBaseUrl()).hostname
            }
        },
    };
};

const getThemeConfig = () => ({
    base: "/build/theme/" + themeName,
    input: ["./resources/assets/app.js"],
    publicDirectory,
    hotFile: path.join(publicDirectory, `${themeName}.hot`),
    buildDirectory: path.join("build", "theme", themeName),
    refresh: [
        ...refreshPaths.filter((refreshPath) => refreshPath !== 'resources/views/**'),
        'themes/' + themeName + '/resources/views/**/*.blade.php',
        'themes/' + themeName + '/templates/**/*.html',
        'themes/' + themeName + '/parts/**/*.html',
        'resources/views/**/*.blade.php',
    ],
    assets: [
        'resources/assets/images/**',
        'resources/assets/fonts/**',
    ],
});

export default defineConfig({
    base: "/build/theme/" + themeName,
    build: {
        emptyOutDir: false,
    },
    plugins: [
        tailwindcss(),
        laravel(getThemeConfig()),
        wordpressThemeJson({
            baseThemeJsonPath: './theme.json',
            // The type scale, the two families (with their own @font-face) and
            // the border radii are all hand-authored in theme.json: only the
            // colour palette is meant to flow in from @theme static.
            disableTailwindFonts: true,
            disableTailwindFontSizes: true,
            disableTailwindBorderRadius: true,
        }),
        {
            name: "blade-and-block-templates",
            handleHotUpdate({ file, server }) {
                if (file.endsWith(".blade.php") || file.endsWith(".html")) {
                    server.ws.send({
                        type: "full-reload",
                        path: "*",
                    });
                }
            },
        },
    ],
    ...getDevServerConfig()
});
