import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { apiErrorMessage } from '../api/axios'
import Alert from '../components/ui/Alert'
import PasswordField from '../components/ui/PasswordField'

const ROLES = [
  { value: 'customer', label: 'Book services', hint: 'Find & hire local pros' },
  { value: 'provider', label: 'Offer services', hint: 'List your business' },
]

export default function Register() {
  const { register } = useAuth()
  const navigate = useNavigate()
  const [form, setForm] = useState({
    name: '', email: '', phone: '', password: '', password_confirmation: '', role: 'customer',
  })
  const [error, setError] = useState('')
  const [submitting, setSubmitting] = useState(false)

  const update = (key) => (e) => setForm((f) => ({ ...f, [key]: e.target.value }))

  const submit = async (e) => {
    e.preventDefault()
    setSubmitting(true)
    setError('')
    try {
      const user = await register(form)
      navigate(user.role === 'provider' ? '/provider' : '/my-bookings', { replace: true })
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
          <h2>Join thousands getting work done right.</h2>
          <p>Create your free account to start booking trusted local pros, or list your own services.</p>
          <ul className="auth-points">
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
              Free to join, no hidden fees
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
              Set up in under two minutes
            </li>
            <li>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
              Switch between booking &amp; offering anytime
            </li>
          </ul>
        </div>

        <div className="auth-footnote">ServeHub · Local Services Marketplace</div>
      </aside>

      <main className="auth-main">
        <div className="auth-form-wrap wide">
          <div className="eyebrow">Join ServeHub</div>
          <h1>Create your account</h1>

          <div className="auth-card">
            <Alert>{error}</Alert>
            <form onSubmit={submit}>
              <div className="field">
                <label>I want to</label>
                <div className="role-picker">
                  {ROLES.map((opt) => (
                    <button
                      type="button"
                      key={opt.value}
                      className={`role-card ${form.role === opt.value ? 'active' : ''}`}
                      onClick={() => setForm((f) => ({ ...f, role: opt.value }))}
                      aria-pressed={form.role === opt.value}
                    >
                      <strong>{opt.label}</strong>
                      <span>{opt.hint}</span>
                    </button>
                  ))}
                </div>
              </div>

              <div className="field">
                <label htmlFor="reg-name">Full name</label>
                <input id="reg-name" className="input" required autoComplete="name" value={form.name} onChange={update('name')} />
              </div>

              <div className="form-row">
                <div className="field">
                  <label htmlFor="reg-email">Email</label>
                  <input id="reg-email" type="email" className="input" required autoComplete="email" value={form.email} onChange={update('email')} />
                </div>
                <div className="field">
                  <label htmlFor="reg-phone">Phone (optional)</label>
                  <input id="reg-phone" className="input" autoComplete="tel" value={form.phone} onChange={update('phone')} />
                </div>
              </div>

              <div className="form-row">
                <PasswordField
                  id="reg-password"
                  label="Password"
                  required
                  minLength={8}
                  autoComplete="new-password"
                  placeholder="At least 8 characters"
                  value={form.password}
                  onChange={update('password')}
                />
                <PasswordField
                  id="reg-password-confirm"
                  label="Confirm password"
                  required
                  minLength={8}
                  autoComplete="new-password"
                  placeholder="Re-enter password"
                  value={form.password_confirmation}
                  onChange={update('password_confirmation')}
                />
              </div>

              <button className="btn btn-accent btn-block" disabled={submitting}>
                {submitting ? 'Creating account…' : 'Create account'}
              </button>
            </form>
          </div>

          <p className="auth-footer-link">
            Already have an account? <Link to="/login">Log in</Link>
          </p>
        </div>
      </main>
    </div>
  )
}
