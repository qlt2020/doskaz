<template>
  <div class="contacts">
    <ViTop />
    <MainHeader />
    <div class="container">
      <div class="contacts__top">
        <h2 class="title">{{ $t("contacts.title") }}</h2>
      </div>
    </div>
    <div class="contacts__wrapper vld-parent" ref="contactsContainer">
      <div class="container">
        <div class="contacts__content">
          <div class="contacts__left">
            <span class="contacts__link-title">{{ $t("contacts.title") }}</span>
            <div class="contacts__link-list">
              <div class="contacts__link-item">
                <span class="contacts__link-label">Email</span>
                <a href="mailto:info@doskaz.kz" class="contacts__link --mail">
                  info@doskaz.kz
                </a>
              </div>
              <div class="contacts__link-item">
                <span class="contacts__link-label">{{
                  $t("profile.number")
                }}</span>
                <a href="tel:+77012234630" class="contacts__link --phone">
                  8(701) 223-4630
                </a>
              </div>
            </div>
            <p class="contacts__text">
              {{
                $t("contacts.callCenterWorkingHours", {
                  from: "09:00",
                  to: "18:00",
                })
              }}
            </p>
            <div class="contacts__social">
              <a
                href="https://www.facebook.com/doskazkz/"
                target="_blank"
                rel="noreferrer noopener"
                class="contacts__social-link --fcb"
              ></a>
              <a
                href="https://www.instagram.com/doskaz.kz/"
                target="_blank"
                rel="noreferrer noopener"
                class="contacts__social-link --inst"
              ></a>
            </div>
          </div>
          <div class="contacts__right">
            <span class="contacts__link-title">{{
              $t("contacts.writeToUs")
            }}</span>
            <div
              class="contacts__line contacts__line-sm required"
              :class="{ error: !!violations.name }"
            >
              <div class="input">
                <input
                  id="c_name"
                  type="text"
                  v-model="feedback.name"
                  :placeholder="$t('complaint.firstName')"
                />
                <span class="error-msg">{{ violations.name }}</span>
              </div>
              <div class="input">
                <input
                  id="l_name"
                  type="text"
                  v-model="feedback.surname"
                  :placeholder="$t('complaint.lastName')"
                />
                <span class="error-msg">{{ violations.name }}</span>
              </div>
            </div>
            <div
              class="contacts__line required"
              :class="{ error: !!violations.email }"
            >
              <div class="input">
                <input
                  type="email"
                  v-model="feedback.email"
                  :placeholder="$t('profile.edit.emailLabel')"
                />
                <span class="error-msg">{{ violations.email }}</span>
              </div>
            </div>
            <div
              class="contacts__line required"
              :class="{ error: !!violations.email }"
            >
              <!-- <div class="select dropdown__block" style="width: 100%">
                <select id="city" v-model="feedback.city_id">
                  <option value="0">
                    {{ $t("complaint.city") }}
                  </option>
                  <option v-for="city in cities" :value="city.id" :key="city.id"
                    >{{ city.name }}
                  </option>
                </select>
              </div> -->
              <DropdownBlock
                :options="citiesForSelect"
                v-model="feedback.city_id"
              />
            </div>
            <div
              class="contacts__line required"
              :class="{ error: !!violations.text }"
            >
              <textarea
                class="textarea"
                v-model="feedback.text"
                :placeholder="$t('contacts.message')"
              ></textarea>
              <span class="error-msg">{{ violations.text }}</span>
            </div>
            <div class="contacts__line">
              <vue-recaptcha
                class="recaptcha"
                sitekey="6LcKej0cAAAAAPFABtykm4iT3hdnmob_a_UiTily"
                :loadRecaptchaScript="true"
                @verify="onSubmit"
                @expired="expire"
              ></vue-recaptcha>
              <button
                type="button"
                class="button"
                @click.prevent="leaveFeedback"
                :disabled="submitDisabled"
              >
                {{ $t("contacts.form.submitButtonLabel") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-represent">
      <div class="represent">
        <div class="represent__title">
          {{ $t("contacts.chooseRegionalRepresentativeLabel") }}
        </div>
        <p class="represent__text">
          {{ $t("contacts.regionalRepresentativesText") }}
        </p>
        <div class="select">
          <DropdownBlock
            :options="citiesForSelect"
            :top="'auto'"
            :bottom="'100%'"
            v-model="city"
          />
        </div>
        <div class="represent__list">
          <div
            class="represent__item"
            v-for="item in regionalRepresentativesFromCity"
            :key="item.id"
          >
            <div class="represent__item-row">
              <img :src="item.image" class="represent__item-img" />
              <span class="represent__item-name">
                {{ item.name }}
              </span>
            </div>
            <div class="represent__item-row">
              <div class="represent__item-row__info">
                <span class="represent__item-row__label">{{
                  $t("profile.number")
                }}</span>
                <a :href="`tel:${item.phone}`" class="represent__item-link">
                  {{ item.phone }}
                </a>
              </div>
              <div class="represent__item-row__info">
                <span class="represent__item-row__label">Email</span>
                <a :href="`mailto:${item.email}`" class="represent__item-link">
                  {{ item.email }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" v-show="formSent">
      <div class="modal__content">
        <div class="modal__head">
          <div class="modal__title">
            {{ $t("contacts.form.submittedPopupTitle") }}
          </div>
          <div class="close-button" v-on:click="formSent = false">
            <img src="@/assets/icons/close_h.svg" alt="" />
          </div>
        </div>
        <div class="modal__body">
          <p class="text">
            {{ $t("contacts.form.submittedPopupMessage") }}
          </p>
        </div>
        <div class="modal__buttons">
          <button
            class="btn btn_green modal__buttons__item"
            @click="formSent = false"
          >
            <span class="text">
              Ок
            </span>
          </button>
        </div>
      </div>
    </div>
    <MainFooter />
  </div>
</template>

<script>
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";
import mapValidationErrors from "../mapValidationErrors";
import MainFooter from "@/components/MainFooter";
import VueRecaptcha from "vue-recaptcha";
import DropdownBlock from "@/components/DropdownBlock";

export default {
  components: { MainHeader, ViTop, MainFooter, VueRecaptcha, DropdownBlock },
  head() {
    return {
      title: this.$t("contacts.title"),
      script: [
        {
          hid: "stripe",
          src:
            "https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit",
          defer: true,
        },
      ],
    };
  },
  async asyncData({ $axios }) {
    const [regionalRepresentatives, cities] = await Promise.all([
      $axios.$get("/api/regionalRepresentatives"),
      $axios.$get("/api/cities"),
    ]);

    return {
      regionalRepresentatives,
      cities,
    };
  },
  data() {
    return {
      formSent: false,
      city: null,
      errors: [],
      submitDisabled: true,
      feedback: {
        firstName: "",
        lastName: "",
        email: "",
        text: "",
        city_id: 0,
      },
    };
  },
  mounted() {
    this.city = this.cities[0].id;
  },
  computed: {
    citiesForSelect() {
      var citiesList = this.cities.map((c) => {
        return {
          value: c.id,
          title: c.name,
        };
      });
      return citiesList;
    },
    availableCities() {
      const regionalRepresentativesCities = this.regionalRepresentatives.map(
        (item) => item.cityId
      );
      return this.cities.filter((city) =>
        regionalRepresentativesCities.includes(city.id)
      );
    },
    regionalRepresentativesFromCity() {
      return this.regionalRepresentatives.filter(
        (item) => item.cityId === this.city
      );
    },
    violations() {
      return mapValidationErrors(this.errors);
    },
  },
  methods: {
    async leaveFeedback() {
      const loader = this.$loading.show({
        container: this.$refs.contactsContainer,
      });
      try {
        this.errors = [];
        const { data, status } = await this.$axios.post(
          "/api/feedback",
          this.feedback,
          {
            validateStatus: (status) => status <= 400,
          }
        );

        if (status === 400) {
          this.errors = data.errors;
          return;
        }
        this.formSent = true;
        this.feedback = {
          name: "",
          email: "",
          text: "",
        };
      } catch (e) {
        throw e;
      } finally {
        loader.hide();
      }
    },
    onSubmit(token) {
      this.submitDisabled = false;
    },
    expire() {
      this.submitDisabled = true;
    },
  },
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";
.contacts {
  .modal {
    &__content {
      width: 500px;
    }
    &__title {
      text-align: center;
      margin-bottom: 15px;
      padding: 0 0 0 23px;
    }
    &__body {
      p {
        line-height: 20px;
        text-align: center;
      }
    }
    &__buttons {
      width: 200px;
      margin: 20px auto 0;
    }
  }
}
.container-represent {
  background: #ffffff;
  margin-top: 80px;

  @media all and (max-width: 768px) {
    margin-top: 55px;
    background: unset;
    padding: 0 15px;
  }
}
.represent {
  padding: 60px 0 80px;
  max-width: 1200px;
  margin: 0 auto;

  @media all and (max-width: 768px) {
    padding: 0 0 30px;
  }

  .select {
    width: 270px;
    margin-top: 45px;
    font-family: "Montserrat";
    &:after {
      display: none;
    }
    @media all and (max-width: 768px) {
      height: 60px;
      margin-top: 30px;
      &:after {
        top: 26px;
      }
    }
  }

  &__title {
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: bold;
    font-size: 28px;
    line-height: 30px;
    color: #202020;
    margin-bottom: 20px;
  }

  &__text {
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 19px;
    color: #474747;
    @media all and (max-width: 768px) {
      font-size: 16px;
      line-height: 17px;
    }
  }

  &__list {
    margin-top: 30px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-column-gap: 30px;
    grid-row-gap: 20px;

    @media screen and (max-width: 768px) {
      grid-template-columns: unset;
      margin-top: 20px;
    }
  }

  &__item {
    background: #ffffff;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    padding: 20px;
    display: grid;
    grid-row-gap: 20px;
    @media all and (max-width: 768px) {
      width: 100%;
      height: 213px;
    }

    &-row {
      display: flex;
      align-items: center;

      @media all and (max-width: 768px) {
        &:not(:first-child) {
          display: grid;
          grid-row-gap: 20px;
        }
      }

      &__info {
        display: grid;
        grid-row-gap: 10px;
        &:first-child {
          margin-right: 35px;
        }
      }

      &__label {
        font-family: SFProDisplay;
        font-style: normal;
        font-weight: 600;
        font-size: 18px;
        line-height: 19px;
        color: #2d9cdb;
      }
    }

    &.disabled {
      &:after {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 1;
      }
    }

    &-img {
      width: 41px;
      border-radius: 50%;
      height: 41px;
      margin-right: 10px;
      @media all and (max-width: 768px) {
      }
    }

    &-name {
      font-style: normal;
      font-weight: 500;
      font-size: 18px;
      line-height: 32px;
      color: #000000;
    }

    &-text {
      font-size: 14px;
      line-height: 20px;
      margin: 11px 0 14px;
      display: block;
    }

    &-link {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 500;
      font-size: 18px;
      line-height: 19px;
      color: #7c7c7c;

      &:hover {
        opacity: 0.7;
      }
    }
  }
}

.contacts {
  background: #fafafa;

  &__wrapper {
    @media all and (max-width: 768px) {
    }
  }

  &__top {
    padding: 50px 0 25px;
    .title {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: bold;
      font-size: 28px;
      line-height: 33px;
      color: #000000;
    }
    @media all and (max-width: 768px) {
      padding: 23px 0 32px;
    }
  }

  &__content {
    display: grid;
    grid-template-columns: 370px 770px;
    grid-column-gap: 30px;
    @media all and (max-width: 768px) {
      grid-template-columns: unset;
      grid-row-gap: 25px;
    }
  }

  &__left {
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    padding: 30px 25px;
    display: grid;
    grid-template-rows: 2fr auto 3fr 1fr;
    height: 394px;
    @media all and (max-width: 768px) {
      width: 100%;
      padding: 30px 15px;
    }
  }

  &__right {
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    padding: 30px 25px;

    @media all and (max-width: 768px) {
      width: 100%;
      padding: 30px 15px;
    }

    .input {
      @media all and (max-width: 768px) {
        height: 60px;
        font-size: 18px;
      }
    }
  }

  &__text {
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 19px;
    color: #474747;
    margin: auto;

    @media all and (max-width: 768px) {
    }
  }

  &__link {
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 19px;
    color: #7c7c7c;

    @media all and (max-width: 768px) {
    }

    &:hover {
      color: $blue;
    }

    &-list {
      display: grid;
      grid-row-gap: 25px;
    }

    &-item {
      display: grid;
      grid-row-gap: 10px;
    }

    &-label {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 600;
      font-size: 18px;
      line-height: 19px;
      color: #2d9cdb;
    }

    &-title {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 600;
      font-size: 24px;
      line-height: 34px;
      color: #202020;
      position: relative;
      &::before {
        content: "";
        background: #2d9cdb;
        width: 3px;
        height: 25px;
        position: absolute;
        top: 4px;
        left: -25px;
        @media screen and (max-width: 768px) {
          left: -16px;
        }
      }
    }
  }

  &__line {
    position: relative;
    margin: 24px 0 0;
    display: flex;
    align-items: center;

    @media screen and (max-width: 768px) {
      display: grid;
      margin-top: 45px;
      grid-row-gap: 20px;
    }

    .select {
      @media screen and (max-width: 768px) {
        height: 60px;
        &:after {
          top: 26px;
        }
      }
    }

    &-sm {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-column-gap: 30px;
      margin-top: 35px;

      @media screen and (max-width: 768px) {
        grid-template-columns: unset;
        grid-row-gap: 30px;
      }

      .input {
        margin: unset;
      }
    }

    .error-msg {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      color: $red;
      line-height: 20px;
      font-size: 14px;
      /* white-space: nowrap; */
      z-index: 2;
    }

    &.required {
      label {
        &:after {
          content: "*";
          color: #e0202e;
          margin: 0 0 0 5px;
        }
      }

      &.error {
        .input {
          position: relative;
          border: 1px solid $red;
        }

        .error-msg {
          display: block;
        }

        .textarea {
          border: 1px solid $red;
          display: block;
        }
      }
    }

    button {
      cursor: pointer;
      min-width: 260px;
      height: 60px;
      background: $blue;
      border-radius: 5px;
      color: #ffffff;
      border: none;
      outline: none;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: bold;
      font-size: 18px;
      line-height: 19px;
      margin-left: 25px;
      &:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: $blue;
      }
      &:hover {
        opacity: 0.7;
      }

      @media screen and (max-width: 768px) {
        margin-left: unset;
        width: 100%;
      }
    }

    label {
      margin: 0 0 6px;
    }

    textarea {
      min-height: 225px;
      background: #f4f4f4;
    }

    &:first-child {
      margin: 0;
    }

    &:last-child {
      margin: 40px 0 0;
    }

    .recaptcha {
      @media screen and (max-width: 768px) {
        margin: auto;
      }
    }
  }

  &__social {
    font-size: 0;

    &-link {
      vertical-align: top;
      display: inline-block;
      background-color: #2d9cdb;
      border-radius: 5px;
      background-position: center;
      background-repeat: no-repeat;
      margin: 0 0 0 12px;
      height: 36px;
      width: 36px;
      transition: opacity 0.4s;

      &:first-child {
        margin: 0;
      }

      &:hover {
        opacity: 0.7;
      }

      &.--fcb {
        background-image: url("@/assets/icons/fb.png");
      }

      &.--inst {
        background-image: url("@/assets/icons/insta.png");
      }
    }
  }
}
</style>
