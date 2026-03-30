import './bootstrap';
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
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const orangeTheme = {
  name: 'orange-theme',
  '--cui-primary': '#FF6B35',
  '--cui-primary-rgb': '255, 107, 53',
  '--cui-primary-transparent': 'rgba(255, 107, 53, 0.1)',
  '--cui-primary-opacity-10': 'rgba(255, 107, 53, 0.1)',
  '--cui-primary-opacity-20': 'rgba(255, 107, 53, 0.2)',
  '--cui-primary-opacity-25': 'rgba(255, 107, 53, 0.25)',
  '--cui-primary-opacity-50': 'rgba(255, 107, 53, 0.5)',
  '--cui-primary-opacity-75': 'rgba(255, 107, 53, 0.75)',
  '--cui-primary-hover': '#F57C00',
  '--cui-primary-focus': 'rgba(255, 107, 53, 0.25)',
  '--cui-primary-active': '#E55A2B',
  
  '--cui-secondary': '#F7931E',
  '--cui-info': '#FCD34D',
  '--cui-success': '#10B981',
  '--cui-warning': '#F59E0B',
  '--cui-danger': '#EF4444',

  '--cui-body-bg': '#0f0f1a',
  '--cui-card-bg': '#1a1a2a',
  '--cui-sidebar-bg': '#111827',
}

const orangeDarkTheme = {
  name: 'orange-dark',
  '--cui-body-bg': '#0f0f1a',
  '--cui-card-bg': '#1a1a2a',
  '--cui-sidebar-bg': '#111827',
  '--cui-primary': '#FF6B35',
  '--cui-primary-hover': '#F57C00',
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(store)
            .use(ZiggyVue)
            .use(CoreuiVue, {
                themes: [orangeTheme, orangeDarkTheme],
            })
            .provide('icons', icons)
            .component('CIcon', CIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
