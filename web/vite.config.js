import { fileURLToPath, URL } from 'node:url';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// https://vitejs.dev/config/
export default defineConfig(() => {
    return {
        plugins: [vue()],
        resolve: {
            alias: {
                '@': fileURLToPath(new URL('./src', import.meta.url))
            }
        },
        define: {
            'process.env': process.env,
        },
        server: {
            port: 3000,
        },
        // preview: {
        //     port: 3000,
        //     strictPort: true,
        //     host: true
        // },
    };
});
