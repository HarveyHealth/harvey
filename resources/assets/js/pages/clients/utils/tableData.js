import _ from 'lodash'

export default function(clientList) {
    return clientList.map(e => {
        let data = e.attributes
        return {
            data: data,
            values: [
                `${data.first_name} ${data.last_name}`,
                data.created_at ? 'Yes' : 'No',
                data.phone,
                data.email,
                `${data.city}, ${data.state}`,
                data.has_an_appointment ? 'Yes' : 'No',
               data.doctor_name ? `Dr. ${data.doctor_name}`: 'No Doctor',
                `Yes`,  // data.intake_form ? 'Yes' : 'No',
                `Yes`  // data.has_an_appointment ? 'Yes' : 'No',
            ]
        }
    })
}