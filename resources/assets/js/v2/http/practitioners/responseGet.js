export default function(response) {
  App.State.app.practitioners.all = response.data.data;
  App.State.app.practitioners.licensed = response.data.data;
  if (App.Config.isPatient) {
    App.State.app.clientPractitioner = App.Fn.findClientPractitioner(App.Config.user.doctor_name);
    App.State.app.practitioners.licensed = App.Fn.filterForLicensing(App.Config.user.state);
  }
  App.State.app.received.practitioners = true;
  App.Util.debug('Response: GET practitioners')(App.State.app.practitioners);
}
