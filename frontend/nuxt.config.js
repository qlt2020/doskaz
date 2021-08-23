import axios from 'axios'


export default {
    srcDir: 'src/',
    loading: false,
    components: true,
    modules: [
        '@nuxtjs/axios',
        'cookie-universal-nuxt',
        '@nuxtjs/redirect-module',
        '@nuxtjs/robots',
        'nuxt-i18n',
        '@nuxtjs/feed',
        '@nuxtjs/sentry'
    ],
    proxy: {
        '/pipeline': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/img': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/api': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/static': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        },
        '/storage': {
            target: process.env.BACKEND_DOMAIN || 'http://localhost',
        }
    },
    axios: {
        proxy: true
    },
    redirect: [
        {from: '^/kurs', to: 'https://oft.kz/kurs'},
        {from: '^/kurs-obrashenie', to: 'https://oft.kz/kurs-obrashenie'}
    ],
    plugins: [
        {src: '~plugins/i18n.js'},
        {src: '~plugins/axios.js'},
        {src: '~plugins/csrf.js', mode: 'server'},
        {src: '~plugins/no-ssr.js', mode: 'client'},
        {src: '~plugins/authenticated.js'}
    ],
    buildModules: [
        '@nuxtjs/google-analytics',
        '@nuxtjs/yandex-metrika'
    ],
    css: [
        '~/static/normalize.css',
        '@fortawesome/fontawesome-free/css/all.css',
        '~/styles/layout.scss'
    ],
    head: {
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'}
        ],
        script: [
            {type: "text/javascript", src: "https://code.jquery.com/jquery-1.11.0.min.js"}
        ],
        link: [
            {rel: 'icon', type: 'image/png', href: '/favicon.png?v1'}
        ]
    },
    googleAnalytics: {
        id: 'UA-176268948-1'
    },
    yandexMetrika: {
        id: '66710635',
        webvisor: true,
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true
    },
    robots: process.env.ROBOTS_ALLOW
        ? [
            {UserAgent: "*"},
            {Disallow: "*?query="},
            {Disallow: "/search"},
            {Allow: "*.css"},
            {Allow: "*.js"},
            {Allow: "*.jpeg"},
            {Allow: "*.png"},
            {Allow: "*.pdf"},
            {Sitemap: "https://doskaz.kz/storage/sitemap.xml"},
        ]
        : {
            UserAgent: "*",
            Disallow: "/",
        },
    i18n: {
        strategy: 'prefix_except_default',
        defaultLocale: 'ru',
        detectBrowserLanguage: {
            alwaysRedirect: true
        },
        lazy: true,
        langDir: 'lang/',
        locales: [
            {code: 'kz', name: 'Qazaq', file: 'kz.js'},
            {code: 'ru', name: 'Русский', file: 'ru.js'},
        ],
        vueI18n: '~/plugins/i18n-options.js'
    },
    feed: [
        {
            path: '/blog/feed.xml',
            type: 'rss2',
            async create(feed) {
                feed.options = {
                    title: 'Доступный Казахстан - Блог',
                    link: 'https://doskaz.kz/feed.xml',
                    description: ''
                }
                const {data: {items: posts}} = await axios.get(`${process.env.BACKEND_DOMAIN}/api/blog/posts`);
                posts.forEach(post => {
                    const url = `https://doskaz.kz/blog/${post.categorySlug}/${post.slug}`
                    feed.addItem({
                        title: post.title,
                        id: url,
                        link: url,
                        description: post.annotation,
                        content: post.content,
                        date: new Date(post.publishedAt),
                        image: post.image
                    })
                })
            }
        }
    ],
    build: {
        extend(config, {isClient}) {
            if (isClient) {
                config.devtool = 'source-map'
            }
        }
    }
}
