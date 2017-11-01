import { capitalize } from '../../../utils/filters/textformat';
import moment from 'moment';
import _ from 'lodash';

export default function (orders, tests, patientLookUp, practitionerLookup, testList) {
    if (!orders.length || !tests.length || _.isEmpty(patientLookUp) || _.isEmpty(practitionerLookup) || _.isEmpty(testList)) return [];
    return orders.map(obj => {
        let data = {
            id: obj.id,
            patient_id: obj.attributes.patient_id,
            patient_user_id: patientLookUp[obj.attributes.patient_id] ? patientLookUp[obj.attributes.patient_id].attributes.user_id : null,
            practitioner_id: obj.attributes.practitioner_id,
            status_id: obj.attributes.status_id,
            shipment_code: obj.attributes.shipment_code,
            completed_at: obj.attributes.status == "complete" ? "Complete" : capitalize(obj.attributes.status),
            tests_status: {},
            sku_ids: {},
            result_urls: {},
            shipment_codes: {},
            shipment_label_url: obj.attributes.shipment_label_url,
            completed_ats: {},
            order_date: moment(obj.attributes.created_at.date).format("dddd, MMMM Do"),
            address_1: obj.attributes.address_1,
            address_2: obj.attributes.address_2,
            state: obj.attributes.state,
            zip: obj.attributes.zip,
            city: obj.attributes.city,
            test_list: [],
            date: obj.attributes.created_at.date,
            total_price: 0,
            paid: obj.invoice && obj.invoice.attributes ? obj.invoice.attributes.status : false,
            invoice_paid: obj.invoice && obj.invoice.attributes ? Number(obj.invoice.attributes.amount).toFixed(2) : false,
            card: {
                brand: obj.invoice && obj.invoice.attributes ? obj.invoice.attributes.card_brand : null,
                last4: obj.invoice && obj.invoice.attributes ? obj.invoice.attributes.card_last_four : null
            },
            samples: {}
        };

        tests.forEach(test => {
            if (test.attributes.lab_order_id == obj.id && test.attributes.status !== 'canceled') {
                data.total_price += eval(test.included.attributes.price);
                data.samples[test.included.attributes.sample] = data.samples[test.included.attributes.sample] ? data.samples[test.included.attributes.sample] : test.included.attributes.sample;
                data.number_of_tests = data.number_of_tests ? data.number_of_tests + 1 : 1;
                data.sku_ids[test.attributes.sku_id] = test.included;
                data.tests_status[test.attributes.lab_order_id] = test.attributes.status;
                data.result_urls[test.attributes.lab_order_id] = test.attributes.result_url;
                data.shipment_codes[test.attributes.lab_order_id] = test.attributes.shipment_code;
                data.completed_ats[test.attributes.lab_order_id] = test.attributes.completed_at;
                data.test_list.push({
                    item_type: test.included.attributes.item_type,
                    price: test.included.attributes.price,
                    name: test.included.attributes.name,
                    original_status: test.attributes.status,
                    status: obj.attributes.status === 'recommended' ?
                        [capitalize(test.attributes.status)].concat(_.pull(['Recommended', 'Confirmed', 'Complete', 'Shipped', 'Received', 'Mailed', 'Processing', 'Canceled'], capitalize(test.attributes.status))) :
                        obj.attributes.status === 'confirmed' ?
                            [capitalize(test.attributes.status)].concat(_.pull(['Confirmed', 'Complete', 'Shipped', 'Received', 'Mailed', 'Processing', 'Canceled'], capitalize(test.attributes.status))) :
                            [capitalize(test.attributes.status)].concat(_.pull(['Complete', 'Shipped', 'Received', 'Mailed', 'Processing', 'Canceled'], capitalize(test.attributes.status))),
                    test_id: Number(test.id),
                    current_status: capitalize(test.attributes.status),
                    sku: test.included,
                    shipment_code: test.attributes.shipment_code
                });
            }
        });
        data.test_list = data.test_list.filter(e => e.original_status !== 'canceled');
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
        };
    });
}