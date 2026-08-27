import api from './axios'

export const categoriesApi = {
  list: (activeOnly = true) =>
    api.get('/service-categories', { params: { active_only: activeOnly ? 1 : 0 } }).then((r) => r.data.data),
  get: (id) => api.get(`/service-categories/${id}`).then((r) => r.data.data),
  create: (payload) => api.post('/admin/service-categories', payload).then((r) => r.data.data),
  update: (id, payload) => api.put(`/admin/service-categories/${id}`, payload).then((r) => r.data.data),
  remove: (id) => api.delete(`/admin/service-categories/${id}`).then((r) => r.data),
}
