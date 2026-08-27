import { useEffect, useState } from 'react'
import { availabilityApi } from '../../api/availability'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import Alert from '../../components/ui/Alert'

const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

export default function Availability() {
  const [slots, setSlots] = useState(
    DAYS.map((_, day_of_week) => ({ day_of_week, start_time: '09:00', end_time: '17:00', is_available: false }))
  )
  const [loading, setLoading] = useState(true)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState('')
  const [success, setSuccess] = useState('')

  useEffect(() => {
    availabilityApi
      .list()
      .then((existing) => {
        setSlots((prev) =>
          prev.map((slot) => {
            const found = existing.find((e) => e.day_of_week === slot.day_of_week)
            return found
              ? { ...slot, start_time: found.start_time.slice(0, 5), end_time: found.end_time.slice(0, 5), is_available: found.is_available }
              : slot
          })
        )
      })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }, [])

  const updateSlot = (day, key, value) => {
    setSlots((prev) => prev.map((s) => (s.day_of_week === day ? { ...s, [key]: value } : s)))
  }

  const submit = async () => {
    setSaving(true)
    setError('')
    setSuccess('')
    try {
      await availabilityApi.save(slots)
      setSuccess('Availability updated.')
    } catch (e) {
      setError(apiErrorMessage(e))
    } finally {
      setSaving(false)
    }
  }

  if (loading) return <Spinner />

  return (
    <div style={{ maxWidth: 640 }}>
      <div className="eyebrow">Provider dashboard</div>
      <h1>Weekly availability</h1>
      <div className="card">
        <Alert>{error}</Alert>
        <Alert type="success">{success}</Alert>
        {slots.map((slot) => (
          <div
            key={slot.day_of_week}
            style={{
              display: 'grid',
              gridTemplateColumns: '120px 1fr 1fr auto',
              gap: 10,
              alignItems: 'center',
              padding: '10px 0',
              borderBottom: '1px solid var(--line)',
            }}
          >
            <strong style={{ fontSize: '0.88rem' }}>{DAYS[slot.day_of_week]}</strong>
            <input
              type="time"
              className="input"
              disabled={!slot.is_available}
              value={slot.start_time}
              onChange={(e) => updateSlot(slot.day_of_week, 'start_time', e.target.value)}
            />
            <input
              type="time"
              className="input"
              disabled={!slot.is_available}
              value={slot.end_time}
              onChange={(e) => updateSlot(slot.day_of_week, 'end_time', e.target.value)}
            />
            <label style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: '0.82rem' }}>
              <input
                type="checkbox"
                checked={slot.is_available}
                onChange={(e) => updateSlot(slot.day_of_week, 'is_available', e.target.checked)}
              />
              Open
            </label>
          </div>
        ))}
        <button className="btn btn-primary" style={{ marginTop: 16 }} disabled={saving} onClick={submit}>
          {saving ? 'Saving…' : 'Save availability'}
        </button>
      </div>
    </div>
  )
}
