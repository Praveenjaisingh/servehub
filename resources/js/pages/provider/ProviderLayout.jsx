import { Outlet } from 'react-router-dom'
import DashboardNav from '../../components/layout/DashboardNav'

const links = [
  { to: '/provider', label: 'Overview', end: true },
  { to: '/provider/profile', label: 'Business profile' },
  { to: '/provider/services', label: 'My services' },
  { to: '/provider/availability', label: 'Availability' },
  { to: '/provider/bookings', label: 'Bookings' },
]

export default function ProviderLayout() {
  return (
    <div className="container">
      <div className="dash-layout">
        <DashboardNav links={links} />
        <div><Outlet /></div>
      </div>
    </div>
  )
}
