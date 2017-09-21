import combineAppointmentData from './combineAppointmentData';

export default function (cb) {
    axios
        .get(`${this.$root.apiUrl}/appointments?include=patient.user`)
        .then(response => {
            this.$root.global.appointments = combineAppointmentData(response.data).reverse();
            if (cb && typeof cb === 'function') cb(response);
        })
        .catch(error => console.log(error.response));
}
