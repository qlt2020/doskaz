import {make, Payload} from 'vuex-pathify'
import set from 'lodash/set'

export const state = () => ({
    isLoading: false,
    item: {},
    validationErrors: {},
    apiPath: null,
    id: null,
    operationResult: null

})

export const mutations = {
    ...make.mutations(state),
    SET_PROPERTY_BY_PATH: (state, {value, path}) => {
        set(state, `item.${path}`, value)
    }
}

export const actions = {
    reset({commit}) {
        //commit('SET_IS_LOADING', false);
        commit('SET_ITEM', {})
        //commit('SET_API_PATH', null)
        commit('SET_ID', null)
        commit('SET_VALIDATION_ERRORS', {})
        commit('SET_OPERATION_RESULT', null)
    },
    async loadItem({commit, state}, id) {
        commit('SET_ID', id)
        commit('SET_IS_LOADING', true)
        const {data: item} = await this.$axios.get(`${state.apiPath}/${id}`)
        commit('SET_ITEM', item)
        commit('SET_IS_LOADING', false)
    },
    async submit({commit, state}) {
        commit('SET_IS_LOADING', true)
        try {
            commit('SET_VALIDATION_ERRORS', {})
            if (state.item.id || state.id) {
                const {data: updatedItem, status} = await this.$axios.put(`${state.apiPath}/${state.item.id || state.id}`, state.item)
                commit('SET_OPERATION_RESULT', {
                    status: 'success',
                    message: 'Данные сохранены!'
                })
            } else {
                const {data: createdItem, status} = await this.$axios.post(state.apiPath, state.item)
                commit('SET_ITEM', createdItem)
                commit('SET_OPERATION_RESULT', {
                    status: 'success',
                    message: 'Запись создана!'
                })
            }
        } catch (e) {
            if (e.response && e.response.status) {
                commit('SET_OPERATION_RESULT', {
                    status: 'error',
                    message: 'Ошибка сохранения! Проверьте правильность заполнения полей и попробуйте снова'
                })
                switch (e.response.status) {
                    case 400:
                        const validationErrors = {};
                        e.response.data.errors.violations.forEach(error => {
                            set(validationErrors, error.propertyPath, error.title)
                        });
                        commit('SET_VALIDATION_ERRORS', validationErrors)
                        break;
                    default:
                        throw e
                }
            }
        } finally {
            commit('SET_IS_LOADING', false)
            window.scrollTo({top: 0})
        }
       /* this.app.$bvToast.toast(`2123 body content`, {
            title: `Toaster`,
            toaster: 'b-toaster-top-center',
            solid: true,
            appendToast: true
        })*/
    },
    updateItem({commit, state}, {key, value}) {
        commit('SET_ITEM', {
            ...state.item,
            [key]: value
        })
    },
    updateByPath({commit, state}, {key, value}) {
        commit('SET_PROPERTY_BY_PATH', {key, value})
    }
}
