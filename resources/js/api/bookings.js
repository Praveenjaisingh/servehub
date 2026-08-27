import api from './axios'

export const bookingsApi = {
  create: (payload) => api.post('/bookings', payload).then((r) => r.data.data),
  get: (id) => api.get(`/bookings/${id}`).then((r) => r.data.data),
  mine: (params = {}) => api.get('/my-bookings', { params }).then((r) => r.data.data),
  forProvider: (params = {}) => api.get('/provider-bookings', { params }).then((r) => r.data.data),
  updateStatus: (id, status, reason) =>
    api.patch(`/bookings/${id}/status`, { status, reason }).then((r) => r.data.data),
  cancel: (id, reason) => api.post(`/bookings/${id}/cancel`, { reason }).then((r) => r.data.data),
}
