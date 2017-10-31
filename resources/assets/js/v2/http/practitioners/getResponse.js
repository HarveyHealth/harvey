export default function(response) {
  App.setState('practitioners.data.all', response.data.data);
  App.setState('practitioners.data.licensed', response.data.data);

  if (App.Config.user.isPatient) {
    const filtered = App.Logic.practitioners.filterByLicense(response.data.data, App.Config.user.info.state);
    App.setState('practitioners.data.licensed', filtered);
  }

  App.setState('isLoading.practitioners', false);
}
