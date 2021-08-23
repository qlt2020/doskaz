import {make} from 'vuex-pathify'

export const state = function () {
    return {
        categories: [
            {key: 'movement', category: 'movement'},
            {key: 'vision', category: 'vision'},
            {key: 'limb', category: 'limb'},
            {key: 'hearing', category: 'hearing'},
            {key: 'temporal', category: 'limb'},
            {key: 'babyCarriage', category: 'movement'},
            {key: 'missingLimbs', category: 'limb'},
            {key: 'pregnant', category: 'limb'},
            {key: 'intellectual', category: 'intellectual'},
            {key: 'agedPeople', category: 'limb'},
            {key: 'justView', category: 'movement'},
        ],
        popupOpen: false,
        category: null
    }
}

export const mutations = make.mutations(state)

export const getters = {
    currentCategory: state => state.categories.find(category => category.key === state.category),
    currentCategoryValue: (state, getters) => getters.currentCategory ? getters.currentCategory.category : undefined
}

export const actions = {
    init({commit, rootState}) {
        const category = rootState.settings.userCategory;
        if (category) {
            commit('SET_CATEGORY', category);
        } else {
            commit('SET_POPUP_OPEN', true)
        }
    },
    selectCategory({dispatch, commit}, category) {
        commit('SET_CATEGORY', category);
        commit('SET_POPUP_OPEN', false);
        dispatch('settings/selectUserCategory', category, {root: true})
    },
    closePopup({commit}) {
        commit('SET_POPUP_OPEN', false)
    }
}