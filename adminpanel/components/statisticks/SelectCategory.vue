<template>
    <select :multiple="options.multiple" ref="select" class="select-stat" >
        <template>
            <option v-if="allOptions" value=0>Все</option>
            <option v-for="option in selectOptions" :key="option.title" :value="option.id">{{option.title}}</option>
        </template>
    </select>
</template>

<script>
    export default {
        name: "Select",
        props: {
            value: [String, Number, Array],
            usersValue: {
              type: Boolean,
              default: false
            },
            allOptions: {
              type: Boolean,
              default: false
            },
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
                return this.options || this.asyncItems
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
