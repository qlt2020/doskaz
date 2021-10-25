<template>
  <div class="user-page__edit">
    <h3 class="user-page__title">{{ $t("profile.edit.pageTitle") }}</h3>
    <p class="user-page__text --line">
      {{ $t("profile.edit.pointsInfo") }}
      {{ $tc("profile.edit.pointsCount", 70) }}
    </p>
    <p class="user-page__text --line">
      {{ $t("profile.edit.personalDataInfo") }}
    </p>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">
          {{ $t("profile.edit.myCategory") }}
        </label>
      </div>
      <div class="col">
        <div
          class="profile__edit__category"
          @click="showCategoriesList = !showCategoriesList"
        >
          <div
            v-if="
              profile.category !== null &&
                profile.category !== undefined &&
                profile.category !== ''
            "
            class="profile__edit__category__icon"
          >
            <img
              v-if="profile.category !== null"
              :src="
                require(`@/assets/icons/categories/${profile.category}.svg`)
              "
            />
          </div>
          <div class="profile__edit__category__text">
            {{
              !profile.category
                ? $t("blog.notSelected")
                : $t(`disabilityCategories.${profile.category}`)
            }}
          </div>
          <svg
            width="9"
            height="6"
            viewBox="0 0 9 6"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            :style="
              showCategoriesList
                ? 'transform: translate(0, -50%) rotate(-180deg) scale(2);'
                : ''
            "
          >
            <path d="M1 1L4.5 4.5L8 1" stroke="#fff" stroke-width="1.5" />
          </svg>
        </div>
        <div v-if="showCategoriesList" class="profile__edit__categories__list">
          <div class="profile__edit__category" @click="selectCategory('')">
            <div class="profile__edit__category__text">
              {{ $t("blog.notSelected") }}
            </div>
          </div>
          <div
            v-for="category in getCategoriesList"
            :key="category.key"
            class="profile__edit__category"
            @click="selectCategory(category)"
          >
            <div class="profile__edit__category__icon">
              <img
                :src="
                  require(`@/assets/icons/categories/${category.key}_blue.svg`)
                "
              />
            </div>
            <div class="profile__edit__category__text">
              {{ $t(`disabilityCategories.${category.key}`) }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">{{
          $t("profile.edit.lastNameLabel")
        }}</label>
      </div>
      <div class="col">
        <div class="input" :class="{ error: false }">
          <input type="text" v-model.trim="profile.lastName" />
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">{{
          $t("profile.edit.firstNameLabel")
        }}</label>
      </div>
      <div class="col">
        <div class="input" :class="{ error: false }">
          <input type="text" v-model.trim="profile.firstName" />
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">{{
          $t("profile.edit.middleNameLabel")
        }}</label>
      </div>
      <div class="col">
        <div class="input" :class="{ error: false }">
          <input type="text" v-model.trim="profile.middleName" />
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">{{ $t("profile.edit.sex") }}</label>
      </div>
      <div class="col">
        <div class="sex__radio__button">
          <label class="sex__radio__button__item">
            <input
              type="radio"
              name="user_sex"
              class="sex__radio__button__input"
              :checked="profile.gender === 'm'"
              @change="profile.gender = 'm'"
            />
            <div class="sex__radio__button__label">
              {{ $t("profile.edit.male") }}
            </div>
          </label>
          <label class="sex__radio__button__item">
            <input
              type="radio"
              name="user_sex"
              class="sex__radio__button__input"
              :checked="profile.gender === 'f'"
              @change="profile.gender = 'f'"
            />
            <div class="sex__radio__button__label">
              {{ $t("profile.edit.female") }}
            </div>
          </label>
          <label class="sex__radio__button__item">
            <input
              type="radio"
              name="user_sex"
              class="sex__radio__button__input"
              :checked="profile.gender === 'u' || profile.gender === null"
              @change="profile.gender = 'u'"
            />
            <div class="sex__radio__button__label">
              {{ $t("blog.notSelected") }}
            </div>
          </label>
        </div>
        <div class="profile__edit__city hidden">
          <select v-model="profile.gender">
            <option :value="'m'" :key="'m'">
              {{ $t("profile.edit.male") }}
            </option>
            <option :value="'f'" :key="'f'">
              {{ $t("profile.edit.female") }}
            </option>
            <option :value="'u'" :key="'u'">
              {{ $t("blog.notSelected") }}
            </option>
          </select>
          <svg
            width="9"
            height="6"
            viewBox="0 0 9 6"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            :style="
              showCategoriesList
                ? 'transform: translate(0, -50%) rotate(-180deg);'
                : ''
            "
          >
            <path d="M1 1L4.5 4.5L8 1" stroke="#696969" stroke-width="1.5" />
          </svg>
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">{{
          $t("profile.edit.emailLabel")
        }}</label>
      </div>
      <div class="col">
        <div class="input" :class="{ error: false }">
          <input type="email" v-model.trim="profile.email" />
          <span class="error-msg">{{}}</span>
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">{{
          $t("profile.edit.phoneLabel")
        }}</label>
      </div>
      <div class="col">
        <div
          class="input"
          :class="{
            error: errors.find((e) => e.property === 'phoneChangeToken'),
          }"
        >
          <client-only>
            <input
              type="text"
              v-imask="{
                mask: '{+7}(000)000-00-00',
                lazy: false,
                unmask: true,
              }"
              :value="profile.phone"
              @accept="profile.phone = $event.detail.unmaskedValue"
            />
          </client-only>
          <span
            v-if="errors.find((e) => e.property === 'phoneChangeToken')"
            class="error-msg"
            >{{
              errors.find((e) => e.property === "phoneChangeToken").message
            }}</span
          >
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">
          {{ $t("profile.edit.myCity") }}
        </label>
      </div>
      <div class="col">
        <div class="profile__edit__city">
          <DropdownBlock
            :options="[
              {
                value: null,
                title: $t('blog.notSelected'),
              },
              ...getCitiesList,
            ]"
            v-model="profile.city_id"
          />
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">
          {{ $t("profile.edit.birthDate") }}
        </label>
      </div>
      <div class="col">
        <div class="user-page__birth-date">
          <vuejs-date-picker
            id="birth-date"
            format="dd.MM.yyyy"
            :disabledDates="{ from: new Date(Date.now()) }"
            :language="locale"
            v-model="profile.birthday"
            :placeholder="$t('blog.notSelected')"
          />
        </div>
      </div>
    </div>
    <div class="user-page__line">
      <div class="col --label">
        <label class="user-page__label">{{
          $t("profile.edit.smsCodeLabel")
        }}</label>
      </div>
      <div class="col --flex">
        <div class="input --flex-col --md " :class="{ error: !!smsError }">
          <input type="text" v-model.trim="smsCode" />
          <span class="error-msg">{{ smsError }}</span>
        </div>
        <div class="--flex-col">
          <div class="user-page__sms">
            <span
              class="user-page__sms-text"
              @click="sendSmsCode"
              v-if="!smsWait"
              >{{ $t("profile.edit.sendSmsButton") }}</span
            >
            <span class="user-page__sms-text" v-else>{{
              $t("profile.edit.resendSmsCodeMessage")
            }}</span>
          </div>
        </div>
      </div>
    </div>
    <div
      class="user-page__line"
      :class="{ disabled: !profile.abilities.includes('status_change') }"
    >
      <div class="col --label">
        <label class="user-page__label">{{
          $t("profile.edit.statusLabel")
        }}</label>
      </div>
      <div class="col">
        <div class="input" :class="{ error: false }">
          <input
            type="text"
            v-model.trim="profile.status"
            :readonly="!profile.abilities.includes('status_change')"
            :placeholder="
              profile.abilities.includes('status_change')
                ? ''
                : $t('profile.edit.statusInputPlaceholder')
            "
          />
        </div>
      </div>
    </div>
    <button class="btn btn_green" @click.prevent="submit">
      <span class="text">
        {{ $t("profile.edit.saveButton") }}
      </span>
    </button>
    <div id="recaptcha_verifier"></div>
    <SubscribeNotifModal
      v-if="subscribeNotifModalVisible"
      :edit="true"
      @close="subscribeNotifModalVisible = false"
      @showNextModal="subscribeNotifDoneModalVisible = true"
    />
    <SubscribeNotifDoneModal
      v-if="subscribeNotifDoneModalVisible"
      @close="subscribeNotifDoneModalVisible = false"
    />
  </div>
