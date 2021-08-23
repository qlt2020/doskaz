import {make} from "vuex-pathify";

export const state = () => ({
    attributesList: {}
})

export const mutations = make.mutations(state)


export const actions = {
    async init({commit}) {
        const {data} = await this.$axios.get('/api/objects/attributes')
        commit('SET_ATTRIBUTES_LIST', data)
    }
}
