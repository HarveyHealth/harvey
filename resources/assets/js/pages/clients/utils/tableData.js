import _ from 'lodash'

export default function(clientList) {
    return clientList.map(e => {
        let data = e.attributes
        return {
            data: data,
            values: [
                `${data.first_name} ${data.last_name}`,
                true,  // data.created_at ? 'Yes' : 'No',
                data.phone,
                data.email,
                `${data.city}, ${data.state}`,
                true,  // data.has_an_appointment ? 'Yes' : 'No',
               `Dr. Amanda Frick`,  // `Dr. ${data.doctor_name}`,
                true,  // data.intake_form ? 'Yes' : 'No',
                true,  // data.phone_verified_at ? 'Yes' : 'No',
                true  // data.has_an_appointment ? 'Yes' : 'No',
            ]
        }
    })
}