</template>

<script>
import { call, get } from "vuex-pathify";
import mapValidationErrors from "@/mapValidationErrors";
import firebase from "firebase/app";
import "firebase/auth";
import ru from "vuejs-datepicker/dist/locale/translations/ru";
import SubscribeNotifModal from "~/components/modals/SubscribeNotifModal";
import SubscribeNotifDoneModal from "~/components/modals/SubscribeNotifDoneModal";
import DropdownBlock from "@/components/DropdownBlock";

export default {
  name: "ProfileEdit",
  props: ["profile"],

  components: {
    SubscribeNotifDoneModal,
    SubscribeNotifModal,
  },

  data() {
    return {
      errors: [],
      codeSent: false,
      smsWait: false,
      smsErrorCode: null,
      smsCode: "",
      showCategoriesList: false,
      newGender: "",
      locale: ru,
      subscribeNotifModalVisible: false,
      subscribeNotifDoneModalVisible: false,
      oldCategory: "",
      oldCity: 0,
    };
  },
  mounted() {
    this.oldCategory = this.profile.category;
    this.oldCity = this.profile.city_id;
    if (!firebase.apps.length) {
      firebase.initializeApp({
        apiKey: process.env.NUXT_ENV_FIREBASE_API_KEY,
      });
      firebase.auth().languageCode = "ru";
      this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
        "recaptcha_verifier",
        {
          size: "invisible",
        }
      );
    }
  },
  methods: {
    async sendSmsCode() {
      if (
        this.smsWait ||
        !this.profile.phone ||
        this.profile.phone.length !== 12
      ) {
        return;
      }
      this.smsWait = true;
      const timeout = setTimeout(() => {
        this.smsWait = false;
      }, 15 * 1000);
      this.smsErrorCode = null;
      this.confirmationResult = null;
      try {
        this.confirmationResult = await firebase
          .auth()
          .signInWithPhoneNumber(this.profile.phone, this.recaptchaVerifier);
        this.codeSent = true;
      } catch (e) {
        this.smsErrorCode = e.code;
      }
    },
    async confirmSmsCode() {
      if (!this.smsCode) {
        this.smsErrorCode = true;
        return;
      }
      try {
        const result = await this.confirmationResult.confirm(this.smsCode);
        this.profile.phoneChangeToken = await result.user.getIdToken();
      } catch (e) {
        this.smsErrorCode = e.code;
      }
    },
    async submit() {
      var date = new Date(this.profile.birthday);
      var month =
        date.getMonth().toString().length === 1
          ? `0${date.getMonth() + 1}`
          : date.getMonth() + 1;
      var day =
        date.getDate().toString().length === 1
          ? `0${date.getDate()}`
          : date.getDate();
      var newDate = `${date.getFullYear()}-${month}-${day}`;
      this.profile.birthday = newDate;
      this.smsErrorCode = null;
      this.errors = [];
      const loader = this.$loading.show();
      await this.confirmSmsCode();
      if (this.smsErrorCode) {
        loader.hide();
        return;
      }

      try {
        const { data, status } = await this.$axios.put(
          "/api/profile",
          this.profile,
          {
            validateStatus: (status) => status <= 400,
          }
        );
        if (status === 400 || status === 422) {
          this.errors = data.errors;
          loader.hide();
          return;
        }
        this.smsErrorCode = null;
        this.codeSent = false;
        this.smsCode = null;
        const newUser = await this.loadUser();
        if (
          (this.oldCategory !== newUser.category ||
            this.oldCity !== newUser.city_id) &&
          newUser.category
        ) {
          this.subscribeNotifModalVisible = true;
        }
        if (!newUser.category) {
          await this.$store.dispatch("notifications/unsubscribe");
        }
        this.oldCategory = newUser.category;
        this.oldCity = newUser.city_id;
      } catch (e) {
        throw e;
      } finally {
        loader.hide();
      }
    },
    ...call("authentication", ["loadUser"]),
    selectCategory(category) {
      this.profile.category = category.key;
      this.showCategoriesList = false;
    },
  },
  computed: {
    cities: get("cities/list"),
    getCitiesList() {
      var cityList = [...this.cities];
      var list = cityList.map((c) => {
        return {
          value: c.id,
          title: c.name,
          bounds: c.bounds,
        };
      });
      let ind = list.findIndex((l) => l.value === 1);
      list.splice(ind, 1);
      return list;
    },
    categories: get("disabilitiesCategorySettings/categories"),
    getCategoriesList() {
      var categoriesList = [];
      this.categories.map((el) => {
        if (el.key !== "justView") {
          categoriesList.push(el);
        }
      });
      return categoriesList;
    },
    smsError() {
      const messages = {
        "auth/invalid-verification-code": this.$t(
          "profile.edit.invalidVerificationCodeError"
        ),
        "auth/too-many-requests": this.$t("profile.edit.tooManyRequestsError"),
      };

      if (!this.smsErrorCode) {
        return;
      }

      return (
        messages[this.smsErrorCode] || this.$t("profile.edit.errorPleaseRetry")
      );
    },
  },
};
</script>

