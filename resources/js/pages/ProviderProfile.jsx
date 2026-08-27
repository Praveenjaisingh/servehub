import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'
import { providersApi } from '../api/providers'
import { reviewsApi } from '../api/reviews'
import { apiErrorMessage } from '../api/axios'
import Spinner from '../components/ui/Spinner'
import Alert from '../components/ui/Alert'
import Rating from '../components/ui/Rating'
import EmptyState from '../components/ui/EmptyState'

export default function ProviderProfile() {
  const { id } = useParams()
  const [provider, setProvider] = useState(null)
  const [reviews, setReviews] = useState([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    setLoading(true)
    Promise.all([providersApi.get(id), reviewsApi.forProvider(id)])
      .then(([p, r]) => {
        setProvider(p)
        setReviews(r.data)
      })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }, [id])

  if (loading) return <Spinner />
  if (error) return <div className="container"><Alert>{error}</Alert></div>
  if (!provider) return null

  return (
    <div className="container" style={{ maxWidth: 880 }}>
      <div className="page-header">
        <div>
          <div className="eyebrow">{provider.city || 'Provider'}</div>
          <h1 style={{ marginBottom: 4 }}>
            {provider.business_name} {provider.is_verified && <span className="badge">Verified</span>}
          </h1>
          <Rating value={provider.average_rating} count={provider.total_reviews} />
        </div>
      </div>

      {provider.bio && (
        <div className="card" style={{ marginBottom: 24 }}>
          <h3>About</h3>
          <p>{provider.bio}</p>
          {provider.skills?.length > 0 && (
            <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap', marginTop: 8 }}>
              {provider.skills.map((s) => (
                <span key={s} className="badge">{s}</span>
              ))}
            </div>
          )}
        </div>
      )}

      <h2>Services</h2>
      {provider.services?.length ? (
        <div className="grid grid-3" style={{ marginBottom: 32 }}>
          {provider.services.map((s) => (
            <Link key={s.id} to={`/services/${s.id}`} className="card" style={{ textDecoration: 'none', color: 'inherit' }}>
              <h3 style={{ marginBottom: 6 }}>{s.title}</h3>
              <span className="price">₹{s.price} {s.price_type === 'hourly' ? '/ hr' : ''}</span>
            </Link>
          ))}
        </div>
      ) : (
        <EmptyState title="No active services yet" />
      )}

      <h2>Reviews</h2>
      {reviews.length ? (
        <div className="grid grid-2">
          {reviews.map((r) => (
            <div key={r.id} className="card card-compact">
              <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                <strong>{r.customer?.name}</strong>
                <Rating value={r.rating} />
              </div>
              {r.comment && <p style={{ marginTop: 6 }}>{r.comment}</p>}
            </div>
          ))}
        </div>
      ) : (
        <EmptyState title="No reviews yet" hint="Be the first to book and leave feedback." />
      )}
    </div>
  )
}
