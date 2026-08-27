import { useEffect, useState } from 'react'
import { Link, useNavigate, useParams } from 'react-router-dom'
import { bookingsApi } from '../../api/bookings'
import { reviewsApi } from '../../api/reviews'
import { apiErrorMessage } from '../../api/axios'
import { useAuth } from '../../context/AuthContext'
import Spinner from '../../components/ui/Spinner'
import Alert from '../../components/ui/Alert'
import Stamp from '../../components/ui/Stamp'
import Modal from '../../components/ui/Modal'

export default function BookingDetail() {
  const { id } = useParams()
  const { user } = useAuth()
  const navigate = useNavigate()
  const [booking, setBooking] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [showCancel, setShowCancel] = useState(false)
  const [showReview, setShowReview] = useState(false)
  const [reason, setReason] = useState('')
  const [rating, setRating] = useState(5)
  const [comment, setComment] = useState('')
  const [busy, setBusy] = useState(false)

  const load = () => {
    setLoading(true)
    bookingsApi
      .get(id)
      .then(setBooking)
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }

  useEffect(load, [id])

  if (loading) return <Spinner />
  if (error) return <Alert>{error}</Alert>
  if (!booking) return null

  const canCancel = ['pending', 'accepted', 'in_progress'].includes(booking.status)
  const canReview = booking.status === 'completed' && !booking.review && user?.role === 'customer'

  const submitCancel = async () => {
    setBusy(true)
    try {
      await bookingsApi.cancel(id, reason)
      setShowCancel(false)
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    } finally {
      setBusy(false)
    }
  }

  const submitReview = async () => {
    setBusy(true)
    try {
      await reviewsApi.create({ booking_id: booking.id, rating, comment })
      setShowReview(false)
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    } finally {
      setBusy(false)
    }
  }

  return (
    <div style={{ maxWidth: 640 }}>
      <button className="btn btn-ghost btn-sm" onClick={() => navigate(-1)} style={{ marginBottom: 12 }}>
        ← Back
      </button>

      <div className="ticket" style={{ marginBottom: 20 }}>
        <div className="ticket-body">
          <div className="ticket-ref">{booking.reference}</div>
          <h2 style={{ margin: '6px 0' }}>{booking.service?.title}</h2>
          <p style={{ margin: 0 }}>
            <Link to={`/providers/${booking.provider?.id}`}>{booking.provider?.business_name}</Link>
          </p>
        </div>
        <div className="ticket-stub">
          <Stamp status={booking.status} />
          <span className="price">₹{booking.total_amount}</span>
        </div>
      </div>

      <Alert>{error}</Alert>

      <div className="card" style={{ marginBottom: 20 }}>
        <h3>Details</h3>
        <table className="table">
          <tbody>
            <tr><td>Date</td><td>{booking.booking_date}</td></tr>
            <tr><td>Time</td><td>{booking.booking_time?.slice(0, 5)}</td></tr>
            <tr><td>Address</td><td>{booking.address}</td></tr>
            {booking.notes && <tr><td>Notes</td><td>{booking.notes}</td></tr>}
            {booking.cancelled_reason && <tr><td>Cancellation reason</td><td>{booking.cancelled_reason}</td></tr>}
          </tbody>
        </table>
      </div>

      {booking.review && (
        <div className="card" style={{ marginBottom: 20 }}>
          <h3>Your review</h3>
          <p>★ {booking.review.rating} — {booking.review.comment}</p>
        </div>
      )}

      <div style={{ display: 'flex', gap: 10 }}>
        {canCancel && (
          <button className="btn btn-danger" onClick={() => setShowCancel(true)}>Cancel booking</button>
        )}
        {canReview && (
          <button className="btn btn-accent" onClick={() => setShowReview(true)}>Leave a review</button>
        )}
      </div>

      {showCancel && (
        <Modal title="Cancel this booking?" onClose={() => setShowCancel(false)}>
          <div className="field">
            <label>Reason (optional)</label>
            <textarea className="textarea" value={reason} onChange={(e) => setReason(e.target.value)} />
          </div>
          <button className="btn btn-danger btn-block" disabled={busy} onClick={submitCancel}>
            {busy ? 'Cancelling…' : 'Confirm cancellation'}
          </button>
        </Modal>
      )}

      {showReview && (
        <Modal title="Rate this provider" onClose={() => setShowReview(false)}>
          <div className="field">
            <label>Rating</label>
            <div style={{ display: 'flex', gap: 6 }}>
              {[1, 2, 3, 4, 5].map((n) => (
                <button
                  type="button"
                  key={n}
                  className={`btn btn-sm ${rating === n ? 'btn-accent' : 'btn-outline'}`}
                  onClick={() => setRating(n)}
                >
                  {n}★
                </button>
              ))}
            </div>
          </div>
          <div className="field">
            <label>Comment (optional)</label>
            <textarea className="textarea" value={comment} onChange={(e) => setComment(e.target.value)} />
          </div>
          <button className="btn btn-accent btn-block" disabled={busy} onClick={submitReview}>
            {busy ? 'Submitting…' : 'Submit review'}
          </button>
        </Modal>
      )}
    </div>
  )
}
