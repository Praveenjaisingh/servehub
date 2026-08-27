import api from './axios'

export const reviewsApi = {
  forProvider: (providerId, params = {}) =>
    api.get(`/providers/${providerId}/reviews`, { params }).then((r) => r.data.data),
  create: (payload) => api.post('/reviews', payload).then((r) => r.data.data),
  remove: (id) => api.delete(`/reviews/${id}`).then((r) => r.data),
}
