<template>
  <div class="user-profile">
    <div class="user-profile__content" :class="{ '--verified': isVerified }">
      <div class="user-profile__favorites" v-if="currentAward">
        <img
          :src="require(`~/assets/img/user/award-${currentAward.type}.svg`)"
          :alt="currentAward.title"
        />
      </div>

      <div class="user-profile_wrap">
        <div
          class="user-profile__icon"
          v-bind:style="{ 'background-image': 'url(' + avatar + ')' }"
        ></div>
        <div class="user-profile__icon-edit">
          <img
            :src="
              viModeEnabled
                ? require('~/assets/icons/delete-icon-black.svg')
                : require('~/assets/icons/delete-icon.svg')
            "
            alt="delete-icon"
            class="user-profile__icon-link-delete"
            v-on:click="avatarDelete"
          />
          <img
            :src="
              viModeEnabled
                ? require('~/assets/icons/photo-icon-black.svg')
                : require('~/assets/icons/photo-icon.svg')
            "
            alt="photo-icon"
            class="user-profile__icon-link-photo"
            v-on:click="popupAvatarDefault"
          />
        </div>
        <div class="user-profile_name-wrap">
          <div class="user-profile__name">
            <username
              :value="name"
              :placeholder="$t('profile.namePlaceholder')"
            />
          </div>
          <div class="user-profile__title">
            <span>{{ profile.status || $t("profile.statusPlaceholder") }}</span>
          </div>
        </div>
      </div>
      <div class="user-profile__mob-info">
        <div class="user-profile__email">
          <div class="user-profile__subtitle">
            {{ $t("profile.emailPlaceholder") }}
          </div>
          <span>{{ profile.email || $t("profile.emailPlaceholder") }}</span>
        </div>
        <div class="user-profile__phone">
          <div class="user-profile__subtitle">
            {{ $t("profile.number") }}
          </div>
          <span>{{ profile.phone || $t("profile.phonePlaceholder") }}</span>
        </div>
        <div class="user-profile__edit" v-if="$route.name !== 'profile-edit'">
          <nuxt-link :to="localePath({ name: 'profile-edit' })">
            <span>{{ $t("profile.editLink") }}</span>
          </nuxt-link>
        </div>
      </div>
      <div
        class="popup__wrapper"
        v-show="popupAvatar && !profile.abilities.includes('avatar_upload')"
      >
        <div class="popup__scroll">
          <div class="popup__in">
            <span class="popup__close" @click="closeUploader"></span>
            <p class="popup__title">
              {{ $t("profile.avatarChangeHint", { level: 7 }) }}
            </p>
            <div class="user-profile__avatar-list">
              <span
                v-for="(preset, index) in avatarPresets"
                :key="index"
                :style="{ 'background-image': 'url(' + preset + ')' }"
                @click="chooseAvatarPreset(index + 1)"
                class="user-profile__avatar"
              ></span>
            </div>
            <span class="user-profile__avatar-file">
              <input
                id="foto_upload"
                type="file"
                @change.self="uploadAvatar"
                accept="image/jpeg, image/png"
              />
              <label for="foto_upload">
                <img :src="require('~/assets/icons/camera.svg')" />
                <span>Загрузить фото</span>
              </label>
            </span>
            <span v-if="uploadError" class="upload-error"
              >Фото не должно превышать 5 мб загрузите новое фото</span
            >
          </div>
        </div>
      </div>

      <!-- <div
        class="popup__wrapper"
        v-show="popupAvatar && profile.abilities.includes('avatar_upload')"
      >
        <div class="popup__scroll">
          <div class="popup__in --lrg">
            <span class="popup__close" v-on:click="popupAvatar = false"></span>
            <h3 class="popup__title">
              {{ $t("profile.avatarChangePopupTitle") }}
            </h3>
            <div class="user-profile__avatar-list">
              <span class="user-profile__avatar">
                <span class="user-profile__avatar-file">
                  <input
                    type="file"
                    @change="uploadAvatar"
                    accept="image/jpeg, image/png"
                  />
                  <img :src="require('~/assets/icons/avatar-img.svg')" />
                </span>
              </span>
              <span
                v-for="(preset, index) in avatarPresets"
                :key="index"
                :style="{ 'background-image': 'url(' + preset + ')' }"
                @click="chooseAvatarPreset(index + 1)"
                class="user-profile__avatar"
              ></span>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</template>

