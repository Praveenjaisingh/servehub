import api from './axios'

export const authApi = {
  register: (payload) => api.post('/register', payload).then((r) => r.data.data),
  login: (payload) => api.post('/login', payload).then((r) => r.data.data),
  logout: () => api.post('/logout').then((r) => r.data),
  profile: () => api.get('/profile').then((r) => r.data.data),
}
