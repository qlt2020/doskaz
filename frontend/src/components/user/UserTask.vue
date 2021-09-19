<template>
  <div class="user-task" v-if="currentTask">
    <div class="user-task__title">
      <span>{{ $t("profile.tasks.current") }}</span>
    </div>
    <div class="user-task__description">
      <span>{{ currentTask.title }}</span>
    </div>

    <div class="user-task__progress">
      <div class="progress">
        <div
          class="progress__bar"
          v-bind:style="{ width: currentTask.progress + '%' }"
        ></div>
        <div class="progress__num" v-bind:style="{ left: 0 + '%' }">
          <span>0</span>
        </div>
        <div class="progress__num" v-bind:style="{ left: 25 + '%' }">
          <span>2</span>
        </div>
        <div class="progress__num" v-bind:style="{ left: 50 + '%' }">
          <span>3</span>
        </div>
        <div class="progress__num" v-bind:style="{ left: 75 + '%' }">
          <span>4</span>
        </div>
        <div class="progress__num" v-bind:style="{ left: 98 + '%' }">
          <span>5</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { get } from "vuex-pathify";
export default {
  data() {
    return {};
  },
  computed: {
    currentTask: get("authentication/user@currentTask")
  }
};
</script>

<style lang="scss">
@import "./../../styles/mixins.scss";

.user-task {
  background: #f1f8fc;
  padding: 37px 37px;
  width: 100%;
  position: relative;
  border-radius: 10px;
  @media all and (max-width: 1280px) {
    padding: 26px 30px 30px;
  }
  @media all and (max-width: 1024px) {
    padding: 20px;
  }
  @media all and (max-width: 768px) {
    border-radius: 0;
  }

  &__title {
    font-weight: 600;
    font-size: 20px;
    line-height: 30px;
    color: #333333;
    margin-bottom: 18px;
  }

  &__description {
    font-size: 16px;
    font-weight: 300;
    line-height: 20px;
    color: #333333;
  }

  &__progress {
    margin-top: 48px;

    .progress {
      display: block;
      width: 100%;
      height: 8px;
      background: rgba(123, 149, 167, 0.3);
      border-radius: 10px;
      position: relative;
      &__bar {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        border-radius: 10px;
        background: #0f6bf5;
        z-index: 2;
      }

      &__num {
        position: absolute;
        bottom: 100%;
        text-align: center;
        font-size: 14px;
        font-weight: 300;
        line-height: 20px;
        color: #333333;
        z-index: 1;

        &::before {
          position: absolute;
          content: "";
          border-left: 1px solid fade-out(#7b95a7, 0.3);
          height: 8px;
          top: 100%;
          left: calc(50% - 1px);
          @media all and (max-width: 768px) {
            height: 3px;
          }
        }
      }
    }
  }
}
</style>
