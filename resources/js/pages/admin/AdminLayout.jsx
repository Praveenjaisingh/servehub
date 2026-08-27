import { Outlet } from 'react-router-dom'
import DashboardNav from '../../components/layout/DashboardNav'

const links = [
  { to: '/admin', label: 'Overview', end: true },
  { to: '/admin/users', label: 'Users' },
  { to: '/admin/categories', label: 'Categories' },
  { to: '/admin/providers', label: 'Providers' },
]

export default function AdminLayout() {
  return (
    <div className="container">
      <div className="dash-layout">
        <DashboardNav links={links} />
        <div><Outlet /></div>
      </div>
    </div>
  )
}
