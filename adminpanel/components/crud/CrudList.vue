<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ title }}</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <nuxt-link :to="`${$route.path}/create`" class="btn btn-info d-none d-lg-block m-l-15" v-if="create"><i
                        class="fa fa-plus-circle"></i> Создать
                    </nuxt-link>
                </div>
            </div>
        </div>

        <slot name="filter"/>

        <b-card>
            <loading :active="isLoading" :is-full-page="false"/>
            <b-table striped bordered hover :items="items" :fields="fields">
                <template v-slot:[x]="data">
                    <nuxt-link class="btn btn-sm btn-success" :to="`${$route.path}/${data.item.id}`" v-if="actions.includes('edit')">Редактировать</nuxt-link>
                    <delete v-if="actions.includes('delete')" :item="data.item"/>
                    <component v-for="(action, index) in customActions" :key="index" :is="action" :item="data.item"/>
                </template>
                <template v-for="customSlot in customSlots" v-slot:[customSlot.slot]="data">
                    <component :is="customSlot.type" :options="customSlot.options" :value="data.item[customSlot.key]" :item="data.item"/>
                </template>
            </b-table>
            <b-pagination align="center" v-model="currentPage" :total-rows="pagesCount"/>
        </b-card>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import {call, get, sync} from 'vuex-pathify'
    import Delete from "./list-actions/Delete";

    export default {
        name: "CrudList",
        components: {
            Delete,
            Loading
        },
        props: {
            title: String,
            create: {
                type: Boolean,
                default: true
            },
            apiPath: String,
            tableFields: {
                type: Array,
                default() {
                    return []
                }
            },
            actions: {
                type: Array,
                default() {
                    return [
                        'edit',
                        'delete'
                    ]
                }
            },
            customActions: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        beforeMount() {
            this.reset();
            this.$store.set('crud/list/apiPath', this.apiPath);
        },
        mounted() {
            this.load()
        },
        methods: {
            ...call('crud/list/*')
        },
        computed: {
            ...get('crud/list/@', [
                'isLoading',
                'pagesCount',
                'items'
            ]),
            x() {
                return 'cell(_actions)'
            },
            currentPage: sync('crud/list/currentPage|changePage'),
            fields() {
                const fields = this.tableFields.map(field => {
                    return {
                        key: field.key,
                        label: field.label,
                        sortable: field.sortable
                    }
                });

                return fields.concat([
                    {key: '_actions', label: 'Действия'}
                ])
            },
            customSlots() {
                return this.tableFields.filter(f => !!f.type).map(f => ({
                    key: f.key,
                    type: f.type,
                    options: f.typeOptions,
                    slot: `cell(${f.key})`
                }))
            }
        }
    }
</script>

<style scoped>

</style>
