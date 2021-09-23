<template>
    <div class="statisticks">
        <div class="statisticks__header row mb-4 align-items-center">
            <div class="statisticks__header-title statisticks__main-total-title col-3">
                По жалобам и обращениям
            </div>
            <div class="col-9">
                <div class="d-flex justify-content-end align-items-center">
                    <span
                        class="btn btn-info d-lg-block m-l-15"
                        @click="exportList"
                    >
                        <i class="fas fa-download"></i>
                        Экспорт
                    </span>
                </div>
            </div>
        </div>
        <div class="statisticks__main">
            <div class="statisticks__filter d-flex">
                <Select
                    :value="selectedCity"
                    :options="cityList"
                    @input="changeCity(arguments[0])"
                />
                <picker style="margin-right: 20px" :range="true" :input-class="{'form-control custom-item': true}"
                        format="YYYY-MM-DD" v-model="selectedDate" @change="changeDate" @clear="clearDate"/>
                <button class="btn btn-danger d-lg-block mr2" @click="resetFilter">
                    <i class="fas fa-times"></i>
                    Сбросить
                </button>

            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Город</th>
                <th scope="col">Жалобы</th>
                <th scope="col">Обращения</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(complaint, index) in complaintsList" :key="complaint + index">
                <th scope="row">{{ index }}</th>
                <td>{{ complaint.city_name }}</td>
                <td>{{ complaint.complaint_count }}</td>
                <td>{{ complaint.feedback_count }}</td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    import Select from "@/components/statisticks/Select";
    import {get} from 'vuex-pathify';
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/ru';

    export default {
        components: {
            Select,
            picker: DatePicker
        },
        data() {
            return {
                cityList: {options:[{value: null, title: 'Весь Казахстан'}]},
                countryData: true,
                selectedCity: '',
                selectedDate: '',
                dateFrom: '',
                dateTo: ''
            }
        },
        async mounted() {
            await this.$store.dispatch('statisticks/complaintsList');
            await this.$store.dispatch('cities/load');
            await this.getCityList();
        },
        computed: {
            complaintsList: get('statisticks/complaintsList'),
            city: get('cities/items'),
        },
        methods: {
            changeCity(value) {
                this.$store.commit('statisticks/filterComplaintsList', {'field': 'city_id', 'value': value ?? ''});
                this.selectedCity = value
                this.$store.dispatch('statisticks/complaintsList');
            },
            async changeDate(val) {
                let from = new Date(val[0]).toISOString().split('T')[0]
                let to = new Date(val[1]).toISOString().split('T')[0]
                await this.$store.commit('statisticks/filterComplaintsList', {'field': 'dateFrom', 'value': from});
                this.dateFrom = from;
                await this.$store.commit('statisticks/filterComplaintsList', {'field': 'dateTo', 'value': to});
                this.dateTo = to;
                await this.$store.dispatch('statisticks/complaintsList');
            },
            async clearDate() {
                await this.$store.commit('statisticks/filterComplaintsList', {'field': 'dateFrom', 'value': ''});
                await this.$store.commit('statisticks/filterComplaintsList', {'field': 'dateTo', 'value': ''});
                await this.$store.dispatch('statisticks/complaintsList');
            },
            resetFilter() {
                this.$store.commit('statisticks/filterComplaintsListReset');
                this.$store.dispatch('statisticks/complaintsList');
                this.selectedCity = ''
                this.selectedDate = ''
                this.dateFrom = ''
                this.dateTo = ''
            },
            async getCityList() {
                let cities = await this.city
                cities.forEach(city => {
                    this.cityList.options.push({'value': city.id, 'title': city.name})
                });
            },
            async exportList() {
                let dateFrom = '',
                      dateTo = ''
                if (this.dateFrom) {
                    dateFrom = `&dateFrom=${this.dateFrom}`
                } 
                if (this.dateTo) {
                    dateTo = `&dateTo=${this.dateTo}`
                }

                window.open(`/api/complaints/export/excel?city_id=${this.selectedCity ? this.selectedCity : 0}${dateTo}${dateFrom}`, '_blank')
            }
        }
    }
</script>

<style>

    .statisticks .statisticks__filter {
        margin-bottom: 43px;
    }
    .statisticks .statisticks__filter .select2 {
        position: inherit;
        margin-right: 20px;

    }

    .statisticks .statisticks__filter .select-stat {
        width: 320px;

    }

    .statisticks tbody {
        color: #262626;
        background-color: #ffffff;
        border: 1px solid #F0F0F0;
    }

    .statisticks .table .thead-light th {
        color: #262626;
        font-weight: 600;
    }

    .statisticks .statisticks__table-subtitle {
        font-size: 12px;
    }

    .statisticks .table-sm td, .table-sm th {
        padding: 1rem 1rem;
        /* font-weight: 600; */
    }

    .statisticks .statistics_table_stroke {
        padding: 1rem;
    }

    .statisticks .table-responsive::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }

    .statisticks .table-responsive::-webkit-scrollbar-track {
        background: white;
    }
    .statisticks .table-responsive::-webkit-scrollbar-thumb {
        background: #D7D5D5;
    }

    .statisticks .b-table-sticky-header > .table.b-table > thead > tr > th {
        padding: 0.5rem 1rem;
    }

    .statisticks {
        padding: 40px 0;
    }

    .statisticks .statisticks__header-title {
        font-size: 22px;
    }


    .statisticks__block-fix-width {
        min-width: 350px;
    }

    .statisticks__block-fix-width .select2 {
        top: 80px;
        left: 25px;
    }

    .select-stat {
        width: 140px;
        border: none;
        position: absolute;
        right: 0;
    }

    .statisticks .select2 {
        position: absolute;
        right: 15px;
        z-index: 9;
        width: 140px;
    }


    .statisticks .statisticks__main .--gray {
        color: #828282 !important;
    }

    .statisticks .statisticks__main .--blue {
        color: #2D9CDB !important;
    }

    .statisticks .statisticks__main .--fuschia {
        color: #F178B6 !important;
    }
</style>
