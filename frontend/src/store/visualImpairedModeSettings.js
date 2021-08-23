import {make} from 'vuex-pathify'
import defaults from 'lodash/defaults'

export const state = () => ({
    enabled: false,
    fontFamily: 'Lato',
    colorTheme: 'white',
    fontSize: 'lrg'
})

export const mutations = make.mutations(state);

export const actions = {
    async init({commit}) {
        const settings = defaults(this.app.$cookies.get('vi.settings') || {}, {
            enabled: false,
            fontFamily: 'Lato',
            colorTheme: 'white',
            fontSize: 'lrg'
        })
        commit('SET_ENABLED', settings.enabled)
        commit('SET_FONT_FAMILY', settings.fontFamily)
        commit('SET_COLOR_THEME', settings.colorTheme)
        commit('SET_FONT_SIZE', settings.fontSize)
    },

    enable({commit, dispatch}) {
        commit('SET_ENABLED', true)
        dispatch('updateSettings')
        this.$router.go()
    },
    disable({commit, dispatch}) {
        commit('SET_ENABLED', false)
        dispatch('updateSettings')
        this.$router.go()
    },
    toggle({commit, dispatch, state: {enabled}}) {
        commit('SET_ENABLED', !enabled)
        dispatch('updateSettings')
        this.$router.go()
    },
    changeFontFamily({commit, dispatch}, fontFamily) {
        commit('SET_FONT_FAMILY', fontFamily)
        dispatch('updateSettings')
    },
    changeFontSize({commit, dispatch}, fontSize) {
        commit('SET_FONT_SIZE', fontSize)
        dispatch('updateSettings')
    },
    changeColorTheme({commit, dispatch}, colorTheme) {
        commit('SET_COLOR_THEME', colorTheme)
        dispatch('updateSettings')
    },
    updateSettings({state}) {
        this.app.$cookies.set('vi.settings', state, {
            maxAge: 3600 * 24 * 365,
            path: '/'
        });
    }
}

export const getters = {
    bodyClasses: (state) => state.enabled ? ['fontFamily', 'colorTheme', 'fontSize'].map(key => state[key]).join(' '): ''
}