<script>
import { sync, get, call } from "vuex-pathify";
import Username from "../Username";
import range from "lodash/range";

export default {
  components: { Username },
  data() {
    return {
      isAchievement: true,
      isVerified: true,
      userLevel: 7,
      isAvatarLoaded: false,
      defaultAvatarType: 0,
      popupAvatar: false,
      popupAvatarLevel: false,
      uploadError: false,
    };
  },
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
    avatar() {
      return this.profile.avatar || require("~/assets/img/user/default.svg");
    },
    currentPage() {
      return this.$route.path;
    },
    avatarLoadedCheck() {
      var avatarType = 0;

      if (this.userLevel < 7) {
        this.isAvatarLoaded ? (avatarType = 1) : (avatarType = 2);
      } else {
        this.isAvatarLoaded ? (avatarType = 3) : (avatarType = 4);
      }

      return avatarType;
    },
    name: get("authentication/name"),
    profile: sync("authentication/user"),
    avatarPresets() {
      return range(1, 7).map((presetNumber) =>
        require(`~/assets/img/user/av${presetNumber}.svg`)
      );
    },
    currentAward: get("awards/last"),
    currentAwardIcon() {},
  },
  methods: {
    popupAvatarDefault: function() {
      this.popupAvatar = true;
    },
    async avatarDelete() {
      await this.$axios.delete("/api/profile/avatar");
      await this.loadUser();
    },
    async chooseAvatarPreset(presetNumber) {
      await this.$axios.post(`/api/profile/chooseAvatarPreset/${presetNumber}`);
      await this.loadUser();
      this.popupAvatar = false;
      this.uploadError = false;
    },
    ...call("authentication", ["loadUser"]),
    async uploadAvatar(e) {
      if (e.target.files[0].size < 5e6) {
        this.uploadError = false;
        await this.$axios.post("/api/profile/avatar", e.target.files[0]);
        await this.loadUser();
        this.popupAvatar = false;
      } else {
        this.uploadError = true;
      }
    },
    closeUploader() {
      this.popupAvatar = false;
      this.uploadError = false;
    },
  },
};
</script>

<style lang="scss">
@import "./../../styles/mixins.scss";

