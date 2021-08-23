import {make} from "vuex-pathify";

export const state = () => ({
    items: []
})

export const mutations = make.mutations(state)

export const actions = {
    async load({commit}) {
        const cities = await this.$axios.$get('/api/cities')
        commit('SET_ITEMS', cities)
    }
}
