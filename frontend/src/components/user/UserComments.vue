<template>
    <div class="user-comments">
        <h3 class="user-profile__mob-title">{{ $t('profile.comments.tabTitle') }}</h3>
        <div class="user-objects__filter">
            <div class="filter">
                <div class="filter__text">{{ $t('profile.sort') }}</div>
                <div class="filter__dropdown">
                    <dropdown :options="options" v-model="filterValue"/>
                </div>
            </div>
        </div>
        <div class="user-comments__list">
            <UserComment
                    v-for="item in items"
                    :key="item.id"
                    :item="item"
            />
        </div>
        <div class="user-comments__pagination">
            <pagination :pages="pages" v-if="pages > 1"/>
        </div>
    </div>
</template>

<script>
    import UserComment from "./UserComment";
    import Pagination from "../Pagination";
    import Dropdown from "../Dropdown";

    export default {
        name: 'UserComments',
        props: [
            'pages',
            'items'
        ],
        components: {
            Dropdown,
            Pagination,
            UserComment
        },
        methods: {
            toggleDropdown (event) {
                event.currentTarget.classList.toggle('opened')
            }
        },
        computed: {
            options() {
                return [
                    {value: 'date desc', title: this.$t('profile.sortNewestFirst')},
                    {value: 'popularity desc', title: this.$t('profile.comments.sortByPopularity')}
                ]
            },
            filterValue: {
                get() {
                    return this.$route.query.sort || 'date desc'
                },
                set(v) {
                    this.$router.push({...this.$route, query: {sort: v}})
                }
            }
        }
    };
</script>

<style lang="scss">
    @import "./../../styles/mixins.scss";
    .user-comments {
        padding: 35px 0 0;
        @media all and (max-width: 1023px){
            padding: 28px 0 0;
        }
        &__list {
            margin: 55px 0 0;
            @media all and (max-width: 1023px){
                margin: 30px 0 0;
            }
        }
        &__item {
            display: flex;
            margin: 35px 0 0;
            &:first-child {
                margin: 0;
            }
        }
        &__image {
            width: 70px;
            height: 50px;
            background-size: cover;
            @media all and (max-width: 1023px){
                width: 50px;
            }
        }
        &__description {
            margin: 0 0 0 40px;
            padding: 4px 0 0;
            max-width: calc(100% - 110px);
            @media all and (max-width: 1023px){
                max-width: calc(100% - 70px);
                margin: 0 0 0 20px;
            }
        }
        &__text {
            font-size: 16px;
            line-height: 20px;
            @media all and (max-width: 1023px){
                font-size: 14px;
            }
        }
        &__info {
            margin: 11px 0 0;
            color: $text;
            font-size: 14px;
            line-height: 20px;
            strong {
                color: $text;
            }
            @media all and (max-width: 1023px){
                font-size: 12px;
            }
        }
    }
</style>