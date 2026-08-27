import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { bookingsApi } from '../../api/bookings'
import { providersApi } from '../../api/providers'
import Spinner from '../../components/ui/Spinner'
import Stamp from '../../components/ui/Stamp'
import EmptyState from '../../components/ui/EmptyState'

export default function ProviderOverview() {
  const [profile, setProfile] = useState(null)
  const [profileMissing, setProfileMissing] = useState(false)
  const [bookings, setBookings] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    Promise.all([
      providersApi.me().catch(() => { setProfileMissing(true); return null }),
      bookingsApi.forProvider({ status: 'pending', per_page: 5 }).catch(() => ({ data: [] })),
    ])
      .then(([p, b]) => {
        setProfile(p)
        setBookings(b.data)
      })
      .finally(() => setLoading(false))
  }, [])

  if (loading) return <Spinner />

  return (
    <div>
      <div className="eyebrow">Provider dashboard</div>
      <h1>Overview</h1>

      {profileMissing && (
        <div className="card" style={{ marginBottom: 20, borderColor: 'var(--accent)' }}>
          <h3>Set up your business profile</h3>
          <p>Customers can't find you until your provider profile is complete.</p>
          <Link to="/provider/profile" className="btn btn-accent btn-sm">Complete profile</Link>
        </div>
      )}

      {profile && (
        <div className="grid grid-3" style={{ marginBottom: 28 }}>
          <div className="stat-tile">
            <div className="value">{profile.average_rating?.toFixed?.(1) ?? '—'}</div>
            <div className="label">Average rating</div>
          </div>
          <div className="stat-tile">
            <div className="value">{profile.total_reviews}</div>
            <div className="label">Total reviews</div>
          </div>
          <div className="stat-tile">
            <div className="value">{profile.is_verified ? 'Yes' : 'Pending'}</div>
            <div className="label">Verified</div>
          </div>
        </div>
      )}

      <h2>Pending booking requests</h2>
      {bookings.length === 0 ? (
        <EmptyState title="Nothing waiting on you" hint="New booking requests will show up here." />
      ) : (
        <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
          {bookings.map((b) => (
            <Link key={b.id} to="/provider/bookings" className="ticket" style={{ textDecoration: 'none', color: 'inherit' }}>
              <div className="ticket-body">
                <div className="ticket-ref">{b.reference}</div>
                <h3 style={{ margin: '4px 0' }}>{b.service?.title}</h3>
                <p style={{ margin: 0, fontSize: '0.85rem' }}>{b.customer?.name} · {b.booking_date}</p>
              </div>
              <div className="ticket-stub">
                <Stamp status={b.status} />
              </div>
            </Link>
          ))}
        </div>
      )}
    </div>
  )
}
