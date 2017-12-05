import Util from '../util';

export default {
    signup: {
        appointmentIsSelected: false,
        availability: [],
        billingConfirmed: false,
        cardBrand: '',
        cardCvc: '',
        cardExpiration: '',
        cardName: '',
        cardNumber: '',
        cardLastFour: '',
        code: '',
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
        hasCompletedSignup: false,
        phone: '',
        phoneCode: '',
        phonePending: false,
        phoneConfirmed: false,
        practitionerName: '',
        practitionerState: '',
        selectedDate: null,
        selectedDay: null,
        selectedPractitioner: null,
        selectedWeek: null,
        selectedTime: null,
        showProgress: false,
        steps: ['practitioner', 'phone', 'schedule', 'payment', 'confirmation'],
        stepsCompleted: 0
    },
    userPost: {
        email: Util.data.fromStorage('email') || '',
        first_name: Util.data.fromStorage('first_name') || '',
        last_name: Util.data.fromStorage('last_name') || '',
        password: Util.data.fromStorage('password') || '',
        terms: '',
        zip: ''
    },
    zipValidation: null
};
