import moment from 'moment';
import { capitalize } from 'lodash';

export function formatTableData(dictionary, time) {
    return Object.values(dictionary).map(e => {
        let data = {};

        data.date = moment.tz(e.attributes.paid_on.date, e.attributes.paid_on.timezone).tz(time).format('MMM Do YYYY, h:mma z');
        data.discount = '$' + e.attributes.discount;
        data.card = `${e.attributes.card_brand} **** ${e.attributes.card_last_four}`;
        data.price = '$' + e.attributes.subtotal;
        data.total = '$' + e.attributes.amount;
        data.patientId = e.attributes.patient_id;
        data.details = e.attributes.description;
        data.service = e.attributes.description;
        data.status = capitalize(e.attributes.status);
        data.id = '#' + e.id;
        data.patient_name = e.patient.search_name;

        return {
            data,
            values: [
                data.id,
                data.patient_name,
                data.date,
                data.service,
                data.details,
                data.price,
                data.discount,
                data.total,
                data.card,
                data.status
            ]
        };
    });
}