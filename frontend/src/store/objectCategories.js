import { make } from "vuex-pathify";

export const state = () => ({
  categories: [],
  categoryId: []
});

export const mutations = make.mutations(state);

export const actions = {
  load({ commit }) {
    return this.$axios.get(`/api/objectCategories`).then(res => {
      let other_index = res.data.findIndex(r => {
        return r.icon.includes('/drugoe.svg')
      })
      let other = res.data.find(r => {
        return r.icon.includes('/drugoe.svg')
      })
      res.data.splice(other_index, 1)
      res.data.push(other)
      res.data.forEach(r => {
        let other_index_sub = r.subCategories.findIndex(rs => {
          return rs.icon.includes('/drugoe.svg')
        })
        let other_sub = r.subCategories.find(rs => {
          return rs.icon.includes('/drugoe.svg')
        })
        r.subCategories.splice(other_index_sub, 1)
        r.subCategories.push(other_sub)
      })
      commit("SET_CATEGORIES", res.data);
    });
  },
  getCategories({ commit, state }) {
    return this.$axios.get(`/api/objectCategories`).then(res => {
      let other_index = res.data.findIndex(r => {
        return r.icon.includes('/drugoe.svg')
      })
      let other = res.data.find(r => {
        return r.icon.includes('/drugoe.svg')
      })
      res.data.splice(other_index, 1)
      res.data.push(other)
      res.data.forEach(r => {
        let other_index_sub = r.subCategories.findIndex(rs => {
          return rs.icon.includes('/drugoe.svg')
        })
        let other_sub = r.subCategories.find(rs => {
          return rs.icon.includes('/drugoe.svg')
        })
        r.subCategories.splice(other_index_sub, 1)
        r.subCategories.push(other_sub)
      })
      commit("SET_CATEGORIES", res.data);
    });
  }
};

export const getters = {
  retCategories: state => {
    return state.categories;
  }
};
