import get from "lodash/get";

export default function () {
    return {
        value: {
            get() {
                return get(this.$store.get('crud/edit/item'), this.path)
            },
            set(v) {
                this.$store.commit('crud/edit/SET_PROPERTY_BY_PATH', {value: v, path: this.path})
            }
        },
        error() {
            return get(this.$store.get('crud/edit/validationErrors'), this.path)
        }
    }
}
