import combineUserData from './combineUserData';

export default function (responseHandler) {
    Util.startFetch('upcomingAppointments');

    axios.get(`${Store.API}appointments?filter=upcoming&include=patient.user`)
        .then(r => {
            Store.isLoading.upcomingAppointments = false;
            Store.appointments.upcoming = combineUserData(r.data);
            if (responseHandler) responseHandler(r);
        })
        .catch(error => {
            if (error.response) {
                console.warn(error.response);
            }
        });
  }