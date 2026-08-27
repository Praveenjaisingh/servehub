import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { servicesApi } from '../api/services'
import { categoriesApi } from '../api/categories'
import { apiErrorMessage } from '../api/axios'
import Spinner from '../components/ui/Spinner'
import EmptyState from '../components/ui/EmptyState'
import Alert from '../components/ui/Alert'
import Pagination from '../components/ui/Pagination'

export default function Home() {
  const [categories, setCategories] = useState([])
  const [services, setServices] = useState([])
  const [meta, setMeta] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  const [search, setSearch] = useState('')
  const [categoryId, setCategoryId] = useState(null)
  const [page, setPage] = useState(1)

  useEffect(() => {
    categoriesApi.list(true).then(setCategories).catch(() => {})
  }, [])

  useEffect(() => {
    setLoading(true)
    setError('')
    servicesApi
      .list({ search: search || undefined, category_id: categoryId || undefined, page })
      .then((res) => {
        setServices(res.data)
        setMeta(res.meta)
      })
      .catch((e) => setError(apiErrorMessage(e)))
      .finally(() => setLoading(false))
  }, [search, categoryId, page])

  return (
    <div className="container">
      <section style={{ padding: '20px 0 40px' }}>
        <div className="eyebrow">Local trades, on the clock</div>
        <h1 style={{ maxWidth: 620 }}>Book a vetted local pro, without the guesswork.</h1>
        <p style={{ maxWidth: 520, fontSize: '1.02rem' }}>
          Electricians, plumbers, cleaners, tutors and more — search by trade, compare providers, and book a slot
          that fits your day.
        </p>

        <form
          onSubmit={(e) => {
            e.preventDefault()
            setPage(1)
          }}
          style={{ display: 'flex', gap: 10, maxWidth: 480, marginTop: 20 }}
        >
          <input
            className="input"
            placeholder="Search “ceiling fan install”, “deep clean”…"
            value={search}
            onChange={(e) => {
              setSearch(e.target.value)
              setPage(1)
            }}
          />
        </form>
      </section>

      <div className="pill-nav">
        <button className={categoryId === null ? 'active' : ''} onClick={() => { setCategoryId(null); setPage(1) }}>
          All categories
        </button>
        {categories.map((c) => (
          <button
            key={c.id}
            className={categoryId === c.id ? 'active' : ''}
            onClick={() => { setCategoryId(c.id); setPage(1) }}
          >
            {c.name}
          </button>
        ))}
      </div>

      <Alert>{error}</Alert>

      {loading ? (
        <Spinner />
      ) : services.length === 0 ? (
        <EmptyState title="No services match yet" hint="Try a different search term or category." />
      ) : (
        <>
          <div className="grid grid-3">
            {services.map((service) => (
              <Link
                key={service.id}
                to={`/services/${service.id}`}
                className="card"
                style={{ textDecoration: 'none', color: 'inherit', display: 'block' }}
              >
                <div className="eyebrow">{service.category?.name}</div>
                <h3 style={{ marginBottom: 6 }}>{service.title}</h3>
                <p style={{ fontSize: '0.88rem', minHeight: 40, overflow: 'hidden' }}>
                  {service.description?.slice(0, 90)}
                  {service.description?.length > 90 ? '…' : ''}
                </p>
                <hr className="divider" style={{ margin: '12px 0' }} />
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <span className="price">
                    ₹{service.price} {service.price_type === 'hourly' ? '/ hr' : ''}
                  </span>
                  <span style={{ fontSize: '0.82rem', color: 'var(--ink-faint)' }}>
                    {service.provider?.business_name}
                  </span>
                </div>
              </Link>
            ))}
          </div>
          <Pagination meta={meta} onPageChange={setPage} />
        </>
      )}
    </div>
  )
}
