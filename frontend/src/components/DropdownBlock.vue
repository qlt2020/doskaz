<template>
  <div class="dropdown__block" v-click-outside="closeDropdown">
    <div
      class="dropdown__block__selected dropdown__block__item"
      @click="!active ? null : toggleDropdown($event)"
      :class="{ opened: isOpened }"
      :style="!active ? `opacity: 0.7` : ``"
    >
      <span>{{ selectedTitle }}</span>
      <svg
        width="9"
        height="6"
        viewBox="0 0 9 6"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path d="M1 1L4.5 4.5L8 1" stroke="#3a3a3a" stroke-width="1.5" />
      </svg>
    </div>
    <div
      class="dropdown__block__list"
      :style="`max-height: ${maxHeight}px; top: ${top}; bottom: ${bottom}`"
    >
      <div
        class="dropdown__block__item"
        v-for="(option, index) in options"
        :key="index"
        @click="selectOption(option)"
      >
        <span>{{ option.title || option.name}}</span>
      </div>
    </div>
  </div>
</template>

<script>
import ClickOutside from "vue-click-outside";

export default {
  name: "DropdownBlock",
  props: {
    value: {
      default: null,
    },
    options: {
      type: Array,
      default() {
        return [];
      },
    },
    maxHeight: {
      default: 400,
    },
    top: {
      type: String,
      default: "calc(100% + 1px);",
    },
    bottom: {
      type: String,
      default: "unset",
    },
    active: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      isOpened: false,
    };
  },
  methods: {
    toggleDropdown() {
      this.isOpened = !this.isOpened;
    },
    closeDropdown() {
      this.isOpened = false;
    },
    selectOption(option) {
      this.$emit("input", option.value || option.id);
      this.closeDropdown();
    },
  },
  directives: {
    ClickOutside,
  },
  computed: {
    selectedTitle() {
      const selectedOption = this.options.find(
        (item) => item.value === this.value
      );
      const selectStat = this.options.find((item) => item.id === this.value)

      if (selectedOption) {
        return selectedOption.title;
      } else if (selectStat) {
        return selectStat.title || selectStat.name
      } else {
        return this.options[0].title;
      }
    },
  },
};
</script>

<style scoped lang="scss">
.dropdown__block {
  position: relative;
  width: 100%;
  background: #fff;
  &__item {
    position: relative;
    padding: 15px 35px 15px 20px;
    cursor: pointer;
    span {
      font-weight: 500;
      font-size: 16px;
      color: #3a3a3a;
      font-family: "Montserrat";
    }
  }
  &__list {
    box-shadow: 0px 16px 24px 0px #0000000f;
    margin: 0;
    top: calc(100% + 10px);
    left: 0;
    right: 0;
    min-width: 270px;
    border: none;
    padding: 0;
    position: absolute;
    background: #ffffff;
    display: none;
    z-index: 10;
    border-radius: 0 0 14px 14px;
    overflow: auto;
    border: 1px solid #2d9cdb;
    border-top: none;
    .dropdown__block__item {
      transition: 0.1s ease;
      &:hover {
        background: #cbe4f3;
        span {
          color: #2d9cdb;
        }
      }
      &:after {
        content: "";
        height: 1px;
        position: absolute;
        left: 20px;
        right: 20px;
        bottom: -0.5px;
        background: #eae7e7;
      }
      &:last-child {
        &:after {
          display: none;
        }
      }
    }
  }
  &__selected {
    cursor: pointer;
    box-shadow: 0px 4px 24px 4px rgba(0, 0, 0, 0.1);
    border-radius: 6px;
    border: 1px solid transparent;
    border-bottom: none;
    &.opened + .dropdown__block__list {
      display: block;
    }
    svg {
      position: absolute;
      top: 50%;
      transform: translate(0, -50%) scale(2);
      right: 20px;
      transition: 0.1s ease;
    }
    span {
      display: block;
      text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;
      color: #3a3a3a;
    }
    &.opened {
      border-radius: 6px 6px 0 0;
      border-color: #2d9cdb;
      svg {
        transform: translate(0, -50%) scale(2) rotate(-180deg);
      }
    }
  }
}
</style>
