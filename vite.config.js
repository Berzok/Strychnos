import {defineConfig, loadEnv} from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';


// process.env is now equivalent to import.meta.env
process.env = {...process.env, ...loadEnv('development', process.cwd())};

export default defineConfig({
    base: '/',
    build: {
        outDir: 'dist'
    },
    css: {
        preprocessorOptions: {
            scss: {
                // example : additionalData: `@import "./src/design/styles/variables";`
                // dont need include file extend .scss
                additionalData: ``
            },
        },
    },
    define: {
        'process.env': process.env
    },
    plugins: [vue()],
    mode: 'development',
    publicDir: 'view/src/assets',
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './view/src'),
            '~': path.resolve(__dirname, './node_modules'),
            'assets': path.resolve(__dirname, './view/src/assets')
        },
        extensions: ['.vue', '.js', '.scss', '.css', '.ts']
    },
    server: {
        host: true,
        port: 3000
    },
});