<template>
    <nuxt-link :to="localePath(objectLink)" class="user-object">
        <div class="user-object__image">
            <div class="image" v-bind:style="{'background-image': 'url(' + objectImg + ')'}"></div>
            <div class="type" v-bind:style="{'background-image': 'url(' + scores[status].icon + ')'}"></div>
        </div>
        <div class="user-object__description">
            <div class="user-object__title">
                <span>{{objectTitle}}</span>
            </div>
            <div class="user-object__params">
                <div class="user-object__param">
                    <span>{{scores[status].title}}</span>
                </div>
                <div class="user-object__param">
                    <formatted-date :date="objectDate" format="dd.MM.yyyy"/>
                </div>
                <div class="user-object__param">
                    <span><span>{{ $t('profile.objects.commentsCountLabel') }}</span> {{objectComments}}</span>
                </div>
                <div class="user-object__param">
                    <span><span>{{ $t('profile.objects.complaintsCountLabel') }}</span> {{objectReports}}</span>
                </div>
            </div>
        </div>
    </nuxt-link>
</template>

<script>
    import FormattedDate from "~/components/FormattedDate";

    export default {
        components: {FormattedDate},
        props: [
            "objectLink",
            "objectTypeImg",
            "objectImg",
            "objectTitle",
            "status",
            "objectDate",
            "objectComments",
            "objectReports"
        ],
        computed: {
            scores() {
                return {
                    full_accessible: {
                        title: this.$t('accessibilityStatus.full_accessible'),
                        icon: require('~/assets/icons/green.svg')
                    },
                    partial_accessible: {
                        title: this.$t('accessibilityStatus.partial_accessible'),
                        icon: require('~/assets/icons/yellow.svg')
                    },
                    not_accessible: {
                        title: this.$t('accessibilityStatus.not_accessible'),
                        icon: require('~/assets/icons/red.svg')
                    },
                    not_provided: {
                        title: this.$t('accessibilityStatus.not_provided'),
                        icon: require('~/assets/icons/red.svg')
                    },
                    unknown: {
                        title: this.$t('accessibilityStatus.unknown'),
                        icon: require('~/assets/icons/red.svg')
                    }
                }
            }
        }
    };
</script>

