export default function(response) {
  App.setState({
      'practitioners.data.all': response.data.data,
      'practitioners.data.licensed': response.data.data
  });

  if (App.Config.user.isPatient) {
    const filtered = App.Logic.practitioners.filterByLicense(response.data.data, App.Config.user.info.state);
    App.setState('practitioners.data.licensed', filtered);
    App.Logic.practitioners.findUserDoctor(response.data.data);
  }

  App.setState('practitioners.isLoading', false);
}
