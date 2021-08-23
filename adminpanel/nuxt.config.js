/*require('dotenv').config()*/


export default {
    ssr: false,
    /*
    ** Headers of the page
    */
    head: {
        title: process.env.npm_package_name || '',
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: process.env.npm_package_description || ''}
        ],
        link: [
            {rel: 'stylesheet', href: 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css'}
        ],
        script: [
            {type: "text/javascript", src: "https://code.jquery.com/jquery-3.4.1.min.js"},
            {type: "text/javascript", src: "https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"},
        ]
    },
    /*
    ** Customize the progress-bar color
    */
    loading: {color: '#fb9678'},
    /*
    ** Global CSS
    */
    css: [
        'bootstrap-vue/dist/bootstrap-vue.css'
    ],
    /*
    ** Plugins to load before mounting the App
    */
    plugins: [
        {src: '~plugins/axios.js'},
        {src: '~plugins/authenticated.js'},
        {src: '~plugins/loadingOverlay.js'}
    ],
    /*
    ** Nuxt.js dev-modules
    */
    buildModules: [],
    /*
    ** Nuxt.js modules
    */
    modules: [
        'bootstrap-vue/nuxt',
        '@nuxtjs/axios',
        'cookie-universal-nuxt'
    ],
    /*
    ** Axios module configuration
    ** See https://axios.nuxtjs.org/options
    */
    axios: {
        proxy: true
    },
    /*
    ** Build configuration
    */
    build: {
        /*
        ** You can extend webpack config here
        */
        extend(config, ctx) {
        }
    },
    router: {
        base: '/adminpanel/'
    },
    proxy: {
        '/image': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/storage': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/pipeline': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/api': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/img': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        }
    },
    bootstrapVue: {
        bootstrapCSS: false, // Or `css: false`
        bootstrapVueCSS: false // Or `bvCSS: false`
    }
}
