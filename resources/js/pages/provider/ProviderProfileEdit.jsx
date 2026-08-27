import { useEffect, useState } from 'react'
import { providersApi } from '../../api/providers'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import Alert from '../../components/ui/Alert'

const emptyForm = {
  business_name: '', bio: '', experience_years: '', skills: '', city: '', address: '',
}

export default function ProviderProfileEdit() {
  const [form, setForm] = useState(emptyForm)
  const [exists, setExists] = useState(false)
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')

  useEffect(() => {
    providersApi
      .me()
      .then((p) => {
        setExists(true)
        setForm({
          business_name: p.business_name || '',
          bio: p.bio || '',
          experience_years: p.experience_years ?? '',
          skills: (p.skills || []).join(', '),
          city: p.city || '',
          address: p.address || '',
        })
      })
      .catch(() => setExists(false))
      .finally(() => setLoading(false))
  }, [])

  const update = (key) => (e) => setForm((f) => ({ ...f, [key]: e.target.value }))

  const submit = async (e) => {
    e.preventDefault()
    setSaving(true)
    setError('')
    setSuccess('')
    const payload = {
      ...form,
      experience_years: form.experience_years === '' ? undefined : Number(form.experience_years),
      skills: form.skills ? form.skills.split(',').map((s) => s.trim()).filter(Boolean) : [],
    }
    try {
      if (exists) {
        await providersApi.update(payload)
      } else {
        await providersApi.save(payload)
        setExists(true)
      }
      setSuccess('Profile saved.')
    } catch (err) {
      setError(apiErrorMessage(err))
    } finally {
      setSaving(false)
    }
  }

  if (loading) return <Spinner />

  return (
    <div style={{ maxWidth: 560 }}>
      <div className="eyebrow">Provider dashboard</div>
      <h1>Business profile</h1>
      <div className="card">
        <Alert>{error}</Alert>
        <Alert type="success">{success}</Alert>
        <form onSubmit={submit}>
          <div className="field">
            <label>Business name</label>
            <input className="input" required value={form.business_name} onChange={update('business_name')} />
          </div>
          <div className="field">
            <label>Bio</label>
            <textarea className="textarea" value={form.bio} onChange={update('bio')} placeholder="Tell customers about your experience" />
          </div>
          <div className="form-row">
            <div className="field">
              <label>Years of experience</label>
              <input type="number" min="0" className="input" value={form.experience_years} onChange={update('experience_years')} />
            </div>
            <div className="field">
              <label>City</label>
              <input className="input" value={form.city} onChange={update('city')} />
            </div>
          </div>
          <div className="field">
            <label>Skills (comma separated)</label>
            <input className="input" placeholder="wiring, panel upgrades, inspections" value={form.skills} onChange={update('skills')} />
          </div>
          <div className="field">
            <label>Service address</label>
            <input className="input" value={form.address} onChange={update('address')} />
          </div>
          <button className="btn btn-primary" disabled={saving}>
            {saving ? 'Saving…' : 'Save profile'}
          </button>
        </form>
      </div>
    </div>
  )
}
