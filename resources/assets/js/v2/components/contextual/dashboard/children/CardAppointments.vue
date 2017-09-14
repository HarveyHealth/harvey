<template>
  <Card :heading="heading" :class="!Config.user.isPatient ? 'Column-lg-6' : false">
    <div v-for="(appt, i) in appointments" :class="classNames(i)">
      <p v-if="!Config.user.isPractitioner">Dr. {{ appt.attributes.practitioner_name }}</p>
      <p v-if="!Config.user.isPatient">{{ Util.misc.fullName(appt.user.attributes) }}</p>
      <p>
        <router-link :to="{ name: 'appointments', params: { appt_id: appt.id } }">
          {{ Util.time.toLocal(appt.attributes.appointment_at.date, 'dddd, MMMM Do [at] h:mm a') }}
        </router-link>
      </p>
    </div>
    <div v-if="!appointments.length" class="Card-Content">
      <p class="copy-muted-2 font-italic">None</p>
    </div>
  </Card>
</template>

<script>
import Card from './Card';

export default {
  components: { Card },
  methods: {
    classNames(i) {
      return `Card-Content space-children-xs ${i > 0 ? 'border-top': ''}`;
    }
  },
  props: {
    appointments: Array,
    heading: String,
  }
}
</script>
