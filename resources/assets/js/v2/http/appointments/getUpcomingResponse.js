export default function (response) {
  App.setState('appointments.isLoading.upcoming', false);
  App.setState('appointments.data.upcoming', App.Logic.appointments.combineUserData(response.data));
}
