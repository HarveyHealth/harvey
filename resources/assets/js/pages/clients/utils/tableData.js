import _ from 'lodash'
import moment from 'moment'


export default function(clientList) {
    return clientList.map(e => {
        let data = e.attributes
        return {
            data: data,
            values: [
                `${data.first_name} ${data.last_name}`,
                data.created_at ? moment(data.created_at.date).format('ddd, MMM Do YYYY') : 'N/A',
                `${data.city}, ${data.state}`,
                data.doctor_name ? `Dr. ${data.doctor_name}` : 'N/A',
                data.has_an_appointment ? 'Yes' : 'No',
                data.has_completed_an_appointment ? 'Yes' : 'No'
            ]
        }
    })
}