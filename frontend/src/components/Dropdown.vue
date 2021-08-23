<template>
    <div class="dropdown" v-click-outside="closeDropdown">
        <div class="dropdown__selected" @click="toggleDropdown($event)" :class="{opened: isOpened}">
            <span>{{ selectedTitle }}</span>
        </div>
        <div class="dropdown__list">
            <div class="dropdown__item" v-for="(option, index) in options" :key="index" @click="selectOption(option)">
                <span>{{ option.title }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import ClickOutside from 'vue-click-outside'

    export default {
        name: "Dropdown",
        props: {
            value: {
                default: null
            },
            options: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data() {
            return {
                isOpened: false
            }
        },
        methods: {
            toggleDropdown() {
                this.isOpened = !this.isOpened
            },
            closeDropdown() {
                this.isOpened = false
            },
            selectOption(option) {
                this.$emit('input', option.value)
                this.closeDropdown()
            }
        },
        directives: {
            ClickOutside
        },
        computed: {
            selectedTitle() {
                const selectedOption = this.options.find(item => item.value === this.value);
                if(selectedOption) {
                    return selectedOption.title
                }
            }
        }
    }
</script>

<style scoped>

</style>