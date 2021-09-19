import {make} from "vuex-pathify";

export const state = () => ({
    items: [],
})

export const getters = {
    getCategories: state => state.items
  }

export const mutations = make.mutations(state)

export const actions = {
    load({commit, state}) {
        if (state.items.length) {
            return;
        }
        return this.$axios.get(`/api/objectCategories`).then(res => {
            commit('SET_ITEMS', res.data);
        })
    }
}
