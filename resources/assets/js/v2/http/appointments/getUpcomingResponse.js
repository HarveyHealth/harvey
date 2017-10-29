export default function (response) {
  App.setState('appointments.isLoading.upcoming', false);
  App.setState('appointments.data.upcoming', response.data.data);
}
