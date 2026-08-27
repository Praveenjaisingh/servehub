import api from './axios'

export const adminApi = {
  users: (params = {}) => api.get('/admin/users', { params }).then((r) => r.data.data),
  user: (id) => api.get(`/admin/users/${id}`).then((r) => r.data.data),
  updateUserStatus: (id, status) => api.patch(`/admin/users/${id}/status`, { status }).then((r) => r.data.data),
  deleteUser: (id) => api.delete(`/admin/users/${id}`).then((r) => r.data),
  stats: () => api.get('/admin/dashboard/stats').then((r) => r.data.data),
  revenue: (params = {}) => api.get('/admin/dashboard/revenue', { params }).then((r) => r.data.data),
  bookingReport: (params = {}) => api.get('/admin/dashboard/bookings', { params }).then((r) => r.data.data),
}
