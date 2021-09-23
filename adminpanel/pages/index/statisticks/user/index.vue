<template>
  <div class="statisticks">
      <div class="statisticks__header row mb-4 align-items-center">
          <div class="statisticks__header-title statisticks__main-total-title col-3">
              По пользователям
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
              :options="cities"
              @input="changeCity(arguments[0])"
            />            
            <Select
              :allOptions="true"
              :optionsTypeObj="true"
              :value="selectedCategory"
              :options="usersTitleList"
              @input="changeCategory(arguments[0])"
            />
          <button class="btn btn-danger d-lg-block mr2" @click="resetFilter">
            <i class="fas fa-times"></i>
              Сбросить
          </button>
        </div>
        <b-table-simple hover small caption-top responsive sticky-header="70vh" :no-border-collapse="true" class="table">
          <b-thead head-variant="light" class="table_header">
            <b-tr v-if="usersList[0]" sticky-header>
              <b-th colspan="1" :stickyColumn=true>№</b-th>
              <b-th colspan="1" :stickyColumn=true>Город</b-th>
              <b-th colspan="4" v-if="selectedCategory === 'all'">Все</b-th>
              <b-th colspan="4" v-for="categories in usersList[0].categories" :key="categories.name" class="text-nowrap">
                {{  usersTitleList.options[categories.name] }}
              </b-th>
            </b-tr>
            <b-tr v-if="usersList[0]">
              <b-th :stickyColumn=true></b-th>
              <b-th :stickyColumn=true></b-th>
              <template v-for="(categories, key, index) in usersList[0].categories">
                <b-th class="statisticks__table-subtitle" :key="categories + key + index + 0">Общее</b-th>
                <b-th class="statisticks__table-subtitle --blue" :key="categories + key + index + 1">Мужчины</b-th>
                <b-th class="statisticks__table-subtitle --fuschia" :key="categories + key + index + 2">Женщины</b-th>
                <b-th class="statisticks__table-subtitle text-nowrap --gray" :key="categories + key + index + 3">Не определено</b-th>
              </template>
              <template v-if="selectedCategory === 'all'">
                <b-th class="statisticks__table-subtitle">Общее</b-th>
                <b-th class="statisticks__table-subtitle --blue">Мужчины</b-th>
                <b-th class="statisticks__table-subtitle --fuschia">Женщины</b-th>
                <b-th class="statisticks__table-subtitle text-nowrap --gray">Не определено</b-th>
              </template>
            </b-tr>
          </b-thead>
          <b-tbody head-variant="light" v-if="usersList">
            <b-tr v-for="(item, index) in usersList" :key="item.name">
              <b-th class="statistics_table_stroke" :stickyColumn=true>{{index}}</b-th>
              <b-th class="statistics_table_stroke" :stickyColumn=true>{{item.name}}</b-th>
              <template v-if="selectedCategory === 'all'">
                <b-td>{{item.total.total}}</b-td>
                <b-td class="--blue">{{item.total.men}}</b-td>
                <b-td class="--fuschia">{{item.total.women}}</b-td>
                <b-td class="--gray">{{item.total.unknown}}</b-td>
              </template>
              <template v-for="(categoria, key, index) in item.categories">
                <b-td :key="key + index + 0">{{categoria.total}}</b-td>
                <b-td :key="key + index + 1" class="--blue">{{categoria.men}}</b-td>
                <b-td :key="key + index + 2" class="--fuschia">{{categoria.women}}</b-td>
                <b-td :key="key + index+ 3" class="--gray">{{categoria.unknown}}</b-td>
              </template>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </div>

  </div>
</template>

<script>
    import Select from "@/components/statisticks/Select";
    import {get} from 'vuex-pathify';

    export default {
        components: {
            Select
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
                  withChild: 'Семьи с детьми до семи лет',
                  undefined: 'Категория не выбрана',
                }
              },
              countryData: true,
              selectedCity: 0,
              selectedCategory: 0
            }
        },
        async mounted() {
          await this.$store.dispatch('statisticks/usersList');
          await this.$store.dispatch('cities/load');
        },
        computed: {
          usersList: get('statisticks/usersList'),
          city: get('cities/items'),
          cities() {
            let self = {options:[{value: 0, title: 'Весь Казахстан'}]};
            this.city.forEach(c => {
              self.options.push({'value': c.id, 'title': c.name})
            });
            return self
          },
        },
        methods: {
          changeCategory(value) {
            if (value == 0) {
              this.$store.commit('statisticks/filterUsersList', {'field': 'category', 'value': 'all'});
            } else {
              this.$store.commit('statisticks/filterUsersList', {'field': 'category', 'value': value});
            }
            this.$store.dispatch('statisticks/usersList');
            this.selectedCategory = value
          },
          changeCity(value) {
            this.$store.commit('statisticks/filterUsersList', {'field': 'city_id', 'value': value});
            this.selectedCity = value
            this.$store.dispatch('statisticks/usersList');
          },
          resetFilter() {
            this.$store.commit('statisticks/filterUsersListReset');
            this.$store.dispatch('statisticks/usersList');
            this.selectedCity = 0
            this.selectedCategory = 0
          },
          async exportList() {
            window.open(`/api/user/statistics/export/excel?city_id=${this.selectedCity}&category=${this.selectedCategory == 0 ? 'all' : this.selectedCategory}`, '_blank')
          }
        }
    }
</script>

<style>

.statisticks .table .table_header {
  position: sticky;
  top: 0;
  z-index: 99;
}

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