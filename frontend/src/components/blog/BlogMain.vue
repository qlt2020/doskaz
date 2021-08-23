<template>
    <div class="blog__in">
        <div class="blog__content --main">
            <h2 class="title">{{ $t('blog.title') }}</h2>
            <form class="input" @submit.prevent="search">
                <input type="text" :placeholder="$t('blog.searchPlaceholder')" :value="$route.query.search" ref="search"/>
                <button alt="search">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M23.3397 20.1519L19.3617 16.1746C20.4101 14.5385 21.036 12.6053 21.036 10.5179C21.036 4.70874 16.3272 0 10.518 0C4.70944 0 0 4.70874 0 10.518C0 16.3273 4.70944 21.036 10.518 21.036C12.6053 21.036 14.5385 20.4101 16.1739 19.3617L20.1518 23.3397C21.0322 24.2201 22.4593 24.2201 23.3397 23.3397C24.2201 22.4593 24.2201 21.0323 23.3397 20.1519ZM10.518 18.0309C6.369 18.0309 3.00511 14.6677 3.00511 10.518C3.00511 6.36905 6.36905 3.00516 10.518 3.00516C14.6676 3.00516 18.0308 6.36905 18.0308 10.518C18.0308 14.6677 14.6676 18.0309 10.518 18.0309Z"
                              fill="#5B6067"/>
                    </svg>
                </button>
            </form>
            <div class="blog__side --main">
                <div class="blog__category">
                    <span class="blog__category-title">{{ $t('blog.categories') }}</span>
                    <nuxt-link
                            :to="localePath({name: 'blog-category', query: {period: $route.query.period}})"
                            class="blog__category-link"
                            :class="{isActive: !activeCategory}">
                        <span>{{ $t('blog.allCategories') }}</span>
                    </nuxt-link>

                    <nuxt-link
                            :to="localePath({name: 'blog-category', params: {category: category.slug }, query: {period: $route.query.period}})"
                            class="blog__category-link"
                            v-for="category in categories"
                            :class="{isActive: activeCategory === category.slug}"
                            :key="category.slug">
                        <span>{{ category.title }}</span>
                    </nuxt-link>

                </div>
                <div class="blog__category">
                    <span class="blog__category-title">{{ $t('blog.date') }}</span>
                    <nuxt-link :to="{...$route, query: {}}" class="blog__category-link" :class="{isActive: !$route.query.period}"><span>{{ $t('blog.dateOverall') }}</span></nuxt-link>
                    <nuxt-link v-for="period in periods" :key="period.key" :to="{...$route, query: {period: period.key}}" class="blog__category-link" :class="{isActive: period.key === $route.query.period}"><span>{{ $t(`blog.dateFilterPeriod.${period.key}`) }}</span></nuxt-link>
                </div>
                <div class="blog__category --share">
                    <span class="blog__category-title">{{ $t('blog.share') }}</span>
                    <div class="social">
                        <a href="" class="social__link --fcb">
                            <img src="~/assets/img/social/fcb.svg"/>
                        </a>
                        <a href="" class="social__link --vk">
                            <img src="~/assets/img/social/vk.svg"/>
                        </a>
                        <a href="" class="social__link --ok">
                            <img src="~/assets/img/social/ok.svg"/>
                        </a>
                        <a href="" class="social__link --my">
                            <img src="~/assets/img/social/my.svg"/>
                        </a>
                    </div>
                </div>
                <div class="blog__category">
                    <a href="/blog/feed.xml" class="subscribe-link">{{ $t('blog.subscribe') }}</a>
                </div>
            </div>
            <ul class="blog__list">
                <li class="blog__list-item" v-for="post in posts" :key="post.id">
                    <div class="blog__item">
                        <h3 class=""></h3>
                        <nuxt-link
                                :to="localePath({name: 'blog-cat-slug', params: {cat: post.categorySlug, slug: post.slug}})"
                                class="blog__item-title">{{ post.title }}
                        </nuxt-link>
                        <img class="blog__item-img" v-if="post.image" :src="post.image" :alt="post.title">
                        <p class="blog__item-text" v-html="post.annotation"></p>
                        <div class="blog__item-bottom">
                            <div>
                                <formatted-date class="blog__item-link" :date="post.publishedAt" format="d MMMM y"/>
                                <span class="blog__item-link">{{ post.categoryTitle }}</span>
                            </div>
                            <div>
                                <nuxt-link
                                        :to="localePath({name: 'blog-cat-slug', params: {cat: post.categorySlug, slug: post.slug}})"
                                        class="blog__item-link">{{ $t('blog.postLinkTitle') }}
                                </nuxt-link>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
            <div class="blog__pagination">
                <Pagination :pages="pages" v-if="pages > 1"/>
            </div>
        </div>
    </div>
