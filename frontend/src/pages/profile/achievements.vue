<template>
  <div>
    <UserAchievments :events="items" :awards="awards" />

    <template v-if="notification">
      <div
        class="popup__wrapper"
        v-if="notification.data.type === 'levelReached'"
      >
        <div class="popup__scroll">
          <div class="popup__in">
            <span
              class="popup__close"
              v-on:click="closeNotification(notification)"
            ></span>
            <h5 class="popup__title">
              {{ $t("profile.notifications.levelReachedTitle") }}
            </h5>
            <p class="popup__text">
              {{
                $t("profile.notifications.levelReachedText", {
                  level: notification.data.level
                })
              }}
              <template v-if="notification.data.unlockedAbility">
                <br />{{
                  $t(
                    `profile.notifications.ability.${notification.data.unlockedAbility}`
                  )
                }}
              </template>
            </p>
            <div
              class="popup__new-level"
              v-bind:style="{
                'background-image':
                  'url(' + require('~/assets/img/user/newLevel.svg') + ')'
              }"
            ></div>
          </div>
        </div>
      </div>

      <div
        class="popup__wrapper"
        v-if="notification.data.type === 'pointsEarned'"
      >
        <div class="popup__scroll">
          <div class="popup__in">
            <span
              class="popup__close"
              v-on:click="closeNotification(notification)"
            ></span>
            <h5 class="popup__title">
              {{
                $t("profile.notifications.pointsEarnedTitle", {
                  n: $tc("pointsCount", notification.data.points)
                })
              }}
            </h5>
            <p class="popup__text">
              {{
                $t(
                  `profile.notifications.pointsEarnedTasks.${notification.data.taskType}`
                )
              }}
              <br />«{{ notification.data.taskName }}»
            </p>
            <div class="popup__new-points">{{ notification.data.points }}</div>
          </div>
        </div>
      </div>
    </template>

    <div class="popup__wrapper" v-if="addAwardPopup">
      <div class="popup__scroll">
        <div class="popup__in">
          <span class="popup__close" v-on:click="addAwardPopup = false"></span>
          <h5 class="popup__title">Добавить награду</h5>
          <div class="popup__award-wrapper">
            <form>
              <div class="popup__award-list">
                <div class="popup__award">
                  <div
                    class="popup__award-icon"
                    v-bind:style="{
                      'background-image':
                        'url(' +
                        require('~/assets/img/user/award-gold.svg') +
                        ')'
                    }"
                  ></div>
                  <input
                    id="award-gold"
                    type="radio"
                    class="popup__award-input"
                    name="award"
                  />
                  <label for="award-gold">золотая</label>
                </div>
                <div class="popup__award">
                  <div
                    class="popup__award-icon"
                    v-bind:style="{
                      'background-image':
                        'url(' +
                        require('~/assets/img/user/award-silver.svg') +
                        ')'
                    }"
                  ></div>
                  <input
                    id="award-silver"
                    type="radio"
                    class="popup__award-input"
                    name="award"
                  />
                  <label for="award-silver">серебряная</label>
                </div>
                <div class="popup__award">
                  <div
                    class="popup__award-icon"
                    v-bind:style="{
                      'background-image':
                        'url(' +
                        require('~/assets/img/user/award-bronze.svg') +
                        ')'
                    }"
                  ></div>
                  <input
                    id="award-bronze"
                    type="radio"
                    class="popup__award-input"
                    name="award"
                  />
                  <label for="award-bronze">бронзовая</label>
                </div>
              </div>
            </form>
          </div>
          <div class="select">
            <select>
              <option disabled selected>Выберите категорию</option>
              <option>Категория1</option>
              <option>Категория2</option>
              <option>Категория3</option>
              <option>Категория4</option>
            </select>
          </div>
          <button type="button" class="user-page__button">Добавить</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { get } from "vuex-pathify";
import first from "lodash/first";
import UserAchievments from "~/components/user/UserAchievments";

export default {
  data() {
    return {
      newLevelPopup: false,
      newPointsPopup: false,
      addAwardPopup: false,
      closedNotifications: []
    };
  },
  async asyncData({ $axios }) {
    const [{ items }, notifications] = await Promise.all([
      $axios.$get("/api/profile/events"),
      $axios.$get("/api/profileNotifications")
    ]);
    return { items, notifications };
  },
  methods: {
    async closeNotification(notification) {
      await this.$axios.$delete(`/api/profileNotifications/${notification.id}`);
      this.closedNotifications.push(notification);
    }
  },
  computed: {
    currentPage() {
      return this.$route.path;
    },
    awards: get("awards/items"),
    notification() {
      return first(
        this.notifications.filter(n => !this.closedNotifications.includes(n))
      );
    }
  },
  components: {
    UserAchievments
  }
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";

.user-page {
  overflow-x: hidden;
  /* 
    &__header {
        width: 100%;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: flex-end;
        @media all and (max-width: 768px) {
            display: none;
        }

        .menu {
            height: 50px;
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            padding: 0 0 0 50%;
            @media all and (max-width: 1280px) {
                padding: 0 0 0 550px;
            }
            @media all and (max-width: 1200px) {
                padding: 0 0 0 490px;
            }
            @media all and (max-width: 1024px) {
                padding: 0 0 0 360px;
            }

            &__content {
                margin-left: -130px;
                display: flex;
                justify-content: flex-start;
                align-items: center;
                position: relative;
            }

            &__item {
                padding: 0 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 16px;
                line-height: 20px;
                color: #ffffff;
                height: 50px;
                @media all and (max-width: 1200px) {
                    padding: 0 10px;
                    font-size: 14px;
                }

                span {
                    color: #ffffff;
                }

                &.--logout {
                    position: absolute;
                    right: 0;
                    top: 0;
                    width: 50px;
                    height: 50px;
                    display: block;
                    padding: 15px 0 0 10px;

                    svg {
                        display: block;
                    }
                }

                &.isActive, &:hover {
                    background: transparentize(#0f6bf5, 0.25);
                }
            }
        }
    } */

  /* &__row {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        flex-direction: row;
        @media all and (max-width: 768px) {
            display: block;
        }
    } */
}
</style>
