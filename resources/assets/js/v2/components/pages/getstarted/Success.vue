<template>
  <div>
    <div class="vertical-center tc">
      <div class="signup-container small naked">
        <div class="signup-main-icon">
          <img class="success-icon" src="https://d35oe889gdmcln.cloudfront.net/assets/images/signup/calendar.png" alt="">
        </div>
        <h2 class="heading-1 color-good">Appointment Confirmed!</h2>
        <h3 class="heading-3">
          Dr. {{ $root.$data.signup.practitionerName }}, ND<br>
          {{ appointmentDate | toDate }}<br/>
          {{ appointmentDate | toTime }} {{$root.addTimezone()}}
        </h3>
        <AddEventButton :config="calendarConfig" />
        <p v-html="note"></p>
        <div class="button-wrapper">
          <a href="/dashboard" class="button button--cancel">Dashboard</a>
          <a @click.prevent="showIntakeModal" href="#" class="button button--blue">Start Intake Form</a>
        </div>
      </div>
      <Overlay :isActive="showModal" />
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

import { AddEventButton } from 'inputs';
import { Modal, Overlay } from 'layout';

export default {
    name: 'success',
    components: {
        AddEventButton,
        Modal,
        Overlay
    },
    data() {
        return {
            showModal: false,
            title: 'Appointment confirmed!',
            note: 'You must complete the patient intake form (below) before talking with your doctor. We will send you text and email reminders before your appointment. You can with us on this screen if you have any questions.',
            intakeUrl: `https://goharvey.intakeq.com/new/Qqy0mI/DpjPFg?harveyID=${Laravel.user.id}`,
            appointmentDate: this.$root.$data.signup.data.appointment_at,
            appointmentInformation: this.$root.$data.signup.data,
            env: this.$root.$data.environment
        };
    },
    computed: {
      calendarConfig() {
          const info = this.State('getstarted.signup');
          return {
              start: App.Util.time.toLocal(info.data.appointment_at, 'MM/DD/YYYY hh:mm A'),
              end: moment.utc(info.data.appointment_at).add(60, 'm').local().format('MM/DD/YYYY hh:mm A'),
              timezone: '',
              location: '',
              title: `Appointment with Dr. ${info.practitionerName}, ND`,
              description: `Your Google Meet Link: ${info.googleMeetLink || ''}`
          }
      },
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
        analytics.page('Success');
    },
};
</script>
