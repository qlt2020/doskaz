import { make } from "vuex-pathify";

export const state = () => ({
  categories: [],
  categoryId: []
});

export const mutations = make.mutations(state);

export const actions = {
  load({ commit }) {
    return this.$axios.get(`/api/objectCategories`).then(res => {
      commit("SET_CATEGORIES", res.data);
    });
  },
  getCategories({ commit, state }) {
    if (state.categories.length) {
      return;
    }
    return this.$axios.get(`/api/objectCategories`).then(res => {
      commit("SET_CATEGORIES", res.data);
    });
  }
};

export const getters = {
  retCategories: state => {
    return state.categories;
  }
};
