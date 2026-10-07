import { defineConfig } from "vite";
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';
import { globSync } from 'glob';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';

// Détection de l'environnement
const isDocker = process.env.IS_DOCKER || process.env.DOCKER_ENV || process.env.DDEV_PRIMARY_URL;
const port = 5173;
const publicDirectory = "../../public";
const themeName = path.basename(__dirname);

const getBaseUrl = () => {
    return process.env.APP_URL || process.env.DDEV_PRIMARY_URL || 'http://localhost';
};

const isHttps = getBaseUrl().startsWith('https');

const blockEntries = globSync([
    './resources/views/blocks/*/{index,view}.{js,jsx,ts,tsx}',
    './resources/views/blocks/*/{editor,style}.css',
])
    .reduce((acc, file) => {
        // Keyed by path: blocks sharing a file name never overwrite each other
        acc[file.replace(/^\.\//, '').replace(/\.\w+$/, '')] = file;
        return acc;
    }, {});
const hasBlocks = Object.keys(blockEntries).length > 0;

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
    input: ["./resources/assets/app.js", ...Object.values(blockEntries)],
    publicDirectory,
    hotFile: path.join(publicDirectory, `${themeName}.hot`),
    buildDirectory: path.join("build", "theme", themeName),
    refresh: [
        ...refreshPaths.filter((refreshPath) => refreshPath !== 'resources/views/**'),
        'themes/'+themeName+'/resources/views/**/*.blade.php',
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
            fontLabels: {
                sans: 'Sans Serif',
                mono: 'Monospace',
            },
            fontSizeLabels: {
                xs: 'Extra Small',
                sm: 'Small',
                base: 'Medium',
                lg: 'Large',
                xl: 'Extra Large',
                '2xl': '2X Large',
                '3xl': '3X Large',
                '4xl': '4X Large',
                '5xl': '5X Large',
            },
            borderRadiusLabels: {
                xs: 'Extra Small',
                sm: 'Small',
                md: 'Medium',
                lg: 'Large',
                xl: 'Extra Large',
                '2xl': '2X Large',
            },
        }),
        ...(hasBlocks ? [wordpressPlugin()] : []),
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
    ...getDevServerConfig()
});
