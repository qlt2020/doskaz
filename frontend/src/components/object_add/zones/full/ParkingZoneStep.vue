<template>
  <div class="add-object__content">
    <attributes-list form="full" zone="parking" :value="value.attributes" />
    <accessibility-score :attributes="value.attributes" type="parking_full" />
    <div class="add-object__line --lrg">
      <h5 class="add-object__title">Комментарий к зоне парковки</h5>
    </div>
    <div class="add-object__line">
      <textarea
        class="add-object__textarea"
        v-model="value.comment"
        placeholder="Система оценила доступность парковки. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением"
      />
    </div>
  </div>
</template>

<script>
import AccessibilityScore from "@/components/object_add/AccessibilityScore";
import AttributeField from "@/components/object_add/AttributeField";
import AttributesList from "../../AttributesList";
export default {
  name: "ParkingZoneStep",
  components: { AttributesList, AccessibilityScore, AttributeField },
  props: ["value"]
};
</script>

<style scoped></style>
