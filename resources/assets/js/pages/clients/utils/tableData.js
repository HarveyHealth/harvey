import _ from 'lodash'
import moment from 'moment'


export default function(clientList) {
    return clientList.map(e => {
        let data = e.attributes
        return {
            data: data,
            values: [
                `${data.first_name} ${data.last_name}`,
                data.created_at ? moment(data.created_at.date).format('M/D/YYYY') : 'No',
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