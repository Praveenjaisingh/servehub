import { Link } from 'react-router-dom'

export default function NotFound() {
  return (
    <div className="container" style={{ textAlign: 'center', padding: '80px 0' }}>
      <div className="eyebrow" style={{ justifyContent: 'center' }}>404</div>
      <h1>This page isn't on the job sheet.</h1>
      <p>The page you're looking for doesn't exist or has moved.</p>
      <Link to="/" className="btn btn-primary">Back to browsing</Link>
    </div>
  )
}
