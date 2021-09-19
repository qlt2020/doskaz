<template>
  <div class="statisticks">
      <div class="statisticks__header row mb-4 align-items-center">
          <div class="statisticks__header-title statisticks__main-total-title col-3">
              По доступности объектов
          </div>
          <div class="col-9">
              <div class="d-flex justify-content-end align-items-center">
                  <span class="btn btn-info d-lg-block m-l-15" @click="exportList">
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
            <SelectCategory
              :options="categories"
              :value="selectedCategory"
              :allOptions="true"
              @input="changeCategory(arguments[0])"
            />
            <div v-if="subCategoriesVisible">
              <SelectCategory
                v-if="subCategoriesVisible"
                :options="subCategoriesOptions.subCategories"
                :value="selectedSubCategory"
                @input="changeSubcategory(arguments[0])"
              />
            </div>

          <button class="btn btn-danger d-lg-block mr2" @click="resetFilter">
            <i class="fas fa-times"></i>
              Сбросить
          </button>

        </div>
        <b-table-simple hover small caption-top responsive sticky-header="70vh">
          <b-thead head-variant="light" >
            <b-tr v-if="usersTitleList.options" sticky-header>
              <b-th colspan="1">№</b-th>
              <b-th colspan="1">Объект</b-th>
              <b-th colspan="1">Общее</b-th>
              <b-th colspan="3">Для всех групп</b-th>
              <b-th colspan="3" v-for="categories in usersTitleList.options" :key="categories" class="text-nowrap">
                {{categories}}
              </b-th>
            </b-tr>
            <b-tr v-if="objectsStat">
              <b-th></b-th>
              <b-th></b-th>
              <b-th></b-th>
              <b-th class="statisticks__table-subtitle --green">Доступно</b-th>
              <b-th class="statisticks__table-subtitle --orange text-nowrap">Частично доступно</b-th>
              <b-th class="statisticks__table-subtitle --red">Недоступно</b-th>
              <template v-for="(categories, key, index) in usersTitleList.options">
                <b-th class="statisticks__table-subtitle --green" :key="categories + key + index + 1">Доступно</b-th>
                <b-th class="statisticks__table-subtitle --orange text-nowrap" :key="categories + key + index + 4">Частично доступно</b-th>
                <b-th class="statisticks__table-subtitle --red" :key="categories + key + index + 25">Недоступно</b-th>
              </template>
            </b-tr>
          </b-thead>
          <template v-if="objectsStat.length > 1">
          </template>
          <b-tbody head-variant="light" sticky-header v-if="objectsStat.length > 1">
            <template  v-for="(item, index) in objectsStat" >
              <b-tr :key="item.category_title + index">
                <b-th :key="item.category_title + index +6" class="statistics_table_stroke">{{index}}</b-th>
                <b-th :key="item.category_title + index + 5" class="statistics_table_stroke">{{item.category_title}}</b-th>
                <b-th :key="item.category_title + index + 4" class="statistics_table_stroke">{{item.objects_total_count}}</b-th>
                <b-th :key="item.category_title + index + 3" class="statistics_table_stroke">{{item.objects_total_full_accessible}}</b-th>
                <b-th :key="item.category_title + index + 2" class="statistics_table_stroke">{{item.objects_total_partial_accessible}}</b-th>
                <b-th :key="item.category_title + index + 1" class="statistics_table_stroke">{{item.objects_total_not_accessible}}</b-th>

                <template v-for="(group, groupName, index) in usersTitleList.options">
                  <b-td class="--green" :key="groupName + index + item.category_id+1">{{item[`${groupName}_full_accessible`] }}</b-td>
                  <b-td class="--orange" :key="groupName + index + item.category_id+2">{{item[`${groupName}_partial_accessible`] }}</b-td>
                  <b-td class="--red" :key="groupName + index + item.category_id+4">{{item[`${groupName}_not_accessible`] }}</b-td>
                </template>
              </b-tr>

              <template  v-for="(subcategory, subcategoryIndex) in item.subcategory" >
                <b-tr :key="item.category_title + (subcategoryIndex + index * subcategoryIndex* subcategoryIndex) ">
                  <b-th :key="subcategory.sub_category_id + 17 *4" class="statistics_table_stroke-subcategoria">{{index}}.{{subcategoryIndex + 1}}</b-th>
                  <b-th :key="subcategory.sub_category_id + 19 *5" class="statistics_table_stroke-subcategoria">{{subcategory.sub_category_title}}</b-th>
                  <b-th :key="subcategory.sub_category_id + 19 *15" class="statistics_table_stroke-subcategoria">{{subcategory.objects_total_count}}</b-th>
                  <b-th :key="subcategory.sub_category_id + 19 *16" class="statistics_table_stroke-subcategoria">{{subcategory.objects_total_full_accessible}}</b-th>
                  <b-th :key="subcategory.sub_category_id + 19 *17" class="statistics_table_stroke-subcategoria">{{subcategory.objects_total_partial_accessible}}</b-th>
                  <b-th :key="subcategory.sub_category_id + 19 *18" class="statistics_table_stroke-subcategoria">{{subcategory.objects_total_not_accessible}}</b-th>
                  <template v-for="(group, groupName, index) in usersTitleList.options">
                    <b-td class="--green" :key="group + index + groupName + (subcategory.sub_category_id+11)">{{subcategory[`${groupName}_full_accessible`] }}</b-td>
                    <b-td class="--orange" :key="group + index + groupName + (subcategory.sub_category_id+13)">{{subcategory[`${groupName}_partial_accessible`] }}</b-td>
                    <b-td class="--red" :key="group + index + groupName + (subcategory.sub_category_id+15)">{{subcategory[`${groupName}_not_accessible`] }}</b-td>
                  </template>
                </b-tr>
              </template>
            </template>
          </b-tbody>
        </b-table-simple>
      </div>

  </div>