</template>

<script>
    import Pagination from "~/components/Pagination";
    import FormattedDate from "~/components/FormattedDate";

    export default {
        props: ['posts', 'categories', 'pages'],
        components: {
            FormattedDate,
            Pagination
        },
        methods: {
            search() {
                this.$router.push({...this.$route, query: {...this.$route.query, page: undefined, search: this.$refs.search.value || undefined}})
            }
        },
        computed: {
            activeCategory() {
                return this.$route.params.category
            },
            periods() {
                return [
                    {key: 'year'},
                    {key: 'month'},
                    {key: 'week'}
                ]
            }
        }
    };
</script>

<style lang="scss">
    .subscribe-link {
        display: block;
        padding: 0 0 0 30px;
        line-height: 20px;
        color: #5B6067;
        font-size: 14px;
        -webkit-transition: opacity 0.3s;
        -moz-transition: opacity 0.3s;
        -ms-transition: opacity 0.3s;
        -o-transition: opacity 0.3s;
        transition: opacity 0.3s;
        background: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0.00322759 10.6289C2.50061 10.6289 4.8483 11.6006 6.6126 13.3679C8.38013 15.1352 9.35326 17.4893 9.35326 19.9952H13.2018C13.2018 12.7165 7.28072 6.79526 0.00322759 6.79526V10.6289ZM0.00927943 3.83323C8.90987 3.83323 16.1515 11.0854 16.1515 20H20C20 8.97127 11.0316 0 0.00927943 0V3.83323ZM5.33124 17.3221C5.33124 18.788 4.13742 19.9771 2.66562 19.9771C1.19382 19.9771 0 18.7884 0 17.3221C0 15.8553 1.19342 14.6671 2.66521 14.6671C4.13701 14.6671 5.33124 15.8553 5.33124 17.3221Z' fill='%23E67C2B'/%3E%3C/svg%3E%0A") left top no-repeat;
        background-size: 20px;
        white-space: nowrap;
        &:hover {
            opacity: 0.7;
        }
    }

    .blog {
        &__in {
            display: flex;
            justify-content: space-between;
            padding: 45px 0 30px;
            position: relative;
            @media all and (max-width: 1023px) {
                flex-direction: column;
                padding: 28px 0 0;
            }
            .input {
                margin: 34px 0;
                @media all and (max-width: 1023px) {
                    margin: 22px 0 26px;
                }
                @media all and (max-width: 768px) {
                    margin: 16px 0;
                    height: 34px;
                    padding: 0 10px;
                }
                input {
                    width: calc(100% - 40px);
                    font-size: 16px;
                    @media all and (max-width: 768px) {
                        width: calc(100% - 30px);
                    }
                }
                button {
                    width: 30px;
                    height: 30px;
                    line-height: 30px;
                    svg {
                        display: inline-block;
                        width: 16px;
                        height: 16px;
                    }
                }
            }
        }

        &__content {
            width: 710px;
            @media all and (max-width: 1023px) {
                width: 100%;
            }
            @media all and (max-width: 768px) {
                .title {
                    font-size: 18px;
                    line-height: 20px;
                }
            }
            &.--main {
                @media all and (max-width: 1023px) {
                    width: calc(100% - 230px);
                }
                @media all and (max-width: 768px) {
                    width: 100%;
                }
            }
        }

        &__side {
            width: 260px;
            @media all and (max-width: 1023px) {
                width: 100%;
                border-bottom: 1px solid #7B95A7;
            }
            &.--main {
                position: absolute;
                top: 45px;
                right: 0;
                @media all and (max-width: 1023px) {
                    width: 200px;
                    border: none;
                }
                @media all and (max-width: 768px) {
                    position: relative;
                    width: 100%;
                    overflow-x: auto;
                    top: auto;
                    display: flex;
                    align-items: center;
                    .blog__category {
                        display: flex;
                        padding: 0;
                        margin: 0 20px 0 0;
                        .social {
                            margin: 0;
                        }
                        &-title {
                            display: none;
                        }
                    }
                }
            }
        }

        &__list {
            list-style: none;
            padding: 0;
            margin: 48px 0 0;
            @media all and (max-width: 1023px) {
                margin: 28px 0 0;
            }
            @media all and (max-width: 768px) {
                margin: 20px 0 0;
            }

            &-item {
                padding: 48px 0 35px;
                border-bottom: 1px solid #7B95A7;
                &:first-child {
                    padding: 0 0 35px;
                }
                @media all and (max-width: 1023px) {
                    padding: 26px 0;
                    &:first-child {
                        padding: 0 0 26px;
                    }
                }
                @media all and (max-width: 1023px) {
                    padding: 24px 0 18px;
                    &:first-child {
                        padding: 0 0 18px;
                    }
                }
            }
        }

        &__item {
            &-title, & > h3 {
                font-weight: bold;
                font-size: 32px;
                margin: 0 0 32px;
                line-height: 40px;
                @media all and (max-width: 1023px)  {
                    font-size: 22px;
                    line-height: 30px;
                    margin: 0 0 24px;
                }
                @media all and (max-width: 768px) {
                    font-size: 16px;
                    line-height: 20px;
                    margin: 0 0 8px;
                }
            }

            &-img, & > img {
                display: block;
                max-width: 100%;
                margin: 32px 0 29px;
                @media all and (max-width: 1023px) {
                    margin: 24px 0;
                }
                @media all and (max-width: 768px) {
                    margin: 8px 0 16px;
                }
            }

            &-text, & > p {
                font-size: 16px;
                line-height: 30px;
                margin: 24px 0;
                @media all and (max-width: 1023px) {
                    line-height: 20px;
                    font-size: 14px;
                }
                @media all and (max-width: 768px) {
                    font-size: 12px;
                    margin: 16px 0;
                }
            }

            &-bottom {
                display: flex;
                justify-content: space-between;
                font-size: 0;
            }

            &-link {
                display: inline-block;
                font-size: 14px;
                line-height: 20px;
                color: #5B6067;
                @media all and (max-width: 768px) {
                    font-size: 12px;
                }
                & + .blog__item-link {
                    margin: 0 0 0 50px;
                }
            }
        }

        &__pagination {
            padding: 40px 0 30px;
            @media all and (max-width: 768px) {
                overflow-x: auto;
            }
        }

        &__category {
            margin: 24px 0;
            @media all and (max-width: 768px) {
                margin: 0;
                padding: 20px 0;
            }

            &.--share {
                margin: 35px 0 54px;
                @media all and (max-width: 768px) {
                    margin: 0;
                }
            }

            &-link {
                display: block;
                line-height: 30px;
                color: #333333;
                position: relative;
                margin: 2px 0;

                @media all and (max-width: 768px) {
                    font-size: 14px;
                    line-height: 20px;
                    margin: 0 12px 0 0;
                }

                span {
                    border-bottom: 3px solid transparent;
                    line-height: 20px;
                    @media all and (max-width: 768px) {
                        white-space: nowrap;
                        border: none;
                    }
                }

                &:hover {
                    background: #F1F8FC;
                    &:before {
                        position: absolute;
                        content: '';
                        width: 10px;
                        top: 0;
                        right: 100%;
                        height: 100%;
                        background: #F1F8FC;
                    }
                }

                &.isActive {
                    font-weight: 600;
                    span {
                        border-color: #0F6BF5;
                    }
                }
            }

            &-title {
                font-size: 14px;
                margin: 0 0 10px;
                display: block;
                color: #5B6067;
            }

            .social {
                margin: 15px 0 0;
                @media all and (max-width: 768px) {
                    display: flex;
                    margin: 0 20px 0 0;
                }
            }
        }
    }

    .social {
        font-size: 0;

        &__link {
            display: inline-block;
            width: 50px;
            height: 50px;
            margin: 0 0 0 20px;
            line-height: 50px;
            text-align: center;
            transition: opacity 0.3s;
            @media all and (max-width: 1023px) {
                width: 40px;
                height: 40px;
                line-height: 40px;
                margin: 0 0 0 10px;
            }

            &:hover {
                opacity: 0.7;
            }

            img {
                vertical-align: middle;
                display: inline-block;
            }

            &.--fcb {
                background: #4267B2;
            }

            &.--vk {
                background: #4A76A8;
            }

            &.--ok {
                background: #EE8208;
            }

            &.--my {
                background: #168DE2;
            }

            &:first-child {
                margin: 0;
            }
        }

        &.--md {
            .social__link {
                width: 30px;
                height: 30px;
                margin: 0 0 0 10px;
                line-height: 30px;

                img {
                    -webkit-transform: scale(0.6);
                    -moz-transform: scale(0.6);
                    -ms-transform: scale(0.6);
                    -o-transform: scale(0.6);
                    transform: scale(0.6);
                }

                &:first-child {
                    margin: 0;
                }
            }
        }
    }

    a.blog__item-link {
        transition: opacity 0.4s;

        &:hover {
            opacity: 0.7
        }
    }
</style>