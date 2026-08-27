import { useEffect, useState } from 'react'
import { categoriesApi } from '../../api/categories'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import Alert from '../../components/ui/Alert'
import Modal from '../../components/ui/Modal'

export default function AdminCategories() {
  const [categories, setCategories] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [showForm, setShowForm] = useState(false)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState({ name: '', description: '', status: 'active' })
  const [saving, setSaving] = useState(false)

  const load = () => {
    setLoading(true)
    categoriesApi.list(false).then(setCategories).catch((e) => setError(apiErrorMessage(e))).finally(() => setLoading(false))
  }

  useEffect(load, [])

  const openCreate = () => { setEditing(null); setForm({ name: '', description: '', status: 'active' }); setShowForm(true) }
  const openEdit = (c) => { setEditing(c); setForm({ name: c.name, description: c.description || '', status: c.status }); setShowForm(true) }

  const submit = async (e) => {
    e.preventDefault()
    setSaving(true)
    setError('')
    try {
      if (editing) await categoriesApi.update(editing.id, form)
      else await categoriesApi.create(form)
      setShowForm(false)
      load()
    } catch (err) {
      setError(apiErrorMessage(err))
    } finally {
      setSaving(false)
    }
  }

  const remove = async (c) => {
    if (!confirm(`Delete category "${c.name}"?`)) return
    try {
      await categoriesApi.remove(c.id)
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    }
  }

  return (
    <div>
      <div className="page-header">
        <div>
          <div className="eyebrow">Admin dashboard</div>
          <h1>Service categories</h1>
        </div>
        <button className="btn btn-accent" onClick={openCreate}>+ Add category</button>
      </div>

      <Alert>{error}</Alert>

      {loading ? (
        <Spinner />
      ) : (
        <div className="grid grid-3">
          {categories.map((c) => (
            <div key={c.id} className="card">
              <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                <h3>{c.name}</h3>
                <span className={`stamp stamp-${c.status}`}>{c.status}</span>
              </div>
              <p style={{ fontSize: '0.85rem' }}>{c.description}</p>
              <div style={{ display: 'flex', gap: 8 }}>
                <button className="btn btn-outline btn-sm" onClick={() => openEdit(c)}>Edit</button>
                <button className="btn btn-danger btn-sm" onClick={() => remove(c)}>Delete</button>
              </div>
            </div>
          ))}
        </div>
      )}

      {showForm && (
        <Modal title={editing ? 'Edit category' : 'Add category'} onClose={() => setShowForm(false)}>
          <form onSubmit={submit}>
            <div className="field">
              <label>Name</label>
              <input className="input" required value={form.name} onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))} />
            </div>
            <div className="field">
              <label>Description</label>
              <textarea className="textarea" value={form.description} onChange={(e) => setForm((f) => ({ ...f, description: e.target.value }))} />
            </div>
            <div className="field">
              <label>Status</label>
              <select className="select" value={form.status} onChange={(e) => setForm((f) => ({ ...f, status: e.target.value }))}>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <button className="btn btn-primary btn-block" disabled={saving}>{saving ? 'Saving…' : 'Save'}</button>
          </form>
        </Modal>
      )}
    </div>
  )
}
