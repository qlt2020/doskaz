import { make } from "vuex-pathify";
import last from "lodash/last";

export const state = () => ({
  items: []
});

export const mutations = make.mutations(state);

export const actions = {
  async load({ commit }) {
    const awards = await this.$axios.$get("/api/profile/awards");
    commit("SET_ITEMS", awards);
  }
};

export const getters = {
  last: state => last(state.items)
};
