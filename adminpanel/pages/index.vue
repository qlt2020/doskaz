<template>
    <div id="main-wrapper">
        <nav-header></nav-header>
        <aside class="left-sidebar" style="overflow-y: scroll;">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul>
                        <sidebar-link v-for="link in links" :key="link.path" :path="link.path"><i :class="link.icon"></i>
                            {{link.title}}
                            <template v-if="link.subtitles">
                                <sidebar-link v-for="subtitle in link.subtitles" :key="subtitle.key" :path="subtitle.path" class="link_subtitle">
                                    {{subtitle.title}}
                                </sidebar-link>
                            </template>
                        </sidebar-link>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="page-wrapper">
            <div class="container-fluid">
                <b-toast></b-toast>
                <nuxt-child/>
            </div>
        </div>
    </div>
</template>

<script>
    import NavHeader from "../components/NavHeader";
    import SidebarLink from "../components/SidebarLink";
    import {get} from 'vuex-pathify'

    export default {
        components: {SidebarLink, NavHeader},
        middleware: ['authenticated'],
        head: {
            link: [
                {rel: 'stylesheet', href: '/adminpanel/eliteadmin/university/dist/css/style.min.css'}
            ],
            bodyAttrs: {
                class: ['fixed-layout', 'skin-blue']
            }
        },
        mounted() {
            if (this.$route.name === 'index') {
                window.location.href = `/adminpanel${this.links[0].path}`
            }
        },
        computed: {
            operationResult: get('crud/edit/operationResult'),
            can: get('authentication/can'),
            links() {
                return [
                    {path: '/statisticks/statisticks', key: 'statisticks', icon: 'fas fa-chart-bar', title: 'Статистика',
                        subtitles: [
                            {path: '/statisticks/statisticks', key:"statisticks_subtitle", title:"Статистика"},
                            {path: '/statisticks/complaints', key:"statisticks_complaints", title:"По жалобам и обращениям"},
                            {path: '/statisticks/access', key:"statisticks_access", title:"По доступности"},
                            {path: '/statisticks/user', key:"statisticks_user", title:"По пользователям"},
                        ]
                    },
                    {path: '/users', key: 'users_access', icon: 'fa fa-user', title: 'Пользователи'},
                    {path: '/blog/categories', key: 'blog_access', icon: 'fa fa-list', title: 'Категории медиатеки'},
                    {path: '/blog/posts', key: 'blog_access', icon: 'fa fa-rss', title: 'Записи медиатеки'},
                    {path: '/help/categories', key: 'help_access', icon: 'fa fa-question', title: 'Категории помощи'},
                    {path: '/help/posts', key: 'help_access', icon: 'fa fa-question', title: 'Записи помощи'},
                    {path: '/complaints', key: 'complaints_access', icon: 'fa fa-exclamation-circle', title: 'Жалобы'},
                    {path: '/addingRequests', key: 'adding_requests_access', icon: 'fa fa-file', title: 'Заявки на добавление объектов'},
                    {path: '/objects', key: 'objects_access', icon: 'fa fa-map-marker-alt', title: 'Объекты'},
                    {path: '/regionalRepresentatives', key: 'regional_representatives_access', icon: 'fa fa-id-card', title: 'Региональные представители'},
                    {path: '/regionalCoordinators', key: 'regional_coordinators_access', icon: 'fa fa-id-card', title: 'Региональные координаторы'},
                    {path: '/administrationTasks', key: 'administration_tasks_access', icon: 'fa fa-check', title: 'Задания от администрации'},
                    {path: '/feedback', key: 'feedback_access', icon: 'fa fa-comment-alt', title: 'Обратная связь'},
                    {path: '/photosAddingRequests', key: 'objects_access', icon: 'fa fa-images', title: 'Запросы на добавление фото'}
                ]
                // ].filter(item => this.can(item.key))
            }
        },
        watch: {
            operationResult(v) {
                if(v) {
                    this.$bvToast.toast(v.message, {
                        toaster: 'b-toaster-top-center',
                        variant: v.status === 'error' ? 'danger' : 'success',
                        noAutoHide: false,
                        solid: true,
                        appendToast: false
                    })
                }
            }
        }
    }
</script>

<style lang="css">
    @import url('https://fonts.googleapis.com/css?family=Roboto:400,500,700,900&display=swap&subset=cyrillic,cyrillic-ext,latin-ext');

    body, h5, h4 {
        font-family: 'Roboto', sans-serif !important;
    }

    .sidebar-nav .link_subtitle a {
        padding: 7px 16px;
    }

    .sidebar-nav ul li a {
        padding: 10px 20px 10px 15px;
    }

</style>
