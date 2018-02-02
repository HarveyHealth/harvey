export default function (response) {
  App.State.appointments.isLoading.upcoming = false;
  App.State.appointments.data.upcoming = App.Logic.appointments.combineUserData(response.data);
}
