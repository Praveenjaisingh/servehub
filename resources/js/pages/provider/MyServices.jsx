import { useEffect, useState } from 'react'
import { servicesApi } from '../../api/services'
import { categoriesApi } from '../../api/categories'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import EmptyState from '../../components/ui/EmptyState'
import Alert from '../../components/ui/Alert'
import Stamp from '../../components/ui/Stamp'
import ServiceFormModal from './ServiceFormModal'

export default function MyServices() {
  const [services, setServices] = useState([])
  const [categories, setCategories] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [showForm, setShowForm] = useState(false)
  const [editing, setEditing] = useState(null)

  const load = () => {
    setLoading(true)
    servicesApi
      .mine()
      .then((res) => setServices(res.data))
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    load()
    categoriesApi.list(true).then(setCategories).catch(() => {})
  }, [])

  const remove = async (id) => {
    if (!confirm('Delete this service?')) return
    try {
      await servicesApi.remove(id)
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    }
  }

  return (
    <div>
      <div className="page-header">
        <div>
          <div className="eyebrow">Provider dashboard</div>
          <h1>My services</h1>
        </div>
        <button className="btn btn-accent" onClick={() => { setEditing(null); setShowForm(true) }}>
          + Add service
        </button>
      </div>

      <Alert>{error}</Alert>

      {loading ? (
        <Spinner />
      ) : services.length === 0 ? (
        <EmptyState title="No services yet" hint="Add your first service so customers can book you." />
      ) : (
        <div className="grid grid-3">
          {services.map((s) => (
            <div key={s.id} className="card">
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                <h3 style={{ marginBottom: 4 }}>{s.title}</h3>
                <Stamp status={s.status} />
              </div>
              <p style={{ fontSize: '0.85rem' }}>{s.category?.name}</p>
              <span className="price">₹{s.price} {s.price_type === 'hourly' ? '/ hr' : ''}</span>
              <div style={{ display: 'flex', gap: 8, marginTop: 14 }}>
                <button className="btn btn-outline btn-sm" onClick={() => { setEditing(s); setShowForm(true) }}>Edit</button>
                <button className="btn btn-danger btn-sm" onClick={() => remove(s.id)}>Delete</button>
              </div>
            </div>
          ))}
        </div>
      )}

      {showForm && (
        <ServiceFormModal
          categories={categories}
          existing={editing}
          onClose={() => setShowForm(false)}
          onSaved={() => { setShowForm(false); load() }}
        />
      )}
    </div>
  )
}
