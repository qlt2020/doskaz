import { make } from "vuex-pathify";

export const state = () => ({
  clickedObjectId: 0,
});

export const mutations = {
  ...make.mutations(state),
};