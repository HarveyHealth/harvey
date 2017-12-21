export default function(response) {
  App.setState({
      'practitioners.data.all': response.data.data
  });

  if (App.Config.user.isPatient) {
    App.Logic.practitioners.findUserDoctor(response.data.data);
  }

  App.setState('practitioners.isLoading', false);
}
