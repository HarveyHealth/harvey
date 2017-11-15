export default function (status, userType) {
  const noShow = ['No-Show-Doctor', 'No-Show-Patient'].indexOf(status) >= 0;
  const isPatient = userType === 'patient';

  return noShow && isPatient ? 'Missed Appointment' : status;
}
