<template>
  <div class="city-list">
    <div class="head">
      <span>{{ $t("index.selectCity") }}</span>
      <!-- <button class="btn btn_blue" @click="confirm">
        <span>Подтвердить</span>
      </button> -->
      <div class="city-list-close" @click="confirm">
        <svg
          width="13"
          height="13"
          viewBox="0 0 13 13"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M1 1L6.5 6.5M12 12L6.5 6.5M6.5 6.5L12 1L1.32353 11.6765"
            stroke="black"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </div>
    </div>
    <div class="list">
      <span
        v-for="city in cities"
        :key="city.id"
        @click="select(city)"
        :class="{ selected: selectedCity === city.id }"
      >
        {{ city.name }}
        <i v-show="selectedCity === city.id" class="fas fa-check"></i>
      </span>
    </div>
  </div>
</template>

<script>
import { call, get } from "vuex-pathify";
export default {
  computed: {
    selectedCity: get("settings/cityId"),
    cities: get("cities/list"),
  },
  methods: {
    select(city) {
      this.selectCity(city.id);
    },
    selectCity: call("settings/select"),
    confirm() {
      this.$emit("close");
    },
  },
};
</script>

<style lang="scss" scoped>
.city-list {
  max-height: calc(100% - 80px);
  width: 370px;
  .head {
    position: relative;
    display: flex;
    justify-content: space-between;
    width: inherit;
    background: white;
    padding: 10px 20px;
    border-radius: 10px 10px 0px 0px;
    align-items: center;
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);
    span {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      font-size: 18px;
    }
    .btn {
      span {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 600;
        font-size: 14px;
      }
    }
    .city-list-close {
      /* position: absolute; */
      /* right: -25px; */
      /* top: 10px; */
      /* background: white; */
      width: 25px;
      height: 50px;
      display: flex;
      align-items: center;
      border-radius: 0px 10px 10px 0px;
      justify-content: center;
      cursor: pointer;
    }
  }
  .list {
    display: flex;
    flex-direction: column;
    overflow-x: hidden;
    overflow-y: auto;
    max-height: inherit;
    background: white;
    border-radius: 0px 0px 10px 10px;
    padding: 15px 0;

    &::-webkit-scrollbar {
      width: 8px;
    }

    &::-webkit-scrollbar-track {
      background: #efefef;
      border-radius: 10px;
    }

    &::-webkit-scrollbar-thumb {
      background: transparentize(#d1e0ea, 0.5);
    }
    span {
      padding: 10px 25px;
      &.selected {
        background: rgba(0, 141, 222, 0.16078);
        font-weight: 500;
        i {
          font-size: 14px;
          color: #000;
        }
      }
      &:hover {
        cursor: pointer;
        font-weight: 500;
        background: rgba(0, 141, 222, 0.16078);
      }
      &:nth-child(19) {
        border-bottom: 2px #a5a5a5 solid;
        padding-bottom: 20px;
      }
    }
  }
}
</style>
