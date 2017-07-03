import convertStatus from './convertStatus';
import toLocal from '../../../utils/methods/toLocal';
import { capitalize } from '../../../utils/filters/textformat';
import moment from 'moment';
import _ from 'lodash';


const SHIPPED_STATUS_ID = 0;
const CANCELED_STATUS_ID = 1;
const COMPLETE_STATUS_ID = 2;
const MAILED_STATUS_ID = 3;
const PENDING_STATUS_ID = 4;
const PROCESSING_STATUS_ID = 5;
const RECEIVED_STATUS_ID = 6;

export default function (orders, tests, patientLookUp, practitionerLookup, testList) {
    if (orders.length == 0 || tests.length == 0 || _.isEmpty(patientLookUp) || _.isEmpty(practitionerLookup)) return []
    return orders.map(obj => {
        let data = {
            id: obj.id,
            patient_id: obj.attributes.patient_id,
            practitioner_id: obj.attributes.practitioner_id,
            status_id: obj.attributes.status_id,
            shipment_code: obj.attributes.shipment_code,
            completed_at: capitalize(obj.attributes.status),
            tests_status: {},
            sku_ids: {},
            result_urls: {},
            shipment_codes: {},
            completed_ats: {},
            order_date: moment(obj.attributes.created_at.date).format("ddd MMM Do"),
            address_1: obj.attributes.address_1,
            address_2: obj.attributes.address_2,
            state: obj.attributes.state,
            zip: obj.attributes.zip,
            city: obj.attributes.city,
            test_list: []
        }
        tests.map(test => {
            if (test.attributes.lab_order_id == obj.id) {
                data.number_of_tests = data.number_of_tests ?
                    data.number_of_tests + 1 : 1
                data.sku_ids[test.attributes.sku_id] = test.included
                data.tests_status[test.attributes.lab_order_id] = test.attributes.status
                data.result_urls[test.attributes.lab_order_id] = test.attributes.result_url
                data.shipment_codes[test.attributes.lab_order_id] = test.attributes.shipment_code
                data.completed_ats[test.attributes.lab_order_id] = test.attributes.completed_at
                data.test_list.push({
                    item_type: testList[test.id].attributes.item_type,
                    price: testList[test.id].attributes.price,
                    name: testList[test.id].attributes.name,
                    status: [capitalize(test.attributes.status)].concat(_.pull(['Recommended', 'Confirmed', 'Complete', 'Shipped', 'Received', 'Mailed', 'Processing', 'Canceled'], capitalize(test.attributes.status))),
                    test_id: test.id,
                    sku: testList[test.id]
                })
            }
        })
        data.number_of_tests = !data.number_of_tests ? 0 : data.number_of_tests
        return {
            data,
            values: [
                data.order_date,
                patientLookUp[Number(data.patient_id)].attributes.name,
                `Dr. ${practitionerLookup[Number(data.practitioner_id)].attributes.name}`,
                `#${data.id}`,
                data.number_of_tests,
                data.completed_at
            ]
        }
    })
}