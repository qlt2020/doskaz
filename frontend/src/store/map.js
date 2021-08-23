import {make} from 'vuex-pathify'

export const state = () => ({
    coordinates: null,
    zoom: 14,
    coordinatesAndZoom: null,
    selectedCategories: [],
    search: '',
    accessibilityLevels: [
        'full_accessible',
        'partial_accessible',
        'not_accessible',
    ],
    searchHighlights: []
})

export const mutations = {
    ...make.mutations(state),
}

export const actions = {
    toggleCategory({state, commit}, category) {
        if (!state.selectedCategories.includes(category)) {
            commit('SET_SELECTED_CATEGORIES', [...state.selectedCategories, category])
        } else {
            commit('SET_SELECTED_CATEGORIES', state.selectedCategories.filter(item => item !== category))
        }
    },
    toggleAccessibilityLevel({state, commit}, accessibilityLevel) {
        if (!state.accessibilityLevels.includes(accessibilityLevel)) {
            commit('SET_ACCESSIBILITY_LEVELS', [...state.accessibilityLevels, accessibilityLevel])
        } else {
            commit('SET_ACCESSIBILITY_LEVELS', state.accessibilityLevels.filter(item => item !== accessibilityLevel))
        }
    },
    async search({commit}, {cityId, query}) {
        const data = await this.$axios.$get('/api/objects/search', {
            params: {
                cityId,
                query
            }
        });

        commit('SET_SEARCH_HIGHLIGHTS', data)
    },
    setCoordinatesAndZoom({commit}, payload) {
        commit('SET_COORDINATES_AND_ZOOM', payload)
    }
}
