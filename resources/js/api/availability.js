import api from './axios'

export const availabilityApi = {
  list: () => api.get('/availability').then((r) => r.data.data),
  save: (slots) => api.post('/availability', { slots }).then((r) => r.data.data),
  remove: (id) => api.delete(`/availability/${id}`).then((r) => r.data),
}
