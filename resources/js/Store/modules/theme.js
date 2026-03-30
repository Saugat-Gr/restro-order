export default {
  namespaced: true,

  state: () => ({
    theme: 'light'
  }),

  mutations: {
    SET_THEME(state, payload) {
      state.theme = payload
    }
  },

  actions: {
    toggleTheme({ commit }, theme) {
      commit('SET_THEME', theme)
    }
  },

  getters: {
    theme: (state) => state.theme
  }
}