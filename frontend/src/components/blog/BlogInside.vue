<template>
    <div class="blog__inside">
        <div class="breadcrumbs">
            <nuxt-link
                    :to="localePath({name: 'blog-category'})" class="breadcrumbs__link"
            >
                {{ $t('blog.title') }}
            </nuxt-link>
            <nuxt-link
                    :to="localePath({
                        name: 'blog-category',
                        params: {category: $route.params.cat}
                    })"
                    class="breadcrumbs__link"
            >
                {{ post.categoryTitle }}
            </nuxt-link>
        </div>
        <div class="blog__in">
            <div class="blog__content">
                <div class="blog__content-top">
                    <div class="blog__content-top__text">
                        <h3>{{ post.title }}</h3>
                        <formatted-date element="span" class="" :date="post.publishedAt" format="d MMMM y"/>
                    </div>
                    <img :src="post.image" :alt="post.title"/>
                </div>
                <div class="blog__inside">
                    <div v-html="post.content" class="blog__inside-content"></div>
                    <div class="blog__inside-bottom">
                        <formatted-date element="span" class="blog__inside-date" :date="post.publishedAt"
                                        format="d MMMM y"/>
                        <div class="social --md">
                            <a class="social__link --fcb" @click="share('fb')">
                                <img src="~/assets/img/social/fcb.svg"/>
                            </a>
                            <a class="social__link --vk" @click="share('vk')">
                                <img src="~/assets/img/social/vk.svg"/>
                            </a>
                            <a class="social__link --ok" @click="share('ok')">
                                <img src="~/assets/img/social/ok.svg"/>
                            </a>
                            <a class="social__link --my" @click="share('mailru')">
                                <img src="~/assets/img/social/my.svg"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="blog__side">
                <div class="blog__category">
                    <span class="blog__category-title">{{ $t('blog.similarPosts') }}</span>
                    <ul class="blog__material">
                        <li
                                class="blog__material-item"
                                v-for="post in similarPosts"
                                :key="post.id"
                        >
                            <nuxt-link
                                    :to="localePath({
                                    name: 'blog-cat-slug',
                                    params: {
                                        cat: post.categorySlug,
                                        slug: post.slug
                                      }
                                    })"
                                    class="blog__material-link"
                            >
                                <img :src="post.image" :alt="post.title"/>
                                <span>{{ post.title }}</span>
                            </nuxt-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <client-only>
            <comments-block :id="post.id"/>
        </client-only>
    </div>
</template>

<script>
    import Comments from "~/components/Comments";
    import CommentsBlock from "~/components/blog/CommentsBlock";
    import FormattedDate from "~/components/FormattedDate";

    export default {
        props: ['post', 'similarPosts'],
        components: {FormattedDate, CommentsBlock, Comments},
        methods: {
            share(network) {
                window.open(this.shareLinks[network]);
            }
        },
        computed: {
            shareLinks() {
                const path = encodeURIComponent(
                    window.location.origin + this.$route.fullPath
                );

                return {
                    ok: `https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=${path}`,
                    fb: `https://www.facebook.com/sharer.php?u=${path}`,
                    vk: `https://vk.com/share.php?url=${path}`,
                    mailru: `https://connect.mail.ru/share?url=${path}`
                };
            }
        }
    };
</script>

