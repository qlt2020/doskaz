<template>
  <div class="statisticks" ref="to_pdf">
        <div class="statisticks__header row mb-4 align-items-center">
            <div class="statisticks__header-title statisticks__main-total-title col-2">
                Статистика
            </div>
            <div class="col-10">
                <div class="d-flex justify-content-end align-items-center">
                    <span
                        id="export_btn"
                        class="btn btn-info d-lg-block m-l-15"
                        @click="exportAll"
                    >
                    <i class="fas fa-download"></i>
                        Экспорт
                    </span>
                </div>
            </div>
        </div>
       <div class="statisticks__main">
           <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-3 d-flex flex-column">
                        <div class="statisticks__main-total statisticks__block mb-3">
                            <div class="statisticks__main-total-title">
                                Доступные по всем категориям
                            </div>
                            <div class="statisticks__main-total-number --green">
                                {{objectsCount.fullAccessible}}
                            </div>

                        </div>
                        <div class="statisticks__main-total statisticks__block mb-3">
                            <div class="statisticks__main-total-title">
                                Частично доступные по всем категориям
                            </div>
                            <div class="statisticks__main-total-number --orange">
                                {{objectsCount.partialAccessible}}
                            </div>
                        </div>
                        <div class="statisticks__main-total statisticks__block">
                            <div class="statisticks__main-total-title">
                                Недоступные по всем категориям
                            </div>
                            <div class="statisticks__main-total-number --red">
                                {{objectsCount.notAccessible}}
                            </div>
                        </div>
                    </div>
                    <!-- {{groupPopulation}} -->
                    <!-- {{objectsStat}} -->
                    <div class="col-9">
                        <div class="statisticks__block statisticks__main-offer">
                            <Select @input="changeCategory" :options="groupPopulation" :value="selectedCategoryObj"/>
                            <AnychartDoughnut
                                :statData="objectsStat"
                                :title="'Количество объектов в разрезе возможностей для категории'"
                                :selectedCategory="selectedCategoryObj"
                            />
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-3 d-flex flex-column">
                        <div class="statisticks__main-total statisticks__block mb-3">
                            <div class="statisticks__main-total-title statisticks__main-total-title_diagram">
                                Зарегистрированных пользователей
                            </div>
                            <div class="statisticks__main-total-number --green">
                                {{usersStat.registered}}
                            </div>
                        </div>
                        <div class="statisticks__main-total statisticks__block mb-3">
                            <div class="statisticks__main-total-title">
                                Количество мужчин
                            </div>
                            <div class="statisticks__main-total-number --blue">
                                {{usersStat.men}}
                            </div>
                        </div>
                        <div class="statisticks__main-total statisticks__block">
                            <div class="statisticks__main-total-title">
                                Количество женщин
                            </div>
                            <div class="statisticks__main-total-number --fuschia">
                               {{usersStat.women}}
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="statisticks__block statisticks__main-offer">
                            <AnychartDoughnut
                                :statData="usersStat.categories"
                                :title="'Количество пользователей в разрезе категорий'"
                                :totalData="usersStat.registered"
                                :categoryData="usersTitleList"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4" v-if="usersAge">
                <div class="statisticks__block statisticks__block-fix-width">
                    <Select @input="changeCategory(arguments[0], true)" :options="ageGroupPopulations" :usersValue="true" :value="selectedCategoryAge" class="mtop"/>
                    <AnychartDoughnut
                        :statData="usersAge"
                        :categoryData="usersAgeList"
                        :title="'Диаграмма по возрасту в разрезе категорий'"
                        :selectedCategory="selectedCategoryObj"
                        :totalData="usersStat.registered"
                        :rowPie="true"
                    />
                </div>
            </div>
           </div>
            <div class="row statisticks__main-row mt-5" style="height: 320px">
            <div class="col-2 d-flex flex-column justify-content-start">
                <div class="statisticks__main-total statisticks__block mb-3">
                    <div class="statisticks__main-total-title">
                        Количество жалоб
                    </div>
                    <div class="statisticks__main-total-number --red" v-if="complaintsCount">
                        {{complaintsCount.count}}
                    </div>
                </div>
                <div class="statisticks__main-total statisticks__block">
                    <div class="statisticks__main-total-title">
                        Количество обращений
                    </div>
                    <div class="statisticks__main-total-number --green" v-if="feedbackCount">
                        {{feedbackCount.count}}
                    </div>
                </div>
            </div>
            <div class="col-10 d-flex justify-content-between" >
                <div
                    style="margin-right: 20px"
                    class="statisticks__block"
                >
                    <Select
                        v-if="currentYear"
                        :value="currentYear"
                        :options="yearsComplaints"
                        @input="changeYear(arguments[0], 'filterComplaints', 'getComplaintsFilter')"
                    />
                    <AnychartColumn
                        :statData="complaintsFiltered"
                        :fill="'#EB5757'"
                        :stroke="'#EB5757'"
                        :title="'Количество жалоб за год'"
                    />
                </div>
                <div class="statisticks__block">
                    <Select
                        v-if="currentYear"
                        :value="currentYear"
                        :options="yearsFeedback"
                        @input="changeYear(arguments[0], 'feedbackFilter', 'getFeedbackFilter')"
                    />
                    <AnychartColumn
                        :statData="feedbackFiltered"
                        :fill="'#27AE60'"
                        :stroke="'#27AE60'"
                        :title="'Количество обращений за год'"
                    />
                </div>
            </div>
           </div>
       </div>
  </div>
