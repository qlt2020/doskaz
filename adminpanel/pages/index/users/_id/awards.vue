<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Редактирование пользователя</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center"></div>
            </div>
        </div>
        <b-card>
            <b-table striped hover :items="awards" :fields="columns"/>
            <b-modal centered title="Выдача награды" ref="modal" @ok="submit">
                <b-form class="vld-parent" ref="form">
                    <b-form-group label="Вид награды">
                        <b-form-select v-model="awardForm.type" :options="awardsTypes" required/>
                    </b-form-group>

                    <b-form-group label="Наименование">
                        <b-form-input required v-model.trim="awardForm.title" :state="errors.title ? false : null"/>
                        <b-form-invalid-feedback :state="!!errors.title">
                            {{ errors.title }}
                        </b-form-invalid-feedback>
                    </b-form-group>
                </b-form>
            </b-modal>
            <b-btn variant="primary" @click="showModal">Выдать награду</b-btn>
        </b-card>
    </div>
</template>

<script>
    import {format} from 'date-fns'
    import ru from 'date-fns/locale/ru'
    import mapValidationErrors from "@/mapValidationErrors";

    export default {
        async asyncData({$axios, params: {id}}) {
            const {data: awards} = await $axios.get(`/api/admin/awards/user/${id}`);
            return {
                awards
            }
        },
        data() {
            return {
                awardForm: {
                    title: '',
                    type: 'bronze'
                },
                errors: {}
            }
        },
        methods: {
            showModal() {
                this.errors = {};
                this.awardForm.title = '';
                this.awardForm.type = 'bronze';
                this.$refs.modal.show()
            },
            async submit(bvModalEvt) {
                bvModalEvt.preventDefault()
                this.errors = {}
                const loader = this.$loading.show({
                    container: this.$refs.form
                })

                const {data, status} = await this.$axios.post(`/api/admin/awards/user/${this.$route.params.id}`, this.awardForm, {
                    validateStatus: status => status <= 400
                })

                loader.hide()
                if (status === 400) {
                    this.errors = mapValidationErrors(data.errors.violations)
                } else {
                    this.$refs.modal.hide()
                    const {data: awards} = await this.$axios.get(`/api/admin/awards/user/${this.$route.params.id}`);
                    this.awards = awards
                }
            }
        },
        computed: {
            awardsTypes() {
                return {
                    bronze: 'Бронзовая',
                    silver: 'Серебрянная',
                    gold: 'Золотая',
                }
            },
            columns() {
                return [
                    {key: 'title', label: 'Наименование'},
                    {
                        key: 'type', label: 'Тип', formatter: (value, key, item) => {
                            return this.awardsTypes[value]
                        }
                    },
                    {key: 'issuedBy', label: 'Кем выдано'},
                    {
                        key: 'issuedAt', label: 'Дата выдачи', formatter: (value, key, item) => {
                            return format(new Date(value), 'yyyy-MM-dd HH:mm', {
                                locale: ru
                            })
                        }
                    },
                ]
            }
        }
    }
</script>

<style scoped>

</style>
