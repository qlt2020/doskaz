<template>
  <div class="about">
    <ViTop />
    <MainHeader />
    <div class="container">
      <div class="statisticks">
        <div class="statisticks__header row mb-4 align-items-center">
          <div
            class="statisticks__header-title statisticks__main-total-title col-6"
          >
            Статистика
          </div>
          <div class="col-6">
            <div class="d-flex justify-content-end align-items-center">
              <button class="button b_green" @click="exportAll" id="export_btn">
                <i class="fas fa-download" style="color:#fff"></i>
                Скачать
              </button>
            </div>
          </div>
        </div>
        <div class="statisticks__main" ref="to_pdf">
           <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-3 d-flex flex-column">
                        <div class="statisticks__main-total statisticks__block mb-3">
                            <div class="statisticks__main-total-title">
                                Доступные
                            </div>
                            <div class="statisticks__main-total-number --green">
                                {{objectsCount.fullAccessible}}
                            </div>

                        </div>
                        <div class="statisticks__main-total statisticks__block mb-3">
                            <div class="statisticks__main-total-title">
                                Частично доступные
                            </div>
                            <div class="statisticks__main-total-number --orange">
                                {{objectsCount.partialAccessible}}
                            </div>
                        </div>
                        <div class="statisticks__main-total statisticks__block">
                            <div class="statisticks__main-total-title">
                                Недоступные
                            </div>
                            <div class="statisticks__main-total-number --red">
                                {{objectsCount.notAccessible}}
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="statisticks__block statisticks__main-offer">
                            <div class="statisticks__block-head">
                                <div class="statisticks__block-head_title">
                                    Количество объектов в разрезе возможностей для 
                                </div>
                                <DropdownBlock 
                                    @input="changeCategory" 
                                    :options="groupPopulation.options" 
                                    :value="selectedCategoryObj"
                                    :top="'49px'"
                                />
                            </div>
                            <AnychartDoughnut
                                :statData="objectsStat"
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
                            <div class="statisticks__block-head">
                                <div class="statisticks__block-head_title">
                                    Количество пользователей в разрезе категорий
                                </div>
                            </div>
                            <AnychartDoughnut
                                :statData="usersStat.categories"
                                :totalData="usersStat.registered"
                                :categoryData="usersTitleList"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4" v-if="usersAge">
                <div class="statisticks__block statisticks__block-fix-width">
                    <div class="statisticks__block-head">
                        <div class="statisticks__block-head_title">
                            Количество объектов в разрезе возможностей для 
                        </div>
                        <DropdownBlock 
                            @input="changeCategory(arguments[0], true)"
                            :options="ageGroupPopulations.options" 
                            :value="selectedCategoryAge" 
                            class="mtop"
                        />
                    </div>
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
                <div class="statisticks__block-head" v-if="yearsComplaints.options.length">
                    <div class="statisticks__block-head_title">
                        Количество жалоб за год
                    </div>
                    <DropdownBlock
                        v-if="selectedComplaints"
                        :value="selectedComplaints"
                        :options="yearsComplaints.options"
                        @input="changeYear(arguments[0], 'filterComplaints', 'getComplaintsFilter')"
                    />
                </div>
                    <AnychartColumn
                        :statData="complaintsFiltered"
                        :fill="'#EB5757'"
                        :stroke="'#EB5757'"
                    />
                </div>
                <div class="statisticks__block">
                    <div class="statisticks__block-head" v-if="yearsFeedback.options.length">
                        <div class="statisticks__block-head_title">
                            Количество обращений за год
                        </div>
                        <DropdownBlock
                            v-if="selectedFeedback"
                            :value="selectedFeedback"
                            :options="yearsFeedback.options"
                            @input="changeYear(arguments[0], 'feedbackFilter', 'getFeedbackFilter')"
                        />
                    </div>
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
    </div>
    <MainFooter />
  </div>
</template>

<script>
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";
import MainFooter from "@/components/MainFooter";
import BackBtn from "@/components/BackBtn";
import { get } from "vuex-pathify";
import DropdownBlock from "@/components/DropdownBlock";

import AnychartColumn from '@/components/statistics/AnychartColumn.vue';
import AnychartDoughnut from '@/components/statistics/AnychartDoughnut.vue';
import {format} from "date-fns";
import { jsPDF } from "jspdf";

export default {
  name: "StatisticsTotal",
  components: {
    MainHeader,
    ViTop,
    MainFooter,
    BackBtn,
    DropdownBlock,
    AnychartColumn, 
    AnychartDoughnut
  },
  data() {
    return {
      newObjects:[],
      currentYear: null,
      selectedComplaints: null,
      selectedFeedback: null,
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
    };
  },
  async mounted() {
    console.log(html2pdf);
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
    complaintsCount: get('statistics/complaintsCount'),
    complaintsFiltered: get('statistics/complaintsFilteredStat'),
    complaintsStat: get('statistics/complaintsStat'),
    objectsStat: get('statistics/objectsStat'),
    feedbackStat: get('statistics/feedbackStat'),
    feedbackCount: get('statistics/feedbackCount'),
    feedbackFiltered: get('statistics/feedbackFilteredStat'),
    usersStat: get('statistics/usersStat'),
    groupPopulation:get('statistics/group'),
    usersAge: get('statistics/usersAge'),
    ageGroupPopulations() {
        const populations = {}
        populations.options = [...this.groupPopulation.options]
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
      const doc = new jsPDF({ unit: 'in', format: 'a3', orientation: 'l' });
      const div = this.$refs.to_pdf;
      console.log(div);
      doc.text(this.$refs.to_pdf, 1, 1);
      doc.save("two-by-four.pdf");
      // let date = format(new Date(), 'd.MM.yyyy')
      // html2pdf(this.$refs.to_pdf, {
      //     margin: 2,
      //     filename: `${date}_Статистика.pdf`,
      //     html2canvas: {
      //         letterRendering: true,
      //         ignoreElements: (el) => {return el.id === 'export_btn'}
      //     },
      //     doc
      // })

      // let element = this.$refs.to_pdf
      // let opt = {
      //   filename: 'grid.pdf',
      //   margin:   10,
      //   html2canvas: {
      //       letterRendering: true,
      //       ignoreElements: (el) => {return el.id === 'export_btn'}
      //   },
      //   jsPDF:    {
      //     format: 'a4',
      //     orientation: 'landscape'
      //   }
      // }
      // html2pdf().set(opt).from(element).save();

    },


  },
};
</script>

<style lang="scss" scoped>
@import "@/styles/mixins.scss";
@import "bootstrap/dist/css/bootstrap.css";
@import "bootstrap-vue/dist/bootstrap-vue.css";

.statisticks {
    padding: 40px 0;
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

.statisticks .statisticks__header-title {
    font-size: 22px;
}

.statisticks  .statisticks__block-fix-width .select-stat {
    width: 300px;
}

.statisticks .button {
  border-radius: 10px;
  height: 54px;
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

.statisticks__block-head {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}
.statisticks__block-head_title {
    font-family: SFProDisplay;
    font-weight: 500;
    font-size: 17px;
    margin-right: 10px;
}

.statisticks__block-fix-width {
    min-width: 350px;
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
