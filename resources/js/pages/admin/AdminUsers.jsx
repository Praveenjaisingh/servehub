import { useEffect, useState } from 'react'
import { adminApi } from '../../api/admin'
import { apiErrorMessage } from '../../api/axios'
import Spinner from '../../components/ui/Spinner'
import Alert from '../../components/ui/Alert'
import Pagination from '../../components/ui/Pagination'

const ROLES = ['all', 'customer', 'provider', 'admin']

export default function AdminUsers() {
  const [users, setUsers] = useState([])
  const [meta, setMeta] = useState(null)
  const [role, setRole] = useState('all')
  const [search, setSearch] = useState('')
  const [page, setPage] = useState(1)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  const load = () => {
    setLoading(true)
    adminApi
      .users({ role: role === 'all' ? undefined : role, search: search || undefined, page })
      .then((res) => { setUsers(res.data); setMeta(res.meta) })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }

  useEffect(load, [role, page])

  const toggleStatus = async (user) => {
    const nextStatus = user.status === 'active' ? 'suspended' : 'active'
    try {
      await adminApi.updateUserStatus(user.id, nextStatus)
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    }
  }

  const removeUser = async (user) => {
    if (!confirm(`Delete ${user.name}? This cannot be undone.`)) return
    try {
      await adminApi.deleteUser(user.id)
      load()
    } catch (e) {
      setError(apiErrorMessage(e))
    }
  }

  return (
    <div>
      <div className="page-header">
        <div>
          <div className="eyebrow">Admin dashboard</div>
          <h1>Users</h1>
        </div>
        <form onSubmit={(e) => { e.preventDefault(); setPage(1); load() }} style={{ display: 'flex', gap: 8 }}>
          <input className="input" placeholder="Search name or email" value={search} onChange={(e) => setSearch(e.target.value)} />
          <button className="btn btn-outline">Search</button>
        </form>
      </div>

      <div className="pill-nav">
        {ROLES.map((r) => (
          <button key={r} className={role === r ? 'active' : ''} onClick={() => { setRole(r); setPage(1) }}>
            {r}
          </button>
        ))}
      </div>

      <Alert>{error}</Alert>

      {loading ? (
        <Spinner />
      ) : (
        <div className="card" style={{ padding: 0, overflow: 'hidden' }}>
          <table className="table">
            <thead>
              <tr>
                <th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th>
              </tr>
            </thead>
            <tbody>
              {users.map((u) => (
                <tr key={u.id}>
                  <td>{u.name}</td>
                  <td>{u.email}</td>
                  <td style={{ textTransform: 'capitalize' }}>{u.role}</td>
                  <td>
                    <span className={`stamp stamp-${u.status === 'active' ? 'active' : 'cancelled'}`}>{u.status}</span>
                  </td>
                  <td>
                    <div style={{ display: 'flex', gap: 6 }}>
                      <button className="btn btn-outline btn-sm" onClick={() => toggleStatus(u)}>
                        {u.status === 'active' ? 'Suspend' : 'Reactivate'}
                      </button>
                      {u.role !== 'admin' && (
                        <button className="btn btn-danger btn-sm" onClick={() => removeUser(u)}>Delete</button>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
      <Pagination meta={meta} onPageChange={setPage} />
    </div>
  )
}