</template>

<script>
    import Select from "@/components/statisticks/Select";
    import SelectCategory from "@/components/statisticks/SelectCategory";
    import {get} from 'vuex-pathify';

    export default {
        components: {
            Select, SelectCategory
        },
        data() {
            return {
              usersTitleList: {
                options: {
                  movement: 'Люди передвигающиеся на кресло коляске',
                  vision: 'Люди с инвалидностью по зрению',
                  limb: 'Люди с нарушениями опорно-двигательного аппарата',
                  hearing: 'Люди с инвалидностью по слуху',
                  temporal: 'Временно травмированные люди',
                  babyCarriage: 'Люди с детскими колясками',
                  missingLimbs: 'Люди с отсутствующими конечностями',
                  pregnant: 'Беременные женщины',
                  intellectual: 'Люди с интеллектуальной инвалидностью',
                  agedPeople: 'Пожилые люди',
                  kids: 'Семьи с детьми до семи лет',
                }
              },
              cityList: {options:[{value: 'all', title: 'Весь Казахстан'}]},
              countryData: true,
              selectedCity: 'all',
              selectedCategory: 'all',
              selectedSubCategory: 'all',
              subCategoriesOptions: {},
              subCategoriesVisible: false
            }
        },
        async mounted() {
          await this.$store.dispatch('statisticks/getObjectsStatTable');
          await this.$store.dispatch('cities/load');
          this.$store.dispatch('objectCategories/load'),

          await this.getCityList();
        },
        computed: {
          objectsStat: get('statisticks/getObjectsStatTable'),
          city: get('cities/items'),

          categories: get('objectCategories/items'),
        },
        methods: {
          changeCategory(value) {
            if (value === 'all' || value === null) {
              this.subCategoriesVisible = false;
              this.$store.commit('statisticks/filterObjectsReset');
              this.selectedCity = 'all';
              this.selectedSubCategory = 'all';
            } else {
              this.$store.commit('statisticks/filterObjectsReset');
              this.selectedCity = 'all';
              this.$store.commit('statisticks/filterObjectsStat', {'field': 'main_category_id', 'value': value});
              this.getSubCategories(value);
            }
            this.selectedCategory = value;
            this.$store.dispatch('statisticks/getObjectsStatTable');
          },
          changeSubcategory(value) {
            if (value === 'all' || value === null) {
              this.$store.commit('statisticks/filterObjectsReset');
              this.selectedCity = 'all';
              this.selectedCategory = 'all';
              this.subCategoriesVisible = false

            } else {
              this.$store.commit('statisticks/filterObjectsStat', {'field': 'category_id', 'value': value});
            }
            this.$store.dispatch('statisticks/getObjectsStatTable');
          },
          async getSubCategories(value) {
            this.subCategoriesOptions = await this.categories.find(cat => cat.id == value);
            this.subCategoriesVisible = true;
          },
          changeCity(value) {
            if (value === 'all') {
              this.subCategoriesVisible = false
              this.selectedCategory = 'all';
              this.$store.commit('statisticks/filterObjectsReset');

            } else {
              this.$store.commit('statisticks/filterObjectsStat', {'field': 'city_id', 'value': value});
              this.subCategoriesVisible = false
            }
            this.selectedCity = value
            this.$store.dispatch('statisticks/getObjectsStatTable');
          },
          resetFilter() {
            this.$store.commit('statisticks/filterObjectsReset');
            this.$store.dispatch('statisticks/getObjectsStatTable');
            this.subCategoriesVisible = false
            this.selectedCity = 'all'
            this.selectedCategory = 'all',
            this.selectedSubCategory = 'all';
          },
          async getCityList() {
            let cities = await this.city
            cities.forEach(city => {
              this.cityList.options.push({'value': city.id, 'title': city.name})
            });
          },
          async exportList() {
              window.open('/api/admin/objects/statistic/export/excel', '_blank')
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

.statisticks .statistics_table_stroke-subcategoria {
  font-weight: 400;
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


.statisticks .statisticks__main .--green {
  color: #219653 !important;
}

.statisticks .statisticks__main .--orange {
    color:  #F2994A !important;
}

.statisticks .statisticks__main .--red {
    color: #EB5757 !important;
}
</style>
