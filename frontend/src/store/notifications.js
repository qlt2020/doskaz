import { make } from "vuex-pathify";

export const state = () => ({
  data: [],
  subscribed: false,
});

export const mutations = make.mutations(state);

export const getters = {
  data: (state) => {
    return state.data;
  },
};

export const actions = {
  async subscribe({commit}, category) {
    return new Promise(async (resolve) => {
      const res = await this.$axios.$post("/api/notifications/subscribe", {
        content: {
          type: 'objectCategory',
          category
        }
      });
      commit('SET_SUBSCRIBED', true)
      resolve(res)
    })
  },
  async unsubscribe({commit}) {
    return new Promise(async (resolve) => {
      const res = await this.$axios.$get("/api/notifications/unsubscribe");
      commit('SET_SUBSCRIBED', false)
      resolve(res)
    })
  }
};
