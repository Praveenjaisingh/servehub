import { NavLink, useNavigate } from 'react-router-dom'
import { useAuth } from '../../context/AuthContext'

export default function Navbar() {
  const { user, logout } = useAuth()
  const navigate = useNavigate()

  const handleLogout = async () => {
    await logout()
    navigate('/')
  }

  const dashboardPath =
    user?.role === 'admin' ? '/admin' : user?.role === 'provider' ? '/provider' : '/my-bookings'

  return (
    <header
      style={{
        position: 'sticky',
        top: 0,
        zIndex: 20,
        background: 'var(--surface)',
        borderBottom: '1px solid var(--line)',
      }}
    >
      <div className="container" style={{ display: 'flex', alignItems: 'center', height: 68, gap: 24 }}>
        <NavLink
          to="/"
          style={{
            fontFamily: 'var(--font-display)',
            fontWeight: 700,
            fontSize: '1.2rem',
            color: 'var(--ink)',
            textDecoration: 'none',
            display: 'flex',
            alignItems: 'center',
            gap: 8,
          }}
        >
          <span
            style={{
              width: 10,
              height: 10,
              background: 'var(--accent)',
              display: 'inline-block',
              borderRadius: 2,
            }}
          />
          ServeHub
        </NavLink>

        <nav style={{ display: 'flex', gap: 18, marginLeft: 8 }}>
          <NavLink to="/" end style={navStyle}>
            Browse
          </NavLink>
          <NavLink to="/providers" style={navStyle}>
            Providers
          </NavLink>
        </nav>

        <div style={{ marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 10 }}>
          {user ? (
            <>
              <NavLink to={dashboardPath} className="btn btn-outline btn-sm">
                Dashboard
              </NavLink>
              <span style={{ fontSize: '0.85rem', color: 'var(--ink-soft)' }}>{user.name}</span>
              <button className="btn btn-ghost btn-sm" onClick={handleLogout}>
                Log out
              </button>
            </>
          ) : (
            <>
              <NavLink to="/login" className="btn btn-ghost btn-sm">
                Log in
              </NavLink>
              <NavLink to="/register" className="btn btn-accent btn-sm">
                Get started
              </NavLink>
            </>
          )}
        </div>
      </div>
    </header>
  )
}

const navStyle = ({ isActive }) => ({
  fontSize: '0.9rem',
  fontWeight: 600,
  color: isActive ? 'var(--primary)' : 'var(--ink-soft)',
  textDecoration: 'none',
})
