<template>
  <div class="user-object">
    <div class="top">
      <formatted-date class="date" :date="objectDate" format="dd.MM.yyyy" />
      <div class="object-status" :class="scores[status].value">
        {{ scores[status].title }}
      </div>
    </div>
    <div class="category">
      <div class="cat-icon">
        <img :src="'/' + objectCatIcon" />
      </div>
      <span class="category-title">
        {{ objectCategoryTitle }}
      </span>
      <span class="sub-category-title">/ {{ objectCategorySubtitle }} </span>
    </div>
    <span class="title">{{ objectTitle }}</span>
    <span class="address">{{ objectAddress }}</span>
    <nuxt-link :to="localePath(objectLink)" class="btn btn_blue"
      ><span>{{ $t("profile.objects.openObject") }}</span></nuxt-link
    >
  </div>
</template>

<script>
import FormattedDate from "~/components/FormattedDate";

export default {
  components: { FormattedDate },
  props: [
    "objectLink",
    "objectTypeImg",
    "objectImg",
    "objectTitle",
    "status",
    "objectDate",
    "objectComments",
    "objectReports",
    "objectCatIcon",
    "objectCategoryTitle",
    "objectCategorySubtitle",
    "objectAddress",
  ],
  computed: {
    scores() {
      return {
        full_accessible: {
          title: this.$t("accessibilityStatus.full_accessible"),
          value: "available",
        },
        partial_accessible: {
          title: this.$t("accessibilityStatus.partial_accessible"),
          value: "partially-available",
        },
        not_accessible: {
          title: this.$t("accessibilityStatus.not_accessible"),
          value: "not-available",
        },
        not_provided: {
          title: this.$t("accessibilityStatus.not_provided"),
          value: "not-available",
        },
        unknown: {
          title: this.$t("accessibilityStatus.unknown"),
          value: "not-available",
        },
      };
    },
  },
};
</script>
<style lang="scss" scoped>
.user-object {
  display: flex;
  flex-direction: column;
  background: #ffffff;
  box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06), 0px 2px 6px rgba(0, 0, 0, 0.04),
    0px 0px 1px rgba(0, 0, 0, 0.04);
  border-radius: 10px;
  padding: 20px;
  .top {
    width: 100%;
    display: flex;
    justify-content: space-between;
    .date {
      font-family: "SFProDisplay";
      font-style: normal;
      font-weight: 500;
      font-size: 14px;
      color: #2d9cdb;
    }
    .object-status {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 0px;
      width: 160px;
      border-radius: 7px;
      font-family: "SFProDisplay";
      font-style: normal;
      font-weight: 600;
      font-size: 14px;
      &.available {
        background: #27ae604d;
        color: #27ae60;
      }
      &.partially-available {
        background: #f2994a4d;
        color: #f2994a;
      }
      &.not-available {
        background: #eb57574d;
        color: #eb5757;
      }
    }
  }
  .title {
    font-family: "SFProDisplay";
    font-style: normal;
    font-weight: bold;
    font-size: 20px;
    color: #535353;
    margin: 10px 0px;
  }
  .address {
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    color: #535353;
    margin-bottom: 30px;
  }
  .category {
    display: flex;
    font-family: Montserrat;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    margin: 10px 0px;
    .category-title {
      color: #717171;
    }
    .sub-category-title {
      color: #000000;
    }
    .cat-icon {
      width: 20px;
      height: 20px;
      background: #2d9cdb;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      img {
        width: 12px;
      }
    }
  }
}
</style>
