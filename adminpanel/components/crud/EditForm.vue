<template>
    <form>
        <div>
            <fields :value="item" :fields="fields" @input="update" :error="validationErrors"/>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" class="btn btn-primary" @click.prevent="submit">Сохранить</button>
            </div>
        </div>
    </form>
</template>

<script>
    import {sync, call, get} from 'vuex-pathify'
    import FieldWrapper from "./FieldWrapper";
    import TextInput from "./edit-fields/TextInput";
    import Fields from "./Fields";

    export default {
        name: "EditForm",
        components: {Fields, TextInput, FieldWrapper},
        props: [
            'fields',
            'editBasePath'
        ],
        computed: {
            ...sync('crud/edit', [
                'item'
            ]),
            ...get('crud/edit', [
                'validationErrors',
            ])
        },
        methods: {
            update(val) {
                /*if (this.item[key] === val) {
                    return;
                }*/
                this.item = val
            },
            submitForm: call('crud/edit/submit'),
            async submit() {
                try {
                    const create = !this.item.id
                    await this.submitForm();
                    if(Object.keys(this.validationErrors).length) {
                        return ;
                    }
                    if (create) {
                        await this.$router.push(`${this.editBasePath}/${this.item.id}`)
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        }
    }
</script>

<style scoped>

</style>
