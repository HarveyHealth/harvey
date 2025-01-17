// appointmentData = returned from appointment api call
// contains appointment data and included user/patient information
// this function combines the relavant patient data with the appointment details
export default function(appointmentData) {

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

      // first, get the patient information from the provided patient_id from appointment
    _included.map((item) => {
          if (item.type === 'patient' && item.id === patientData.id.toString()) {
              patientData.user_id = item.attributes.user_id;
          }
      });

    //   now find the related user
    _included.map((item) => {
          // needed since the data types are different
          if (item.type === 'user' && item.id === patientData.user_id.toString()) {
              patientData.address_1 = item.attributes.address_1;
              patientData.address_2 = item.attributes.address_2;
              patientData.city = item.attributes.city;
              patientData.email = item.attributes.email;
              patientData.has_a_card = item.attributes.has_a_card;
              patientData.first_name = item.attributes.first_name;
              patientData.last_name = item.attributes.last_name;
              patientData.state = item.attributes.state;
              patientData.phone = item.attributes.phone;
              patientData.zip = item.attributes.zip;
          }
      });

      _included.map((item) => {
          // needed since the data types are different
          if (item.type === 'invoice' 
          && _appointment.relationships
          && _appointment.relationships.invoice
          && _appointment.relationships.invoice.data
          && _appointment.relationships.invoice.data.id === item.id) {
              patientData.amount = item.attributes.amount;
              patientData.card_brand = item.attributes.card_brand;
              patientData.card_last_four = item.attributes.card_last_four;
              patientData.status = item.attributes.status;
              patientData.description = item.attributes.description;
              patientData.subtotal = item.attributes.subtotal;
              patientData.discount = item.attributes.discount;
          }
      });

      return patientData;
  };

  return combineAppointmentData(appointmentData);

}
