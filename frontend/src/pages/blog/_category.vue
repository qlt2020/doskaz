<template>
    <BlogMain :posts="posts" :categories="categories" :pages="pages"/>
</template>

<script>
    import MainHeader from "~/components/MainHeader";
    import BlogMain from "~/components/blog/BlogMain";

    export default {
        layout: 'blog',
        components: {
            MainHeader,
            BlogMain
        },
        head() {
            return {
                title: 'Блог',
                meta: [
                    {property: 'og:title', content: 'Блог'}
                ]
            }
        },
        watchQuery: ['page', 'search', 'period'],
        async asyncData({$axios, params, query, error}) {
            try {
                const [categories, {items: posts, pages}] = await Promise.all([
                    $axios.$get('/api/blog/categories'),
                    $axios.$get('/api/blog/posts', {
                        params: {
                            page: query.page || 1,
                            category: params.category,
                            search: query.search,
                            period: query.period,
                        }
                    })
                ]);

                return {posts, categories: categories.reverse(), pages}
            } catch (e) {
                if (e.response.status) {
                    return error({statusCode: e.response.status})
                }
                return error({statusCode: 500})
            }
        }
    };
</script>
