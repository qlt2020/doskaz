<template>
    <div>
        <template v-for="(group, index1) in formGroups">
            <div v-if="group.title" :key="`g-${index1}`">
                <h4>{{ group.title }}</h4>
                <hr/>
            </div>

            <template v-for="(subGroup, index2) in group.subGroups">
                <div v-if="subGroup.title">
                    <h5>{{ subGroup.title }}</h5>
                    <hr/>
                </div>

                <field-wrapper :label="`${attribute.title || ''} ${attribute.subTitle || ''}`"  v-for="(attribute, index3) in subGroup.attributes"  :key="`${index1}-${index2}-${index3}`">
                    <select class="form-control" :value="value[`attribute${attribute.key}`]" @input="onInput(`attribute${attribute.key}`, $event)"  style="width: 200px;">
                        <option v-for="opt in options" :key="opt.value" :value="opt.value">{{opt.title}}</option>
                    </select>
                </field-wrapper>

            </template>
        </template>
    </div>
</template>

<script>

    import {get} from 'vuex-pathify'
    import FieldWrapper from "./crud/FieldWrapper";
    import flatMapDeep from 'lodash/flatMapDeep'

    export default {
        name: "AttributesList",
        components: {FieldWrapper},
        props: [
            'value',
            'form',
            'zone'
        ],
        computed: {
            ...get('objectAdding', [
                'attributesList'
            ]),
            formGroups() {
                return this.attributesList[this.form][this.zone]
            },
            options() {
                return [
                    {value: 'unknown', title: 'Неизвестно'},
                    {value: 'not_provided', title: 'Не предусмотрено'},
                    {value: 'no', title: 'Нет'},
                    {value: 'yes', title: 'Да'},
                ]
            }
        },
        mounted() {
            const emptyAttributes = flatMapDeep(this.formGroups, group => {
               return group.subGroups.map(s => s.attributes)
            })
                .filter(attribute => !this.value[`attribute${attribute.key}`])

            if(emptyAttributes.length) {
                const initialValues = {};
                emptyAttributes.map(({key}) => {
                    initialValues[`attribute${key}`] = 'unknown'
                })
                this.$emit('input', {...this.value, ...initialValues})
            }
        },
        methods: {
            onInput(path, e) {
                this.$emit('input', {
                    ...this.value,
                    [path]: e.target.value
                })
            }
        }
    }
</script>

<style scoped>

</style>
