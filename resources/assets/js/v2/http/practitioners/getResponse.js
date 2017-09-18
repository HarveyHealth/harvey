export default function(response) {
  const State = this.$root.$data.State;

  State.practitioners.data.all = response.data.data;
  State.practitioners.data.licensed = response.data.data;

  if (App.Config.user.isPatient) {
    State.practitioners.data.licensed = App.Logic.practitioners.filterByLicense.call(this, App.Config.user.info.state);
    State.dashboard.practitioner = App.Logic.dashboard.findPractitioner.call(this, App.Config.user.info.doctor_name);
  }

  State.isLoading.practitioners = false;
}
