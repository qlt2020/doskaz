<template>
    <div class="add-object__line" :class="{'--lrg': !!title}">
        <div class="col">
            <label class="add-object__label pre-formatted" v-if="title">{{ title }}</label>
            <span class="add-object__label-text pre-formatted" v-if="title && subTitle">{{ subTitle }}</span>
            <label class="add-object__label --gray pre-formatted" v-if="!title && subTitle">{{subTitle}}</label>
        </div>
        <div class="col --small">
            <div class="select">
                <select v-model="val">
                    <option v-for="option in options" :key="option" :value="option">{{ $t(`objects.attribute.${option}`) }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AttributeField",
    props: [
        'title',
        'subTitle',
        'value'
    ],
    computed: {
        val: {
            get() {
                return this.value
            },
            set(v) {
                this.$emit('input', v)
            }
        },
        options() {
          return [
            'unknown',
            'not_provided',
            'no',
            'yes'
          ]
        }
    }
}
</script>

<style scoped>
.pre-formatted {
    white-space: pre-wrap;
}
</style>