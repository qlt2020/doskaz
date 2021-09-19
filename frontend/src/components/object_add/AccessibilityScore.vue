<template>
  <div class="add-object__grade">
    <div class="add-object__line --lrg">
      <h5 class="add-object__title --lrg">
        {{ $t("accessibilityScore.title") }}
      </h5>
    </div>

    <div
      class="add-object__line"
      v-for="(category, index) in categories"
      :key="category.key"
      :class="{
        '--lrg': index > 0,
        '--av-green': score[category.key] === 'full_accessible',
        '--av-yellow': score[category.key] === 'partial_accessible',
        '--av-red': score[category.key] === 'not_accessible'
      }"
    >
      <div class="col">
        <p class="add-object__text">
          {{ $t(`accessibilityScore.category.${category.key}`) }}
        </p>
      </div>
      <div class="col --small --rating">
        <div class="add-object__rating">
          <span></span>
        </div>

        <div class="add-object__rating-text --text-green">
          {{ $t("accessibilityScore.status.full_accessible") }}
        </div>
        <div class="add-object__rating-text --text-yellow">
          {{ $t("accessibilityScore.status.partial_accessible") }}
        </div>
        <div class="add-object__rating-text --text-red">
          {{ $t("accessibilityScore.status.not_accessible") }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AccessibilityScore",
  props: ["type", "attributes"],
  data() {
    return {
      score: {}
    };
  },
  watch: {
    attributes: {
      handler() {
        this.calculateScore();
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    async calculateScore() {
      const { data } = await this.$axios.post(
        "/api/objects/calculateZoneScore",
        {
          type: this.type,
          attributes: this.attributes
        }
      );
      this.score = data;
    }
  },
  computed: {
    categories() {
      if (this.type === 'kidsAccessibility_small' || this.type === 'kidsAccessibility_middle' || this.type === 'kidsAccessibility_full') {
        return [{ key: "kids"}]
      }
      return [
        { key: "movement" },
        { key: "limb" },
        { key: "vision" },
        { key: "hearing" },
        { key: "intellectual" }
      ];
    }
  }
};
</script>

<style scoped></style>