<style scoped lang="scss">
.user-page__edit {
  .input {
    height: 60px;
  }
}
.profile__edit__category {
  display: flex;
  align-items: center;
  border-radius: 6px;
  background: #2d9cdb;
  padding: 0 40px 0 20px;
  height: 60px;
  cursor: pointer;
  svg {
    position: absolute;
    top: 50%;
    transform: translate(0, -50%) scale(2);
    right: 20px;
    transition: 0.1s ease;
  }
  &__icon {
    width: 25px;
    margin-right: 15px;
  }
  &__text {
    font-weight: 300;
    font-size: 18px;
    font-family: "SFProDisplay";
    color: #fff;
    text-overflow: ellipsis;
  }
}
.profile__edit__categories__list {
  position: absolute;
  top: 100%;
  z-index: 100;
  left: 0;
  right: 0;
  border-radius: 6px;
  overflow: hidden;
  box-shadow: 0px 0px 30px 10px #0000000a;
  .profile__edit__category {
    border-radius: 0;
    transition: 0.3s ease;
    background: #fff;
    &:hover {
      background: #cbe4f3;
    }
    &__text {
      color: #000;
    }
  }
}
.sex__radio__button {
  display: flex;
  &__item {
    margin-right: 35px;
    display: flex;
    align-items: center;
    input {
      -webkit-appearance: radio;
      margin-right: 10px;
    }
  }

  &__label {
    font-family: "SFProDisplay";
    font-size: 18px;
    font-weight: 500;
  }
}
.profile__edit__city {
  border-radius: 6px;
  box-shadow: 0px 24px 32px 0px #0000000a;
  select {
    height: 50px;
    color: #696969;
    font-size: 18px;
    font-weight: 500;
    font-family: "SFProDisplay";
    width: 100%;
    border: none;
    padding: 0 20px;
  }
  svg {
    position: absolute;
    top: 50%;
    transform: translate(0, -50%);
    right: 20px;
    transition: 0.1s ease;
  }
  &.hidden {
    display: none;
  }
}

.btn_green {
  background: #27ae60;
  height: 60px;
  width: 290px;
  margin-top: 40px;
  .text {
    font-size: 18px;
    line-height: 106%;
    font-family: "SFProDisplay";
  }
}

.user-page__line.disabled {
  opacity: 1;
  .input {
    background: #a9a9a9;
    input {
      background: #a9a9a9;
      color: #fff;
      &::placeholder {
        /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #fff;
        opacity: 1; /* Firefox */
      }

      &:-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: #fff;
      }

      &::-ms-input-placeholder {
        /* Microsoft Edge */
        color: #fff;
      }
    }
  }
}

.user-page__edit {
  .user-page__line .input input {
    font-size: 18px;
  }
}

@media screen and (max-width: 1200px) {
  .user-page__edit {
    .input {
      height: 60px;
      padding: 0 20px;
    }
  }
}

@media screen and (max-width: 1023px) {
  .user-page__label {
    font-size: 16px;
  }
}

@media screen and (max-width: 575px) {
  .sex__radio__button {
    display: none;
  }
  .profile__edit__city {
    &.hidden {
      display: block;
    }
  }
  .btn_green {
    width: 100%;
  }
}
</style>
