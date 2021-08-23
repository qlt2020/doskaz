import {make} from 'vuex-pathify'

export const state = () => ({
    user: null
});

export const mutations = make.mutations(state);

export const actions = {
    async loadUser({commit}) {
        const {data: user} = await this.$axios.get('/api/admin/profile');
        commit('SET_USER', user);
    },
    async logout() {
        await this.$axios.delete('/api/token');
        window.location.href = '/'
    }
};

export const getters = {
    can: state => attribute => {
        return state.user.permissions.includes(attribute)
    }
}

