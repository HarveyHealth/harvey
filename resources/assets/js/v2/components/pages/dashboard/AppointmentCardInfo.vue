<template>
    <CardContent :withBorder="hasBorder">
        <Paragraph :weight="'thin'" v-if="!Store.isPractitioner">
            Dr. {{ doctor }}, ND</Paragraph>
        <Paragraph :weight="'thin'" v-if="!Store.isPatient">
            {{ Util.getFullName(user) }}
        </Paragraph>
        <Paragraph :weight="'thin'">
            <router-link :to="route">{{ date }} ({{ Store.zoneAbbr}})</router-link>
        </Paragraph>
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
            date: Util.toLocalTime(this.appointment.attributes.appointment_at.date, 'ddd, MMM Do [at] h:mm a'),
            doctor: this.appointment.attributes.practitioner_name,
            route: { name: 'appointments', params: { appt_id: this.appointment.id } },
            user: this.appointment.user.attributes
        };
    }
};
</script>
