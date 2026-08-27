import { useEffect, useState } from 'react'
import { servicesApi } from '../../api/services'
import { apiErrorMessage } from '../../api/axios'
import Modal from '../../components/ui/Modal'
import Alert from '../../components/ui/Alert'

export default function ServiceFormModal({ categories, existing, onClose, onSaved }) {
  const [form, setForm] = useState({
    category_id: existing?.category_id || categories[0]?.id || '',
    title: existing?.title || '',
    description: existing?.description || '',
    price: existing?.price || '',
    price_type: existing?.price_type || 'fixed',
    duration_minutes: existing?.duration_minutes || '',
    status: existing?.status || 'active',
  })
  const [error, setError] = useState('')
  const [saving, setSaving] = useState(false)

  const update = (key) => (e) => setForm((f) => ({ ...f, [key]: e.target.value }))

  const submit = async (e) => {
    e.preventDefault()
    setSaving(true)
    setError('')
    const payload = {
      ...form,
      category_id: Number(form.category_id),
      price: Number(form.price),
      duration_minutes: form.duration_minutes ? Number(form.duration_minutes) : undefined,
    }
    try {
      if (existing) {
        await servicesApi.update(existing.id, payload)
      } else {
        await servicesApi.create(payload)
      }
      onSaved()
    } catch (err) {
      setError(apiErrorMessage(err))
    } finally {
      setSaving(false)
    }
  }

  return (
    <Modal title={existing ? 'Edit service' : 'Add a service'} onClose={onClose} width={560}>
      <Alert>{error}</Alert>
      <form onSubmit={submit}>
        <div className="field">
          <label>Title</label>
          <input className="input" required value={form.title} onChange={update('title')} />
        </div>
        <div className="field">
          <label>Description</label>
          <textarea className="textarea" required value={form.description} onChange={update('description')} />
        </div>
        <div className="form-row">
          <div className="field">
            <label>Category</label>
            <select className="select" required value={form.category_id} onChange={update('category_id')}>
              {categories.map((c) => (
                <option key={c.id} value={c.id}>{c.name}</option>
              ))}
            </select>
          </div>
          <div className="field">
            <label>Pricing type</label>
            <select className="select" value={form.price_type} onChange={update('price_type')}>
              <option value="fixed">Fixed</option>
              <option value="hourly">Hourly</option>
            </select>
          </div>
        </div>
        <div className="form-row">
          <div className="field">
            <label>Price (₹)</label>
            <input type="number" min="0" step="0.01" className="input" required value={form.price} onChange={update('price')} />
          </div>
          <div className="field">
            <label>Duration (minutes)</label>
            <input type="number" min="1" className="input" value={form.duration_minutes} onChange={update('duration_minutes')} />
          </div>
        </div>
        {existing && (
          <div className="field">
            <label>Status</label>
            <select className="select" value={form.status} onChange={update('status')}>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        )}
        <button className="btn btn-primary btn-block" disabled={saving}>
          {saving ? 'Saving…' : existing ? 'Save changes' : 'Create service'}
        </button>
      </form>
    </Modal>
  )
}
