<template>
  <div class="add-object__line" :class="{ '--lrg': !!title }">
    <div class="col">
      <label class="add-object__label pre-formatted" v-if="title">{{
        title
      }}</label>
      <span
        class="add-object__label-text pre-formatted"
        v-if="title && subTitle"
        >{{ subTitle }}</span
      >
      <label
        class="add-object__label --gray pre-formatted"
        v-if="!title && subTitle"
        >{{ subTitle }}</label
      >
    </div>
    <div class="col --small">
      <div class="select">
        <DropdownBlock
          :options="[
            {
              value: 'unknown',
              title: $t(`objects.attribute.unknown`),
            },
            {
              value: 'not_provided',
              title: $t(`objects.attribute.not_provided`),
            },
            {
              value: 'no',
              title: $t(`objects.attribute.no`),
            },
            {
              value: 'yes',
              title: $t(`objects.attribute.yes`),
            },
          ]"
          v-model="val"
        />
      </div>
    </div>
  </div>
</template>

<script>
import DropdownBlock from "@/components/DropdownBlock";
export default {
  components: { DropdownBlock },
  name: "AttributeField",
  props: ["title", "subTitle", "value"],
  computed: {
    val: {
      get() {
        return this.value;
      },
      set(v) {
        this.$emit("input", v);
      },
    },
    options() {
      var arr = ["unknown", "not_provided", "no", "yes"];
    },
  },
};
</script>

<style scoped>
.pre-formatted {
  white-space: pre-wrap;
}
</style>
