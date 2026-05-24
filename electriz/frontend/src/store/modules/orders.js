import axios from 'axios'
export default {
  namespaced: true,
  state: () => ({ items: [], current: null, loading: false }),
  mutations: { SET_ORDERS(state, items) { state.items = items }, SET_CURRENT(state, o) { state.current = o }, SET_LOADING(state, v) { state.loading = v } },
  getters: { items: s => s.items, current: s => s.current, loading: s => s.loading },
  actions: {
    async fetchOrders({ commit }) { commit('SET_LOADING', true); try { const { data } = await axios.get('/orders'); commit('SET_ORDERS', data.data || data) } finally { commit('SET_LOADING', false) } },
    async createOrder({ dispatch }, payload) { const { data } = await axios.post('/orders', payload); await dispatch('fetchOrders'); return data },
    async fetchOrder({ commit }, id) { const { data } = await axios.get(`/orders/${id}`); commit('SET_CURRENT', data.data || data) },
  },
}
