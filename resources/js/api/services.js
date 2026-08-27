import api from './axios'

export const servicesApi = {
  list: (params = {}) => api.get('/services', { params }).then((r) => r.data.data),
  get: (id) => api.get(`/services/${id}`).then((r) => r.data.data),
  mine: (params = {}) => api.get('/my-services', { params }).then((r) => r.data.data),
  create: (payload) => api.post('/services', payload).then((r) => r.data.data),
  update: (id, payload) => api.put(`/services/${id}`, payload).then((r) => r.data.data),
  remove: (id) => api.delete(`/services/${id}`).then((r) => r.data),
}
