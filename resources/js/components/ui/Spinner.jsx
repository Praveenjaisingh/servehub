export default function Spinner({ center = true }) {
  if (center) {
    return (
      <div className="spinner-center">
        <div className="spinner" />
      </div>
    )
  }
  return <div className="spinner" />
}
