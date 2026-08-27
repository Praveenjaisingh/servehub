import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { providersApi } from '../../api/providers'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import Alert from '../../components/ui/Alert'
import Rating from '../../components/ui/Rating'
import Pagination from '../../components/ui/Pagination'

export default function AdminProviders() {
  const [providers, setProviders] = useState([])
  const [meta, setMeta] = useState(null)
  const [page, setPage] = useState(1)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  const load = () => {
    setLoading(true)
    providersApi
      .list({ page })
      .then((res) => { setProviders(res.data); setMeta(res.meta) })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }

  useEffect(load, [page])

  const verify = async (id) => {
    try {
      await providersApi.verify(id)
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    }
  }

  return (
    <div>
      <div className="eyebrow">Admin dashboard</div>
      <h1>Providers</h1>
      <Alert>{error}</Alert>

      {loading ? (
        <Spinner />
      ) : (
        <div className="grid grid-3">
          {providers.map((p) => (
            <div key={p.id} className="card">
              <h3><Link to={`/providers/${p.id}`}>{p.business_name}</Link></h3>
              <p style={{ fontSize: '0.85rem' }}>{p.city}</p>
              <Rating value={p.average_rating} count={p.total_reviews} />
              <div style={{ marginTop: 12 }}>
                {p.is_verified ? (
                  <span className="badge">Verified</span>
                ) : (
                  <button className="btn btn-accent btn-sm" onClick={() => verify(p.id)}>Verify provider</button>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
      <Pagination meta={meta} onPageChange={setPage} />
    </div>
  )
}
