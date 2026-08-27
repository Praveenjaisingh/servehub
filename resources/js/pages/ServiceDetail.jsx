import { useEffect, useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { servicesApi } from '../api/services'
import { apiErrorMessage } from '../api/axios'
import { useAuth } from '../context/AuthContext'
import Spinner from '../components/ui/Spinner'
import Alert from '../components/ui/Alert'
import Rating from '../components/ui/Rating'
import BookServiceForm from './customer/BookServiceForm'

export default function ServiceDetail() {
  const { id } = useParams()
  const { user } = useAuth()
  const navigate = useNavigate()
  const [service, setService] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [showBooking, setShowBooking] = useState(false)

  useEffect(() => {
    setLoading(true)
    servicesApi
      .get(id)
      .then(setService)
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }, [id])

  if (loading) return <Spinner />
  if (error) return <div className="container"><Alert>{error}</Alert></div>
  if (!service) return null

  const provider = service.provider

  const handleBookClick = () => {
    if (!user) return navigate('/login', { state: { from: { pathname: `/services/${id}` } } })
    if (user.role !== 'customer') return
    setShowBooking(true)
  }

  return (
    <div className="container" style={{ maxWidth: 880 }}>
      <div className="eyebrow">{service.category?.name}</div>
      <div className="page-header">
        <div>
          <h1 style={{ marginBottom: 6 }}>{service.title}</h1>
          <p
            style={{ cursor: 'pointer', color: 'var(--primary)', fontWeight: 600 }}
            onClick={() => navigate(`/providers/${provider?.id}`)}
          >
            {provider?.business_name} {provider?.is_verified && <span className="badge">Verified</span>}
          </p>
        </div>
        <div style={{ textAlign: 'right' }}>
          <div className="price" style={{ fontSize: '1.4rem' }}>
            ₹{service.price} {service.price_type === 'hourly' ? '/ hr' : ''}
          </div>
          <Rating value={provider?.average_rating} count={provider?.total_reviews} />
        </div>
      </div>

      <div className="card" style={{ marginBottom: 24 }}>
        <h3>About this service</h3>
        <p style={{ whiteSpace: 'pre-line' }}>{service.description}</p>
        {service.duration_minutes && (
          <p style={{ fontSize: '0.85rem' }}>Typical duration: {service.duration_minutes} minutes</p>
        )}
      </div>

      {user?.role !== 'provider' && user?.role !== 'admin' && (
        <button className="btn btn-accent" onClick={handleBookClick}>
          Book this service
        </button>
      )}

      {showBooking && (
        <BookServiceForm service={service} onClose={() => setShowBooking(false)} />
      )}
    </div>
  )
}
