import { defineConfig } from "vite";
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import { wordpressPlugin } from '@roots/vite-plugin';
import { globSync } from 'glob';
import path from 'path';

const isDocker = process.env.IS_DOCKER || process.env.DOCKER_ENV || process.env.DDEV_PRIMARY_URL;
const port = 5175;
const publicDirectory = "../../public";
const moduleName = path.basename(__dirname);
const moduleSlug = moduleName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();

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
            cors: { origin: '*' },
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

const getModuleConfig = () => ({
    base: "/build/module/" + moduleSlug,
    input: [...Object.values(blockEntries)],
    publicDirectory,
    hotFile: path.join(publicDirectory, `${moduleSlug}.hot`),
    buildDirectory: path.join("build", "module", moduleSlug),
    refresh: [
        ...refreshPaths,
        'resources/blocks/**',
    ],
});

export default defineConfig({
    base: "/build/module/" + moduleSlug,
    build: {
        emptyOutDir: false,
    },
    plugins: [
        laravel(getModuleConfig()),
        ...(hasBlocks ? [wordpressPlugin()] : []),
    ],
    ...getDevServerConfig()
});
