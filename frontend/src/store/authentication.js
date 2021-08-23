import {commit, make} from 'vuex-pathify'


export const state = () => ({
    authenticated: false,
    user: null,
    created: false
});

export const mutations = make.mutations(state);

export const actions = {
    async loadUser({commit, rootState}) {
        const {data: user} = await this.$axios.get('/api/profile', {
            validateStatus(status) {
                return status < 500
            },
            params: {
                cityId: rootState.settings.cityId
            }
        });
        commit('SET_USER', user);
    },
    async deAuthenticate({commit}) {
        commit('SET_USER', null)
    },
    async oauthAuthenticate({dispatch}, {code, provider}) {
        const {status} = await this.$axios.post('/api/token/oauth', {provider, code});
        await dispatch('loadUser');
        if (status === 201) {
            commit('SET_CREATED', true)
            await this.$router.push('/login?popup=true')
        } else {
            const redirect = this.app.$cookies.get('redirect') || this.app.localePath('/');
            this.app.$cookies.remove('redirect');
            window.location.href = redirect
        }
    },
    async phoneAuthenticate({dispatch}, idToken) {
        const {status} = await this.$axios.post('/api/token/phone', {idToken});
        await dispatch('loadUser');

        if (status === 201) {
            commit('SET_CREATED', true)
            await this.$router.push(this.app.localePath('/login?popup=true'))
        } else {
            const redirect = this.app.$cookies.get('redirect') || this.app.localePath('/');
            this.app.$cookies.remove('redirect');
            window.location.href = redirect
        }
    },
    async logout({dispatch}) {
        await this.$axios.delete('/api/token');
        dispatch('deAuthenticate')
        await this.$router.push(this.app.localePath('/'))
    }
}

export const getters = {
    name: (state) => [state.user.lastName, state.user.firstName].filter(item => !!item).join(' ')
}