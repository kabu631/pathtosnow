// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import '../css/app.css'

// Page loading overlay
let loaderEl = null
let loaderTimer = null

router.on('start', () => {
    loaderTimer = setTimeout(() => {
        if (!loaderEl) {
            loaderEl = document.createElement('div')
            loaderEl.id = 'page-loader'
            loaderEl.innerHTML = '<div class="page-loader-ring"></div>'
            document.body.appendChild(loaderEl)
        }
        loaderEl.classList.add('visible')
    }, 150)
})

router.on('finish', () => {
    clearTimeout(loaderTimer)
    loaderEl?.classList.remove('visible')
})

createInertiaApp({
    title: (title) => title ? `${title} | TrekBazar Nepal` : 'TrekBazar — Nepal Travel & Gear',
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) }).use(plugin).mount(el)
    },
    progress: { color: '#E85D26', showSpinner: true },
})