</template>

<script>
    import AnychartColumn from '@/components/statisticks/AnychartColumn.vue'
    import AnychartDoughnut from '@/components/statisticks/AnychartDoughnut.vue'

    import Select from "@/components/statisticks/Select";
    import {get} from 'vuex-pathify';
    import {format} from "date-fns";
    import html2pdf from "html2pdf.js"

    export default {
        components: {
            AnychartColumn, AnychartDoughnut, Select
        },
        data() {
            return {
                newObjects:[],
                currentYear: null,
                selectedCategoryObj: 'kids',
                selectedCategoryAge: null,
                users: {
                   kids_full_accessible: null,
                   movement_full_accessible: null
                },
                usersAgeList:
                    [
                        {value: 'from_18_to_23', title: 'От 18 до 23 лет'},
                        {value: 'from_23_to_28', title: 'От 23 до 28 лет'},
                        {value: 'from_28_to_33', title: 'От 28 до 33 лет'},
                        {value: 'from_33_to_38', title: 'От 33 до 38 лет'},
                        {value: 'from_38_to_43', title: 'От 38 до 43 лет'},
                        {value: 'from_43_to_50', title: 'От 43 до 50 лет'},
                        {value: 'from_50_to_100', title: 'От 50 и выше'},
                    ],
                usersTitleList: [
                    {value: 'withchild', title: 'Семьи с детьми до семи лет'},
                    {value: 'movement', title: 'Люди передвигающиеся на кресло коляске'},
                    {value: 'babycarriage', title: 'Люди с детскими колясками'},
                    {value: 'vision', title: 'Люди с инвалидностью по зрению'},
                    {value: 'limb', title: 'Люди с нарушениями опорно-двигательного аппарата'},
                    {value: 'temporal', title: 'Временно травмированные люди'},
                    {value: 'missinglimbs', title: 'Люди с отсутствующими конечностями'},
                    {value: 'pregnant', title: 'Беременные женщины'},
                    {value: 'agedpeople', title: 'Пожилые люди'},
                    {value: 'hearing', title: 'Люди с инвалидностью по слуху'},
                    {value: 'intellectual', title: 'Люди с интеллектуальной инвалидностью'},
                    {value: 'undefined', title: 'Неизвестно'},
                    {value: 'justview', title: 'Просто посмотреть'},
                    ],
                yearsComplaints: {options:[]},
                yearsFeedback: {options:[]}
            }
        },
        async mounted() {
            await this.$store.dispatch('statisticks/loadComplaints');
            await this.$store.dispatch('statisticks/loadFeedback');
            await this.yearsCount(true);
            this.$store.dispatch('statisticks/getComplaintsFilter');
            await this.$store.dispatch('statisticks/getObjectsStat');
            this.$store.dispatch('statisticks/getFeedbackFilter');
            await this.yearsCount();
            this.$store.dispatch('statisticks/getUsersStat');
            await this.$store.dispatch('statisticks/getUsersAge');
        },
        computed: {
            complaintsCount: get('statisticks/complaintsCount'),
            complaintsFiltered: get('statisticks/complaintsFilteredStat'),
            complaintsStat: get('statisticks/complaintsStat'),
            objectsStat: get('statisticks/objectsStat'),
            feedbackStat: get('statisticks/feedbackStat'),
            feedbackCount: get('statisticks/feedbackCount'),
            feedbackFiltered: get('statisticks/feedbackFilteredStat'),
            usersStat: get('statisticks/usersStat'),
            groupPopulation:get('statisticks/group'),
            // objectsCount: get('statisticks/objectsCount'),
            usersAge: get('statisticks/usersAge'),
            ageGroupPopulations() {
                const populations = {}
                populations.options = [...this.groupPopulation.options]
                populations.options.push({usersValue: null, title: 'Все группы'})
                return populations
            },
            objectsCount() {
                let fullAccessible = 0,
                      notAccessible = 0,
                      partialAccessible = 0;

                this.objectsStat.forEach(item => {
                    fullAccessible += item[`${this.selectedCategoryObj}_full_accessible`];
                    notAccessible += item[`${this.selectedCategoryObj}_not_accessible`];
                    partialAccessible += item[`${this.selectedCategoryObj}_partial_accessible`];
                })

                return {'fullAccessible': fullAccessible,
                        'notAccessible': notAccessible,
                        'partialAccessible': partialAccessible}
            }
        },
        methods: {
            async yearsCount(complaintsSelect) {
                let yearsApi;
                this.currentYear = (new Date()).getFullYear();

                if (complaintsSelect) {
                    yearsApi = await this.complaintsStat.years
                    yearsApi.forEach(year => {
                        if (year != this.currentYear) {
                            this.yearsComplaints.options.push({'value': year, 'title': year})
                        } else {
                            this.yearsComplaints.options.push({'value': this.currentYear, 'title': this.currentYear})
                        }
                    })
                } else {
                    yearsApi = await this.feedbackStat.years
                    yearsApi.forEach(year => {
                        if (year != this.currentYear) {
                            this.yearsFeedback.options.push({'value': year, 'title': year})
                        } else {
                            this.yearsFeedback.options.push({'value': this.currentYear, 'title': this.currentYear})
                        }
                    })
                }
            },
            changeYear(value, mutation, action ) {
                    this.$store.commit(`statisticks/${mutation}`, {'field': 'year_id', 'value': value});
                    this.$store.dispatch(`statisticks/${action}`);
            },
            changeCategory(value, filterApi) {
                if (filterApi) {
                    if (value === '') {
                        this.selectedCategoryAge = null
                        this.$store.commit('statisticks/SET_USERS_AGE_FILTER', null);
                    } else {
                        this.selectedCategoryAge = value
                        this.$store.commit('statisticks/SET_USERS_AGE_FILTER', value);
                    }
                    this.$store.dispatch('statisticks/getUsersAge');
                } else {
                    this.selectedCategoryObj = value
                }
            },
            exportAll() {
                let date = format(new Date(), 'd.MM.yyyy')
                html2pdf(this.$refs.to_pdf, {
                    margin: 2,
                    filename: `${date}_Статистика.pdf`,
                    html2canvas: {
                        letterRendering: true,
                        ignoreElements: (el) => {return el.id === 'export_btn'}
                    },
                    jsPDF: { unit: 'in', format: 'a3', orientation: 'l' }
                })
            },
        }
    }
