export function formatTableData(dictionary) {
    return Object.values(dictionary).map(e => {
        let object = {};

        object.date = e.attributes.paid_on.date;
        object.discount = e.attributes.discount;
        object.card = `${e.attributes.card_brand} **** ${e.attributes.card_last_four}`;
        object.price = e.attributes.subtotal;
        object.total = e.attributes.amount;
        object.patientId = e.attributes.patient_id;
        object.details = e.attributes.description;
        object.service = e.attributes.description;
        object.status = e.attributes.status;

        return object;
    });
}