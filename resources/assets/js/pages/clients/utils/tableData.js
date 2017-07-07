import _ from 'lodash'
import moment from 'moment'


export default function(clientList) {
    return clientList.map(e => {
        let data = e.attributes
        data['email_hyperlink'] = 5
        data['phone_hyperlink'] = 6
        return {
            data: data,
            values: [
                `${data.first_name} ${data.last_name}`,
                data.created_at ? moment(data.created_at.date).format('ddd, MMM Do YYYY') : 'No',
                `${data.city}, ${data.state}`,
                data.has_an_appointment ? 'Yes' : 'No',
                data.doctor_name ? `Dr. ${data.doctor_name}`: 'ND',
                data.email,
                data.phone
                // `Yes`,  // data.intake_form ? 'Yes' : 'No',
                // `Yes`  // data.has_an_appointment ? 'Yes' : 'No',
            ]
        }
    })
}