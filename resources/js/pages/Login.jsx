import { useState } from 'react'
import { Link, useLocation, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { apiErrorMessage } from '../api/axios'
import Alert from '../components/ui/Alert'
import PasswordField from '../components/ui/PasswordField'

export default function Login() {
  const { login } = useAuth()
  const navigate = useNavigate()
  const location = useLocation()
  const [form, setForm] = useState({ email: '', password: '' })
  const [error, setError] = useState('')
  const [submitting, setSubmitting] = useState(false)

  const submit = async (e) => {
    e.preventDefault()
    setSubmitting(true)
    setError('')
    try {
      const user = await login(form)
      const from = location.state?.from?.pathname
      const fallback = user.role === 'admin' ? '/admin' : user.role === 'provider' ? '/provider' : '/my-bookings'
      navigate(from || fallback, { replace: true })
    } catch (err) {
      setError(apiErrorMessage(err))
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="auth-shell">
      <aside className="auth-side">
        <div className="auth-brand">
          <span className="auth-brand-mark">SH</span>
          ServeHub
        </div>

        <div className="auth-copy">
          <h2>Trusted local pros, booked in minutes.</h2>
          <p>Sign in to manage your bookings, message providers, and keep every job on record.</p>
          <ul className="auth-points">
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
              Verified, background-checked providers
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
              Real-time booking status &amp; history
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
              Transparent pricing, no surprises
            </li>
          </ul>
        </div>

        <div className="auth-footnote">ServeHub · Local Services Marketplace</div>
      </aside>

      <main className="auth-main">
        <div className="auth-form-wrap">
          <div className="eyebrow">Welcome back</div>
          <h1>Log in</h1>

          <div className="auth-card">
            <Alert>{error}</Alert>
            <form onSubmit={submit}>
              <div className="field">
                <label htmlFor="login-email">Email</label>
                <input
                  id="login-email"
                  type="email"
                  className="input"
                  required
                  autoComplete="email"
                  placeholder="you@example.com"
                  value={form.email}
                  onChange={(e) => setForm((f) => ({ ...f, email: e.target.value }))}
                />
              </div>

              <PasswordField
                id="login-password"
                label="Password"
                required
                autoComplete="current-password"
                placeholder="••••••••"
                value={form.password}
                onChange={(e) => setForm((f) => ({ ...f, password: e.target.value }))}
              />

              <button className="btn btn-primary btn-block" disabled={submitting}>
                {submitting ? 'Logging in…' : 'Log in'}
              </button>
            </form>
          </div>

          <p className="auth-footer-link">
            New to ServeHub? <Link to="/register">Create an account</Link>
          </p>
        </div>
      </main>
    </div>
  )
}
