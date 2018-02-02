export default function(appointments) {
    const data = appointments.data;
    const included = appointments.included;

    return data.map(appointment => {
        // Grab associated patient data
        const patient = Util.findObject(included, [
          { where: 'type', is: 'patient' },
          { where: 'id', is: appointment.attributes.patient_id }
        ]);

        // Set the user object on the appointment data
        appointment.user = Util.findObject(included, [
          { where: 'type', is: 'user' },
          { where: 'id', is: patient.attributes.user_id }
        ]);
        
        return appointment;
    });
}
