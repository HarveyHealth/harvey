export default function(status) {
  const statuses = {
    'pending': 'Pending',
    'no_show_patient': 'No-Show-Patient',
    'no_show_doctor': 'No-Show-Doctor',
    'general_conflict': 'General Conflict',
    'canceled': 'Canceled',
    'complete': 'Complete'
  }
  return statuses[status];
}
