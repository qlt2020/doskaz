<template>
  <div class="container">
    <!-- <loading is-full-page :active="isLoading"></loading> -->
    <div
      v-if="['middle', 'full'].includes(formVariant)"
      class="add-object__step-wrapper"
    >
      <div class="add-object__step">
        <div
          class="step"
          v-for="(step, index) in stepsShow"
          :class="{ checked: step.checked, current: step.isCurrent }"
          :key="index"
          :id="`9${index}`"
        >
          <div class="step-progress"></div>
        </div>
      </div>
      <div class="add-object__step-info">
        <div
          class="step"
          v-for="(step, index) in stepsShow"
          :class="{
            active: index === activeStepIndex,
            checked: index < activeStepIndex,
          }"
          :key="index"
        >
          {{
            $t(
              step.group === "first"
                ? "objectAdding.commonInformation"
                : `objects.zone.${step.group}`
            )
          }}
        </div>
      </div>
    </div>
    <div class="add-object__form add-object__form-first">
      <template v-if="['middle', 'full'].includes(formVariant)">
        <div class="add-object__top">
          <span class="add-object__top-step">{{
            $t("objectAdding.currentStep", {
              currentStepNumber,
              totalSteps: stepsShow.length,
            })
          }}</span>
          <h5 class="add-object__title">
            {{
              $t(
                activeStep.group === "first"
                  ? "objectAdding.commonInformation"
                  : `objects.zone.${activeStep.group}`
              )
            }}
          </h5>
        </div>
      </template>
      <template v-else>
        <div class="add-object__line --lrg">
          <h5 class="add-object__title">
            {{ $t("objectAdding.commonInformation") }}
          </h5>
        </div>
      </template>
      <first-step v-show="currentStepKey === 'first'" />
      <zone-step
        v-for="zone in zonesTabsAvailable"
        :key="zone.key"
        v-show="
          ['middle', 'full'].includes(formVariant) &&
            currentStepKey !== 'first' &&
            currentStepKey === zone.key
        "
        :comment-label="zone.commentLabel"
        :form="formVariant"
        :zone="zone.group"
        :zone-key="zone.key"
      />
    </div>
    <div
      class="add-object__button-b"
      v-if="['middle', 'full'].includes(formVariant)"
    >
      <a
        :href="`#9${activeStepIndex - 1}`"
        v-show="activeStepIndex > 0"
        class="add-object__button --prev"
        @click="prevStep"
      >
        <img src="@/assets/icons/back_step.svg" alt="back_step" />
        <span>{{ $t("backward") }}</span>
      </a>
      <button
        type="button"
        class="add-object__button --dub"
        v-if="showDuplicateEntranceStepButton"
        @click="duplicateEntranceStep"
      >
        <span>{{ $t("objectAdding.duplicateStep") }}</span>
      </button>
      <a
        v-if="activeStepIndex + 1 < availableSteps.length"
        :href="`#9${activeStepIndex + 1} `"
        class="add-object__button --next"
        @click="nextStep"
      >
        <span>{{ $t("next") }}</span>
        <img src="@/assets/icons/next_step.svg" alt="next_step" />
      </a>
      <button
        type="button"
        class="add-object__button --submit"
        v-if="activeStepIndex + 1 === availableSteps.length"
        @click.prevent="submit"
      >
        <span>{{ $t("ready") }}!</span>
      </button>
    </div>
    <div class="add-object__form" v-if="formVariant === 'small'">
      <div
        v-for="tab in zonesTabsAvailable"
        :key="tab.key"
        style="margin-bottom: 36px"
        class="add-object__form-wrap"
      >
        <div class="add-object__line --lrg">
          <h5 class="add-object__title">
            {{ $t(`objects.zone.${tab.group}`) }}
          </h5>
        </div>
        <div class="add-object__content">
          <zone-step
            :key="tab.key"
            :form="formVariant"
            :zone="tab.group"
            :zone-key="tab.key"
          />
        </div>
      </div>
    </div>
    <div class="add-object__button-b" v-if="formVariant === 'small'">
      <button
        type="button"
        class="add-object__button --submit --submit_small"
        @click.prevent="submit"
      >
        <span>{{ $t("submit") }}</span>
      </button>
    </div>
    <presence-message
      :name="presentName"
      v-if="this.isPresent && !this.presentIgnore"
      @closed="closePresenceMessage"
    />
  </div>
