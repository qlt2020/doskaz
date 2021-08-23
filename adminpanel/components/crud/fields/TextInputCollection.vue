<template>
    <field-wrapper :error="error" :label="label" :required="required">
        <div class="input-group mb-3"  v-for="(item, index) in value" :key="index">
            <input type="text" class="form-control " :value="item" @input="changeValue(index, $event)"/>
            <div class="input-group-append">
                <button class="btn btn-danger" type="button" @click.prevent="deleteItem(index)"><i class="mdi mdi-close"/></button>
            </div>
        </div>

        <div class="form-group row no-gutters">
            <button class="btn btn-secondary" @click.prevent="addItem">Добавить</button>
        </div>
    </field-wrapper>
</template>

<script>
    import connectField from "../connectField";
    import FieldWrapper from "../FieldWrapper";

    export default {
        name: "TextInputCollection",
        components: {FieldWrapper},
        props: [
            'path',
            'label',
            'required',
            'disabled'
        ],
        methods: {
            addItem() {
                this.value = [...this.value, '']
            },
            changeValue(index, event) {
                const val = [...this.value]
                val[index] = event.target.value

                this.value = val
            },
            deleteItem(index) {
                this.value = this.value.filter((v, i) => i !== index)
            }
        },
        computed: {
            ...connectField()
        }
    }
</script>

<style scoped>

</style>
