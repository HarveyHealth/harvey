<template>
  <CardContent :withBorder="hasBorder">
    <Paragraph isThin v-if="!Config.user.isPractitioner">Dr. {{ doctor }}, ND</Paragraph>
    <Paragraph isThin v-if="!Config.user.isPatient">{{ Util.misc.fullName(user) }}</Paragraph>
    <Paragraph isThin><router-link :to="route">{{ date }} ({{ Config.time.zoneAbbr}})</router-link></Paragraph>
  </CardContent>
</template>

<script>
import { CardContent } from 'layout';
import { Paragraph } from 'typography';

export default {
  components: { CardContent, Paragraph },
  props: {
    appointment: Object,
    hasBorder: Boolean
  },
  data() {
    return {
      date: this.Util.time.toLocal(this.appointment.attributes.appointment_at.date, 'ddd, MMM Do [at] h:mm a'),
      doctor: this.appointment.attributes.practitioner_name,
      route: { name: 'appointments', params: { appt_id: this.appointment.id } },
      user: this.appointment.user.attributes
    };
  }
};
</script>
