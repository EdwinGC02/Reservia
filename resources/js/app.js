// app.js

import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import maindashboard from '@/layouts/maindashboard.vue';
import plugins from './plugins';

import '../css/app.scss'
import '../css/scss/switcher.scss'
import '@mdi/font/css/materialdesignicons.css';
import jQuery from 'jquery'
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

window.$ = window.jQuery = jQuery

import select2 from 'select2/dist/js/select2.full.js'
import 'select2/dist/css/select2.css'

// Inyecta manualmente el plugin en el mismo contexto de window.jQuery
if (typeof window !== 'undefined' && window.jQuery) {
    select2(window.jQuery)
    console.log('✅ Select2 correctamente inicializado:', typeof window.jQuery.fn.select2)
} else {
    console.warn('⚠️ jQuery no encontrado al inicializar Select2')
}

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`].default; // 👈 Get the actual component

        // 👇 Assign DefaultLayout if no layout is defined
        if (!page.layout) {
            page.layout = maindashboard;
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(plugins)
            .mount(el);
    },
});
