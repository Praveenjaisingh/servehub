import api from './axios'

export const providersApi = {
  list: (params = {}) => api.get('/providers', { params }).then((r) => r.data.data),
  get: (id) => api.get(`/providers/${id}`).then((r) => r.data.data),
  me: () => api.get('/provider-profile/me').then((r) => r.data.data),
  save: (payload) => api.post('/provider-profile', payload).then((r) => r.data.data),
  update: (payload) => api.put('/provider-profile', payload).then((r) => r.data.data),
  verify: (id) => api.post(`/admin/providers/${id}/verify`).then((r) => r.data.data),
}
