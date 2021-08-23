<template>
    <div class="pagination" v-hotkey="keymap">
        <nuxt-link class="pagination__prev" v-if="currentPage > 1 && pages > 1"
                     :to="{...$route, query: {...$route.query, page: currentPage - 1}}">
            <span>← Ctrl</span>
        </nuxt-link>
        <nuxt-link class="pagination__btn" v-if="buttons[0] !== 1"
                     :to="{...$route, query: {...$route.query, page: 1}}">
            <span>1</span>
        </nuxt-link>
        <button class="pagination__btn" v-if="currentPage > 3">
            <span>...</span>
        </button>
        <nuxt-link class="pagination__btn" v-for="button in buttons" :key="button"
                     :class="{pagination__btn_active: button === currentPage}"
                     :to="{...$route, query: {...$route.query, page: button}}">
            <span>{{ button }}</span>
        </nuxt-link>
        <button class="pagination__btn" v-if="lastButton < pages - 1 ">
            <span>...</span>
        </button>
        <nuxt-link class="pagination__btn" v-if="lastButton !== pages"
                     :to="{...$route, query: {...$route.query, page: pages}}">
            <span>{{ pages }}</span>
        </nuxt-link>
        <nuxt-link class="pagination__next" v-if="pages > 1 && currentPage !== pages"
                     :to="{...$route, query: {...$route.query, page: currentPage + 1}}">
            <span>Ctrl →</span>
        </nuxt-link>
    </div>
</template>

<script>
    import range from 'lodash/range'
    import last from 'lodash/last'
    import first from 'lodash/first'

    export default {
        props: [
            'pages',
        ],
        methods: {
            selectPage(page) {
                this.$router.push({...this.$route, query: {...this.$route.query, page: page}})
            }
        },
        computed: {
            keymap() {
                const self = this;
                return {
                    'ctrl+left': {
                        keyup() {
                            if (self.currentPage !== 1) {
                                self.selectPage(self.currentPage - 1)
                            }
                        }
                    },
                    'ctrl+right': {
                        keyup() {
                            if (self.currentPage !== self.pages) {
                                self.selectPage(self.currentPage + 1)
                            }
                        }
                    }
                }
            },
            firstButton() {
                return first(this.buttons);
            },
            lastButton() {
                return last(this.buttons);
            },
            currentPage() {
                return Number(this.$route.query.page || 1)
            },
            buttons() {
                return range(this.currentPage - 1, this.currentPage + 2).filter(i => i > 0 && i <= this.pages)
            }
        }
    };
</script>

<style lang="scss">
    @import "./../styles/mixins.scss";

    .pagination {
        display: flex;
        width: 100%;
        justify-content: center;
        align-items: center;

        & > * {
            margin-left: 10px;

            &:first-child {
                margin-left: 0;
            }
        }

        &__btn,
        &__prev,
        &__next {
            border: none;
            flex: none;
            min-width: 40px;
            height: 40px;
            background: #f1f8fc;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            line-height: 20px;
            color: #5b6067;
            padding: 0 15px;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;

            &.pagination__btn_active {
                background: #0f6bf5;
                font-weight: 500;

                span {
                    color: #fff;
                }
            }

            &:hover {
                background: #0f6bf5;

                span {
                    color: #fff;
                }
            }
        }
    }
</style>