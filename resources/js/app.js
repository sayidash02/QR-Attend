import '../css/app.css'

import { createInertiaApp } from '@inertiajs/svelte'

import { mount } from 'svelte'

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

    },

})