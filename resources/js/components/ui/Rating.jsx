export default function Rating({ value = 0, count }) {
  const rounded = Math.round((value || 0) * 10) / 10
  return (
    <span className="rating">
      ★ {rounded || '—'}
      {typeof count === 'number' && (
        <span style={{ color: 'var(--ink-faint)', fontWeight: 500 }}>&nbsp;({count})</span>
      )}
    </span>
  )
}
