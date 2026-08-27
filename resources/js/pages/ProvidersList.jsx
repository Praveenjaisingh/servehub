import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { providersApi } from '../api/providers'
import { apiErrorMessage } from '../api/axios'
import Spinner from '../components/ui/Spinner'
import EmptyState from '../components/ui/EmptyState'
import Alert from '../components/ui/Alert'
import Rating from '../components/ui/Rating'
import Pagination from '../components/ui/Pagination'

export default function ProvidersList() {
  const [providers, setProviders] = useState([])
  const [meta, setMeta] = useState(null)
  const [city, setCity] = useState('')
  const [page, setPage] = useState(1)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    setLoading(true)
    providersApi
      .list({ city: city || undefined, page })
      .then((res) => {
        setProviders(res.data)
        setMeta(res.meta)
      })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }, [city, page])

  return (
    <div className="container">
      <div className="page-header">
        <div>
          <div className="eyebrow">Verified pros</div>
          <h1>Providers</h1>
        </div>
        <input
          className="input"
          style={{ maxWidth: 220 }}
          placeholder="Filter by city"
          value={city}
          onChange={(e) => { setCity(e.target.value); setPage(1) }}
        />
      </div>

      <Alert>{error}</Alert>

      {loading ? (
        <Spinner />
      ) : providers.length === 0 ? (
        <EmptyState title="No providers found" hint="Try a different city." />
      ) : (
        <>
          <div className="grid grid-3">
            {providers.map((p) => (
              <Link key={p.id} to={`/providers/${p.id}`} className="card" style={{ textDecoration: 'none', color: 'inherit' }}>
                <h3 style={{ marginBottom: 4 }}>{p.business_name}</h3>
                <p style={{ fontSize: '0.85rem' }}>{p.city || 'Location not set'}</p>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <Rating value={p.average_rating} count={p.total_reviews} />
                  {p.is_verified && <span className="badge">Verified</span>}
                </div>
              </Link>
            ))}
          </div>
          <Pagination meta={meta} onPageChange={setPage} />
        </>
      )}
    </div>
  )
}
