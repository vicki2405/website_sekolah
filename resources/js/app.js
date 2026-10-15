import '../css/app.pcss';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { MotionPlugin } from '@vueuse/motion';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pageModules = import.meta.glob('./Pages/**/*.vue');
const publicPagesToPreload = [
    'About',
    'Agenda',
    'Contact',
    'Facilities',
    'Gallery',
    'News/Index',
    'Prestasi',
    'Teachers',
];

const scheduleIdleTask = (callback) => {
    if (typeof window === 'undefined') {
        return;
    }

    if ('requestIdleCallback' in window) {
        window.requestIdleCallback(callback, { timeout: 2000 });
        return;
    }

    window.setTimeout(callback, 1200);
};

const preloadPublicPages = () => {
    if (typeof window === 'undefined' || window.location.pathname.startsWith('/admin')) {
        return;
    }

    scheduleIdleTask(() => {
        publicPagesToPreload.forEach((name) => {
            const loader = pageModules[`./Pages/${name}.vue`];

            if (loader) {
                loader();
            }
        });
    });
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            pageModules,
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(MotionPlugin)
            .mount(el);

        preloadPublicPages();

        return app;
    },
    progress: {
        color: '#0284c7',
        showSpinner: true,
    },
});
