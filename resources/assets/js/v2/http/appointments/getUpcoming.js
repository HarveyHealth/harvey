export default function (responseHandler) {
  App.State.appointments.isLoading.upcoming = true;
  App.State.appointments.wasRequested.upcoming = true;

  axios.get(`${App.Config.misc.api}appointments?filter=upcoming&include=patient.user`)
    .then(r => responseHandler(r))
    .catch(error => {
      if (error.response) {
        console.warn(error.response);
      }
    });
}
