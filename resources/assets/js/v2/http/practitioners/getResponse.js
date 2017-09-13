export default function(response) {
  App.State.data.practitioners.all = response.data.data;
  App.State.data.practitioners.licensed = response.data.data;

  if (App.Config.user.isPatient) {
    App.State.data.practitioners.licensed = App.Logic.practitioners.filterByLicense(App.Config.user.info.state);
    App.State.dashboard.practitioner = App.Logic.dashboard.findPractitioner(App.Config.user.info.doctor_name);
  }

  App.State.received.practitioners = true;
  App.Util.misc.debug('Response: GET practitioners')(App.State.data.practitioners);
}
