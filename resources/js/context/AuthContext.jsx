import { createContext, useCallback, useContext, useEffect, useState } from 'react'
import { authApi } from '../api/auth'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => {
    const raw = localStorage.getItem('servehub_user')
    return raw ? JSON.parse(raw) : null
  })
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const token = localStorage.getItem('servehub_token')
    if (!token) {
      setLoading(false)
      return
    }
    authApi
      .profile()
      .then((freshUser) => {
        setUser(freshUser)
        localStorage.setItem('servehub_user', JSON.stringify(freshUser))
      })
      .catch(() => {
        localStorage.removeItem('servehub_token')
        localStorage.removeItem('servehub_user')
        setUser(null)
      })
      .finally(() => setLoading(false))
  }, [])

  const persist = (result) => {
    localStorage.setItem('servehub_token', result.token)
    localStorage.setItem('servehub_user', JSON.stringify(result.user))
    setUser(result.user)
  }

  const login = useCallback(async (credentials) => {
    const result = await authApi.login(credentials)
    persist(result)
    return result.user
  }, [])

  const register = useCallback(async (payload) => {
    const result = await authApi.register(payload)
    persist(result)
    return result.user
  }, [])

  const logout = useCallback(async () => {
    try {
      await authApi.logout()
    } catch {
      /* token may already be invalid — clear locally regardless */
    }
    localStorage.removeItem('servehub_token')
    localStorage.removeItem('servehub_user')
    setUser(null)
  }, [])

  const refreshUser = useCallback(async () => {
    const freshUser = await authApi.profile()
    setUser(freshUser)
    localStorage.setItem('servehub_user', JSON.stringify(freshUser))
    return freshUser
  }, [])

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout, refreshUser }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const ctx = useContext(AuthContext)
  if (!ctx) throw new Error('useAuth must be used within AuthProvider')
  return ctx
}
