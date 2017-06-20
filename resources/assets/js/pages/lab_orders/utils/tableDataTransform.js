import convertStatus from './convertStatus';
import toLocal from '../../../utils/methods/toLocal';
import { capitalize } from '../../../utils/filters/textformat';

export default function (orders, tests, zone) {
    return orders.map(obj => {
        const data = {
            id: obj.id,
            patient_id: obj.attributes.patient_id,
            practitioner_id: obj.attributes.practitioner_id,
            status_id: obj.attributes.status_id,
            shipment_code: obj.attributes.shipment_code,
            completed_at: obj.attributes.completed_at
        }
        return {
            data,
            values: [
                data.id,
                data.patient_id,
                data.practitioner_id,
                data.status_id,
                data.shipment_code,
                data.completed_at
            ]
        }
    })
}