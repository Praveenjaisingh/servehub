import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { apiErrorMessage } from '../api/axios'
import Alert from '../components/ui/Alert'

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
    <div className="container" style={{ maxWidth: 460, paddingTop: 40 }}>
      <div className="eyebrow">Join ServeHub</div>
      <h1>Create your account</h1>
      <div className="card">
        <Alert>{error}</Alert>
        <form onSubmit={submit}>
          <div className="field">
            <label>I want to</label>
            <div style={{ display: 'flex', gap: 10 }}>
              {[
                { value: 'customer', label: 'Book services' },
                { value: 'provider', label: 'Offer services' },
              ].map((opt) => (
                <button
                  type="button"
                  key={opt.value}
                  className={`btn ${form.role === opt.value ? 'btn-primary' : 'btn-outline'}`}
                  style={{ flex: 1 }}
                  onClick={() => setForm((f) => ({ ...f, role: opt.value }))}
                >
                  {opt.label}
                </button>
              ))}
            </div>
          </div>
          <div className="field">
            <label>Full name</label>
            <input className="input" required value={form.name} onChange={update('name')} />
          </div>
          <div className="form-row">
            <div className="field">
              <label>Email</label>
              <input type="email" className="input" required value={form.email} onChange={update('email')} />
            </div>
            <div className="field">
              <label>Phone (optional)</label>
              <input className="input" value={form.phone} onChange={update('phone')} />
            </div>
          </div>
          <div className="form-row">
            <div className="field">
              <label>Password</label>
              <input type="password" className="input" required minLength={8} value={form.password} onChange={update('password')} />
            </div>
            <div className="field">
              <label>Confirm password</label>
              <input
                type="password"
                className="input"
                required
                minLength={8}
                value={form.password_confirmation}
                onChange={update('password_confirmation')}
              />
            </div>
          </div>
          <button className="btn btn-accent btn-block" disabled={submitting}>
            {submitting ? 'Creating account…' : 'Create account'}
          </button>
        </form>
      </div>
      <p style={{ marginTop: 16, textAlign: 'center' }}>
        Already have an account? <Link to="/login">Log in</Link>
      </p>
    </div>
  )
}
