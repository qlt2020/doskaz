<template>
  <div class="add-object__content">
    <attributes-list form="middle" zone="movement" :value="value.attributes" />
    <accessibility-score
      :attributes="value.attributes"
      type="movement_middle"
    />

    <div class="add-object__line --lrg">
      <h5 class="add-object__title">
        Комментарий к зоне путей движения по объекту
      </h5>
    </div>
    <div class="add-object__line">
      <textarea
        class="add-object__textarea"
        v-model="value.comment"
        placeholder="Система оценила доступность путей движения по объекту. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением"
      ></textarea>
    </div>
  </div>
</template>

<script>
import AccessibilityScore from "@/components/object_add/AccessibilityScore";
import AttributeField from "@/components/object_add/AttributeField";
import AttributesList from "../../AttributesList";
export default {
  name: "PathsMovementZoneStep",
  components: { AttributesList, AttributeField, AccessibilityScore },
  props: ["value"]
};
</script>

<style scoped></style>
