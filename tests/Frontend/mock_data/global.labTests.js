export default [
  {
    "type": "lab_tests",
    "id": "1",
    "attributes": {
      "lab_order_id": "1",
      "sku_id": "10",
      "status": "recommended",
      "results_url": null,
      "shipment_code": "9798397051958",
      "completed_at": null
    },
    "links": {
      "self": "http://localhost/api/v1/lab_tests/1"
    },
    "relationships": {
      "sku": {
        "links": {
          "self": "http://localhost/api/v1/lab_tests/1/relationships/sku",
          "related": "http://localhost/api/v1/lab_tests/1/sku"
        },
        "data": {
          "type": "SKUs",
          "id": "10"
        }
      }
    },
    "included": {
      "type": "SKUs",
      "id": "10",
      "attributes": {
        "item_type": "lab-test",
        "name": "Microbiome (Gut)",
        "price": "199.00",
        "sample": "Stool"
      },
      "links": {
        "self": "http://localhost/api/v1/SKUs/10"
      }
    }
  },
  {
    "type": "lab_tests",
    "id": "2",
    "attributes": {
      "lab_order_id": "1",
      "sku_id": "7",
      "status": "recommended",
      "results_url": null,
      "shipment_code": "9782865012619",
      "completed_at": null
    },
    "links": {
      "self": "http://localhost/api/v1/lab_tests/2"
    },
    "relationships": {
      "sku": {
        "links": {
          "self": "http://localhost/api/v1/lab_tests/2/relationships/sku",
          "related": "http://localhost/api/v1/lab_tests/2/sku"
        },
        "data": {
          "type": "SKUs",
          "id": "7"
        }
      }
    },
    "included": {
      "type": "SKUs",
      "id": "7",
      "attributes": {
        "item_type": "lab-test",
        "name": "Toxic Metals",
        "price": "199.00",
        "sample": "Urine"
      },
      "links": {
        "self": "http://localhost/api/v1/SKUs/7"
      }
    }
  },
  {
    "type": "lab_tests",
    "id": "3",
    "attributes": {
      "lab_order_id": "1",
      "sku_id": "9",
      "status": "recommended",
      "results_url": null,
      "shipment_code": "9781240043705",
      "completed_at": null
    },
    "links": {
      "self": "http://localhost/api/v1/lab_tests/3"
    },
    "relationships": {
      "sku": {
        "links": {
          "self": "http://localhost/api/v1/lab_tests/3/relationships/sku",
          "related": "http://localhost/api/v1/lab_tests/3/sku"
        },
        "data": {
          "type": "SKUs",
          "id": "9"
        }
      }
    },
    "included": {
      "type": "SKUs",
      "id": "9",
      "attributes": {
        "item_type": "lab-test",
        "name": "Food Allergy",
        "price": "199.00",
        "sample": "Blood draw"
      },
      "links": {
        "self": "http://localhost/api/v1/SKUs/9"
      }
    }
  },
  {
    "type": "lab_tests",
    "id": "4",
    "attributes": {
      "lab_order_id": "1",
      "sku_id": "3",
      "status": "recommended",
      "results_url": null,
      "shipment_code": "9781211682834",
      "completed_at": null
    },
    "links": {
      "self": "http://localhost/api/v1/lab_tests/4"
    },
    "relationships": {
      "sku": {
        "links": {
          "self": "http://localhost/api/v1/lab_tests/4/relationships/sku",
          "related": "http://localhost/api/v1/lab_tests/4/sku"
        },
        "data": {
          "type": "SKUs",
          "id": "3"
        }
      }
    },
    "included": {
      "type": "SKUs",
      "id": "3",
      "attributes": {
        "item_type": "lab-test",
        "name": "Adrenals",
        "price": "125.00",
        "sample": "Saliva"
      },
      "links": {
        "self": "http://localhost/api/v1/SKUs/3"
      }
    }
  },
  {
    "type": "lab_tests",
    "id": "5",
    "attributes": {
      "lab_order_id": "1",
      "sku_id": "6",
      "status": "recommended",
      "results_url": null,
      "shipment_code": "9799181307701",
      "completed_at": null
    },
    "links": {
      "self": "http://localhost/api/v1/lab_tests/5"
    },
    "relationships": {
      "sku": {
        "links": {
          "self": "http://localhost/api/v1/lab_tests/5/relationships/sku",
          "related": "http://localhost/api/v1/lab_tests/5/sku"
        },
        "data": {
          "type": "SKUs",
          "id": "6"
        }
      }
    },
    "included": {
      "type": "SKUs",
      "id": "6",
      "attributes": {
        "item_type": "lab-test",
        "name": "CBC/CMP",
        "price": "29.00",
        "sample": "Blood draw"
      },
      "links": {
        "self": "http://localhost/api/v1/SKUs/6"
      }
    }
  },
  {
    "type": "lab_tests",
    "id": "6",
    "attributes": {
      "lab_order_id": "2",
      "sku_id": "2",
      "status": "recommended",
      "results_url": null,
      "shipment_code": "",
      "completed_at": null
    },
    "links": {
      "self": "http://localhost/api/v1/lab_tests/6"
    },
    "relationships": {
      "sku": {
        "links": {
          "self": "http://localhost/api/v1/lab_tests/6/relationships/sku",
          "related": "http://localhost/api/v1/lab_tests/6/sku"
        },
        "data": {
          "type": "SKUs",
          "id": "2"
        }
      }
    },
    "included": {
      "type": "SKUs",
      "id": "2",
      "attributes": {
        "item_type": "lab-test",
        "name": "Hormones",
        "price": "99.00",
        "sample": "Blood draw"
      },
      "links": {
        "self": "http://localhost/api/v1/SKUs/2"
      }
    }
  },
  {
    "type": "lab_tests",
    "id": "7",
    "attributes": {
      "lab_order_id": "2",
      "sku_id": "1",
      "status": "recommended",
      "results_url": null,
      "shipment_code": "",
      "completed_at": null
    },
    "links": {
      "self": "http://localhost/api/v1/lab_tests/7"
    },
    "relationships": {
      "sku": {
        "links": {
          "self": "http://localhost/api/v1/lab_tests/7/relationships/sku",
          "related": "http://localhost/api/v1/lab_tests/7/sku"
        },
        "data": {
          "type": "SKUs",
          "id": "1"
        }
      }
    },
    "included": {
      "type": "SKUs",
      "id": "1",
      "attributes": {
        "item_type": "lab-test",
        "name": "Micronutrients",
        "price": "299.00",
        "sample": "Blood draw"
      },
      "links": {
        "self": "http://localhost/api/v1/SKUs/1"
      }
    }
  }
]
