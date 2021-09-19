<template>
    <div>
        <div class="form-group" v-if="allowOverride">
            <b-form-checkbox v-model="overrideScore">Переопределить оценку</b-form-checkbox>
        </div>

        <div class="form-group row" v-for="category in categories" :key="category.key">
            <label class="col-sm-3 col-form-label">{{ category.title }}</label>
            <div class="col-sm-9">
                <select class="form-control" :disabled="!overrideScore" style="width: 200px" :value="scoreData[category.key]" :class="classes[scoreData[category.key]]" @input="changeScoreProperty(category.key, $event)">
                    <option v-for="option in options" :key="option.key" :value="option.key">{{option.title}}</option>
                </select>
            </div>
        </div>

        <!--<div class="row pb-3" v-for="category in categories" :key="category.key">
            <div class="col-md-8">{{category.title}}</div>
            <div class="col-md-4">
                <b-badge variant="success" v-if="score[category.key] === 'full_accessible'">Доступно</b-badge>
                <b-badge variant="warning" v-if="score[category.key] === 'partial_accessible'">Частично доступно
                </b-badge>
                <b-badge variant="danger" v-if="score[category.key] === 'not_accessible'">Недоступно</b-badge>
                <b-badge variant="secondary" v-if="score[category.key] === 'not_provided'">Не предусмотрено</b-badge>
            </div>
        </div>-->
    </div>
</template>

<script>
    export default {
        name: "AccessibilityScore",
        props: {
            type: String,
            value: Object,
            allowOverride: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                score: {}
            }
        },
        watch: {
            value: {
                handler() {
                    this.calculate()
                },
                immediate: true,
                deep: true
            },
            type: {
                handler() {
                    this.calculate()
                }
            }
        },
        methods: {
            async calculate() {
                const {data} = await this.$axios.post('/api/objects/calculateZoneScore', {
                    ...this.value,
                    type: this.type || this.value.type,

                })
                this.score = data
            },
            changeScoreProperty(property, e) {
                this.$emit('input', {
                    ...this.value,
                    overriddenScore: {
                        ...this.value.overriddenScore,
                        [property]: e.target.value
                    }
                })
                console.log(e.target.value);
            },
        },
        computed: {
            overrideScore: {
                get() {
                    return !!this.value['overriddenScore']
                },
                set(v) {
                    this.$emit('input', {
                        ...this.value,
                        overriddenScore: v ? this.score : null
                    })
                }
            },
            scoreData() {
                return this.overrideScore ? this.value.overriddenScore : this.score
            },
            classes() {
                return {
                    full_accessible: 'bg-success',
                    partial_accessible: 'bg-warning',
                    not_accessible: 'bg-danger',
                    not_provided: 'bg-secondary',
                    unknown: 'bg-secondary',
                }
            },
            options( ){
                return [
                    {key: 'full_accessible', title: 'Доступно', class: 'bg-success'},
                    {key: 'partial_accessible', title: 'Частично доступно', class: 'bg-warning'},
                    {key: 'not_accessible', title: 'Недоступно', class: 'bg-danger'},
                    {key: 'not_provided', title: 'Не предусмотрено', class: 'bg-secondary'},
                    {key: 'unknown', title: 'Неизвестно', class: 'bg-secondary'},
                ]
            },
            categories() {
                if (this.type === 'kidsAccessibility_small' || this.type === 'kidsAccessibility_middle' || this.type === 'kidsAccessibility_full') {
                    return [{ key: "kids"}]
                }
                return [
                    {key: 'movement', title: 'Люди, передвигающиеся на кресло-коляске'},
                    {key: 'limb', title: 'Люди с нарушением опорно-двигательного аппарата'},
                    {key: 'vision', title: 'Люди с нарушениями зрения'},
                    {key: 'hearing', title: 'Люди с нарушениями слуха'},
                    {key: 'intellectual', title: 'Люди с интелектуальной инвалидностью'},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
