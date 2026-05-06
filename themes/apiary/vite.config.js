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

const blockEntries = globSync('./resources/blocks/*/{index,view}.{js,jsx,ts,tsx}')
    .concat(globSync('./resources/blocks/*/{editor,style}.css'))
    .reduce((acc, file) => {
        const slug = path.basename(path.dirname(file));
        const name = path.basename(file, path.extname(file));
        acc[`blocks/${slug}/${name}`] = file;
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
        ...refreshPaths,
        'themes/'+themeName+'/resources/views/**',
        'resources/blocks/**',
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
        {
            name: "copy-theme-json",
            apply: "build",
            async writeBundle(options) {
                const fs = await import('fs/promises');
                const src = path.join(options.dir, 'assets', 'theme.json');
                const dest = path.resolve(__dirname, 'theme.json');
                try {
                    await fs.copyFile(src, dest);
                    console.log('  ✓ theme.json copied to theme root');
                } catch {}
            },
        },
    ],
    ...getDevServerConfig()
});
