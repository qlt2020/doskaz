<template>
    <field-wrapper :error="error" :label="label" :required="required">
        <select class="form-control" v-model="value" style="width: 200px;">
            <option v-for="opt in options" :key="opt.value" :value="opt.value">{{opt.title}}</option>
        </select>
    </field-wrapper>

</template>

<script>
    import TextInput from "../edit-fields/TextInput";
    import FieldWrapper from "../FieldWrapper";
    import connectField from "../connectField";
    import Selection from "../edit-fields/Selection";

    export default {
        name: "AttributeSelectionField",
        props: [
            'path',
            'label',
            'required',
        ],
        components: {Selection, TextInput, FieldWrapper},
        computed: {
            ...connectField(),
            options() {
                return [
                    {value: 'unknown', title: 'Неизвестно'},
                    {value: 'not_provided', title: 'Не предусмотрено'},
                    {value: 'no', title: 'Нет'},
                    {value: 'yes', title: 'Да'},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