</script>

<style>

.statisticks {
    padding: 40px 0;
}

.statisticks .height100 {
    height: 100%;
}

.statisticks .statisticks__main-offer .select-stat {
    width: 200px;
}

.statisticks .statisticks__header-title {
    font-size: 22px;
}

.statisticks  .statisticks__block-fix-width .select-stat {
    width: 300px;
}

.statisticks__block {
    position: relative;
    border-radius: 10px;
    box-shadow: 0px, 2px rgba(0, 0, 0, 0.04);
    background-color: white;
    width: 100%;
    padding: 15px;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06), 0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
}

.statisticks__block-fix-width {
    min-width: 350px;
}

.statisticks__block-fix-width .select2 {
    top: 80px;
    left: 25px;
}
.statisticks__main-total {
    height: 30%;
}
.statisticks__main-total .statisticks__main-total-title {
    margin-bottom: 11px;
}

.statisticks__main-total-title {
    font-size: 16px;
    line-height: 18px;
    font-weight: 600;
    font-family: SFProDisplay;
}
.statisticks__main-total-title_diagram {
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.statisticks__main-total-number {
    font-size: SFProDisplay;
    font-size: 20px;
    font-weight: 500;
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

.chart {
    height: 100%;
}
.anychart-credits {
    display: none;
}

.--green {
    color: #27AE60;
}

.--red {
    color: #EB5757;
}

.--orange {
    color:#F2994A;
}

.--blue {
    color: #2D9CDB;
}

.--fuschia {
    color: #F178B6;
}
</style>
