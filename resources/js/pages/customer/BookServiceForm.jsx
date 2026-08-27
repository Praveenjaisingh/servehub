import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { bookingsApi } from '../../api/bookings'
import { apiErrorMessage } from '../../api/axios'
import Modal from '../../components/ui/Modal'
import Alert from '../../components/ui/Alert'

export default function BookServiceForm({ service, onClose }) {
  const navigate = useNavigate()
  const [form, setForm] = useState({ booking_date: '', booking_time: '', address: '', notes: '' })
  const [error, setError] = useState('')
  const [submitting, setSubmitting] = useState(false)

  const update = (key) => (e) => setForm((f) => ({ ...f, [key]: e.target.value }))

  const submit = async (e) => {
    e.preventDefault()
    setSubmitting(true)
    setError('')
    try {
      const booking = await bookingsApi.create({ service_id: service.id, ...form })
      onClose()
      navigate(`/my-bookings/${booking.id}`)
    } catch (err) {
      setError(apiErrorMessage(err))
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <Modal title={`Book: ${service.title}`} onClose={onClose}>
      <Alert>{error}</Alert>
      <form onSubmit={submit}>
        <div className="form-row">
          <div className="field">
            <label>Date</label>
            <input
              type="date"
              className="input"
              required
              min={new Date().toISOString().slice(0, 10)}
              value={form.booking_date}
              onChange={update('booking_date')}
            />
          </div>
          <div className="field">
            <label>Time</label>
            <input type="time" className="input" required value={form.booking_time} onChange={update('booking_time')} />
          </div>
        </div>
        <div className="field">
          <label>Service address</label>
          <input
            className="input"
            required
            placeholder="Where should the provider come?"
            value={form.address}
            onChange={update('address')}
          />
        </div>
        <div className="field">
          <label>Notes (optional)</label>
          <textarea
            className="textarea"
            placeholder="Anything the provider should know beforehand"
            value={form.notes}
            onChange={update('notes')}
          />
        </div>
        <button className="btn btn-accent btn-block" disabled={submitting}>
          {submitting ? 'Sending request…' : `Request booking — ₹${service.price}`}
        </button>
      </form>
    </Modal>
  )
}
