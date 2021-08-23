<template>
    <picker :input-class="{'form-control custom-item': true, ' is-invalid': error}" type="datetime"
            format="YYYY-MM-DD HH:mm" v-model="val"/>
</template>

<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import 'vue2-datepicker/locale/ru';

    export default {
        name: "DatePicker",
        components: {
            picker: DatePicker
        },
        props: [
            'value',
            'error'
        ],
        computed: {
            val: {
                get() {
                    return this.value ? new Date(this.value) : null
                },
                set(val) {
                    this.$emit('input', val ? val.toISOString() : null)
                }
            }
        }
    }
</script>

<style>
    .mx-scrollbar-wrap ul .cell {
        display: block;
    }

    .mx-datepicker {
        display: block;
    }

    .custom-item {
        background-image: none !important;
    }
</style>
