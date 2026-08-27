export default function Pagination({ meta, onPageChange }) {
  if (!meta || meta.last_page <= 1) return null

  const pages = Array.from({ length: meta.last_page }, (_, i) => i + 1).filter(
    (p) => p === 1 || p === meta.last_page || Math.abs(p - meta.current_page) <= 1
  )

  return (
    <div style={{ display: 'flex', gap: 6, justifyContent: 'center', marginTop: 28, flexWrap: 'wrap' }}>
      <button
        className="btn btn-outline btn-sm"
        disabled={meta.current_page <= 1}
        onClick={() => onPageChange(meta.current_page - 1)}
      >
        Prev
      </button>
      {pages.map((p, i) => (
        <span key={p} style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
          {i > 0 && pages[i - 1] !== p - 1 && <span style={{ color: 'var(--ink-faint)' }}>…</span>}
          <button
            className={`btn btn-sm ${p === meta.current_page ? 'btn-primary' : 'btn-outline'}`}
            onClick={() => onPageChange(p)}
          >
            {p}
          </button>
        </span>
      ))}
      <button
        className="btn btn-outline btn-sm"
        disabled={meta.current_page >= meta.last_page}
        onClick={() => onPageChange(meta.current_page + 1)}
      >
        Next
      </button>
    </div>
  )
}
