import { make } from "vuex-pathify";

export const state = () => ({
  cityName: null,
  position: [],
  loaded: false,
});

export const mutations = { ...make.mutations(state) };

export const actions = {
  ...make.actions(state),
};

export const getters = {
  ...make.getters(state),
};
