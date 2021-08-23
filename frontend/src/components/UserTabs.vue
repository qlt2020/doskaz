<template>
  <div class="user-tabs">
    <div class="user-tabs__links">
      <div class="user-tabs__link" :class="{'isActive': activeTab===0}" @click="setActiveTab(0)">
        <span>Мои задания</span>
      </div>
      <div
        class="user-tabs__link isUpdated"
        :class="{'isActive': activeTab===1}"
        @click="setActiveTab(1)"
      >
        <span>Мои комментарии</span>
      </div>
      <div class="user-tabs__link" :class="{'isActive': activeTab===2}" @click="setActiveTab(2)">
        <span>Мои объекты</span>
      </div>
    </div>
    <div class="user-tabs__tabs">
      <div class="user-tabs__tab" :class="{'isActive': activeTab===0}">
        <div class="scroll">
          <div class="item">
            <div class="item__icon">
              <svg
                width="5"
                height="20"
                viewBox="0 0 5 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0)">
                  <path
                    d="M2.04102 16.3265V20"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                  <path
                    d="M2.04102 0V13.9942"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                </g>
                <defs>
                  <clipPath id="clip0">
                    <rect width="4.08163" height="20" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </div>
            <div class="item__date">
              <span>6 июля</span>
            </div>
            <div class="item__text">
              <span>Добавьте 15 объектов в городе</span>
            </div>
          </div>
          <div class="item">
            <div class="item__icon">
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M14.5928 10.5814H9.47656V5.4651"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
                <path
                  d="M18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5C14.6944 1.5 18.5 5.30558 18.5 10Z"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
              </svg>
            </div>
            <div class="item__date">
              <span>29 Сентября</span>
            </div>
            <div class="item__text">
              <span>Сентября</span>
            </div>
          </div>
          <div class="item">
            <div class="item__icon">
              <svg
                width="5"
                height="20"
                viewBox="0 0 5 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0)">
                  <path
                    d="M2.04102 16.3265V20"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                  <path
                    d="M2.04102 0V13.9942"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                </g>
                <defs>
                  <clipPath id="clip0">
                    <rect width="4.08163" height="20" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </div>
            <div class="item__date">
              <span>6 июля</span>
            </div>
            <div class="item__text">
              <span>Добавьте 15 объектов в городе</span>
            </div>
          </div>
          <div class="item">
            <div class="item__icon">
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M14.5928 10.5814H9.47656V5.4651"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
                <path
                  d="M18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5C14.6944 1.5 18.5 5.30558 18.5 10Z"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
              </svg>
            </div>
            <div class="item__date">
              <span>29 Сентября</span>
            </div>
            <div class="item__text">
              <span>Сентября</span>
            </div>
          </div>
          <div class="item">
            <div class="item__icon">
              <svg
                width="5"
                height="20"
                viewBox="0 0 5 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0)">
                  <path
                    d="M2.04102 16.3265V20"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                  <path
                    d="M2.04102 0V13.9942"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                </g>
                <defs>
                  <clipPath id="clip0">
                    <rect width="4.08163" height="20" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </div>
            <div class="item__date">
              <span>6 июля</span>
            </div>
            <div class="item__text">
              <span>Добавьте 15 объектов в городе</span>
            </div>
          </div>
          <div class="item">
            <div class="item__icon">
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M14.5928 10.5814H9.47656V5.4651"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
                <path
                  d="M18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5C14.6944 1.5 18.5 5.30558 18.5 10Z"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
              </svg>
            </div>
            <div class="item__date">
              <span>29 Сентября</span>
            </div>
            <div class="item__text">
              <span>Сентября</span>
            </div>
          </div>
          <div class="item">
            <div class="item__icon">
              <svg
                width="5"
                height="20"
                viewBox="0 0 5 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g clip-path="url(#clip0)">
                  <path
                    d="M2.04102 16.3265V20"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                  <path
                    d="M2.04102 0V13.9942"
                    stroke="#3180F7"
                    stroke-width="7"
                    stroke-miterlimit="10"
                  />
                </g>
                <defs>
                  <clipPath id="clip0">
                    <rect width="4.08163" height="20" fill="white" />
                  </clipPath>
                </defs>
              </svg>
            </div>
            <div class="item__date">
              <span>6 июля</span>
            </div>
            <div class="item__text">
              <span>Добавьте 15 объектов в городе</span>
            </div>
          </div>
          <div class="item">
            <div class="item__icon">
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M14.5928 10.5814H9.47656V5.4651"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
                <path
                  d="M18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5C14.6944 1.5 18.5 5.30558 18.5 10Z"
                  stroke="#0F6BF5"
                  stroke-width="3"
                  stroke-miterlimit="10"
                />
              </svg>
            </div>
            <div class="item__date">
              <span>29 Сентября</span>
            </div>
            <div class="item__text">
              <span>Сентября</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeTab: 0
    };
  },
  components: {},
  methods: {
    setActiveTab(tab) {
      this.activeTab = tab;
    }
  }
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";

.user-tabs {
  height: 100%;

  &__links {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
  }

  &__link {
    display: block;
    margin-left: 20px;
    background: $tr;
    padding: 10px;
    font-size: 16px;
    line-height: 20px;
    color: #333333;
    position: relative;
    cursor: pointer;

    &.isActive {
      background: #f1f8fc;
      font-weight: 700;
    }

    &.isUpdated {
      &:after {
        content: "";
        position: absolute;
        top: 10px;
        right: 0;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #3dba3b;
      }
    }

    &:first-child {
      margin-left: 0;
    }
  }

  &__tabs {
    padding-top: 30px;
    height: 100%;
  }

  &__tab {
    height: 100%;
    width: 100%;
    display: none;

    &.isActive {
      display: block;
    }

    .scroll {
      position: relative;
      overflow-x: hidden;
      overflow-y: auto;
      height: 100%;
      padding-right: 30px;
      padding-bottom: 30px;

      &::-webkit-scrollbar {
        width: 10px;
      }

      &::-webkit-scrollbar-track {
        background: $tr;
      }

      &::-webkit-scrollbar-thumb {
        background: transparentize(#c4c4c4, 0.5);
      }
    }

    .item {
      display: flex;
      width: 100%;
      justify-content: flex-start;
      align-items: flex-start;
      margin-bottom: 40px;

      &__icon {
        width: 20px;
        height: 20px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;

        svg {
          max-width: 100%;
          max-height: 100%;
        }
      }

      &__date {
        margin-left: 30px;
        font-size: 14px;
        line-height: 20px;
        color: #5b6067;
        width: 100px;
      }

      &__text {
        flex: 1 0 auto;
        max-width: calc(100% - 150px);
        font-size: 16px;
        line-height: 20px;
        color: #333333;
      }
    }
  }
}
</style>