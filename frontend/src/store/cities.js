import {make} from 'vuex-pathify'

export const state = () => ({
    list: []
})

export const mutations = make.mutations(state)


export const getters = {
    selectedCity: ({list: cities}, getters, {settings: {cityId}}) => cities.find(city => city.id === cityId)
}

export const actions = {
    async load({commit}) {
        const cities = await this.$axios.$get('/api/cities')
        commit('SET_LIST', cities)
    }
}