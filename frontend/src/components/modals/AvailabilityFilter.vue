<template>
  <div class="availability">
    <div class="title">
      <span>{{ $t("showAvailability") }}</span>
      <i class="fas fa-times " @click="close"></i>
    </div>
    <div
      class="filter-item"
      @click="toggleAccessibilityLevel('full_accessible')"
      :class="{
        isActive: accessibilityLevels.includes('full_accessible'),
      }"
    >
      <i class="fas fa-check available"></i>
      <span class="text-available">{{
        $t("accessibilityStatus.full_accessible")
      }}</span>
    </div>
    <div
      class="filter-item"
      @click="toggleAccessibilityLevel('partial_accessible')"
      :class="{
        isActive: accessibilityLevels.includes('partial_accessible'),
      }"
    >
      <i class="fas fa-minus partial-access"> </i>
      <span class="text-partial-access">{{
        $t("accessibilityStatus.partial_accessible")
      }}</span>
    </div>
    <div
      class="filter-item"
      @click="toggleAccessibilityLevel('not_accessible')"
      :class="{
        isActive: accessibilityLevels.includes('not_accessible'),
      }"
    >
      <i class="fas fa-times not-available"> </i>
      <span class="text-not-available">{{
        $t("accessibilityStatus.not_accessible")
      }}</span>
    </div>
  </div>
</template>

<script>
import { sync, call, get } from "vuex-pathify";
export default {
  name: "AvailabilityFilter",
  computed: {
    ...sync("map", ["accessibilityLevels"]),
  },
  methods: {
    ...call("map", ["toggleCategory", "toggleAccessibilityLevel"]),
    open() {
      this.availabilityToggle = true;
    },
    close() {
      this.$emit("close");
    },
  },
};
</script>

<style lang="scss" scoped>
.availability {
  display: none;
  @media all and (max-width: 1023px) {
    display: flex;
    grid-gap: 10px;
    gap: 10px;
    flex-direction: column;
    position: fixed;
    top: 90px;
    left: 30px;
    right: 30px;
    background: white;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    .title {
      display: flex;
      justify-content: space-between;
      span {
        font-family: "Montserrat";
        font-weight: 500;
        font-size: 18px;
      }
    }

    .filter-item {
      display: flex;
      align-items: center;
      gap: 15px;
      font-size: 14px;
      font-weight: 500;
      color: #3a3a3a;
      &.isActive {
        font-weight: 600;
        span {
          &.text-partial-access {
            color: #f2994a;
          }
          &.text-available {
            color: #27ae60;
          }
          &.text-not-available {
            color: #eb5757;
          }
        }

        i {
          &.partial-access {
            color: #fff;
            background-color: #f2994a;
          }
          &.available {
            color: #fff;
            background-color: #27ae60;
          }
          &.not-available {
            color: #fff;
            background-color: #eb5757;
          }
        }
      }
    }

    .available {
      display: flex;
      width: 40px;
      height: 40px;
      align-items: center;
      justify-content: center;
      color: #27ae60;
      background-color: #fff;
      border: 1px solid #27ae60;
      border-radius: 10px;
    }
    .partial-access {
      display: flex;
      width: 40px;
      height: 40px;
      align-items: center;
      justify-content: center;
      color: #f2994a;
      background-color: #fff;
      border: 1px solid #f2994a;
      border-radius: 10px;
    }
    .not-available {
      display: flex;
      width: 40px;
      height: 40px;
      align-items: center;
      justify-content: center;
      color: #eb5757;
      background-color: #fff;
      border: 1px solid #eb5757;
      border-radius: 10px;
    }
  }
}
</style>
