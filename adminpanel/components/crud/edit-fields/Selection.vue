<template>
    <select :multiple="options.multiple" ref="select" style="width: 100%">
        <option v-for="option in selectOptions" :key="option.value" :value="option.value">{{option.title}}</option>
    </select>
</template>

<script>
    export default {
        name: "Selection",
        props: {
            value: [String, Number, Array],
            options: {
                type: [Object, Array],
                default() {
                    return {
                        multiple: false,
                        options: [],
                        asyncOptions: null
                    }
                }
            },
        },
        data() {
            return {
                asyncItems: null
            }
        },
        async mounted() {
            if(this.options.asyncOptions) {
                this.asyncItems = await this.options.asyncOptions()
            }

            const vm = this;
            $(this.$refs.select).select2({
                width: 'resolve'
            }).val(this.val)
                .trigger('change')
                .on('change', function () {
                    vm.val = $(this).val()
                })
        },
        computed: {
            val: {
                get() {
                    return this.value
                },
                set(v) {
                    if(v !== this.value) {
                        this.$emit('input', v)
                    }
                }
            },
            selectOptions() {
                return this.options.options || this.asyncItems
            }
        },
        watch: {
            value(value, old) {
                if (String(value) !== String(old)) {
                    $(this.$refs.select)
                        .val(value)
                        .trigger('change');
                }
            }
        },
    }
</script>

<style scoped>

</style>