.user-profile {
  background: #f1f8fc;
  padding: 45px 37px 30px;
  width: 100%;
  position: relative;
  border-radius: 10px;
  @media all and (max-width: 768px) {
    border-radius: 0;
  }
  &_wrap {
    position: relative;
    width: 248px;
    @media all and (max-width: 768px) {
      display: flex;
      align-items: center;
      height: 90px;
      width: 100%;
      margin-bottom: 26px;
    }
  }
  &__mob-title {
    margin-bottom: 30px;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 600;
    font-size: 24px;
    line-height: 34px;
    color: #202020;
  }

  @media all and (max-width: 1280px) {
    padding: 30px;
  }
  @media all and (max-width: 1200px) {
    padding: 20px;
  }
  @media all and (max-width: 930px) {
    padding: 15px;
  }
  @media all and (max-width: 768px) {
    padding: 26px 15px;
  }

  &__mob-info {
    @media all and (max-width: 768px) {
      width: calc(100% - 100px);
    }
  }

  &__avatar {
    width: 120px;
    height: 120px;
    background-position: center;
    background-size: 100%;
    margin: 30px 15px 0;
    cursor: pointer;
    @media all and (max-width: 991px) {
      width: 80px;
      height: 80px;
    }
    &-file {
      overflow: hidden;
      justify-content: space-around;
      align-items: center;
      margin-top: 50px;
      cursor: pointer;
      label {
        width: max-content;
        padding: 10px;
        gap: 10px;
        display: flex;
        border-radius: 10px;
        background-color: #00a7fa;
        align-items: center;
        cursor: pointer;
      }
      input[type="file"] {
        display: none;
      }
      span {
        color: #ffffff;
      }
    }

    &-list {
      display: flex;
      justify-content: flex-start;
      flex-wrap: wrap;
      margin: -14px -15px 0;
    }
  }

  &__content {
    &.--verified:before {
      content: "";
      background-image: url("data:image/svg+xml,%3Csvg width='50' height='70' viewBox='0 0 50 70' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0H50V70L25 60L0 70V0Z' fill='%237AB73F'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M13 30.6733L25.5712 43L38 30.638L34.5812 27L25.5712 35.936L16.49 27.0353L13 30.6733Z' fill='white'/%3E%3C/svg%3E%0A");
      position: absolute;
      top: 0;
      left: 40px;
      width: 50px;
      height: 70px;
      z-index: 1;
      @media all and (max-width: 1023px) {
        width: 36px;
        height: 50px;
        background-size: 36px;
        left: 20px;
      }
      @media all and (max-width: 768px) {
        width: 22px;
        height: 30px;
        background-size: 22px;
      }
    }
  }

  &__favorites {
    position: absolute;
    right: 70px;
    top: 40px;
    cursor: pointer;
    font-size: 0;
    z-index: 1;
    width: 60px;
    height: 60px;

    img {
      width: 100%;
      height: auto;
      display: block;
    }

    @media all and (max-width: 1023px) {
      right: 20px;
      top: 20px;
      width: 50px;
      height: 50px;
    }
    @media all and (max-width: 768px) {
      right: auto;
      left: 78px;
      width: 20px;
      height: 20px;
    }
  }

  &__icon {
    position: relative;
    display: block;
    width: 248px;
    height: 248px;
    border-radius: 50%;
    margin: 0 auto;
    background-color: #ffffff;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    overflow: hidden;
    text-align: center;
    font-size: 0;
    @media all and (max-width: 768px) {
      width: 90px;
      height: 90px;
      margin: 0 20px 0 0;
    }

    &-link {
      cursor: pointer;
      transition: opacity 0.3s;
      &-delete {
        width: 25px;
        height: 25px;
        position: absolute;
        right: 2px;
        top: -3px;
        cursor: pointer;
        @media (max-width: 768px) {
          display: none;
        }
        &:hover {
          opacity: 0.7;
        }
      }
      &-photo {
        width: 53px;
        cursor: pointer;
        height: 53px;
        position: absolute;
        bottom: 0;
        left: 0;
        @media all and (max-width: 768px) {
          width: 100%;
          height: 100%;
        }
        &:hover {
          opacity: 0.7;
        }
      }
    }

    &-edit {
      position: absolute;
      width: 68px;
      height: 67px;
      bottom: 110px;
      right: 2px;
      z-index: 11;
      @media (max-width: 1024px) {
        bottom: 80px;
      }
      @media (max-width: 768px) {
        width: 39px;
        height: 39px;
        left: 55px;
        bottom: 0;
      }
    }

    &.--hl {
      border: 4px solid #9348f2;
    }
  }

  &__name {
    font-weight: 500;
    font-size: 28px;
    line-height: 40px;
    color: #202020;
    margin-top: 28px;
    @media all and (max-width: 1024px) {
      font-size: 20px;
      line-height: 20px;
      margin-top: 14px;
    }
    @media all and (max-width: 768px) {
      margin-top: 0;
      line-height: 100%;
    }
  }

  &__title {
    margin-top: 10px;
    font-weight: 500;
    font-size: 16px;
    line-height: 20px;
    color: #202020;
    @media all and (max-width: 1024px) {
      font-size: 16px;
      margin-top: 8px;
    }
  }

  &__subtitle {
    margin-bottom: 7px;
    color: #949494;
    font-weight: 600;
    @media all and (max-width: 768px) {
      font-size: 18px;
      margin-bottom: 10px;
    }
  }

  &__email,
  &__phone {
    margin-top: 28px;
    font-size: 16px;
    font-weight: 500;
    line-height: 20px;
    color: #202020;
    @media all and (max-width: 1024px) {
      line-height: 20px;
      margin-top: 10px;
    }
  }
  &__phone {
    margin-top: 16px;
  }

  &__edit {
    margin-top: 27px;
    font-size: 16px;
    font-weight: 500;
    line-height: 20px;
    color: #2d9cdb;
    @media all and (max-width: 1024px) {
      line-height: 20px;
      margin-top: 12px;
    }
    @media all and (max-width: 1024px) {
      margin-top: 27px;
    }

    a {
      color: #2d9cdb;
      transition: opacity 0.3s;
      span {
        color: #2d9cdb;
        &:hover {
          opacity: 0.7;
        }
      }
    }
  }
  .upload-error {
    color: red;
    margin-top: 5px;
  }
}
</style>
