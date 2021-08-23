<template>
  <div class="user-achievment">
    <template v-if="addNew===undefined">
      <div class="user-achievment__icon">
        <img src="~/assets/img/user/award-gold.svg" alt="" v-if="awardType === 'gold'"/>
        <img src="~/assets/img/user/award-gold-white.svg" class="white" alt="" v-if="awardType === 'gold'"/>
        <img src="~/assets/img/user/award-gold-black.svg" class="black" alt="" v-if="awardType === 'gold'"/>
        <img src="~/assets/img/user/award-silver.svg" alt="" v-if="awardType === 'silver'"/>
        <img src="~/assets/img/user/award-silver-white.svg" class="white" alt="" v-if="awardType === 'silver'"/>
        <img src="~/assets/img/user/award-silver-black.svg" class="black" alt="" v-if="awardType === 'silver'"/>
        <img src="~/assets/img/user/award-bronze.svg" alt="" v-if="awardType === 'bronze'"/>
        <img src="~/assets/img/user/award-bronze-white.svg" class="white" alt="" v-if="awardType === 'bronze'"/>
        <img src="~/assets/img/user/award-bronze-black.svg" class="black" alt="" v-if="awardType === 'bronze'"/>
      </div>
      <div class="user-achievment__text">
        <span>{{text}}</span>
      </div>
    </template>
    <template v-else>
      <a href="#">
        <div class="user-achievment__icon user-achievment__icon_add"></div>
        <div class="user-achievment__text">
          <span>Добавить награду</span>
        </div>
      </a>
    </template>
  </div>
</template>

<script>
export default {
  props: ["text", "addNew", "awardType"]
};
</script>

<style lang="scss">
@import "./../../styles/mixins.scss";
.user-achievment {
  display: flex;
  justify-content: flex-start;
  align-items: center;

  &__icon {
    display: block;
    width: 60px;
    height: 60px;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    @media all and (max-width: 1023px){
      width: 50px;
      height: 50px;
    }
    img {
      width: 100%;
      height: auto;
      &.black, &.white {
        display: none;
      }
    }

    &.user-achievment__icon_add {
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle opacity='0.3' cx='30' cy='30' r='29' fill='white' stroke='%237B95A7' stroke-width='2'/%3E%3Cpath opacity='0.3' fill-rule='evenodd' clip-rule='evenodd' d='M32 18H28V28H18V32H28V42H32V32H42V28H32V18Z' fill='%237B95A7'/%3E%3C/svg%3E%0A");
    }
  }

  &__text {
    display: block;
    max-width: calc(100% - 70px);
    margin-left: 10px;
    font-size: 14px;
    line-height: 20px;
    color: #333333;
    @media all and (max-width: 1023px){
      font-size: 12px;
    }
  }

  a {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    width: 100%;
  }
}
</style>