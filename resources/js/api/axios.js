import axios from 'axios'

const baseURL = import.meta.env.VITE_API_BASE_URL || '/api'

const api = axios.create({
  baseURL,
  headers: { Accept: 'application/json' },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('servehub_token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

api.interceptors.response.use(
  (response) => {
    // If backend returned HTTP 200/2xx but the envelope indicates a failure:
    if (response.data && typeof response.data === 'object' && response.data.status === false) {
      const error = new Error(response.data.message || 'Request failed.')
      error.response = response
      return Promise.reject(error)
    }
    return response
  },
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('servehub_token')
      localStorage.removeItem('servehub_user')
      if (!window.location.pathname.startsWith('/login')) {
        window.location.href = '/login'
      }
    }
    return Promise.reject(error)
  }
)

/** Pulls a friendly message out of ServeHub's { status, message, errors } envelope. */
export function apiErrorMessage(error) {
  const data = error?.response?.data
  if (data?.errors) {
    const first = Array.isArray(data.errors) ? data.errors[0] : Object.values(data.errors)[0]
    if (Array.isArray(first)) return first[0]
    if (typeof first === 'string') return first
  }
  if (data?.message) return data.message
  return error?.message || 'Something went wrong.'
}

export default api
