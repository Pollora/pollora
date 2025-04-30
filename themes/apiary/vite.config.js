import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import path from 'path';

const isDevelopment = !!process.env.DDEV_PRIMARY_URL;
const port = 5173;
const publicDirectory = "../../public";
const themeName = path.basename(__dirname);

const getDevServerConfig = () => {
    if (!isDevelopment) return {};

    const hostname = new URL(process.env.DDEV_PRIMARY_URL).hostname;
    return {
        server: {
            host: '0.0.0.0',
            port,
            strictPort: true,
            origin: `${process.env.DDEV_PRIMARY_URL}:${port}`,
            hmr: {
                host: hostname,
                port,
                protocol: 'wss',
            },
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "GET,POST,PUT,DELETE,OPTIONS",
                "Access-Control-Allow-Headers": "X-Requested-With, Content-Type, Authorization",
            },
        }
    };
};


const getThemeConfig = () => ({
    base: "/themes/" + themeName,
    input: ["./assets/app.js"],
    publicDirectory,
    hotFile: path.join(publicDirectory, `${themeName}.hot`),
    buildDirectory: path.join( "build", themeName)
});

export default defineConfig({
    base: "/themes/" + themeName,
    build: {
        emptyOutDir: false,
    },
    plugins: [
        tailwindcss(),
        laravel(getThemeConfig()),
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
