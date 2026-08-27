import { useEffect, useState } from 'react'
import { bookingsApi } from '../../api/bookings'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import EmptyState from '../../components/ui/EmptyState'
import Alert from '../../components/ui/Alert'
import Stamp from '../../components/ui/Stamp'
import Pagination from '../../components/ui/Pagination'
import Modal from '../../components/ui/Modal'

const STATUS_FILTERS = ['all', 'pending', 'accepted', 'in_progress', 'completed', 'cancelled', 'rejected']

const NEXT_ACTIONS = {
  pending: [{ status: 'accepted', label: 'Accept', cls: 'btn-primary' }, { status: 'rejected', label: 'Reject', cls: 'btn-danger' }],
  accepted: [{ status: 'in_progress', label: 'Start job', cls: 'btn-primary' }, { status: 'cancelled', label: 'Cancel', cls: 'btn-danger' }],
  in_progress: [{ status: 'completed', label: 'Mark completed', cls: 'btn-primary' }, { status: 'cancelled', label: 'Cancel', cls: 'btn-danger' }],
}

export default function ProviderBookings() {
  const [bookings, setBookings] = useState([])
  const [meta, setMeta] = useState(null)
  const [status, setStatus] = useState('all')
  const [page, setPage] = useState(1)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')
  const [pendingAction, setPendingAction] = useState(null) // { booking, status }
  const [reason, setReason] = useState('')
  const [busy, setBusy] = useState(false)

  const load = () => {
    setLoading(true)
    bookingsApi
      .forProvider({ status: status === 'all' ? undefined : status, page })
      .then((res) => { setBookings(res.data); setMeta(res.meta) })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }

  useEffect(load, [status, page])

  const applyAction = async () => {
    setBusy(true)
    try {
      await bookingsApi.updateStatus(pendingAction.booking.id, pendingAction.status, reason || undefined)
      setPendingAction(null)
      setReason('')
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    } finally {
      setBusy(false)
    }
  }

  const needsReason = pendingAction && ['cancelled', 'rejected'].includes(pendingAction.status)

  return (
    <div>
      <div className="eyebrow">Provider dashboard</div>
      <h1>Bookings</h1>

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
        <EmptyState title="No bookings in this view" />
      ) : (
        <>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
            {bookings.map((b) => (
              <div key={b.id} className="ticket">
                <div className="ticket-body">
                  <div className="ticket-ref">{b.reference}</div>
                  <h3 style={{ margin: '4px 0' }}>{b.service?.title}</h3>
                  <p style={{ margin: 0, fontSize: '0.85rem' }}>
                    {b.customer?.name} · {b.booking_date} at {b.booking_time?.slice(0, 5)}
                  </p>
                </div>
                <div className="ticket-stub">
                  <Stamp status={b.status} />
                  <div style={{ display: 'flex', gap: 6 }}>
                    {(NEXT_ACTIONS[b.status] || []).map((action) => (
                      <button
                        key={action.status}
                        className={`btn btn-sm ${action.cls}`}
                        onClick={() => setPendingAction({ booking: b, status: action.status })}
                      >
                        {action.label}
                      </button>
                    ))}
                  </div>
                </div>
              </div>
            ))}
          </div>
          <Pagination meta={meta} onPageChange={setPage} />
        </>
      )}

      {pendingAction && (
        <Modal title={`Confirm: ${pendingAction.status.replace('_', ' ')}`} onClose={() => setPendingAction(null)}>
          {needsReason && (
            <div className="field">
              <label>Reason</label>
              <textarea className="textarea" value={reason} onChange={(e) => setReason(e.target.value)} />
            </div>
          )}
          <button className="btn btn-primary btn-block" disabled={busy} onClick={applyAction}>
            {busy ? 'Saving…' : 'Confirm'}
          </button>
        </Modal>
      )}
    </div>
  )
}
