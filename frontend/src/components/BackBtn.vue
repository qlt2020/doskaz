<template>
  <a class="back-btn" @click="$router.go(-1)">
    <div class="icon"></div>
    <div class="text">Назад</div>
  </a>
</template>

<script>
export default {
  name: "BackBtn",
  data() {
    return {}
  },
  directives: {},
  computed: {},
  methods: {}
};
</script>

<style lang="scss" scoped>
.back-btn {
  box-shadow: 0px 16px 24px 0px #0000000f;
  background: #fff;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 42px;
  padding: 0 13px;
  cursor: pointer;
  transition: 0.3s ease;
  .icon {
    height: 12px;
    width: 16px;
    background: url("@/assets/icons/arrow_back.png") no-repeat center/contain;
    margin-right: 10px;
  }
  .text {
    font-weight: 500;
    font-size: 16px;
  }
  &:hover {
    box-shadow: 0px 5px 5px 0px #0000000f;
  }
}
</style>
