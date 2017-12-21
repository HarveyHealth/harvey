export default function() {
  let endpoint = null;

  if (App.Config.user.isPatient) {
    endpoint = `${App.Config.misc.api}patients/${App.Config.user.info.patient_id}/practitioners`;
  } else if (App.Config.user.isPractitioner) {
    endpoint = `${App.Config.misc.api}practitioners/${App.Config.user.info.practitioner_id}`;
  } else if (App.Config.user.isAdmin) {
    endpoint = `${App.Config.misc.api}practitioners`;
  }

  return `${endpoint}?include=user`;
}
