const LABELS = {
  pending: 'Pending',
  accepted: 'Accepted',
  in_progress: 'In progress',
  completed: 'Completed',
  rejected: 'Rejected',
  cancelled: 'Cancelled',
  active: 'Active',
  inactive: 'Inactive',
}

export default function Stamp({ status }) {
  const label = LABELS[status] || status
  return <span className={`stamp stamp-${status}`}>{label}</span>
}
