import moment from 'moment-timezone';

export default function(laravel, State) {
  return {
      State, // v2 state management

      apiUrl: '/api/v1',
      appointmentData: null,
      colors: {
        copy: '#4f6268'
      },
      clientList: [],
      permissions: laravel.user.user_type,
      environment: require('get-env')(),
      currentUserId: laravel.user.id,
      flyoutActive: false,
      guest: false,
      stripe: null,
      global: {
          appointments: [],
          currentPage: '',
          creditCards: [],
          transactions: {},
          loadingTransactions: true,
          detailMessages: {},
          loadingAppointments: true,
          loadingCreditCards: true,
          loadingClients: true,
          loadingConfirmedUsers: true,
          loadingPatients: true,
          loadingPractitioners: true,
          practitionerProfileLoading: true,
          loadingLabOrders: true,
          loadingMessages: true,
          loadingLabTests: true,
          loadingTestTypes: true,
          loadingUser: true,
          loadingUserEditing: true,
          menuOpen: false,
          messages: [],
          patients: [],
          practitioners: [],
          recent_appointments: [],
          // Updated: 08/22/2017
          // This is a hotfix and should be included in the backend logic when determining which
          // practitioners to send to the frontend
          regulatedStates: [
            'AK', 'CA', 'HI', 'OR', 'WA', 'AZ', 'CO', 'MT', 'UT', 'KS', 'MN', 'ND', 'CT', 'ME', 'MD', 'NH', 'VT', 'DC'
          ],
          signed_in: laravel.user.signedIn,
          test_results: [],
          upcoming_appointments: [],
          unreadMessages: [],
          labOrders: [],
          labTests: [],
          patientLookUp: {},
          practitionerLookUp: {},
          user: {},
          selfPractitionerInfo: null,
          user_editing: {}
      },
      signup: {
        availability: [],
        availableTimes: [],
        billingConfirmed: false,
        cardBrand: '',
        cardCvc: '',
        cardExpiration: '',
        cardName: '',
        cardNumber: '',
        cardLastFour: '',
        code: '',
        completedSignup: false,
        codeConfirmed: false,
        cost: '',
        data: {
          appointment_at: null,
          discount_code: null,
          reason_for_visit: 'First appointment',
          practitioner_id: null
        },
        discountCode: '',
        googleMeetLink: '',
        phone: '',
        phonePending: false,
        phoneConfirmed: false,
        practitionerName: '',
        practitionerState: '',
        selectedDate: null,
        selectedDay: null,
        selectedPractitioner: 0,
        selectedWeek: null,
        selectedTime: null,
        visistedStages: []
      },
      initialAppointment: {},
      initialAppointmentComplete: false,
      labTests: {},
      timezone: moment.tz.guess(),
      timezoneAbbr: moment.tz(moment.tz.guess()).format('z')
  };
}
