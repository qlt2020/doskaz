<template>
    <b-card>
        <b-row>
            <b-col md="12" lg="6" xl="4">
                <b-form-group label="Email">
                    <b-form-input v-model="filter.email"/>
                </b-form-group>
            </b-col>
            <b-col md="12" lg="6" xl="4">
                <b-form-group label="Номер телефона">
                    <b-form-input v-model="filter.phone"/>
                </b-form-group>
            </b-col>
            <b-col xl="12">
                <b-form-group class="mb-0">
                    <b-btn variant="primary" @click="applyFilter(filter)">Поиск</b-btn>
                    <b-btn variant="secondary" class="ml-3" @click="reset">Сброс</b-btn>
                </b-form-group>
            </b-col>
        </b-row>
    </b-card>
</template>

<script>
    import {call} from 'vuex-pathify'

    export default {
        name: "FilterBlock",
        data() {
            return {
                filter: {}
            }
        },
        methods: {
            reset() {
                this.filter = {}
                this.resetFilter();
            },
            resetFilter: call('crud/list/resetFilter'),
            applyFilter: call('crud/list/applyFilter')
        }
    }
</script>

<style scoped>

</style>
