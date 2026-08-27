import api from './axios'

export const notificationsApi = {
  list: (params = {}) => api.get('/notifications', { params }).then((r) => r.data.data),
  markAsRead: (id) => api.post(`/notifications/${id}/read`).then((r) => r.data.data),
  markAllAsRead: () => api.post('/notifications/read-all').then((r) => r.data.data),
}
