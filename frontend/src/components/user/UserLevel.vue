<template>
  <div class="user-level">
    <div class="user-level__progress">
      <div class="info">
        <div class="info__level">
          <div class="info__level-num">
            <span>{{ level.current }}</span>
          </div>
          <div class="info__level-text">
            <span>{{ $t("profile.level") }}</span>
          </div>
        </div>
        <div class="info__points">
          <span
            ><span class="--mob-hide">{{ $t("profile.points") }} </span
            >{{ level.currentPoints }} / {{ level.nextLevelThreshold }}</span
          >
        </div>
      </div>
      <div class="progress">
        <div
          class="progress__bar"
          v-bind:style="{ width: level.progressToNext + '%' }"
        ></div>
      </div>
    </div>
    <div class="user-level__stats">
      <div class="stat">
        <div class="stat__num">
          <span>{{ stats.objects }}</span>
        </div>
        <div class="stat__cat">
          <span>{{ $t("profile.stats.objects") }}</span>
        </div>
      </div>
      <div class="stat">
        <div class="stat__num">
          <span>0</span>
        </div>
        <div class="stat__cat">
          <span>{{ $t("profile.stats.verifications") }}</span>
        </div>
      </div>
      <div class="stat">
        <div class="stat__num">
          <span>{{ stats.complaints }}</span>
        </div>
        <div class="stat__cat">
          <span>{{ $t("profile.stats.complaints") }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { get } from "vuex-pathify";

export default {
  computed: {
    level: get("authentication/user@level"),
    stats: get("authentication/user@stats")
  }
};
</script>

<style lang="scss">
@import "./../../styles/mixins.scss";

.--mob-hide {
  @media all and (max-width: 768px) {
    display: none;
  }
}

.user-level {
  background: #f1f8fc;
  padding: 30px 37px;
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

  &__progress {
    margin-bottom: 28px;

    .info {
      margin-bottom: 8px;
      display: flex;
      align-items: flex-end;
      justify-content: space-between;

      &__level {
        display: flex;
        align-items: flex-end;
      }

      &__level-num {
        font-weight: 700;
        font-size: 40px;
        line-height: 40px;
        color: #333333;
      }

      &__level-text {
        font-weight: 400;
        font-size: 14px;
        line-height: 20px;
        color: #333333;
        margin-left: 4px;
      }

      &__points {
        font-size: 14px;
        line-height: 20px;
        font-weight: 300;
        color: #333333;
      }
    }

    .progress {
      display: block;
      width: 100%;
      height: 8px;
      background: rgba(123, 149, 167, 0.3);
      border-radius: 40px;
      position: relative;

      &__bar {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        border-radius: 10px;
        background: #0f6bf5;
      }
    }
  }

  &__stats {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: flex-start;

    .stat {
      display: block;
      flex: 1 0 auto;
      text-align: left;
      &__num {
        font-weight: 600;
        font-size: 30px;
        line-height: 40px;
        color: #333333;
      }

      &__cat {
        font-size: 14px;
        line-height: 20px;
        font-weight: 300;
        color: #333333;
      }
    }
  }
}
</style>
