import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { bookingsApi } from '../../api/bookings'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import EmptyState from '../../components/ui/EmptyState'
import Alert from '../../components/ui/Alert'
import Stamp from '../../components/ui/Stamp'
import Pagination from '../../components/ui/Pagination'

const STATUS_FILTERS = ['all', 'pending', 'accepted', 'in_progress', 'completed', 'cancelled', 'rejected']

export default function MyBookings() {
  const [bookings, setBookings] = useState([])
  const [meta, setMeta] = useState(null)
  const [status, setStatus] = useState('all')
  const [page, setPage] = useState(1)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  useEffect(() => {
    setLoading(true)
    bookingsApi
      .mine({ status: status === 'all' ? undefined : status, page })
      .then((res) => {
        setBookings(res.data)
        setMeta(res.meta)
      })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }, [status, page])

  return (
    <div>
      <div className="page-header">
        <div>
          <div className="eyebrow">Your work orders</div>
          <h1>My bookings</h1>
        </div>
      </div>

      <div className="pill-nav">
        {STATUS_FILTERS.map((s) => (
          <button key={s} className={status === s ? 'active' : ''} onClick={() => { setStatus(s); setPage(1) }}>
            {s === 'all' ? 'All' : s.replace('_', ' ')}
          </button>
        ))}
      </div>

      <Alert>{error}</Alert>

      {loading ? (
        <Spinner />
      ) : bookings.length === 0 ? (
        <EmptyState title="No bookings here" hint="Browse services and request your first booking." />
      ) : (
        <>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
            {bookings.map((b) => (
              <Link key={b.id} to={`/my-bookings/${b.id}`} className="ticket" style={{ textDecoration: 'none', color: 'inherit' }}>
                <div className="ticket-body">
                  <div className="ticket-ref">{b.reference}</div>
                  <h3 style={{ margin: '4px 0' }}>{b.service?.title}</h3>
                  <p style={{ margin: 0, fontSize: '0.85rem' }}>
                    {b.provider?.business_name} · {b.booking_date} at {b.booking_time?.slice(0, 5)}
                  </p>
                </div>
                <div className="ticket-stub">
                  <Stamp status={b.status} />
                  <span className="price">₹{b.total_amount}</span>
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
