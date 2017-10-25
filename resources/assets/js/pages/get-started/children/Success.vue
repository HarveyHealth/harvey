<template>
  <div :class="containerClasses">
    <div class="vertical-center">
      <div class="signup-container small naked">
        <div class="signup-main-icon">
          <img class="success-icon" src="https://d35oe889gdmcln.cloudfront.net/assets/images/signup/calendar.png" alt="">
        </div>   
        <h2 class="heading-1 color-good">{{ title }}</h2>   
        <h3 class="heading-3">
          Dr. {{ $root.$data.signup.practitionerName }}, ND<br>
          {{ appointmentDate | toDate }}<br/>
          {{ appointmentDate | toTime }} {{$root.addTimezone()}}
        </h3>
        <div v-cloak title="Add to Calendar" :class="{addeventatc: true, isVisible: calendarVisible, 'mt-sm': true}">
          Add to Calendar
          <span class="start">{{ calendarStart }}</span>
          <span class="end">{{ calendarEnd }}</span>
          <span class="timezone">{{ calendarZone }}</span>
          <span class="title">{{ calendarSummary }}</span>
          <span class="description">{{ calendarDescription }}</span>
          <span class="location">{{ calendarLocation }}</span>
          <span class="organizer">Harvey</span>
          <span class="organizer_email">support@goharvey.com</span>
          <span class="all_day_event">false</span>
          <span class="date_format">MM/DD/YYYY</span>
          <span class="client">ajiwVmWorzcyJqbpmmXE27705</span>
          <span class="addeventatc_icon" style="height: 0 !important; background: none !important;"></span>
        </div>
        <p v-html="note"></p>
        <div class="button-wrapper">
          <a href="/dashboard" class="button button--cancel">Dashboard</a>
          <a @click.prevent="showIntakeModal" href="#" class="button button--blue">Start Intake Form</a>
        </div>
      </div>
      <Overlay :active="showModal" />
      <Modal :active="showModal" :on-close="() => showModal = false">
        <div class="card-content-wrap">
          <div class="inline-centered">
            <h3 class="heading-1">You are leaving Harvey</h3>
            <p>Your patient intake will be conducted by a third-party HIPAA-compliant EMR provider called &ldquo;IntakeQ&rdquo;. When prompted, enter your full name and the same email you used to sign up for Harvey. If you close the form you can come back to it later.</p>
            <div class="button-wrapper">
              <a class="button button--blue" :href="intakeUrl">Go to IntakeQ</a>
            </div>
          </div>
        </div>
      </Modal>
    </div>
  </div>
</template>

<script>
import moment from 'moment';

import Modal from '../../../commons/Modal.vue';
import Overlay from '../../../commons/Overlay.vue';

export default {
  name: 'success',
  components: {
    Modal,
    Overlay
  },
  data() {
    return {
      containerClasses: {
        'anim-fade-slideup': true,
        'anim-fade-slideup-in': false,
        'container': true,
        'pad-md': true,
        'flex-wrapper': true,
        'height-100': true,
        'justify-center': true
      },
      showModal: false,
      title: 'Appointment confirmed!',
      note: 'You must complete the patient intake form (below) before talking with your doctor. We will send you text and email reminders before your appointment. You can with us on this screen if you have any questions.',
      intakeUrl: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${Laravel.user.id}`,
      appointmentDate: this.$root.$data.signup.data.appointment_at,
      appointmentInformation: this.$root.$data.signup.data,
      env: this.$root.$data.environment,
      calendarVisible: false,
      calendarSummary: `Appointment with ${this.$root.$data.signup.practitionerName}`,
      calendarStart: moment.utc(this.$root.$data.signup.data.appointment_at).local().format('MM/DD/YYYY hh:mm A'),
      calendarEnd: moment.utc(this.$root.$data.signup.data.appointment_at).add(60, 'm').local().format('MM/DD/YYYY hh:mm A'),
      calendarZone: '',
      calendarLocation: '',
      calendarDescription: this.$root.$data.signup.googleMeetLink ? `Your Google Meet link: ${this.$root.$data.signup.googleMeetLink}` : ''
    };
  },
  computed: {
    time() {
      const timeObject = this.appointmentDate.format('h:mm a');
      return timeObject;
    },
    date() {
      const dateObject = this.appointmentDate;
      return dateObject;
    }
  },
  methods: {
    showIntakeModal() {
      this.showModal = true;
    },
    sendToIntake() {
      if (this.$root.isOnProduction()) {
        // place intake tracking here
      }
    }
  },
  filters: {
    toDate(date) {
      return moment.utc(date).local().format('dddd, MMMM Do');
    },
    toTime(date) {
      return moment.utc(date).local().format('h:mm a');
    }
  },
  mounted () {
    if (!this.$root.isSignupBookingAllowed) {
      this.$router.push({ name: 'welcome', path: 'welcome' });
      return;
    }
    this.$root.$data.signup.completedSignup = true;
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', true, 300);
    // A purchase event is typically associated with a specified product or product_group.
    // See https://developers.facebook.com/docs/ads-for-websites/pixel-troubleshooting#catalog-pair
    if(this.$root.shouldTrack()) {
      // place view tracking here
      // Segment tracking
      analytics.track("Consultation Confirmed");
      analytics.page('Success');
    }

    // From https://www.addevent.com/buttons/add-to-calendar
    // Has to be added on component mount because it needs to be able to find
    // the corresponding button in the DOM.
    (function (context) {
      var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
      s.type = 'text/javascript';
      s.charset = 'UTF-8';
      s.async = true;
      s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addevent.com/libs/atc/1.6.1/atc.min.js';
      var h = d[g]('body')[0];
      h.appendChild(s);
      s.onload = () => context.calendarVisible = true;
    })(this);
  },
  beforeDestroy() {
    this.$eventHub.$emit('animate', this.containerClasses, 'anim-fade-slideup-in', false);
  }
};
</script>
