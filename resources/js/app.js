import './bootstrap';
import '@coreui/coreui/dist/css/coreui.min.css';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import CoreuiVue from '@coreui/vue';

import '@coreui/coreui/dist/css/coreui.min.css'
import '@coreui/coreui/dist/js/coreui.bundle.min.js'

import CIcon from '@coreui/icons-vue';
import { iconsSet as icons } from '@/assets/icons'

import  store from '@/Store/index.js'
import theme from './Store/modules/theme';

import VueEasymde from 'vue3-easymde';
import 'easymde/dist/easymde.min.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const vueConfig = {
     toolbar:[
         "bold",
         "italic",
         "heading",
         "|",
         "quote",
         "unordered-list",
         "ordered-list",
         "|",
         "link",
         "preview",
         "side-by-side",
         "fullscreen"
     ],
     maxHeight: "250px",
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(store)
            .use(ZiggyVue)
            .use(VueEasymde, vueConfig)
            .use(CoreuiVue)
            .provide('icons', icons)
            .component('CIcon', CIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
