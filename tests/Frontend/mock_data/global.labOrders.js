export default {
  "type": "lab_orders",
  "id": "1",
  "attributes": {
    "address_1": "404 Littel Vista",
    "address_2": null,
    "city": "Connellymouth",
    "completed_at": null,
    "created_at": {
      "date": "2017-11-03 13:49:17.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "patient_id": "28",
    "practitioner_id": "28",
    "shipment_code": "9792626964828",
    "state": "CA",
    "status": "recommended",
    "zip": "54195"
  },
  "links": {
    "self": "http://localhost/api/v1/lab_orders/1"
  },
  "relationships": {
    "patient": {
      "links": {
        "self": "http://localhost/api/v1/lab_orders/1/relationships/patient",
        "related": "http://localhost/api/v1/lab_orders/1/patient"
      },
      "data": {
        "type": "patients",
        "id": "28"
      }
    }
  },
  "user": {
    "type": "users",
    "id": "69",
    "attributes": {
      "address_1": "4450 Frami Ford",
      "address_2": null,
      "card_brand": null,
      "card_last4": null,
      "city": "South Ronaldochester",
      "created_at": {
        "date": "2017-11-03 13:49:17.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "doctor_name": false,
      "email": "elissa97@example.net",
      "email_verified_at": {
        "date": "2017-11-03 13:49:17.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "first_name": "Keyshawn",
      "gender": null,
      "has_a_card": false,
      "has_an_appointment": false,
      "image_url": "https://d35oe889gdmcln.cloudfront.net/assets/images/default_user_image.png",
      "intake_completed_at": null,
      "last_name": "Douglas",
      "phone": "3238177725",
      "phone_verified_at": {
        "date": "2017-11-03 13:49:17.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "state": "AZ",
      "terms_accepted_at": null,
      "timezone": "America/Los_Angeles",
      "user_type": "patient",
      "zip": "85008"
    },
    "links": {
      "self": "http://localhost/api/v1/users/69"
    }
  },
  "patient": {
    "type": "patients",
    "id": "28",
    "attributes": {
      "birthdate": "1967-07-08",
      "height_feet": 4,
      "height_inches": 8,
      "name": "Keyshawn Douglas",
      "user_id": "69"
    },
    "links": {
      "self": "http://localhost/api/v1/patients/28"
    },
    "relationships": {
      "user": {
        "links": {
          "self": "http://localhost/api/v1/patients/28/relationships/user",
          "related": "http://localhost/api/v1/patients/28/user"
        },
        "data": {
          "type": "users",
          "id": "69"
        }
      }
    }
  }
}
