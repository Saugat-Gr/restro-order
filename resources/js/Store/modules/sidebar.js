export default {
  namespaced: true,

  state: () => ({
    visible: true,
    unfoldable: false,
  }),

  mutations: {
    TOGGLE_SIDEBAR(state, payload) {
      state.visible = payload !== undefined ? payload : !state.visible
    },
    TOGGLE_UNFOLDABLE(state) {
      state.unfoldable = !state.unfoldable
    },
  },

  actions: {
    toggleSidebar({ commit }, value) {
      commit('TOGGLE_SIDEBAR', value)
    },
    toggleUnfoldable({ commit }) {
      commit('TOGGLE_UNFOLDABLE')
    },
  },

  getters: {
    visible: (state) => state.visible,
    unfoldable: (state) => state.unfoldable,
  },
}       