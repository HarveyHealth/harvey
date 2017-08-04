import _ from 'lodash'
import moment from 'moment'


export default function(clientList) {
    return clientList
    .sort((a, b) => new Date(b.attributes.created_at.date) - new Date(a.attributes.created_at.date))
    .map(e => {
        let data = e.attributes
        data['email_hyperlink'] = 0
        return {
            data: data,
            values: [
                data.first_name && data.last_name ? `${_.capitalize(data.first_name)} ${_.capitalize(data.last_name)}` : 'N/A',
                data.created_at ? moment(data.created_at.date).format('ddd, MMM Do YYYY') : 'N/A',
                data.city && data.state ? `${data.city}, ${data.state}` : 'N/A',
                data.doctor_name ? `Dr. ${data.doctor_name}` : 'N/A',
                data.has_an_appointment ? 'Yes' : 'No',
                data.has_completed_an_appointment ? 'Yes' : 'No'
            ]
        }
    })
    .splice(0, 50)
}