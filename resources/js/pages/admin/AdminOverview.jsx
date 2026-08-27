import { useEffect, useState } from 'react'
import { adminApi } from '../../api/admin'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import Alert from '../../components/ui/Alert'

export default function AdminOverview() {
  const [stats, setStats] = useState(null)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    adminApi
      .stats()
      .then(setStats)
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }, [])

  if (loading) return <Spinner />

  const tiles = stats && [
    { label: 'Customers', value: stats.total_customers },
    { label: 'Providers', value: stats.total_providers },
    { label: 'Verified providers', value: stats.verified_providers },
    { label: 'Services listed', value: stats.total_services },
    { label: 'Total bookings', value: stats.total_bookings },
    { label: 'Pending bookings', value: stats.pending_bookings },
    { label: 'Completed bookings', value: stats.completed_bookings },
    { label: 'Total revenue', value: `₹${Number(stats.total_revenue).toLocaleString('en-IN')}` },
  ]

  return (
    <div>
      <div className="eyebrow">Admin dashboard</div>
      <h1>Platform overview</h1>
      <Alert>{error}</Alert>
      {tiles && (
        <div className="grid grid-4">
          {tiles.map((t) => (
            <div className="stat-tile" key={t.label}>
              <div className="value">{t.value}</div>
              <div className="label">{t.label}</div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
