import { Routes, Route } from 'react-router-dom'
import { AuthProvider } from './context/AuthContext'
import Navbar from './components/layout/Navbar'
import ProtectedRoute from './components/layout/ProtectedRoute'

import Home from './pages/Home'
import ServiceDetail from './pages/ServiceDetail'
import ProvidersList from './pages/ProvidersList'
import ProviderProfile from './pages/ProviderProfile'
import Login from './pages/Login'
import Register from './pages/Register'
import NotFound from './pages/NotFound'

import MyBookings from './pages/customer/MyBookings'
import BookingDetail from './pages/customer/BookingDetail'

import ProviderLayout from './pages/provider/ProviderLayout'
import ProviderOverview from './pages/provider/ProviderOverview'
import ProviderProfileEdit from './pages/provider/ProviderProfileEdit'
import MyServices from './pages/provider/MyServices'
import Availability from './pages/provider/Availability'
import ProviderBookings from './pages/provider/ProviderBookings'

import AdminLayout from './pages/admin/AdminLayout'
import AdminOverview from './pages/admin/AdminOverview'
import AdminUsers from './pages/admin/AdminUsers'
import AdminCategories from './pages/admin/AdminCategories'
import AdminProviders from './pages/admin/AdminProviders'

export default function App() {
  return (
    <AuthProvider>
      <div className="app-shell">
        <Navbar />
        <main className="app-main">
          <Routes>
            {/* Public */}
            <Route path="/" element={<Home />} />
            <Route path="/services/:id" element={<ServiceDetail />} />
            <Route path="/providers" element={<ProvidersList />} />
            <Route path="/providers/:id" element={<ProviderProfile />} />
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />

            {/* Customer */}
            <Route
              path="/my-bookings"
              element={
                <ProtectedRoute roles={['customer']}>
                  <div className="container"><MyBookings /></div>
                </ProtectedRoute>
              }
            />
            <Route
              path="/my-bookings/:id"
              element={
                <ProtectedRoute roles={['customer']}>
                  <div className="container"><BookingDetail /></div>
                </ProtectedRoute>
              }
            />

            {/* Provider */}
            <Route
              path="/provider"
              element={
                <ProtectedRoute roles={['provider']}>
                  <ProviderLayout />
                </ProtectedRoute>
              }
            >
              <Route index element={<ProviderOverview />} />
              <Route path="profile" element={<ProviderProfileEdit />} />
              <Route path="services" element={<MyServices />} />
              <Route path="availability" element={<Availability />} />
              <Route path="bookings" element={<ProviderBookings />} />
            </Route>

            {/* Admin */}
            <Route
              path="/admin"
              element={
                <ProtectedRoute roles={['admin']}>
                  <AdminLayout />
                </ProtectedRoute>
              }
            >
              <Route index element={<AdminOverview />} />
              <Route path="users" element={<AdminUsers />} />
              <Route path="categories" element={<AdminCategories />} />
              <Route path="providers" element={<AdminProviders />} />
            </Route>

            <Route path="*" element={<NotFound />} />
          </Routes>
        </main>
      </div>
    </AuthProvider>
  )
}
