import { NavLink } from 'react-router-dom'

export default function DashboardNav({ links }) {
  return (
    <aside className="dash-sidebar">
      {links.map((link) => (
        <NavLink key={link.to} to={link.to} end={link.end} className={({ isActive }) => (isActive ? 'active' : '')}>
          {link.label}
        </NavLink>
      ))}
    </aside>
  )
}
