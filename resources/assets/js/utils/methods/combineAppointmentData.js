// appointmentData = returned from appointment api call
// contains appointment data and included user/patient information
// this function combines the relavant patient data with the appointment details
export default function (appointmentData) {
    const combineAppointmentData = (details) => {
        return details.data.map(appt => {
            appt.patientData = getIncludedPatient(details.included, appt);
            return appt;
        });
    };

    const getIncludedPatient = (_included, _appointment) => {
        const patientId = _appointment.attributes.patient_id;
        const patientData = {
            id: patientId
        };

        return patientData;
    };

    return combineAppointmentData(appointmentData);
}