<style lang="scss">
    @import "./../../styles/mixins.scss";

    .user-object {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        justify-content: flex-start;

        &__download {
            line-height: 20px;
            font-size: 14px;
            color: $text;
            padding: 0 0 0 30px;
            background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9Ii0yIDQgMjQgMjQiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDpub25lO3N0cm9rZTojN0I5NUE3O3N0cm9rZS13aWR0aDoyO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTIxLDE5Ljd2NC45YzAsMS40LTEuMSwyLjQtMi40LDIuNEgxLjRDMC4xLDI3LTEsMjUuOS0xLDI0LjZ2LTQuOSIvPg0KPHBvbHlsaW5lIGNsYXNzPSJzdDAiIHBvaW50cz0iMy45LDEzLjYgMTAsMTkuNyAxNi4xLDEzLjYgIi8+DQo8bGluZSBjbGFzcz0ic3QwIiB4MT0iMTAiIHkxPSIxOS43IiB4Mj0iMTAiIHkyPSI1Ii8+DQo8L3N2Zz4NCg==") no-repeat left top 1px;
            background-size: 20px;
            -webkit-transition: opacity 0.3s;
            -moz-transition: opacity 0.3s;
            -ms-transition: opacity 0.3s;
            -o-transition: opacity 0.3s;
            transition: opacity 0.3s;

            &:hover {
                opacity: 0.7;
            }
        }

        &__text {
            margin: 11px 0 0;
            font-size: 16px;
            line-height: 20px;
            @media all and (max-width: 1023px){
                font-size: 14px;
            }
        }

        &__image {
            width: 110px;
            position: relative;
            @media all and (max-width: 1023px){
                width: 80px;
            }
            @media all and (max-width: 768px){
                width: 60px;
            }
            .image {
                width: 100%;
                height: 80px;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
                z-index: 1;
                @media all and (max-width: 768px){
                    height: 60px;
                }
            }

            .type {
                position: absolute;
                top: 0;
                left: 0;
                z-index: 2;
                width: 30px;
                height: 30px;
                background-position: center;
                background-size: cover;
                background-repeat: no-repeat;
                @media all and (max-width: 768px){
                    width: 20px;
                    height: 20px;
                }
            }
        }

        &__description {
            flex: 1 0 auto;
            max-width: calc(100% - 150px);
            margin-left: 40px;
            @media all and (max-width: 1023px){
                max-width: calc(100% - 100px);
                margin-left: 20px;
            }
            @media all and (max-width: 768px){
                max-width: calc(100% - 70px);
                margin-left: 10px;
            }
        }

        &__title {
            font-weight: bold;
            font-size: 18px;
            line-height: 20px;
            color: #333333;
            min-height: 40px;
            margin-top: 6px;
            @media all and (max-width: 1023px){
                font-size: 16px;
                line-height: 20px;
            }

            &.--ticket {
                min-height: 0;
            }
        }

        &__params {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: flex-start;
            flex-wrap: wrap;
            margin-top: 12px;
            position: relative;

            .user-object__download {
                position: absolute;
                right: 0;
                bottom: 0;
            }
        }

        &__param {
            font-size: 14px;
            line-height: 20px;
            color: #5b6067;
            margin-left: 30px;
            @media all and (max-width: 1023px){
                font-size: 12px;
                line-height: 20px;
            }
            @media all and (max-width: 768px){
                margin: 0 30px 0 0;
            }
            &-comments {
                @media all and (max-width: 768px){
                    display: inline-block;
                    padding: 0 0 0 20px;
                    background: url('data:image/svg+xml;base64, PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE1IDEwLjMzMzNDMTUgMTAuNzQ1OSAxNC44MzYxIDExLjE0MTYgMTQuNTQ0NCAxMS40MzMzQzE0LjI1MjcgMTEuNzI1IDEzLjg1NyAxMS44ODg5IDEzLjQ0NDQgMTEuODg4OUg0LjExMTExTDEgMTVWMi41NTU1NkMxIDIuMTQzIDEuMTYzODkgMS43NDczMyAxLjQ1NTYxIDEuNDU1NjFDMS43NDczMyAxLjE2Mzg5IDIuMTQzIDEgMi41NTU1NiAxSDEzLjQ0NDRDMTMuODU3IDEgMTQuMjUyNyAxLjE2Mzg5IDE0LjU0NDQgMS40NTU2MUMxNC44MzYxIDEuNzQ3MzMgMTUgMi4xNDMgMTUgMi41NTU1NlYxMC4zMzMzWiIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utb3BhY2l0eT0iMC43IiBzdHJva2Utd2lkdGg9IjEuMyIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') left center no-repeat;
                    background-size: 14px;
                }
            }
            &-complaint {
                @media all and (max-width: 768px){
                    display: inline-block;
                    padding: 0 0 0 20px;
                    background: url('data:image/svg+xml;base64, PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNCAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyLjEyMDQgMy4yODU3MUMxMS42MzQ3IDMuMjg1NzEgMTEuMjQwOSAzLjY3OTUxIDExLjI0MDkgNC4xNjUyOVY3QzExLjI0MDkgNy4xNTc3MSAxMS4xMDk1IDcuMjg1NzEgMTAuOTQ3NyA3LjI4NTcxQzEwLjc4NTggNy4yODU3MSAxMC42NTQ1IDcuMTU3NzEgMTAuNjU0NSA3VjIuNDUxQzEwLjY1NDUgMS45NjUyMyAxMC4yNjA3IDEuNTcxNDMgOS43NzQ4OSAxLjU3MTQzVjEuNTcxNDNDOS4yODkxMiAxLjU3MTQzIDguODk1MzIgMS45NjUyMyA4Ljg5NTMyIDIuNDUxVjYuNDI4NTdDOC44OTUzMiA2LjU4NjI5IDguNzYzOTcgNi43MTQyOSA4LjYwMjEzIDYuNzE0MjlDOC40NDAyOSA2LjcxNDI5IDguMzA4OTQgNi41ODYyOSA4LjMwODk0IDYuNDI4NTdWMS44Nzk1N0M4LjMwODk0IDEuMzkzOCA3LjkxNTE0IDEgNy40MjkzNiAxVjFDNi45NDM1OSAxIDYuNTQ5NzkgMS4zOTM4IDYuNTQ5NzkgMS44Nzk1N1Y2LjQyODU3QzYuNTQ5NzkgNi41ODYyOSA2LjQxODQ0IDYuNzE0MjkgNi4yNTY2IDYuNzE0MjlDNi4wOTQ3NSA2LjcxNDI5IDUuOTYzNCA2LjU4NjI5IDUuOTYzNCA2LjQyODU3VjMuMDIyNDNDNS45NjM0IDIuNTM2NjYgNS41Njk2IDIuMTQyODYgNS4wODM4MyAyLjE0Mjg2VjIuMTQyODZDNC41OTgwNSAyLjE0Mjg2IDQuMjA0MjUgMi41MzY2NiA0LjIwNDI1IDMuMDIyNDNWOC45ODU2OEM0LjIwNDI1IDkuMDgxNzkgNC4wODE5MSA5LjEyMjU3IDQuMDI0MjUgOS4wNDU2OEwzLjAxNjgzIDcuNzAyMjlDMi42NjUgNy4xNzM3MSAxLjk3ODM0IDYuOTk2NTcgMS40NzUyMyA3LjI5OTQzQzAuOTczODcxIDcuNjA5MTQgMC44NDk1NTcgOC4yODQ1NyAxLjE5NjcgOC44MTE0M0MxLjE5NjcgOC44MTE0MyAzLjExMTgyIDExLjYzNiAzLjkyODA3IDEyLjg0NTFDNC43NDQzMSAxNC4wNTQzIDYuMDY2NjEgMTUgOC41MzkzOCAxNUMxMi42MzM1IDE1IDEzIDExLjkxODkgMTMgMTFDMTMgMTAuMjg2NyAxMyA2LjAzMTU1IDEzIDQuMTYzNzhDMTMgMy42NzgwMSAxMi42MDYyIDMuMjg1NzEgMTIuMTIwNCAzLjI4NTcxVjMuMjg1NzFaIiBzdHJva2U9IiM3Qjk1QTciIHN0cm9rZS1vcGFjaXR5PSIwLjciIHN0cm9rZS13aWR0aD0iMS4zIi8+Cjwvc3ZnPgo=') left center no-repeat;
                    background-size: 14px;
                }
            }
            .yes {
                display: none;
                color: $green;
            }

            .no {
                display: none;
                color: $red;
            }

            &:first-child {
                margin-left: 0;
                width: 140px;
            }

            &.--ticket:first-child {
                width: 80px;
            }

            &.yes {
                .yes {
                    display: block;
                }
            }

            &.no {
                .no {
                    display: block;
                }
            }
        }
    }
</style>