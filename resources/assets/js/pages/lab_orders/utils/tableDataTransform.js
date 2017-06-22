import convertStatus from './convertStatus';
import toLocal from '../../../utils/methods/toLocal';
import { capitalize } from '../../../utils/filters/textformat';
import moment from 'moment';

const SHIPPED_STATUS_ID = 0;
const CANCELED_STATUS_ID = 1;
const COMPLETE_STATUS_ID = 2;
const MAILED_STATUS_ID = 3;
const PENDING_STATUS_ID = 4;
const PROCESSING_STATUS_ID = 5;
const RECEIVED_STATUS_ID = 6;

export default function (orders, tests, patientLookUp, practitionerLookup) {
    return orders.map(obj => {
        let data = {
            id: obj.id,
            patient_id: obj.attributes.patient_id,
            practitioner_id: obj.attributes.practitioner_id,
            status_id: obj.attributes.status_id,
            shipment_code: obj.attributes.shipment_code,
            completed_at: obj.attributes.completed_at ? 'Complete' : 'Pending',
            tests_status: {},
            tests_ids: {},
            sku_ids: {},
            result_urls: {},
            shipment_codes: {},
            completed_ats: {},
            order_date: moment(obj.attributes.created_at.date).format("ddd MMM Do")
        }
        tests.map(test => {
            if (test.attributes.lab_order_id == obj.id) {
                data.number_of_tests = data.number_of_tests ?
                    data.number_of_tests + 1 : 1
                data.sku_ids[test.attributes.lab_order_id] = test.attributes.sku_id
                data.tests_status[test.attributes.lab_order_id] = test.attributes.status
                data.tests_ids[test.attributes.lab_order_id] = test.id
                data.result_urls[test.attributes.lab_order_id] = test.attributes.result_url
                data.shipment_codes[test.attributes.lab_order_id] = test.attributes.shipment_code
                data.completed_ats[test.attributes.lab_order_id] = test.attributes.completed_at
            }
        })
        return {
            data,
            values: [
                data.order_date,
                patientLookUp[data.patient_id].attributes.name,
                `Dr. ${practitionerLookup[data.practitioner_id].attributes.name}`,
                data.id,
                data.number_of_tests,
                data.completed_at
            ]
        }
    })
}