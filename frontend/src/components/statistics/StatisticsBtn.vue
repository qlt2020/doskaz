<template>
    <button 
      @click="$router.push(localePath({ name: 'statistics' }))"
      class="stat_btn"
    >
      <img src="@/assets/icons/stat_diagram.svg" alt="stat_diagram">
      <span>
        Посмотреть статистику
      </span>
    </button>
</template>

<script>
export default {
  name: "StatisticsBtn",
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
  left: 25px;
  width: 570px;
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
    width: 470px;
  }
  span {
    color: #2D9CDB;
    font-family: 'Montserrat';
    font-weight: 600;
    font-size: 14px;
      @media all and (max-width: 1024px) {
        display: none;
      }
  }

  img {
    margin-right: 14px;
  }

  &:hover {
    opacity: 0.8;
  }
}
</style>
