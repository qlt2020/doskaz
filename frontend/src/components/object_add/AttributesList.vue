<template>
  <div>
    <template v-for="(group, index1) in formGroups">
      <div
        class="add-object__line --lrg"
        v-if="group.title"
        :key="`g-${index1}`"
      >
        <h5 class="add-object__title">{{ group.title }}</h5>
      </div>

      <template v-for="(subGroup, index2) in group.subGroups">
        <div class="add-object__line" v-if="subGroup.title">
          <h5 class="add-object__title">{{ subGroup.title }}</h5>
        </div>
        <attribute-field
          v-for="(attribute, index3) in subGroup.attributes"
          :key="`${index1}-${index2}-${index3}`"
          :title="attribute.title"
          :sub-title="attribute.subTitle"
          class="add-object__line-service"
          :value="value[`attribute${attribute.key}`]"
          @input="
            $emit('change', {
              path: `attribute${attribute.key}`,
              value: $event
            })
          "
        />
      </template>
    </template>
  </div>
</template>

<script>
import { get } from "vuex-pathify";
import AttributeField from "./AttributeField";

export default {
  name: "AttributesList",
  components: { AttributeField },
  props: ["form", "zone", "value"],
  computed: {
    ...get("objectAdding", ["attributesList"]),
    formGroups() {
      return this.attributesList[this.form][this.zone];
    }
  }
};
</script>

<style scoped></style>
