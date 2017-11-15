import globalAppointments from './global.appointments';
import globalLabOrders from './global.labOrders';
import globalLabTests from './global.labTests';
import globalPatientLookup from './global.patientLookup';
import globalPractitionerLookup from './global.practitionerLookup';

import discountData from './discountData';
import labTests from './labTests';
import patientCreditCard from './patientCreditCard';

export default {
  discountData,
  labTests,
  global: {
    appointments: globalAppointments,
    labOrders: globalLabOrders,
    labTests: globalLabTests,
    patientLookup: globalPatientLookup,
    practitionerLookup: globalPractitionerLookup,
  },
  patientCreditCard,
}
