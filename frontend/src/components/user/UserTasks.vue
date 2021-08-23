<template>
    <div class="user-tasks">
        <h3 class="user-profile__mob-title">{{ $t('profile.tasks.tabTitle') }}</h3>
        <div class="user-objects__filter --between">
            <div class="filter">
                <div class="filter__text">{{ $t('profile.sort') }}</div>
                <div class="filter__dropdown">
                    <dropdown :options="sortOptions" v-model="sort"/>
                </div>
            </div>
        </div>
        <div class="user-tasks__content">
            <UserTasksItem
                    v-for="(task, index) in items"
                    :key="index"
                    :tasksItemStatus="task.completedAt ? '--done' : '--new'"
                    :tasksItemDate="task.createdAt"
                    :tasksItemText="task.title"
                    :tasksItemPoints="task.points"
            />
            <!--<UserTasksItem
                    tasksItemStatus="--current"
                    tasksItemDate="29 июня"
                    tasksItemText="Добавьте 5 объектов в Северном промышленном районе"
                    tasksItemPoints="15 баллов"
            />
            <UserTasksItem
                    tasksItemStatus="--done"
                    tasksItemDate="25 июня"
                    tasksItemText="Добавьте свой первый объект в городе"
                    tasksItemPoints="20 баллов"
            />
            <UserTasksItem
                    tasksItemStatus="--done"
                    tasksItemDate="15 июня"
                    tasksItemText="Добавьте 5 объектов в городе"
                    tasksItemPoints="10 баллов"
            />
            <UserTasksItem
                    tasksItemStatus="--done"
                    tasksItemDate="14 июня"
                    tasksItemText="Заполните профиль"
                    tasksItemPoints="50 баллов"
            />-->
        </div>

        <div class="user-tickets__pagination">
            <pagination :pages="pages" v-if="pages > 1"/>
        </div>
    </div>
</template>

<script>
    import UserTasksItem from "./UserTasksItem";
    import Pagination from "../Pagination";
    import Dropdown from "@/components/Dropdown";

    export default {
        name: 'UserTasks',
        props: [
            'pages',
            'items'
        ],
        data() {
            return {
                sort: 'createdAt desc'
            }
        },
        components: {
            Dropdown,
            Pagination,
            UserTasksItem
        },
        computed: {
            sortOptions() {
                return [
                    {value: 'createdAt desc', title: this.$t('profile.sortNewestFirst')},
                    {value: 'createdAt asc', title: this.$t('profile.sortOldestFirst')},
                ]
            },
        },
        watch: {
            sort: {
                handler(v) {
                    this.$router.push({
                        ...this.$route, query: {
                            sort: v
                        }
                    })
                },
            }
        }
    };
</script>

<style lang="scss">
    @import "./../../styles/mixins.scss";

    .user-tasks {
        padding: 35px 0 0;
        @media all and (max-width: 768px) {
            padding: 30px 0;
        }

        &__item {
            margin: 50px 0 0;
            justify-content: space-between;
            display: flex;
            position: relative;
            @media all and (max-width: 1023px) {
                margin: 30px 0 0;
            }
            @media all and (max-width: 768px) {
                padding: 0 0 30px;
            }

            &-left {
                display: flex;
                justify-content: flex-start;
                @media all and (max-width: 768px) {
                    width: 100%;
                }
            }

            &-status {
                width: 50px;

                &.--new {
                    background-size: 4px 20px;
                    background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNSIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDUgMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxnIGNsaXAtcGF0aD0idXJsKCNjbGlwMCkiPgo8cGF0aCBkPSJNMi4wNDA3NyAxNi4zMjY3VjIwLjAwMDEiIHN0cm9rZT0iIzMxODBGNyIgc3Ryb2tlLXdpZHRoPSI3IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiLz4KPHBhdGggZD0iTTIuMDQwNzcgMFYxMy45OTQyIiBzdHJva2U9IiMzMTgwRjciIHN0cm9rZS13aWR0aD0iNyIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIi8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDAiPgo8cmVjdCB3aWR0aD0iNC4wODE2MyIgaGVpZ2h0PSIyMCIgZmlsbD0id2hpdGUiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4K") left 6px top no-repeat;
                }

                &.--current {
                    background-size: 20px;
                    background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE0LjU5MjggMTAuNTgxNkg5LjQ3NjU2VjUuNDY1MzMiIHN0cm9rZT0iIzBGNkJGNSIgc3Ryb2tlLXdpZHRoPSIzIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiLz4KPHBhdGggZD0iTTE4LjUgMTBDMTguNSAxNC42OTQ0IDE0LjY5NDQgMTguNSAxMCAxOC41QzUuMzA1NTggMTguNSAxLjUgMTQuNjk0NCAxLjUgMTBDMS41IDUuMzA1NTggNS4zMDU1OCAxLjUgMTAgMS41QzE0LjY5NDQgMS41IDE4LjUgNS4zMDU1OCAxOC41IDEwWiIgc3Ryb2tlPSIjMEY2QkY1IiBzdHJva2Utd2lkdGg9IjMiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIvPgo8L3N2Zz4K") left top no-repeat;
                }

                &.--done {
                    background-size: 18px 14px;
                    background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAxOCAxNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwKSI+CjxwYXRoIGQ9Ik0xNS4zMjY0IDBMNi42NjUyNyA4LjkxNTIxTDIuNjczNjQgNC43ODcwOEwwIDcuNTM5MTdMNi42NjUyNyAxNC40TDE4IDIuNzUyMDlMMTUuMzI2NCAwWiIgZmlsbD0iIzNEQkEzQiIvPgo8L2c+CjxkZWZzPgo8Y2xpcFBhdGggaWQ9ImNsaXAwIj4KPHJlY3Qgd2lkdGg9IjE4IiBoZWlnaHQ9IjE0LjQiIGZpbGw9IndoaXRlIi8+CjwvY2xpcFBhdGg+CjwvZGVmcz4KPC9zdmc+Cg==") left top 3px no-repeat;
                }

                @media all and (max-width: 768px) {
                    width: 20px;
                }
            }

            &-date {
                width: 100px;
                font-size: 14px;
                line-height: 20px;
                color: $text;
                padding: 0 10px 0 0;
                @media all and (max-width: 1023px) {
                    font-size: 12px;
                }
                @media all and (max-width: 768px) {
                    position: absolute;
                    bottom: 0;
                    left: 30px;
                }
            }

            &-text {
                font-size: 16px;
                line-height: 20px;
                width: 410px;
                @media all and (max-width: 1023px) {
                    font-size: 14px;
                }
                @media all and (max-width: 768px) {
                    width: calc(100% - 30px);
                    margin: 0 0 0 10px;
                }
            }

            &-points {
                font-size: 16px;
                line-height: 20px;
                @media all and (max-width: 1023px) {
                    font-size: 14px;
                }
                @media all and (max-width: 768px) {
                    position: absolute;
                    bottom: 0;
                    left: 106px;
                }
            }

            &:first-child {
                margin: 0;
            }
        }
    }
</style>