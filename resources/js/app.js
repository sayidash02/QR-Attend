import '../css/app.css'

import { createInertiaApp } from '@inertiajs/svelte'

import { mount } from 'svelte'

import Toast from './components/Toast.svelte'

createInertiaApp({

    resolve: async (name) => {

        const pages = import.meta.glob('./pages/**/*.svelte')

        return await pages[`./pages/${name}.svelte`]()

    },

    setup({ el, App, props }) {

        mount(App, {

            target: el,

            props,

        })

        // Mount Toast globally
        const toastContainer = document.createElement('div')

        toastContainer.id = 'toast-root'

        document.body.appendChild(toastContainer)

        mount(Toast, { target: toastContainer })

    },

})