</template>

<script>
import uniqBy from "lodash/uniqBy";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { call, get } from "vuex-pathify";
import FirstStep from "./FirstStep";
import ZoneStep from "./ZoneStep";
import AttributesList from "./AttributesList";
import PresenceMessage from "~/components/object_add/PresenceMessage";

export default {
  components: {
    PresenceMessage,
    AttributesList,
    ZoneStep,
    FirstStep,
    Loading,
  },
  data() {
    return {
      photosUploading: false,
      currentStepKey: "first",
      presence: {},
      presentIgnore: false,
      presenceChecked: false,
      violations: {},
    };
  },
  methods: {
    ...call("objectAdding", ["init", "duplicateEntranceStep", "validate"]),
    async checkPresence() {
      try {
        this.presence = await this.$axios.$post("/api/objects/checkPresence", {
          name: this.form.first.name,
          otherNames: this.form.first.otherNames,
        });
      } catch (e) {
      } finally {
      }
    },
    closePresenceMessage() {
      this.presentIgnore = true;
    },
    submitForm: call("objectAdding/submit"),
    async nextStep() {
      const index = this.availableSteps.indexOf(this.activeStep);
      if (index === 0) {
        await this.validate();
        if (this.errors.length) {
          return false;
        }
      }
      if (this.availableSteps[index + 1]) {
        this.currentStepKey = this.availableSteps[index + 1].key;
      }
    },
    prevStep() {
      const index = this.availableSteps.indexOf(this.activeStep);
      if (this.availableSteps[index - 1]) {
        this.currentStepKey = this.availableSteps[index - 1].key;
      }
    },
    async submit() {
      await this.submitForm();
      if (this.errors.length) {
        this.currentStepKey = "first";
      }
    },
  },
  computed: {
    isPresent() {
      return this.presence.name || this.presence.otherNames;
    },
    presentName() {
      if (this.presence.name) {
        return this.form.first.name;
      }
      if (this.presence.otherNames) {
        return this.form.first.otherNames;
      }
    },
    showDuplicateEntranceStepButton() {
      if (this.activeStep.group === "entrance") {
        switch (this.activeStep.key) {
          case "entrance1":
            return !this.form.entrance2;
          case "entrance2":
            return !this.form.entrance3;
        }
      }
      return false;
    },
    activeStep() {
      return this.steps2.find((step) => step.key === this.currentStepKey);
    },
    steps2() {
      return [
        {
          key: "first",
          title: this.$t("objectAdding.commonInformation"),
          group: "first",
        },
      ].concat(this.zonesTabsAvailable);
    },
    activeStepIndex() {
      return this.availableSteps.indexOf(this.activeStep);
    },
    availableSteps() {
      return this.steps2.filter((step) => !!this.form[step.key]);
    },
    stepsShow() {
      const activeStep = this.activeStep;
      const activeIndex = this.steps2.indexOf(activeStep);
      const filteredSteps = this.steps2.map((step, index) => ({
        ...step,
        isCurrent: activeStep.group === step.group,
        checked: index < activeIndex,
      }));
      return uniqBy(filteredSteps, (step) => step.group);
    },
    currentStepNumber() {
      const current = this.stepsShow.find((step) => step.isCurrent);
      return this.stepsShow.indexOf(current) + 1;
    },
    formVariant: get("objectAdding/data@form"),
    isLoading: get("objectAdding/isLoading"),
    zonesTabsAvailable: get("objectAdding/zonesTabsAvailable"),
    form: get("objectAdding/data"),
    errors: get("objectAdding/errors"),
  },
  watch: {
    currentStepKey() {
      window.scrollTo({ top: 0 });
    },
    async "form.first.address"() {
      if (!this.presenceChecked) {
        this.presenceChecked = true;
        await this.checkPresence();
      }
    },
  },
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";

select {
  background-color: white;
}

.--av-green {
  .add-object__rating {
    &:before {
      background: $green;
      right: 15px;
    }

    &:after {
      background: url("@/assets/icons/av-green.svg") no-repeat;
      background-size: contain;
      right: 10px;
      margin: 0 -10px 0 0;
    }
  }

  .--text-green {
    display: block;
  }
}

.--av-yellow {
  .add-object__rating {
    &:before {
      background: $yellow;
      width: 50%;
    }

    &:after {
      background: url("@/assets/icons/av-yellow.svg") no-repeat;
      background-size: contain;
      left: 50%;
      margin: 0 0 0 -10px;
    }
  }

  .--text-yellow {
    display: block;
  }
}

.--av-red {
  .add-object__rating {
    &:before {
      background: $red;
      width: 15px;
    }

    &:after {
      background: url("@/assets/icons/av-red.svg") no-repeat;
      background-size: contain;
      left: 15px;
      margin: 0 0 0 -10px;
    }
  }

  .--text-red {
    display: block;
  }
}

.add-object {
  .complaint {
    &__top {
      @media all and (max-width: 1023px) {
        padding: 23px 0 21px;
      }
      @media all and (max-width: 768px) {
        padding: 23px 0 30px;
        .title.--md {
          font-size: 18px;
        }
      }
    }

    &__wrapper {
      padding: 40px 0 80px;
      @media all and (max-width: 1023px) {
        padding: 20px 0 30px;
        .container {
          @media all and (max-width: 1023px) {
            padding: 0 20px;
          }
          @media all and (max-width: 768px) {
            padding: 0 15px;
          }
        }
      }
    }
  }

  .step {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }

  &__textarea {
    width: calc(100% + 140px);
    min-height: 171px;
    & .textarea {
      resize: none;
      border: none;
      border-radius: 6px;
      padding: 20px 23px;
      background: #f8f7f7;
      min-height: 171px;
      font-size: 18px;
      @media (max-width: 575px) {
        padding: 19px;
        &::placeholder {
          line-height: 22px;
        }
      }
      @media (max-width: 455px) {
        min-height: 280px;
      }
      &::-webkit-scrollbar {
        width: 6px;
      }
      &::-webkit-scrollbar-track {
        background: $tr;
        border-radius: 10px;
      }
      &::-webkit-scrollbar-thumb {
        background: transparentize(#c4c4c4, 0.5);
        border-radius: 10px;
      }
    }
    &::placeholder {
      font-weight: 500;
      font-size: 18px;
      font-family: "SFProDisplay";
      color: #696969;
    }
    @media all and (max-width: 1024px) {
      width: 100%;
    }
  }
  & .select {
    height: 60px;
  }
  & .input {
    height: 60px;
  }

  &__rating {
    width: 257px;
    height: 15px;
    position: relative;
    padding: 0 15px;
    margin: 5px 0 0;
    background: $light-gray;
    border-radius: 10px;
    @media all and (max-width: 930px) {
      width: 175px;
    }
    @media all and (max-width: 768px) {
      width: 257px;
      padding: 0 10px;
      margin: 0;
    }
    @media all and (max-width: 575px) {
      height: 11px;
    }
    @media all and (max-width: 500px) {
      width: 185px;
    }
    @media all and (max-width: 368px) {
      width: 145px;
    }
    span {
      display: block;
      height: 15px;
      width: 227px;
      position: relative;
      border-left: 1px solid rgba(123, 149, 167, 0.3);
      border-right: 1px solid rgba(123, 149, 167, 0.3);
      @media all and (max-width: 991px) {
        width: 145px;
      }
      @media all and (max-width: 768px) {
        width: 237px;
      }
      @media all and (max-width: 575px) {
        height: 11px;
      }
      @media all and (max-width: 500px) {
        width: 165px;
      }
      @media all and (max-width: 368px) {
        width: 125px;
      }
      &:before {
        content: "";
        position: absolute;
        width: 1px;
        height: 100%;
        left: 50%;
        background: rgba(123, 149, 167, 0.3);
      }
    }

    &-text {
      position: absolute;
      font-size: 16px;
      color: #3a3a3a;
      line-height: 20px;
      left: 100%;
      margin: 0 0 0 28px;
      display: none;
      white-space: nowrap;
      /* top: 50%;
      transform: translateY(-50%); */
      @media all and (max-width: 1023px) {
        position: relative;
        left: auto;
        right: 0;
      }
      @media all and (max-width: 500px) {
        font-size: 12px;
        margin: 0 0 0 20px;
      }
      @media all and (max-width: 348px) {
        margin: 0 0 0 8px;
      }
    }

    &:before {
      content: "";
      position: absolute;
      width: auto;
      left: 0;
      top: 0;
      opacity: 0.5;
      height: 100%;
      overflow: hidden;
      -webkit-border-radius: 10px 0 0 10px;
      -moz-border-radius: 10px 0 0 10px;
      border-radius: 10px 0 0 10px;
    }

    &:after {
      content: "";
      display: block;
      width: 32px;
      height: 32px;
      position: absolute;
      top: -8px;
      @media all and (max-width: 575px) {
        width: 23px;
        height: 23px;
        top: -6px;
      }
    }
  }

  &__text {
    font-size: 16px;
    line-height: 20px;
    color: #3a3a3a;
  }

  &__title {
    font-weight: 600;
    font-family: "SFProDisplay";
    font-size: 24px;
    margin: 0;
    color: #202020;
    &::before {
      content: "";
      background: #2d9cdb;
      width: 3px;
      height: 25px;
      position: absolute;
      top: 1px;
      left: -28px;
      @media screen and (max-width: 1023px) {
        left: -20px;
      }
      @media screen and (max-width: 768px) {
        left: -15px;
      }
    }
    &.--lrg {
      font-size: 22px;
      font-weight: 600;
      line-height: 40px;
      color: #3a3a3a;
      @media all and (max-width: 1023px) {
        font-size: 22px;
        line-height: 30px;
      }
    }

    &.--label {
      font-size: 16px;
      line-height: 20px;
      margin: 10px 0 -10px;
      @media all and (max-width: 768px) {
        font-size: 14px;
      }
    }
  }

  &__button {
    &-b {
      width: 670px;
      margin: 40px 0 0;
      display: flex;
      justify-content: flex-start;
      @media all and (max-width: 1023px) {
        margin: 30px 0 0;
        width: 100%;
      }
      @media all and (max-width: 768px) {
        justify-content: flex-end;
      }
      @media all and (max-width: 600px) {
        flex-wrap: wrap;
      }
    }
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.4s;
    cursor: pointer;
    width: 290px;
    height: 60px;
    line-height: 50px;
    text-align: center;
    border: none;
    border-radius: 10px;
    outline: none;
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    &:hover {
      opacity: 0.7;
    }

    span {
      display: inline-block;
      vertical-align: middle;
      font-size: 18px;
      font-weight: 700;
      font-family: "SFProDisplay";
      color: #fff;
      @media (max-width: 400px) {
        font-size: 16px;
      }
    }

    &.--cancel {
      background: $stroke;
    }

    &.--next {
      background: $blue;
      width: 194px;
      img {
        margin-left: 15px;
      }
      @media all and (max-width: 768px) {
        width: 49%;
      }
      @media all and (max-width: 600px) {
        margin-right: 0;
        order: 3;
      }
      @media all and (max-width: 400px) {
        img {
          margin-left: 5px;
        }
      }
    }

    &.--prev {
      background: #5e889f;
      width: 194px;
      margin-right: 20px;
      img {
        margin-right: 15px;
      }
      @media all and (max-width: 768px) {
        width: 49%;
      }
      @media all and (max-width: 600px) {
        order: 1;
        margin-right: 2%;
      }
    }

    &.--dub {
      width: 194px;
      background: $blue;
      margin-right: 20px;
      @media all and (max-width: 1023px) {
        width: 200px;
        line-height: 1.1;
      }
      @media all and (max-width: 768px) {
        width: 50%;
      }
      @media all and (max-width: 600px) {
        order: 0;
        flex-basis: 50%;
        margin: 0 25%;
        margin-bottom: 2%;
      }
    }

    &.--submit {
      background: #27ae60;
      width: 194px;
      order: 10;
      @media all and (max-width: 768px) {
        width: 49%;
      }
      &_small {
        @media all and (max-width: 768px) {
          width: 100%;
        }
      }
    }
  }

  &__error {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    padding-top: 10px 0 16px;
    color: $red;
    line-height: 20px;
    font-size: 14px;
    white-space: nowrap;
    z-index: 2;
    @media all and (max-width: 1023px) {
      width: 100%;
      min-width: 100%;
    }
  }

  &__label {
    font-weight: 500;
    font-family: "SFProDisplay";
    font-size: 16px;
    color: #3a3a3a;
    &.--gray {
      font-size: 16px;
      line-height: 20px;
      color: $text;
      font-weight: 400;
    }

    &-text {
      font-size: 14px;
      font-weight: 300;
      line-height: 20px;
      color: #3a3a3a;
      display: block;
    }
  }

  &__step {
    background: rgba(45, 156, 219, 0.2);
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    height: 15px;
    border-radius: 10px;
    overflow: hidden;
    @media (max-width: 768px) {
      width: 930px;
    }

    &-wrapper {
      margin-bottom: 50px;
      overflow-x: auto;
      overflow-y: hidden;
      &::-webkit-scrollbar {
        height: 6px;
      }

      &::-webkit-scrollbar-track {
        background: $tr;
        border-radius: 10px;
      }

      &::-webkit-scrollbar-thumb {
        background: transparentize(#c4c4c4, 0.5);
        border-radius: 10px;
      }
    }

    .step {
      position: relative;

      &-progress {
        position: absolute;
        background: $blue;
        width: 0;
        left: 0;
        top: 0;
        height: 100%;
      }

      &:before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 1px;
        background: #2d9cdb;
      }

      &:first-child {
        &:before {
          display: none;
        }
      }

      &.checked {
        .step-progress {
          width: 100%;
        }

        & + .checked,
        & + .--progresses {
          .step-progress {
            &:before {
              content: "";
              height: 100%;
              position: absolute;
              background: $blue;
              width: 10px;
              left: -10px;
              top: 0;
            }
          }
        }
      }
    }

    &-info {
      display: -ms-flexbox;
      display: flex;
      -ms-flex-wrap: wrap;
      flex-wrap: wrap;
      margin: 7px 0 0;
      @media (max-width: 768px) {
        width: 930px;
      }
      .step {
        font-size: 16px;
        font-weight: 300;
        font-family: SFProDisplay;
        line-height: 16px;
        color: $text;
        padding: 0 10px 0 0;
        text-align: left;
        @media (max-width: 1100px) {
          font-size: 15px;
          padding: 0 4px 0 0;
        }
        @media (max-width: 930px) {
          font-size: 13px;
          padding: 0 4px 0 0;
        }
        @media (max-width: 768px) {
          font-size: 16px;
          padding: 0 10px 0 0;
        }
        &.checked {
          color: #2d9cdb;
          font-weight: 500;
        }
        &.active {
          font-weight: 700;
        }
      }
    }
  }

  &__form {
    background: #ffffff;
    padding: 28px;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    margin-top: 22px;
    &-first {
      margin-top: 0;
      padding: 28px 28px 80px 28px;
      .add-object__top {
        margin: 0;
      }
      .add-object__line {
        .col {
          & + .col {
            @media all and (max-width: 768px) {
              margin: 11px 0 0;
            }
          }
        }
        &.--lrg {
          margin: 40px 0 0;
          @media (max-width: 768px) {
            margin: 25px 0 0;
          }
          &:first-child {
            margin: 0;
          }
        }
      }
      .add-object__content {
        margin-top: 50px;
        @media (max-width: 575px) {
          margin-top: 20px;
        }
      }
    }
    &-wrap {
      margin-top: 70px;
      @media (max-width: 575px) {
        margin-top: 20px;
      }
      &:first-child {
        margin-top: 0;
      }
    }
    @media all and (max-width: 1023px) {
      padding: 30px 20px;
    }
    @media all and (max-width: 768px) {
      padding: 30px 15px;
    }
  }

  &__link {
    cursor: pointer;
    padding: 13px 26px;
    margin: 0 0 0 15px;
    border: 1px solid #2d9cdb;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    font-family: "Montserrat";
    color: #2d9cdb;
    -webkit-transition: background 0.4s;
    -moz-transition: background 0.4s;
    -ms-transition: background 0.4s;
    -o-transition: background 0.4s;
    transition: background 0.4s;
    background: transparent;
    @media all and (max-width: 1023px) {
      padding: 10px 19px;
      line-height: 20px;
    }
    @media all and (max-width: 768px) {
      text-align: center;
    }
    @media (max-width: 575px) {
      padding: 13px 26px;
      margin: 0 0 0 15px;
      height: 43px;
      line-height: 43px;
      line-height: inherit;
      white-space: nowrap;
    }

    &:first-child {
      margin: 0;
    }

    &:hover,
    &.active {
      background: #2d9cdb;
      color: #ffffff;
    }

    &-b {
      margin: 25px 0 0;
      display: flex;
      justify-content: flex-start;
      align-items: baseline;
      overflow-y: hidden;
      overflow-x: auto;
      &::-webkit-scrollbar {
        height: 6px;
      }

      &::-webkit-scrollbar-track {
        background: $tr;
        border-radius: 10px;
      }

      &::-webkit-scrollbar-thumb {
        background: transparentize(#c4c4c4, 0.5);
        border-radius: 10px;
      }
      @media all and (max-width: 1023px) {
        margin: 22px 0 0;
      }
      @media all and (max-width: 768px) {
        justify-content: space-between;
      }
      @media all and (max-width: 575px) {
        width: 100%;
        padding-bottom: 30px;
      }
    }
  }

  &__content {
    margin-top: 18px;
    font-family: "SFProDisplay";
    width: 721px;
    .add-object__title::before {
      background: none;
    }
    @media all and (max-width: 1200px) {
      width: 705px;
    }
    @media all and (max-width: 1023px) {
      width: 100%;
    }
    @media all and (max-width: 768px) {
      margin: 16px 0 0;
    }
  }

  &__top {
    margin: 46px 0 0;
    display: flex;
    color: $black;
    align-items: flex-end;
    position: relative;
    @media all and (max-width: 1023px) {
      margin: 30px 0 0;
    }
    @media all and (max-width: 768px) {
      margin: 0;
      flex-wrap: wrap;
    }

    &-step {
      font-weight: 400;
      font-size: 18px;
      font-family: SFProDisplay;
      line-height: 20px;
      margin: 0 0 3px 0;
      width: 130px;
    }

    &-title {
      font-weight: 600;
      font-size: 48px;
      line-height: 50px;
      margin: 0;
      @media all and (max-width: 1023px) {
        font-size: 22px;
        line-height: 30px;
      }
      @media all and (max-width: 768px) {
        min-width: 100%;
        max-width: 100%;
        font-size: 16px;
        line-height: 20px;
      }
    }
  }

  &__line {
    display: flex;
    align-items: center;
    position: relative;
    margin: 30px 0 0;
    @media all and (max-width: 768px) {
      flex-wrap: wrap;
      margin-top: 20px;
    }
    &.--lrg {
      margin: 20px 0 0;
      &.add-object__line-comment {
        margin-top: 65px;
        @media (max-width: 768px) {
          margin-top: 50px;
        }
        .add-object__title {
          color: #3a3a3a;
          @media (max-width: 575px) {
            font-size: 22px;
          }
        }
      }
      @media all and (max-width: 1023px) {
        margin: 25px 0 0;
      }
    }

    &:first-child {
      margin: 0;
      @media (max-width: 768px) {
        margin-bottom: 21px;
      }
      @media (max-width: 575px) {
        font-size: 24px;
      }
    }
    &:nth-child(7) {
      align-items: flex-start;
      .col {
        line-height: 55px;
        @media (max-width: 768px) {
          line-height: inherit;
        }
      }
    }
    &.error {
      @media all and (max-width: 1023px) {
        flex-wrap: wrap;
      }

      .add-object__error {
        display: block;
      }

      .input,
      select {
        border: 1px solid $red;
      }
    }
    &-service {
      .col {
        @media all and (max-width: 575px) {
          min-width: 100% !important;
          max-width: 100% !important;
        }
      }
    }
    .col {
      position: relative;
      -ms-flex-preferred-size: 0;
      flex-basis: 0;
      -ms-flex-positive: 1;
      flex-grow: 1;
      max-width: 100%;
      @media all and (max-width: 768px) {
        min-width: 100%;
        max-width: 100%;
      }
      @media all and (max-width: 575px) {
        max-width: 320px;
        min-width: 320px;
      }
      @media all and (max-width: 475px) {
        max-width: 200px;
        min-width: 200px;
      }
      input {
        width: 100%;
        @media (max-width: 1023px) {
          font-size: 18px;
        }
        @media (max-width: 768px) {
          font-size: 18px;
        }
      }

      &.--long {
        min-width: 534px;
        max-width: 534px;
        &.map {
          max-width: 100%;
          margin-top: 17px;
          width: 100%;
          @media all and (max-width: 1023px) {
            margin-top: 11px;
            max-width: 100%;
            width: 100%;
          }
        }
        @media all and (max-width: 1023px) {
          max-width: calc(100% - 170px);
          min-width: calc(100% - 170px);
        }
        @media all and (max-width: 768px) {
          max-width: 100%;
          min-width: 100%;
        }

        &.--info {
          @media all and (max-width: 1023px) {
            max-width: calc(100% - 220px);
            min-width: calc(100% - 220px);
          }
          @media all and (max-width: 768px) {
            max-width: 100%;
            min-width: 100%;
          }
        }
      }

      &.--small {
        min-width: 257px;
        max-width: 257px;
        margin-left: 20px;
        .select {
          height: 60px;
        }
        @media all and (max-width: 1023px) {
          max-width: none;
        }
        @media all and (max-width: 768px) {
          min-width: 100%;
          max-width: 100%;
        }
      }

      &.--rating {
        display: flex;
        justify-content: flex-start;
        align-items: center;
      }

      & + .col {
        @media all and (max-width: 768px) {
          margin: 15px 0 0;
        }
      }
    }

    .input {
      height: 60px;
      @media (max-width: 768px) {
        height: auto;
      }
      input {
        font-size: 18px;
        @media (max-width: 1200px) {
          height: 100%;
        }
        @media (max-width: 768px) {
          padding: 19px 20px;
        }
      }
    }

    .select {
      &:after {
        @media all and (max-width: 1023px) {
          top: 23px;
          right: 20px;
        }
      }
      @media all and (max-width: 1023px) {
        font-size: 16px;
        height: 50px;
      }
      @media all and (max-width: 768px) {
        height: 59px;
      }
      select {
        font-weight: 500;
        font-size: 18px;
        @media all and (max-width: 768px) {
          padding-left: 30px;
          font-size: 18px;
        }
      }
    }

    .photo-input {
      margin: 0 25px 25px 0;
      &__wrapper {
        @media all and (max-width: 768px) {
          grid-template-columns: repeat(5, 1fr);
        }
        @media all and (max-width: 600px) {
          grid-template-columns: repeat(4, 1fr);
        }
        @media all and (max-width: 500px) {
          grid-template-columns: repeat(3, 1fr);
        }
      }
      @media all and (max-width: 1023px) {
        margin: 0 10px 25px 0;
      }

      &:nth-child(5n) {
        margin: 0 0 25px;
        @media all and (max-width: 1023px) {
          margin: 0 10px 25px 0;
        }
      }

      &__wrapper {
        margin: 0 0 -25px;
      }
    }

    .add-link {
      margin: 15px 0 0;
    }
  }

  &__grade {
    margin-top: 47px;
    .add-object__line.--lrg {
      margin: 45px 0 0;
      @media (max-width: 575px) {
        font-size: 22px;
      }
    }
    @media screen and (max-width: 768px) {
      margin-top: 60px;
    }
  }

  .direction-column {
    flex-direction: column;
    align-items: flex-start;
  }

  .flex-align-start {
    align-items: flex-start;
    .add-object__label {
      line-height: 60px;
      @media (max-width: 768px) {
        line-height: normal;
      }
    }
  }

  &__info {
    position: absolute;
    right: -55px;
    width: 40px;
    height: 40px;
    top: 12px;
    z-index: 1;
    @media all and (max-width: 1023px) {
      right: 0;
      position: relative;
      margin: 0 0 0 10px;
      top: 0;
    }
    @media all and (max-width: 768px) {
      position: absolute;
      width: 24px;
      width: 24px;
      top: -4px;
    }

    &-icon {
      display: block;
      cursor: pointer;

      svg {
        display: block;
        &.black,
        &.white {
          display: none;
        }
        @media all and (max-width: 768px) {
          width: 24px;
          width: 24px;
        }
      }
    }

    &-text {
      font-size: 14px;
      line-height: 20px;
      background: $light-gray;
      position: absolute;
      width: 390px;
      padding: 15px 20px;
      top: 55px;
      left: 0;
      display: none;

      &:before {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        bottom: 100%;
        left: 10px;
        border-bottom: 10px solid $light-gray;
        border-right: 10px solid transparent;
        border-left: 10px solid transparent;
      }

      @media all and (max-width: 1023px) {
        left: auto;
        right: 0;
        &:before {
          left: auto;
          right: 10px;
        }
      }
      @media all and (max-width: 768px) {
        width: 280px;
        padding: 8px 10px;
        font-size: 12px;
      }
    }

    &.--selected {
      z-index: 2;

      .add-object__info-text {
        display: block;
      }
    }
  }
}
</style>