<style lang="scss">
    /* Custom dropdown */
    .custom-dropdown {
        position: relative;
        display: inline-block;
        vertical-align: middle;
        margin: 0 0 20px; /* demo only */
    }

    .custom-dropdown select {
        cursor: pointer;
        background-color: #fff;
        color: #5b6067;
        font-size: 14px;
        line-height: 20px;
        padding: 0 20px 0 0;
        border: 0;
        margin: 0;
        text-indent: 0.01px;
        text-overflow: "";
    }

    .custom-dropdown::after {
        content: "";
        position: absolute;
        pointer-events: none;
    }

    .custom-dropdown::after {
        /*  Custom dropdown arrow */
        background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAiIGhlaWdodD0iNiIgdmlld0JveD0iMCAwIDEwIDYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMi45MTEyOGUtMDUgMS44TDAgMEw1IDQuMkwxMCAwVjEuOEw1IDZMMi45MTEyOGUtMDUgMS44WiIgZmlsbD0iIzVCNjA2NyIvPgo8L3N2Zz4K') center no-repeat;
        width: 20px;
        height: 20px;
        top: 0;
        right: 0;
    }

    .custom-dropdown select[disabled] {
        color: rgba(0, 0, 0, 0.3);
    }

    .custom-dropdown select[disabled]::after {
        color: rgba(0, 0, 0, 0.1);
    }

    .custom-dropdown::after {
        color: rgba(0, 0, 0, 0.4);
    }

    select::-ms-expand {
        display: none;
    }

    .comment-form {
        display: flex;
        margin-top: 15px;

        textarea {
            padding: 14px 20px;
            width: 100%;
            border: 1px solid #7b95a7;
            box-sizing: border-box;
            resize: none;
            overflow: hidden;
            height: 100%;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: center;

            button {
                cursor: pointer;
                display: flex;
                justify-content: center;
                align-items: center;
                border: none;
                outline: none;
                background: none;
            }
        }
    }

    .slider {
        position: relative;
        padding: 0 20px;
        margin: 30px 0 70px;

        img {
            margin: 0 auto;
        }

        .slick-prev {
            position: absolute;
            left: 0;
            top: 50%;
            width: 20px;
            height: 50px;
            margin: -25px 0 0;
            cursor: pointer;
        }

        .slick-next {
            position: absolute;
            right: 0;
            top: 50%;
            width: 20px;
            height: 50px;
            margin: -25px 0 0;
            cursor: pointer;
        }
    }

    .blog {
        &__content {
            &-top {
                position: relative;
                @media all and (max-width: 768px) {
                    margin-left: -20px;
                    margin-right: -20px;
                    min-height: 200px;
                }

                &__text {
                    h3 {
                        font-size: 32px;
                        line-height: 40px;
                        margin: 0 0 20px;
                    }

                    span {
                        display: none;
                    }

                    @media all and (max-width: 768px) {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        display: flex;
                        padding: 0 20px 16px;
                        background: linear-gradient(180deg, rgba(0, 0, 0, 0.25) 0%, rgba(0, 0, 0, 0.5) 70.98%);
                        z-index: 1;
                        flex-direction: column;
                        justify-content: flex-end;
                        align-items: center;
                        h3 {
                            font-size: 22px;
                            line-height: 30px;
                            margin: 0 0 10px;
                            text-align: center;
                            color: #FFFFFF;
                        }
                        span {
                            font-size: 10px;
                            line-height: 14px;
                            display: block;
                            color: #FFFFFF;
                        }
                    }
                }

                img {
                    display: block;
                    width: 100% !important;
                    height: auto !important;
                    @media all and (max-width: 768px) {
                        -webkit-filter: blur(2px);
                        filter: blur(2px);
                    }
                }
            }
        }

        &__inside {
            padding: 35px 0 30px;
            @media all and (max-width: 1023px) {
                padding: 25px 0;
            }
            @media all and (max-width: 768px) {
                padding: 16px 0 20px;
            }

            &-content {
                > h3 {
                    font-size: 24px;
                    line-height: 30px;
                    margin: 0 0 10px;
                    @media all and (max-width: 768px) {
                        font-size: 16px;
                        line-height: 20px;
                    }
                }

                > p {
                    font-size: 16px;
                    line-height: 30px;
                    margin: 10px 0;

                    &:first-child {
                        margin: 0 0 10px;
                    }

                    &:last-child {
                        margin: 10px 0 0;
                    }

                    @media all and (max-width: 1023px) {
                        font-size: 14px;
                        line-height: 20px;
                    }

                    @media all and (max-width: 768px) {
                        font-size: 12px;
                    }

                    > a {
                        color: #0f6bf5;
                    }

                    > a.complaint-text {
                        position: relative;
                        display: flex;
                        align-items: center;
                        padding: 20px 40px 20px 80px;
                        background: #d5e9fc;
                        color: #333333;
                        font-size: 16px;
                        line-height: 30px;
                        transition: opacity 0.3s;

                        &:hover {
                            opacity: 0.7;
                        }

                        svg {
                            display: inline-block;
                            vertical-align: middle;
                        }

                        &:before {
                            content: '';
                            position: absolute;
                            left: 40px;
                            top: 20px;
                            width: 30px;
                            height: 30px;
                            background: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9Ii01NDAgMTA5MiAxOCAxNyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAtNTQwIDEwOTIgMTggMTc7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOm5vbmU7fQ0KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDoxLjQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEwO30NCjwvc3R5bGU+DQo8cGF0aCBjbGFzcz0ic3QwIiBkPSJNLTUzMSwxMDkzLjJjMC40LDAuMiwwLjMsMC40LDAuMiwxLjRjLTAuMSwxLTAuNSwzLTEuMiw0LjhjLTAuNywxLjktMS44LDMuNy0yLjgsNS4xYy0wLjksMS40LTEuOCwyLjMtMi40LDIuOA0KCWMtMC42LDAuNS0xLDAuNy0xLjMsMC43Yy0wLjMsMC0wLjQsMC0wLjUtMC4xYy0wLjEtMC4xLTAuMS0wLjQsMC0wLjZjMC4xLTAuMywwLjItMC42LDAuNi0xYzAuNC0wLjQsMS4xLTAuOSwyLjEtMS40DQoJYzEtMC41LDIuMi0wLjksMy4zLTEuMmMxLTAuMywxLjgtMC41LDIuNS0wLjZjMC43LTAuMSwxLjMtMC4yLDEuOS0wLjNjMC42LDAsMS4yLDAsMS43LDBjMC42LDAsMS4xLDAuMSwxLjYsMC4zDQoJYzAuNSwwLjEsMC45LDAuMywxLjIsMC41YzAuNCwwLjIsMC43LDAuNSwwLjgsMC44YzAuMiwwLjMsMC4yLDAuNywwLDFjLTAuMSwwLjMtMC40LDAuNC0wLjcsMC41Yy0wLjMsMC4xLTAuNiwwLjEtMSwwDQoJYy0wLjQtMC4xLTAuOC0wLjQtMS4zLTAuN2MtMC41LTAuMy0xLTAuNy0xLjYtMS40Yy0wLjctMC42LTEuNS0xLjUtMi4xLTIuM2MtMC43LTAuOC0xLjItMS43LTEuNi0yLjRjLTAuNC0wLjctMC42LTEuMy0wLjctMS44DQoJYy0wLjItMC42LTAuMy0xLjEtMC4zLTEuN2MtMC4xLTAuNSwwLTEsMC0xLjNjMC4xLTAuNCwwLjEtMC42LDAuMy0wLjhjMC4xLTAuMiwwLjMtMC4zLDAuNC0wLjNjMC4xLDAsMC4yLDAsMC4zLTAuMQ0KCWMwLjEsMCwwLjEsMCwwLjMsMEMtNTMxLjIsMTA5My4xLTUzMS4xLDEwOTMuMS01MzEsMTA5My4yIi8+DQo8cGF0aCBjbGFzcz0ic3QxIiBkPSJNLTUzMSwxMDkzLjJjMC40LDAuMiwwLjMsMC40LDAuMiwxLjRjLTAuMSwxLTAuNSwzLTEuMiw0LjhjLTAuNywxLjktMS44LDMuNy0yLjgsNS4xYy0wLjksMS40LTEuOCwyLjMtMi40LDIuOA0KCWMtMC42LDAuNS0xLDAuNy0xLjMsMC43Yy0wLjMsMC0wLjQsMC0wLjUtMC4xYy0wLjEtMC4xLTAuMS0wLjQsMC0wLjZjMC4xLTAuMywwLjItMC42LDAuNi0xYzAuNC0wLjQsMS4xLTAuOSwyLjEtMS40DQoJYzEtMC41LDIuMi0wLjksMy4zLTEuMmMxLTAuMywxLjgtMC41LDIuNS0wLjZjMC43LTAuMSwxLjMtMC4yLDEuOS0wLjNjMC42LDAsMS4yLDAsMS43LDBjMC42LDAsMS4xLDAuMSwxLjYsMC4zDQoJYzAuNSwwLjEsMC45LDAuMywxLjIsMC41YzAuNCwwLjIsMC43LDAuNSwwLjgsMC44YzAuMiwwLjMsMC4yLDAuNywwLDFjLTAuMSwwLjMtMC40LDAuNC0wLjcsMC41Yy0wLjMsMC4xLTAuNiwwLjEtMSwwDQoJYy0wLjQtMC4xLTAuOC0wLjQtMS4zLTAuN2MtMC41LTAuMy0xLTAuNy0xLjYtMS40Yy0wLjctMC42LTEuNS0xLjUtMi4xLTIuM2MtMC43LTAuOC0xLjItMS43LTEuNi0yLjRjLTAuNC0wLjctMC42LTEuMy0wLjctMS44DQoJYy0wLjItMC42LTAuMy0xLjEtMC4zLTEuN2MtMC4xLTAuNSwwLTEsMC0xLjNjMC4xLTAuNCwwLjEtMC42LDAuMy0wLjhjMC4xLTAuMiwwLjMtMC4zLDAuNC0wLjNjMC4xLDAsMC4yLDAsMC4zLTAuMQ0KCWMwLjEsMCwwLjEsMCwwLjMsMEMtNTMxLjIsMTA5My4xLTUzMS4xLDEwOTMuMS01MzEsMTA5My4yIi8+DQo8L3N2Zz4NCg==') #0f6bf5 center no-repeat;
                            background-size: 16px;
                        }
                    }

                    > img {
                        max-width: 100%;
                        margin: 32px 0 28px;
                        display: block;
                    }

                    > iframe {
                        @media all and (max-width: 768px) {
                            width: 100% !important;
                            height: auto !important;
                        }
                    }
                }

                > ul {
                    padding: 0 0 0 40px;
                    margin: 0px 0 20px;
                    list-style: none;
                    @media all and (max-width: 768px) {
                        padding: 0 0 0 20px;
                    }

                    li {
                        position: relative;
                        font-size: 16px;
                        line-height: 30px;
                        margin: 10px 0 0;
                        @media all and (max-width: 1023px) {
                            font-size: 14px;
                            line-height: 20px;
                        }
                        @media all and (max-width: 768px) {
                            font-size: 12px;
                        }

                        &:first-child {
                            margin: 0;
                        }

                        &:before {
                            position: absolute;
                            left: -18px;
                            top: 12px;
                            content: "";
                            width: 6px;
                            height: 6px;
                            -webkit-border-radius: 50%;
                            -moz-border-radius: 50%;
                            border-radius: 50%;
                            background: #0f6bf5;
                            @media all and (max-width: 768px) {
                                top: 8px;
                            }
                        }
                    }
                }
            }

            &-pic {
                display: flex;
                flex-wrap: wrap;
                align-items: flex-start;
                justify-content: flex-start;
                margin: -5px -15px;

                img {
                    margin: 15px;
                }
            }

            &-bottom {
                margin: 30px 0 0;
                padding: 0 0 40px;
                border-bottom: 1px solid #7b95a7;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: relative;
                @media all and (max-width: 768px) {
                    margin: 0;
                    padding: 0 0 20px;
                    .social {
                        position: fixed;
                        right: 20px;
                        bottom: 60px;
                        z-index: 5;
                    }
                }
            }

            &-date {
                color: #5b6067;
                font-size: 14px;
                line-height: 20px;
                @media all and (max-width: 768px) {
                    display: none;
                }
            }

            &-comments {
                > h4 {
                    font-size: 22px;
                    line-height: 30px;
                    margin: 0 0 18px;
                }

                > .comments-block {
                    & + .comments-block {
                        padding: 25px 0;
                        @media all and (max-width: 768px) {
                            padding: 16px 0;
                        }
                    }

                    @media all and (max-width: 768px) {
                        padding: 16px 0;
                        border-top: 1px solid #7B95A7;
                    }
                }
            }

            .blog__in {
                padding: 32px 0 0;
                margin: 0 0 28px;
                @media all and (max-width: 1023px) {
                    padding: 15px 0 0;
                }
            }
        }

        &__material {
            list-style: none;
            padding: 0;

            @media all and (max-width: 1023px) {
                justify-content: space-between;
                display: flex;
                margin: 20px 0 0;
            }

            @media all and (max-width: 768px) {
                overflow-x: auto;
            }

            &-item {
                margin: 30px 0 0;
                @media all and (max-width: 1023px) {
                    margin: 0 0 0 10px;
                    width: calc(100% - 20px);
                    &:first-child {
                        margin: 0;
                    }
                }
                @media all and (max-width: 768px) {
                    width: 230px;
                }
            }

            &-link {
                display: block;
                transition: opacity 0.3s;
                position: relative;

                &:hover {
                    opacity: 0.7;
                }

                img {
                    display: block;
                    max-width: 100%;
                    margin: 0 0 15px;
                    @media all and (max-width: 768px) {
                        width: 230px;
                        height: auto;
                        max-width: 230px;
                        margin: 0;
                    }
                }

                span {
                    display: block;
                    font-size: 14px;
                    line-height: 20px;
                    color: #333333;
                    @media all and (max-width: 1023px) {
                        padding: 0 10px 0 0;
                    }
                    @media all and (max-width: 768px) {
                        font-size: 12px;
                        line-height: 16px;
                        position: absolute;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        padding: 12px 10px 6px;
                        color: #fff;
                        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.6) 68.54%);
                    }
                }
            }
        }
    }

    .breadcrumbs {
        font-size: 0;

        &__link {
            display: inline-block;
            vertical-align: middle;
            font-size: 14px;
            line-height: 20px;
            color: #5b6067;
            transition: opacity 0.3s;

            &:hover {
                opacity: 0.7;
            }

            &:before {
                content: "/";
                display: inline-block;
                vertical-align: middle;
                margin: 0 5px;
            }

            &:first-child {
                &:before {
                    display: none;
                }
            }
        }
    }
</style>
