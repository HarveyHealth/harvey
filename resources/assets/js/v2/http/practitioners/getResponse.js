export default function(response) {
  App.State.practitioners.data.all = response.data.data;
  App.State.practitioners.data.licensed = response.data.data;

  if (App.Config.user.isPatient) {
    const filtered = App.Logic.practitioners.filterByLicense(response.data.data, App.Config.user.info.state);
    App.State.practitioners.data.licensed = filtered;
    App.Logic.practitioners.findUserDoctor(response.data.data);
  }

  App.State.practitioners.isLoading = false;
}
