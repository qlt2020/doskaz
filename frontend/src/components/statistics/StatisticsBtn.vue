<template>
    <button 
      @click="$router.push(localePath({ name: page }))"
      class="stat_btn"
      :style="css"
      :class="classStyle"
    >
      <img v-if="page === 'statisticsAccess'" src="@/assets/icons/stat_diagram.svg" alt="stat_diagram">
      <img v-if="page === 'statisticsTotal'" src="@/assets/icons/stat_pie.svg" alt="stat_pie">
      <span style="text-align: start;">
        {{title}}
      </span>
    </button>
</template>

<script>
export default {
  name: "StatisticsBtn",
  props: {
    title: {
      type: String
    },
    page: {
      type: String,
    },
    css: {
      type: String
    },
    classStyle: {
      type: String
    }
  },
  methods: {}
};
</script>

<style lang="scss" scoped>
.stat_btn {
  position: fixed;
  display: flex;
  align-items: center;
  justify-content: center;
  bottom: 80px;
  /* left: 25px; */
  width: 275px;
  height: 43px;
  border-radius: 10px;
  border: 0;
  box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06), 0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
  outline: none;
  background-color: #fff;
  z-index: 5;
  cursor: pointer;
  transition: 0.3s ease;

  @media all and (max-width: 1366px) {
    bottom: 35px;
    width: 225px;
  }
  @media all and (max-height: 900px) {
    bottom: 15px;
  }
  span {
    color: #2D9CDB;
    font-family: 'Montserrat';
    font-weight: 600;
    font-size: 14px;
      @media all and (max-width: 1023px) {
        display: none;
      }
  }

  img {
    margin-right: 14px;
    @media all and (max-width: 1366px) {
      margin-right: 4px;
    }
  }

  &:hover {
    opacity: 0.8;
  }
}
</style>
