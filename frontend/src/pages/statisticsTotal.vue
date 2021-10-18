<template>
  <div class="about">
    <ViTop />
    <MainHeader v-if="!viModeEnabled"/>
    <div class="container">
    <loading :is-full-page="true" :active="!isLoading" />
      <div class="statisticks">
        <div class="statisticks__header row mb-4 align-items-center">
          <div
            class="statisticks__header-title statisticks__main-total-title col-md-6 col-9"
          >
            <img
                :src="require('@/assets/icons/back-arrow.svg')"
                @click="$router.back()"
                class="title-back_arrow"
            />
            {{$t('statistics.statistics')}}
          </div>
          <div class="col-md-6  col-3">
            <div class="d-flex justify-content-end align-items-center">
              <button class="button b_green" @click="exportAll" id="export_btn">
                <i class="fas fa-download" style="color:#fff"></i>
                <span 
                    :class="
                      !viModeEnabled
                        ? 'button-white'
                        : ''
                    "
                >
                    {{$t('objects.download')}}
                </span>
              </button>
            </div>
          </div>
        </div>

        <client-only>
            <div class="statisticks__main" ref="to_pdf" :class="{to_print: prepToPdf}">
                <div class="row statisticks__main-head">
                    <div class="col-12 col-lg-8">
                        <div class="row">
                            <div class="col-md-3 statisticks__main-count d-flex">
                                <div class="statisticks__main-total statisticks__block mb-3">
                                    <div class="statisticks__main-total-title">
                                        {{$t('statistics.statisticsTotal.accessible')}}
                                    </div>
                                    <div 
                                        class="statisticks__main-total-number statistics-total-number"
                                        :class="!viModeEnabled ?  '--green' : ''"
                                        >
                                        {{objectsCount.fullAccessible}}
                                    </div>

                                </div>
                                <div class="statisticks__main-total statisticks__block mb-3">
                                    <div class="statisticks__main-total-title">
                                        {{$t('statistics.statisticsTotal.partialAccessible')}}
                                    </div>
                                    <div 
                                        class="statisticks__main-total-number"
                                        :class="!viModeEnabled ?  '--orange' : ''"
                                        >
                                        {{objectsCount.partialAccessible}}
                                    </div>
                                </div>
                                <div class="statisticks__main-total statisticks__block">
                                    <div class="statisticks__main-total-title">
                                        {{$t('statistics.statisticsTotal.notAccessible')}}
                                    </div>
                                    <div 
                                        class="statisticks__main-total-number"
                                        :class="!viModeEnabled ? '--red' : ''"
                                        >
                                        {{objectsCount.notAccessible}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="statisticks__block statisticks__main-offer">
                                    <div class="statisticks__block-head">
                                        <div class="statisticks__block-head_title">
                                            {{$t('statistics.statisticsTotal.objectsTotal')}}
                                        </div>
                                        <DropdownBlock 
                                            :toPrint="prepToPdf"
                                            @input="changeCategory" 
                                            :options="groupPopulation.options" 
                                            :value="selectedCategoryObj"
                                            :top="'49px'"
                                        />
                                    </div>
                                    <AnychartDoughnut
                                        v-if="!viModeEnabled"
                                        style="height: 270px"
                                        :statData="objectsStat"
                                        :selectedCategory="selectedCategoryObj"
                                        :rowPie="rowPieTabled"
                                        :legendPosition="legendPositionTabled"
                                        :legendWidth="this.legendWidth"
                                        :legendHeight="this.legendHeight"
                                    />
                                    <div class="statisticks__impaired" v-if="viModeEnabled">
                                        <div 
                                            class="statisticks__impaired-item"
                                            v-for="item in objectsStat" 
                                            :key="item.title"
                                        >
                                            <div class="statisticks__impaired-title">
                                                {{item.category_title}}
                                            </div>
                                            <div class="statisticks__impaired-value">
                                                {{item[`${selectedCategoryObj}Total`]}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 mrtop">
                            <div class="col-md-3 d-flex statisticks__main-count">
                                <div class="statisticks__main-total statisticks__block mb-3">
                                    <div class="statisticks__main-total-title statisticks__main-total-title_diagram">
                                        {{$t('statistics.statisticsTotal.usersCount')}}
                                    </div>
                                    <div 
                                        class="statisticks__main-total-number" 
                                        :class="!viModeEnabled ?  '--green' : ''"
                                    >
                                        {{usersStat.registered}}
                                    </div>
                                </div>
                                <div class="statisticks__main-total statisticks__block mb-3">
                                    <div class="statisticks__main-total-title">
                                        {{$t('statistics.statisticsTotal.mensTotal')}}
                                    </div>
                                    <div 
                                        class="statisticks__main-total-number" 
                                        :class="!viModeEnabled ?  '--blue' : ''"
                                    >
                                        {{usersStat.men}}
                                    </div>
                                </div>
                                <div class="statisticks__main-total statisticks__block">
                                    <div class="statisticks__main-total-title">
                                        {{$t('statistics.statisticsTotal.womenTotal')}}
                                    </div>
                                    <div 
                                        class="statisticks__main-total-number"
                                        :class="!viModeEnabled ?  '--fuschia' : ''"
                                    >
                                    {{usersStat.women}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="statisticks__block statisticks__main-offer statisticks__main-offer-two">
                                    <div class="statisticks__block-head">
                                        <div class="statisticks__block-head_title">
                                            {{$t('statistics.statisticsTotal.usersTotal')}}
                                        </div>
                                    </div>
                                    <AnychartDoughnut
                                        v-if="!viModeEnabled"
                                        :statData="usersStat.categories"
                                        :totalData="usersStat.registered"
                                        :categoryData="usersTitleList"
                                        :rowPie="rowPieTabled"
                                        :legendPosition="legendPositionTabled"
                                        :legendWidth="this.legendWidth"
                                        :legendHeight="this.legendHeight"
                                    />
                                    <div class="statisticks__impaired" v-if="viModeEnabled">
                                        <div 
                                            class="statisticks__impaired-item"
                                            v-for="(item, index) in usersStat.categories"
                                            :key="item.title"
                                        >
                                            <div class="statisticks__impaired-title">
                                                {{$t(`statistics.statisticsTotal.${index}`)}}
                                            </div>
                                            <div class="statisticks__impaired-value">
                                                {{item}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mrtop" v-if="usersAge">
                        <div class="statisticks__block statisticks__block-fix-width">
                            <div class="statisticks__block-head">
                                <div class="statisticks__block-head_title">
                                    {{$t('statistics.statisticsTotal.usersAge')}}
                                </div>
                                <DropdownBlock 
                                    :toPrint="prepToPdf"
                                    @input="changeCategory(arguments[0], true)"
                                    :options="ageGroupPopulations.options" 
                                    :value="selectedCategoryAge" 
                                    class="mtop"
                                />
                            </div>
                            <AnychartDoughnut
                                v-if="!viModeEnabled"
                                :statData="usersAge"
                                :categoryData="usersAgeList"
                                :selectedCategory="selectedCategoryObj"
                                :totalData="usersStat.registered"
                                :rowPie="rowPie"
                                :legendPosition="legendPosition"
                                :legendWidth="this.legendWidth"
                                :legendHeight="this.legendHeight"
                            />
                            <div class="statisticks__impaired" v-if="viModeEnabled">
                                <div 
                                    class="statisticks__impaired-item"
                                    v-for="(item, index) in usersAge"
                                    :key="item + index"
                                >
                                    <div class="statisticks__impaired-title">
                                        {{$t(`statistics.statisticsTotal.${index}`)}}
                                    </div>
                                    <div class="statisticks__impaired-value">
                                        {{item}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row statisticks__main-row mt-5" style="height: 320px">
                    <div class="statisticks__main-feedback col-lg-2 d-flex justify-content-start">
                        <div class="statisticks__main-feedback_total statisticks__main-total statisticks__block mb-3">
                            <div class="statisticks__main-total-title">
                                {{$t('statistics.statisticsTotal.complaintsTotal')}}
                            </div>
                            <div 
                                class="statisticks__main-total-number" 
                                :class="!viModeEnabled ?  '--red' : ''"
                                v-if="complaintsCount"
                            >
                                {{complaintsCount.count}}
                            </div>
                        </div>
                        <div class="statisticks__main-feedback_total statisticks__main-total statisticks__block">
                            <div class="statisticks__main-total-title">
                                {{$t('statistics.statisticsTotal.feedbackTotal')}}
                            </div>
                            <div 
                                class="statisticks__main-total-number" 
                                :class="!viModeEnabled ?  '--green' : ''"
                                v-if="feedbackCount"
                            >
                                {{feedbackCount.count}}
                            </div>
                        </div>
                    </div>
                    <div class="statisticks__main-feedback-graph col-lg-10 d-flex justify-content-between" >
                        <div
                            style="margin-right: 20px"
                            class="statisticks__block"
                        >
                        <div 
                            class="statisticks__block-head" 
                            v-if="yearsComplaints.options.length"
                        >
                            <div class="statisticks__block-head_title">
                                {{$t('statistics.statisticsTotal.complaintsYears')}}
                            </div>
                            <DropdownBlock
                                :toPrint="prepToPdf"
                                v-if="selectedComplaints"
                                :value="selectedComplaints"
                                :options="yearsComplaints.options"
                                @input="changeYear(arguments[0], 'filterComplaints', 'getComplaintsFilter')"
                            />
                        </div>
                            <AnychartColumn
                                v-if="!viModeEnabled"
                                :statData="complaintsFiltered"
                                :fill="'#EB5757'"
                                :stroke="'#EB5757'"
                                :month="month"
                            />
                            <div class="statisticks__impaired" v-if="viModeEnabled">
                                <div 
                                    class="statisticks__impaired-item"
                                    v-for="(item, index) in complaintsFiltered"
                                    :key="item.id + index"
                                >
                                    <div class="statisticks__impaired-title">
                                        {{month[item.id]}}
                                    </div>
                                    <div class="statisticks__impaired-value">
                                        {{item.count}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="statisticks__block">
                            <div class="statisticks__block-head" v-if="yearsFeedback.options.length">
                                <div class="statisticks__block-head_title">
                                    {{$t('statistics.statisticsTotal.feedbackYears')}}
                                </div>
                                <DropdownBlock
                                    :toPrint="prepToPdf"
                                    v-if="selectedFeedback"
                                    :value="selectedFeedback"
                                    :options="yearsFeedback.options"
                                    @input="changeYear(arguments[0], 'feedbackFilter', 'getFeedbackFilter')"
                                />
                            </div>
                            <AnychartColumn
                                v-if="!viModeEnabled"
                                :statData="feedbackFiltered"
                                :fill="'#27AE60'"
                                :stroke="'#27AE60'"
                                :title="'Количество обращений за год'"
                                :month="month"
                            />
                            <div class="statisticks__impaired" v-if="viModeEnabled">
                                <div 
                                    class="statisticks__impaired-item"
                                    v-for="(item, index) in feedbackFiltered"
                                    :key="item.id + index"
                                >
                                    <div class="statisticks__impaired-title">
                                        {{month[item.id]}}
                                    </div>
                                    <div class="statisticks__impaired-value">
                                        {{item.count}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </client-only>

      </div>
    </div>
    <MainFooter />
  </div>
</template>

<script>
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";
import MainFooter from "@/components/MainFooter";
import { get } from "vuex-pathify";
import DropdownBlock from "@/components/DropdownBlock";
import AnychartColumn from '@/components/statistics/AnychartColumn.vue';
import AnychartDoughnut from '@/components/statistics/AnychartDoughnut.vue';
import {format} from "date-fns";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";


export default {
  name: "StatisticsTotal",
  ssr: false,
  components: {
    MainHeader,
    ViTop,
    MainFooter,
    DropdownBlock,
    AnychartColumn, 
    AnychartDoughnut,
    Loading
  },
  data() {
    return {
      rowPie: true,
      rowPieTabled: false, 
      newObjects:[],
      currentYear: null,
      prepToPdf: false,
      selectedComplaints: null,
      selectedFeedback: null,
      selectedCategoryObj: 'kids',
      selectedCategoryAge: null,
      legendPositionTabled: 'right',
      legendPosition: 'bottom',
      legendWidth: "40%",
      legendHeight: "200px",
      users: {
          kids_full_accessible: null,
          movement_full_accessible: null
      },
      usersAgeList:
          [
              {value: 'from_18_to_23', title: this.$t('statistics.statisticsTotal.from_18_to_23')},
              {value: 'from_23_to_28', title: this.$t('statistics.statisticsTotal.from_23_to_28')},
              {value: 'from_28_to_33', title: this.$t('statistics.statisticsTotal.from_28_to_33')},
              {value: 'from_33_to_38', title: this.$t('statistics.statisticsTotal.from_33_to_38')},
              {value: 'from_38_to_43', title: this.$t('statistics.statisticsTotal.from_38_to_43')},
              {value: 'from_43_to_50', title: this.$t('statistics.statisticsTotal.from_43_to_50')},
              {value: 'from_50_to_100', title: this.$t('statistics.statisticsTotal.from_50_to_100')},
          ],
      usersTitleList: [
          {value: 'withchild', title: this.$t('statistics.statisticsTotal.withchild')},
          {value: 'movement', title: this.$t('statistics.statisticsTotal.movement')},
          {value: 'babycarriage', title: this.$t('statistics.statisticsTotal.babycarriage')},
          {value: 'vision', title: this.$t('statistics.statisticsTotal.vision')},
          {value: 'limb', title: this.$t('statistics.statisticsTotal.limb')},
          {value: 'temporal', title: this.$t('statistics.statisticsTotal.temporal')},
          {value: 'missinglimbs', title: this.$t('statistics.statisticsTotal.missinglimbs')},
          {value: 'pregnant', title: this.$t('statistics.statisticsTotal.pregnant')},
          {value: 'agedpeople', title: this.$t('statistics.statisticsTotal.agedpeople')},
          {value: 'hearing', title: this.$t('statistics.statisticsTotal.hearing')},
          {value: 'intellectual', title: this.$t('statistics.statisticsTotal.intellectual')},
          {value: 'undefined', title: this.$t('statistics.statisticsTotal.undefined')},
          ],
      yearsComplaints: {options:[]},
      yearsFeedback: {options:[]},
      groupPopulation: {
        options:[
            {value: 'kids', title: this.$t('statistics.statisticsTotal.withchild')},
            {value: 'movement', title: this.$t('statistics.statisticsTotal.movement')},
            {value: 'babyCarriage', title: this.$t('statistics.statisticsTotal.babycarriage')},
            {value: 'vision', title: this.$t('statistics.statisticsTotal.vision')},
            {value: 'limb', title: this.$t('statistics.statisticsTotal.limb')},
            {value: 'temporal', title: this.$t('statistics.statisticsTotal.temporal')},
            {value: 'missingLimbs', title: this.$t('statistics.statisticsTotal.missinglimbs')},
            {value: 'pregnant', title: this.$t('statistics.statisticsTotal.pregnant')},
            {value: 'agedPeople', title: this.$t('statistics.statisticsTotal.agedpeople')},
            {value: 'hearing', title: this.$t('statistics.statisticsTotal.hearing')},
            {value: 'intellectual', title: this.$t('statistics.statisticsTotal.intellectual')},
        ],
      },
        month: {
          1: this.$t('statistics.statisticsTotal.january'),
          2: this.$t('statistics.statisticsTotal.february'),
          3: this.$t('statistics.statisticsTotal.march'),
          4: this.$t('statistics.statisticsTotal.april'),
          5: this.$t('statistics.statisticsTotal.may'),
          6: this.$t('statistics.statisticsTotal.june'),
          7: this.$t('statistics.statisticsTotal.july'),
          8: this.$t('statistics.statisticsTotal.august'),
          9: this.$t('statistics.statisticsTotal.september'),
          10: this.$t('statistics.statisticsTotal.october'),
          11: this.$t('statistics.statisticsTotal.november'),
          12: this.$t('statistics.statisticsTotal.december'),
        }
    };
  },
  async mounted() {
    if(document.documentElement.clientWidth <= 991){
        this.rowPie = false
        this.legendPosition = "right"
    }
    if(document.documentElement.clientWidth <= 475){
        this.rowPieTabled = true
        this.rowPie = true
        this.legendPosition = "bottom"
        this.legendPositionTabled = "bottom"
        this.legendWidth = "100%"
        this.legendHeight = "100px"
    }

    await this.$store.dispatch('statistics/loadComplaints');
    await this.$store.dispatch('statistics/loadFeedback');
    await this.yearsCount(true);
    this.$store.dispatch('statistics/getComplaintsFilter');
    await this.$store.dispatch('statistics/getObjectsStat');
    this.$store.dispatch('statistics/getFeedbackFilter');
    await this.yearsCount();
    this.$store.dispatch('statistics/getUsersStat');
    await this.$store.dispatch('statistics/getUsersAge');
  },
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
    isLoading: get('statistics/isLoading'),
    complaintsCount: get('statistics/complaintsCount'),
    complaintsFiltered: get('statistics/complaintsFilteredStat'),
    complaintsStat: get('statistics/complaintsStat'),
    objectsStat: get('statistics/objectsStat'),
    feedbackStat: get('statistics/feedbackStat'),
    feedbackCount: get('statistics/feedbackCount'),
    feedbackFiltered: get('statistics/feedbackFilteredStat'),
    usersStat: get('statistics/usersStat'),
    usersAge: get('statistics/usersAge'),
    ageGroupPopulations() {
        const populations = {}
        populations.options = JSON.parse(JSON.stringify(this.groupPopulation.options))
        populations.options.push({value: null, title: 'Все группы'})
        populations.options.map(item => {
            if (item.value === 'kids') {
                item.value = 'withChild'
            }
            return item
        })
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
        this.selectedComplaints =  (new Date()).getFullYear();
        this.selectedFeedback =  (new Date()).getFullYear();
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
            if (mutation === 'filterComplaints') {
                this.selectedComplaints = value
            } else {
                this.selectedFeedback = value
            }
            this.$store.commit(`statistics/${mutation}`, {'field': 'year_id', 'value': value});
            this.$store.dispatch(`statistics/${action}`);
    },
    changeCategory(value, filterApi) {
        if (filterApi) {
            if (value === '') {
                this.selectedCategoryAge = null
                this.$store.commit('statistics/SET_USERS_AGE_FILTER', null);
            } else {
                this.selectedCategoryAge = value
                this.$store.commit('statistics/SET_USERS_AGE_FILTER', value);
            }
            this.$store.dispatch('statistics/getUsersAge');
        } else {
            this.selectedCategoryObj = value
        }
    },
    exportAll() {
        this.prepToPdf = true
        if(this.prepToPdf) {
            let html2pdf;
            import('html2pdf.js').then(pdfModule => {
                html2pdf = pdfModule.default
                let date = format(new Date(), 'd.MM.yyyy')
                html2pdf(this.$refs.to_pdf, {
                    margin: 2,
                    filename: `${date}_Статистика.pdf`,
                    html2canvas: {
                        letterRendering: true,
                        ignoreElements: (el) => {return el.id === 'export_btn'}
                    },
                    jsPDF: {
                        format: 'ledger',
                        orientation: 'p'
                    }
                })
            })
            setTimeout(() => {
                this.prepToPdf = false
            }, 500);
            
        }
        
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@/styles/mixins.scss";
@import "bootstrap/dist/css/bootstrap.css";
@import "bootstrap-vue/dist/bootstrap-vue.css";

.statisticks {
    padding: 40px 0 90px;
}
.statisticks__main {
    &.to_print {
            .statisticks__block {
                box-shadow: none;
            }
        }
}
.statisticks .height100 {
    height: 100%;
}

.statisticks .statisticks__main-offer .select-stat {
    width: 200px;
}

.statisticks .statisticks__main-offer {
    min-height: 350px;
}

.statisticks__main-feedback {
    flex-direction: column;
}

.statisticks__main-count {
    flex-direction: column;
}

.statisticks .statisticks__header-title {
    font-weight: 700;
    font-size: 28px;
    font-family: "SFProDisplay";
    margin: 0;
    color: #000000;
}

.statisticks  .statisticks__block-fix-width .select-stat {
    width: 300px;
}

.statisticks .button {
  border-radius: 10px;
  height: 54px;
}

.statisticks .button-white {
    color: #ffffff;
}

.statisticks__block {
    position: relative;
    border-radius: 10px;
    background-color: white;
    width: 100%;
    padding: 15px;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06), 0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
}

.statisticks__block-head {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 15px;
}
.statisticks__block-head_title {
    font-family: SFProDisplay;
    font-weight: 500;
    margin-bottom: 15px;
}

.statisticks__block-fix-width .statisticks__block-head {
    flex-direction: column;
}

.statisticks__block-fix-width .statisticks__block-head_title {
    margin-bottom: 5px;
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
    /* font-size: 20px; */
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

.--green {
    color: #27AE60;
    font-size: 20px;
}

.--red {
    color: #EB5757;
    font-size: 20px;
}

.--orange {
    color:#F2994A;
    font-size: 20px;
}

.--blue {
    color: #2D9CDB;
    font-size: 20px;
}

.--fuschia {
    color: #F178B6;
    font-size: 20px;
}

::v-deep .dropdown__block__selected {
    box-shadow: 0px 2px 2px 2px rgba(0, 0, 0, 0.05);
}

::v-deep .dropdown__block {
    min-width: 100%;
}

::v-deep .dropdown__block__list {
    min-width: 100%;
}

.statisticks__impaired {
    display: flex;
    flex-direction: column;
    &-item {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid;
        margin-bottom: 10px;
    }
    &-title {
        margin-right: 10px;
        font-weight: 500;
        word-break: break-all;
    }
    &-value {
        font-weight: 600;
    }
}

@media all and (max-width: 1024px) {
    .statisticks {
        padding: 90px 0 90px;
    }
}

@media all and (max-width: 991px) {
    .statisticks {
        padding: 90px 0 130px;
    }
    .mrtop {
        margin-top: 1.5rem;
    } 
    .statisticks__main-feedback {
        flex-direction: row;
    }
    .statisticks__main-feedback_total {
        margin-right: 20px;
        height: 80px;
    }
    .statisticks__main-feedback_total:last-child {
        margin-right: 0;
    }
    .statisticks__block-fix-width {
        min-height: 400px;
    }
}

@media all and (max-width: 767px) {
    .statisticks {
        padding: 90px 0 450px;
    }
    .statisticks__main-head .mrtop {
        margin-top: 3rem !important;
    }

    .statisticks .statisticks__main-offer {
        min-height: 500px;
    }

    .statisticks__main-count {
        flex-direction: row;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .statisticks__main-total  {
        height: 90px;
        margin-right: 10px;
        width: 160px;
    }
    .statisticks__main-total:last-child  {
        margin-right: 0;
    }
    .statisticks__main-feedback-graph {
        flex-direction: column;
    }
    .statisticks__main-feedback-graph .statisticks__block{
        margin-bottom: 10px;
    }

    .statisticks .statisticks__header-title {
        font-size: 22px;
    }
    .statisticks .button {
        width: 54px;
        span {
            display: none;
        }
    }
}

@media all and (max-width: 475px) {
    .statisticks__block-fix-width {
        min-height: 600px;
    }
    .statisticks .statisticks__main-offer {
        min-height: 450px;
    }

    .statisticks__block-fix-width {
        min-height: 450px;
    }

    .statisticks .statisticks__main-offer-two {
        min-height: 360px;
    }
}
</style>
