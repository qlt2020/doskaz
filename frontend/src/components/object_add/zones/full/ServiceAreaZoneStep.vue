<template>
    <div class="add-object__content">
        <attributes-list form="full" zone="service" :value="value.attributes"/>
        <accessibility-score :attributes="value.attributes" type="service_full"/>
        <div class="add-object__line --lrg">
            <h5 class="add-object__title">Комментарий к зоне оказания услуги</h5>
        </div>
        <div class="add-object__line">
                    <textarea class="add-object__textarea"
                              v-model="value.comment"
                              placeholder="Система оценила доступность зоны оказания услуги. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением"></textarea>
        </div>
    </div>
</template>

<script>
    import AccessibilityScore from "@/components/object_add/AccessibilityScore";
    import AttributeField from "@/components/object_add/AttributeField";
    import AttributesList from "../../AttributesList";
    export default {
        name: "ServiceAreaZoneStep",
        components: {AttributesList, AttributeField, AccessibilityScore},
        props: [
            'value',
            'definition'
        ],
    }
</script>

<style scoped>

</style>